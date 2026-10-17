<?php

namespace Tests\Feature;

use App\Events\CashierDashboardUpdated;
use App\Http\Controllers\MenuController;
use App\Models\Cashier;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CashierAuthenticationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('cashiers');
        Schema::dropIfExists('users');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('cashiers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 32)->unique();
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('number')->unique();
            $table->string('code')->unique();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('table_id')->nullable();
            $table->string('number');
            $table->string('status')->default('active');
            $table->dateTime('start_time')->nullable();
            $table->timestamps();
        });
        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2)->default(0);
        });
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bill_id');
            $table->unsignedBigInteger('food_id');
            $table->unsignedInteger('amount')->default(1);
            $table->decimal('total_price', 10, 2)->default(0);
            $table->string('status')->default('0');
            $table->text('note')->nullable();
            $table->time('expected_time')->default('00:00:00');
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('cashiers');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('foods');
        Schema::dropIfExists('bills');
        Schema::dropIfExists('tables');
        Schema::dropIfExists('users');

        parent::tearDown();
    }

    private function signInAsAdmin(): void
    {
        $admin = User::create([
            'name' => 'Test Admin',
            'email' => 'admin@example.test',
            'password' => 'admin-password',
        ]);

        $this->actingAs($admin, 'admin');
    }

    public function test_cashier_can_sign_in_and_open_cashier_dashboard(): void
    {
        $cashier = Cashier::create([
            'name' => 'Test Cashier',
            'phone' => '+963912345678',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $response = $this->post(route('cashier.login.action'), [
            'phone' => '912345678',
            'country_code' => '+963',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('cashier.tables'));
        $this->assertAuthenticatedAs($cashier, 'cashier');
    }

    public function test_disabled_cashier_cannot_sign_in(): void
    {
        Cashier::create([
            'name' => 'Disabled Cashier',
            'phone' => '+963912345678',
            'password' => Hash::make('password123'),
            'is_active' => false,
        ]);

        $response = $this->from(route('admin.login', ['type' => 'cashier']))
            ->post(route('cashier.login.action'), [
                'phone' => '912345678',
                'country_code' => '+963',
                'password' => 'password123',
                'login_type' => 'cashier',
            ]);

        $response->assertRedirect(route('admin.login', ['type' => 'cashier']));
        $response->assertSessionHasErrors('phone');
        $this->assertGuest('cashier');
    }

    public function test_cashier_dashboard_requires_cashier_authentication(): void
    {
        $this->get(route('cashier.tables'))
            ->assertRedirect(route('cashier.login'));
    }

    public function test_cashier_dashboard_shows_busy_tables_before_available_tables(): void
    {
        $cashier = Cashier::create([
            'name' => 'Test Cashier',
            'phone' => '+963912345678',
            'password' => 'password123',
            'is_active' => true,
        ]);
        DB::table('tables')->insert([
            [
                'id' => 1,
                'name' => 'Available Table',
                'number' => 1,
                'code' => '#AVAIL1',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Busy Table',
                'number' => 2,
                'code' => '#BUSY02',
                'status' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->actingAs($cashier, 'cashier')
            ->get(route('cashier.tables'))
            ->assertOk()
            ->assertSeeInOrder(['Busy Table', 'Available Table']);
    }

    public function test_cashier_dashboard_sorts_by_status_then_latest_active_bill_then_table_number(): void
    {
        $cashier = Cashier::create([
            'name' => 'Test Cashier',
            'phone' => '+963912345678',
            'password' => 'password123',
            'is_active' => true,
        ]);
        $tables = [
            [1, 'Available Table', true],
            [2, 'Newest Busy Table', false],
            [3, 'Older Busy Table', false],
            [4, 'Same Time Lower Number', false],
        ];
        foreach ($tables as [$id, $name, $status]) {
            DB::table('tables')->insert([
                'id' => $id,
                'name' => $name,
                'number' => $id,
                'code' => '#SORT'.str_pad((string) $id, 2, '0', STR_PAD_LEFT),
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $now = now();
        DB::table('bills')->insert([
            [
                'table_id' => 1,
                'number' => 'AVAILABLE-NEWEST',
                'status' => 'active',
                'created_at' => $now->copy()->addMinutes(10),
                'updated_at' => $now,
            ],
            [
                'table_id' => 2,
                'number' => 'BUSY-NEWEST',
                'status' => 'active',
                'created_at' => $now->copy()->addMinutes(20),
                'updated_at' => $now,
            ],
            [
                'table_id' => 3,
                'number' => 'BUSY-OLDER',
                'status' => 'active',
                'created_at' => $now->copy()->addMinutes(5),
                'updated_at' => $now,
            ],
            [
                'table_id' => 4,
                'number' => 'BUSY-SAME-TIME',
                'status' => 'active',
                'created_at' => $now->copy()->addMinutes(20),
                'updated_at' => $now,
            ],
        ]);

        $this->actingAs($cashier, 'cashier')
            ->get(route('cashier.tables'))
            ->assertOk()
            ->assertSeeInOrder([
                'Newest Busy Table',
                'Same Time Lower Number',
                'Older Busy Table',
                'Available Table',
            ]);
    }

    public function test_cashier_dashboard_prioritizes_the_table_with_the_latest_order_update(): void
    {
        $cashier = Cashier::create([
            'name' => 'Test Cashier',
            'phone' => '+963912345678',
            'password' => 'password123',
            'is_active' => true,
        ]);
        $now = now();
        DB::table('tables')->insert([
            [
                'id' => 1,
                'name' => 'Recently Updated Table',
                'number' => 1,
                'code' => '#UPDATED01',
                'status' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'name' => 'Latest Bill Table',
                'number' => 2,
                'code' => '#LATEST02',
                'status' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
        DB::table('bills')->insert([
            [
                'id' => 1,
                'table_id' => 1,
                'number' => 'OLDER-BILL',
                'status' => 'active',
                'created_at' => $now->copy()->subHour(),
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'table_id' => 2,
                'number' => 'NEWER-BILL',
                'status' => 'active',
                'created_at' => $now->copy()->subMinutes(10),
                'updated_at' => $now,
            ],
        ]);
        DB::table('orders')->insert([
            'bill_id' => 1,
            'food_id' => 1,
            'amount' => 1,
            'total_price' => 100,
            'status' => '1',
            'created_at' => $now->copy()->subMinutes(5),
            'updated_at' => $now,
        ]);

        $this->actingAs($cashier, 'cashier')
            ->get(route('cashier.tables'))
            ->assertOk()
            ->assertSeeInOrder(['Recently Updated Table', 'Latest Bill Table']);
    }

    public function test_cashier_dashboard_shows_only_active_bills_for_each_table(): void
    {
        $cashier = Cashier::create([
            'name' => 'Test Cashier',
            'phone' => '+963912345678',
            'password' => 'password123',
            'is_active' => true,
        ]);
        DB::table('tables')->insert([
            'id' => 1,
            'name' => 'Busy Table',
            'number' => 1,
            'code' => '#BUSY01',
            'status' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('bills')->insert([
            [
                'table_id' => 1,
                'number' => 'ACTIVE-BILL',
                'status' => 'active',
                'start_time' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'table_id' => 1,
                'number' => 'PAYMENT-BILL',
                'status' => 'payment',
                'start_time' => now()->subHour(),
                'created_at' => now()->subHour(),
                'updated_at' => now()->subHour(),
            ],
        ]);

        $this->actingAs($cashier, 'cashier')
            ->get(route('cashier.tables'))
            ->assertOk()
            ->assertSee('ACTIVE-BILL')
            ->assertDontSee('PAYMENT-BILL');
    }

    public function test_cashier_dashboard_groups_same_food_by_status_and_preserves_all_notes(): void
    {
        $cashier = Cashier::create([
            'name' => 'Test Cashier',
            'phone' => '+963912345678',
            'password' => 'password123',
            'is_active' => true,
        ]);
        DB::table('tables')->insert([
            'id' => 1,
            'name' => 'Busy Table',
            'number' => 1,
            'code' => '#BUSY01',
            'status' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('bills')->insert([
            [
                'id' => 1,
                'table_id' => 1,
                'number' => 'ACTIVE-BILL',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'table_id' => 1,
                'number' => 'SECOND-ACTIVE-BILL',
                'status' => 'active',
                'created_at' => now()->subMinute(),
                'updated_at' => now(),
            ],
        ]);
        DB::table('foods')->insert([
            [
                'id' => 1,
                'name' => 'Test Food',
                'price' => 100,
            ],
            [
                'id' => 2,
                'name' => 'Test Food Two',
                'price' => 50,
            ],
        ]);
        DB::table('orders')->insert([
            [
                'bill_id' => 1,
                'food_id' => 1,
                'amount' => 2,
                'total_price' => 200,
                'status' => '0',
                'expected_time' => '00:00:00',
                'note' => 'No onions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bill_id' => 2,
                'food_id' => 1,
                'amount' => 4,
                'total_price' => 400,
                'status' => '0',
                'expected_time' => '00:00:00',
                'note' => 'From second bill',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bill_id' => 1,
                'food_id' => 2,
                'amount' => 1,
                'total_price' => 50,
                'status' => '0',
                'expected_time' => '00:00:00',
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bill_id' => 1,
                'food_id' => 1,
                'amount' => 3,
                'total_price' => 300,
                'status' => '0',
                'expected_time' => '00:00:00',
                'note' => 'Extra spicy',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bill_id' => 1,
                'food_id' => 1,
                'amount' => 1,
                'total_price' => 100,
                'status' => '1',
                'expected_time' => '01:02:00',
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bill_id' => 2,
                'food_id' => 1,
                'amount' => 2,
                'total_price' => 200,
                'status' => '1',
                'expected_time' => '00:30:00',
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $response = $this->actingAs($cashier, 'cashier')
            ->get(route('cashier.tables'))
            ->assertOk()
            ->assertSee('data-has-pending-confirmation="true"', false)
            ->assertSee('data-confirm-order', false)
            ->assertDontSee('window.location.reload()', false);

        $normalizedHtml = preg_replace('/\s+/', ' ', $response->getContent());
        foreach ([
            'ملاحظة: No onions',
            'ملاحظة: Extra spicy',
            'ملاحظة: From second bill',
            'قيد التاكيد',
            'في المطبخ',
            'مدة التحضير المجمّعة: 01:32',
            'Test Food Two',
            'طلب بانتظار التأكيد',
        ] as $visibleText) {
            $this->assertStringContainsString($visibleText, $normalizedHtml);
        }
        $this->assertStringContainsString('× 9', $normalizedHtml);
        $this->assertStringContainsString('× 3', $normalizedHtml);
        $this->assertSame(1, substr_count($response->getContent(), 'فتح الطلبات المعلّقة'));
        $this->assertSame(1, substr_count($response->getContent(), '<dialog id="pending-orders-dialog-1"'));
        $this->assertSame(4, substr_count($response->getContent(), 'name="expected_minutes"'));
    }

    public function test_menu_order_data_exposes_status_codes_for_bill_request_visibility(): void
    {
        DB::table('tables')->insert([
            'id' => 1,
            'name' => 'Busy Table',
            'number' => 1,
            'code' => '#BUSY01',
            'status' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('bills')->insert([
            'id' => 1,
            'table_id' => 1,
            'number' => 'TEST-BILL',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('foods')->insert([
            'id' => 1,
            'name' => 'Test Food',
            'price' => 100,
        ]);
        DB::table('orders')->insert([
            [
                'bill_id' => 1,
                'food_id' => 1,
                'amount' => 1,
                'total_price' => 100,
                'status' => '1',
                'expected_time' => '00:15:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bill_id' => 1,
                'food_id' => 1,
                'amount' => 1,
                'total_price' => 100,
                'status' => '3',
                'expected_time' => '00:15:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $response = app(MenuController::class)->updateData('TEST-BILL');
        $statuses = $response->getData(true)['data'][0]['statuses'];

        $this->assertSame(['1', '3'], array_column($statuses, 'status_code'));
    }

    public function test_bill_request_button_is_visible_on_initial_render_when_all_orders_are_on_table(): void
    {
        $htmlWithStatusThree = view('Menu.components.orders-modal', [
            'data' => [
                [
                    'amount' => 2,
                    'total_price' => 200,
                    'statuses' => [
                        ['status_code' => '3', 'status' => 'على الطاولة', 'amount' => 2],
                    ],
                ],
            ],
            'services' => [],
        ])->render();

        preg_match('/<button id="request-bill"[^>]*>/', $htmlWithStatusThree, $statusThreeButtonMatch);
        $this->assertNotEmpty($statusThreeButtonMatch);
        $this->assertStringNotContainsString('hidden', $statusThreeButtonMatch[0]);

        $htmlWithStatusTwo = view('Menu.components.orders-modal', [
            'data' => [
                [
                    'amount' => 2,
                    'total_price' => 200,
                    'statuses' => [
                        ['status_code' => '2', 'status' => 'على الطاولة', 'amount' => 2],
                    ],
                ],
            ],
            'services' => [],
        ])->render();

        preg_match('/<button id="request-bill"[^>]*>/', $htmlWithStatusTwo, $statusTwoButtonMatch);
        $this->assertNotEmpty($statusTwoButtonMatch);
        $this->assertStringNotContainsString('hidden', $statusTwoButtonMatch[0]);

        $htmlWithKitchenOrders = view('Menu.components.orders-modal', [
            'data' => [
                [
                    'amount' => 2,
                    'total_price' => 200,
                    'statuses' => [
                        ['status_code' => '3', 'status' => 'على الطاولة', 'amount' => 1],
                        ['status_code' => '1', 'status' => 'في المطبخ', 'amount' => 1],
                    ],
                ],
            ],
            'services' => [],
        ])->render();

        preg_match('/<button id="request-bill"[^>]*>/', $htmlWithKitchenOrders, $hiddenButtonMatch);
        $this->assertNotEmpty($hiddenButtonMatch);
        $this->assertStringContainsString('hidden', $hiddenButtonMatch[0]);
    }

    public function test_cashier_can_confirm_a_pending_order_with_an_expected_preparation_time(): void
    {
        Event::fake([CashierDashboardUpdated::class]);

        $cashier = Cashier::create([
            'name' => 'Test Cashier',
            'phone' => '+963912345678',
            'password' => 'password123',
            'is_active' => true,
        ]);
        DB::table('tables')->insert([
            'id' => 1,
            'name' => 'Busy Table',
            'number' => 1,
            'code' => '#BUSY01',
            'status' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('bills')->insert([
            'id' => 1,
            'table_id' => 1,
            'number' => 'ACTIVE-BILL',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('foods')->insert([
            'id' => 1,
            'name' => 'Test Food',
            'price' => 100,
        ]);
        DB::table('orders')->insert([
            'id' => 1,
            'bill_id' => 1,
            'food_id' => 1,
            'amount' => 2,
            'total_price' => 200,
            'status' => '0',
            'expected_time' => '00:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($cashier, 'cashier')
            ->postJson(route('cashier.orders.confirm', 1), [
                'expected_minutes' => 62,
            ])
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'تم تأكيد الطلب وإرساله إلى المطبخ.',
            ]);

        $this->assertDatabaseHas('orders', [
            'id' => 1,
            'status' => '1',
            'expected_time' => '01:02:00',
        ]);
        $updatedOrderData = app(MenuController::class)
            ->updateData('ACTIVE-BILL')
            ->getData(true)['data'][0]['statuses'];
        $this->assertSame(['1'], array_column($updatedOrderData, 'status_code'));
        $this->assertSame(['في المطبخ'], array_column($updatedOrderData, 'status'));
        Event::assertDispatched(
            CashierDashboardUpdated::class,
            fn (CashierDashboardUpdated $event): bool => $event->tableId === 1
        );
    }

    public function test_cashier_cannot_confirm_a_pending_order_with_zero_expected_minutes(): void
    {
        $cashier = Cashier::create([
            'name' => 'Test Cashier',
            'phone' => '+963912345678',
            'password' => 'password123',
            'is_active' => true,
        ]);
        DB::table('tables')->insert([
            'id' => 1,
            'name' => 'Busy Table',
            'number' => 1,
            'code' => '#BUSY01',
            'status' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('bills')->insert([
            'id' => 1,
            'table_id' => 1,
            'number' => 'ACTIVE-BILL',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('foods')->insert([
            'id' => 1,
            'name' => 'Test Food',
            'price' => 100,
        ]);
        DB::table('orders')->insert([
            'id' => 1,
            'bill_id' => 1,
            'food_id' => 1,
            'amount' => 1,
            'total_price' => 100,
            'status' => '0',
            'expected_time' => '00:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($cashier, 'cashier')
            ->from(route('cashier.tables'))
            ->post(route('cashier.orders.confirm', 1), [
                'expected_minutes' => 0,
            ])
            ->assertRedirect(route('cashier.tables'))
            ->assertSessionHasErrors('expected_minutes');

        $this->assertDatabaseHas('orders', [
            'id' => 1,
            'status' => '0',
            'expected_time' => '00:00:00',
        ]);
    }

    public function test_scheduled_command_marks_only_due_kitchen_orders_as_on_table(): void
    {
        Event::fake([CashierDashboardUpdated::class]);
        $now = now();
        DB::table('tables')->insert([
            'id' => 1,
            'name' => 'Busy Table',
            'number' => 1,
            'code' => '#BUSY01',
            'status' => false,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('bills')->insert([
            [
                'id' => 1,
                'table_id' => 1,
                'number' => 'ACTIVE-BILL',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'table_id' => 1,
                'number' => 'CLOSED-BILL',
                'status' => 'payment',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
        DB::table('foods')->insert([
            'id' => 1,
            'name' => 'Test Food',
            'price' => 100,
        ]);
        DB::table('orders')->insert([
            [
                'id' => 1,
                'bill_id' => 1,
                'food_id' => 1,
                'amount' => 1,
                'total_price' => 100,
                'status' => '1',
                'expected_time' => '00:15:00',
                'created_at' => $now->copy()->subMinutes(20),
                'updated_at' => $now->copy()->subMinutes(20),
            ],
            [
                'id' => 2,
                'bill_id' => 1,
                'food_id' => 1,
                'amount' => 1,
                'total_price' => 100,
                'status' => '1',
                'expected_time' => '00:15:00',
                'created_at' => $now->copy()->subMinutes(10),
                'updated_at' => $now->copy()->subMinutes(10),
            ],
            [
                'id' => 3,
                'bill_id' => 1,
                'food_id' => 1,
                'amount' => 1,
                'total_price' => 100,
                'status' => '0',
                'expected_time' => '00:15:00',
                'created_at' => $now->copy()->subMinutes(20),
                'updated_at' => $now->copy()->subMinutes(20),
            ],
            [
                'id' => 4,
                'bill_id' => 2,
                'food_id' => 1,
                'amount' => 1,
                'total_price' => 100,
                'status' => '1',
                'expected_time' => '00:15:00',
                'created_at' => $now->copy()->subMinutes(20),
                'updated_at' => $now->copy()->subMinutes(20),
            ],
        ]);

        $this->artisan('orders:mark-ready')->assertExitCode(0);

        $this->assertDatabaseHas('orders', ['id' => 1, 'status' => '3']);
        $this->assertDatabaseHas('orders', ['id' => 2, 'status' => '1']);
        $this->assertDatabaseHas('orders', ['id' => 3, 'status' => '0']);
        $this->assertDatabaseHas('orders', ['id' => 4, 'status' => '1']);
        Event::assertDispatched(
            CashierDashboardUpdated::class,
            fn (CashierDashboardUpdated $event): bool => $event->tableId === 1
        );
    }

    public function test_order_confirmation_requires_cashier_authentication(): void
    {
        DB::table('tables')->insert([
            'id' => 1,
            'name' => 'Busy Table',
            'number' => 1,
            'code' => '#BUSY01',
            'status' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('bills')->insert([
            'id' => 1,
            'table_id' => 1,
            'number' => 'ACTIVE-BILL',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('foods')->insert([
            'id' => 1,
            'name' => 'Test Food',
            'price' => 100,
        ]);
        DB::table('orders')->insert([
            'id' => 1,
            'bill_id' => 1,
            'food_id' => 1,
            'amount' => 1,
            'total_price' => 100,
            'status' => '0',
            'expected_time' => '00:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->post(route('cashier.orders.confirm', 1), [
            'expected_minutes' => 10,
        ])->assertRedirect(route('cashier.login'));
    }

    public function test_customer_order_dispatches_a_cashier_update_after_saving_orders(): void
    {
        Event::fake([CashierDashboardUpdated::class]);

        DB::table('tables')->insert([
            'id' => 1,
            'name' => 'Busy Table',
            'number' => 1,
            'code' => '#BUSY01',
            'status' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('bills')->insert([
            'id' => 1,
            'table_id' => 1,
            'number' => 'ACTIVE-BILL',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('foods')->insert([
            'id' => 1,
            'name' => 'Test Food',
            'price' => 100,
        ]);

        $this->postJson(route('bill.new.orders'), [
            'billNumber' => 'ACTIVE-BILL',
            'items' => [
                ['id' => 1, 'quantity' => 2, 'note' => 'No onions'],
            ],
        ])
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('orders', [
            'bill_id' => 1,
            'food_id' => 1,
            'amount' => 2,
            'total_price' => 200,
            'note' => 'No onions',
        ]);
        Event::assertDispatched(
            CashierDashboardUpdated::class,
            fn (CashierDashboardUpdated $event): bool => $event->tableId === 1
        );
    }

    public function test_dashboard_update_event_broadcasts_to_cashier_and_table_channels(): void
    {
        $channels = (new CashierDashboardUpdated(7))->broadcastOn();

        $this->assertCount(2, $channels);
        $this->assertInstanceOf(PrivateChannel::class, $channels[0]);
        $this->assertSame('private-cashier.tables', $channels[0]->name);
        $this->assertInstanceOf(Channel::class, $channels[1]);
        $this->assertSame('table.7', $channels[1]->name);
    }

    public function test_cashier_can_authorize_the_private_table_update_channel(): void
    {
        $cashier = Cashier::create([
            'name' => 'Test Cashier',
            'phone' => '+963912345678',
            'password' => 'password123',
            'is_active' => true,
        ]);

        $this->post(route('cashier.login.action'), [
            'phone' => '912345678',
            'country_code' => '+963',
            'password' => 'password123',
        ])->assertRedirect(route('cashier.tables'));

        $this->assertAuthenticatedAs($cashier, 'cashier');

        $this->postJson('/broadcasting/auth', [
            'socket_id' => '123.456',
            'channel_name' => 'private-cashier.tables',
        ])
            ->assertOk()
            ->assertJsonStructure(['auth']);
    }

    public function test_cashier_account_management_requires_admin_authentication(): void
    {
        $this->get(route('admin.cashiers.index'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_update_cashier_details_and_password(): void
    {
        $this->signInAsAdmin();
        $cashier = Cashier::create([
            'name' => 'Old Name',
            'phone' => '+963912345678',
            'password' => 'original-password',
            'is_active' => true,
        ]);

        $response = $this->put(route('admin.cashiers.update', $cashier), [
            'name' => 'Updated Name',
            'phone' => '+963912345679',
            'password' => 'updated-password',
            'password_confirmation' => 'updated-password',
        ]);

        $response->assertRedirect(route('admin.cashiers.index'));
        $response->assertSessionHas('status', 'تم تحديث حساب الكاشير بنجاح.');
        $cashier->refresh();
        $this->assertSame('Updated Name', $cashier->name);
        $this->assertSame('+963912345679', $cashier->phone);
        $this->assertTrue(Hash::check('updated-password', $cashier->password));
    }

    public function test_admin_can_update_cashier_without_changing_password(): void
    {
        $this->signInAsAdmin();
        $cashier = Cashier::create([
            'name' => 'Original Name',
            'phone' => '+963912345678',
            'password' => 'original-password',
            'is_active' => true,
        ]);
        $originalPassword = $cashier->password;

        $response = $this->put(route('admin.cashiers.update', $cashier), [
            'name' => 'Updated Name',
            'phone' => '+963912345678',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertRedirect(route('admin.cashiers.index'));
        $cashier->refresh();
        $this->assertSame('Updated Name', $cashier->name);
        $this->assertSame($originalPassword, $cashier->password);
    }

    public function test_cashier_update_rejects_a_phone_number_used_by_another_account(): void
    {
        $this->signInAsAdmin();
        Cashier::create([
            'name' => 'Existing Cashier',
            'phone' => '+963912345678',
            'password' => 'password123',
            'is_active' => true,
        ]);
        $cashier = Cashier::create([
            'name' => 'Other Cashier',
            'phone' => '+963912345679',
            'password' => 'password123',
            'is_active' => true,
        ]);

        $response = $this->from(route('admin.cashiers.edit', $cashier))
            ->put(route('admin.cashiers.update', $cashier), [
                'name' => 'Other Cashier',
                'phone' => '+963912345678',
                'password' => '',
                'password_confirmation' => '',
            ]);

        $response->assertRedirect(route('admin.cashiers.edit', $cashier));
        $response->assertSessionHasErrors('phone');
        $this->assertSame('+963912345679', $cashier->fresh()->phone);
    }
}
