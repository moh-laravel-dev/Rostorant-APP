<?php

namespace Tests\Feature;

use App\Events\CashierDashboardUpdated;
use App\Models\Captain;
use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CaptainAuthenticationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('tables');
        Schema::dropIfExists('captains');
        Schema::dropIfExists('users');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('captains', function (Blueprint $table) {
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
            $table->unsignedBigInteger('captain_id')->nullable();
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
            $table->decimal('total_price', 10, 2)->nullable();
            $table->json('services')->nullable();
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
            $table->unsignedBigInteger('food_id')->nullable();
            $table->unsignedInteger('amount')->default(1);
            $table->string('status')->default('0');
            $table->decimal('total_price', 10, 2)->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('foods');
        Schema::dropIfExists('bills');
        Schema::dropIfExists('tables');
        Schema::dropIfExists('captains');
        Schema::dropIfExists('users');

        parent::tearDown();
    }

    private function createCaptain(string $name, string $phone, bool $active = true): Captain
    {
        return Captain::create([
            'name' => $name,
            'phone' => $phone,
            'password' => Hash::make('password123'),
            'is_active' => $active,
        ]);
    }

    public function test_captain_can_login_and_only_see_assigned_tables_on_read_only_dashboard(): void
    {
        $captain = $this->createCaptain('Test Captain', '+963912345678');
        DB::table('tables')->insert([
            [
                'captain_id' => $captain->id,
                'name' => 'Assigned Table',
                'number' => 1,
                'code' => '#ASSIGN1',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'captain_id' => null,
                'name' => 'Unassigned Table',
                'number' => 2,
                'code' => '#UNASGN2',
                'status' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->post(route('captain.login.action'), [
            'phone' => '912345678',
            'country_code' => '+963',
            'password' => 'password123',
        ])->assertRedirect(route('captain.tables'));
        $this->assertAuthenticatedAs($captain, 'captain');

        $this->get(route('captain.tables'))
            ->assertOk()
            ->assertSee('Assigned Table')
            ->assertSee('#ASSIGN1')
            ->assertSee('data:image/svg+xml;base64,', false)
            ->assertDontSee('Unassigned Table')
            ->assertDontSee('#UNASGN2');
    }

    public function test_disabled_captain_cannot_login_and_dashboard_requires_captain_authentication(): void
    {
        $this->createCaptain('Inactive Captain', '+963912345678', false);

        $this->from(route('admin.login', ['type' => 'captain']))
            ->post(route('captain.login.action'), [
                'phone' => '912345678',
                'country_code' => '+963',
                'password' => 'password123',
            ])
            ->assertRedirect(route('admin.login', ['type' => 'captain']))
            ->assertSessionHasErrors('phone');

        $this->get(route('captain.tables'))->assertRedirect(route('captain.login'));
    }

    public function test_busy_table_modal_shows_all_orders_services_and_bill_total(): void
    {
        $captain = $this->createCaptain('Test Captain', '+963912345678');
        DB::table('tables')->insert([
            'id' => 1,
            'captain_id' => $captain->id,
            'name' => 'Busy Table',
            'number' => 1,
            'code' => '#BUSY01',
            'status' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('foods')->insert([
            ['id' => 1, 'name' => 'First Dish', 'price' => 50],
            ['id' => 2, 'name' => 'Second Dish', 'price' => 75],
        ]);
        DB::table('bills')->insert([
            'id' => 1,
            'table_id' => 1,
            'number' => 'BILL-001',
            'status' => 'active',
            'total_price' => 175,
            'services' => json_encode([['name' => 'Extra Service', 'price' => 50, 'currency' => 'ل.س']]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('orders')->insert([
            [
                'bill_id' => 1,
                'food_id' => 1,
                'amount' => 1,
                'status' => '0',
                'total_price' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bill_id' => 1,
                'food_id' => 2,
                'amount' => 1,
                'status' => '2',
                'total_price' => 75,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->actingAs($captain, 'captain')
            ->get(route('captain.tables'))
            ->assertOk()
            ->assertSee('First Dish')
            ->assertSee('Second Dish')
            ->assertSee('Extra Service')
            ->assertSee('175.00')
            ->assertSee('BILL-001');
    }

    public function test_admin_can_assign_tables_to_a_captain(): void
    {
        DB::table('users')->insert([
            'name' => 'Test Admin',
            'email' => 'admin@example.test',
            'phone' => '+963900000001',
            'password' => Hash::make('admin-password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $admin = User::where('email', 'admin@example.test')->firstOrFail();
        $captain = $this->createCaptain('Test Captain', '+963912345678');
        $otherCaptain = $this->createCaptain('Other Captain', '+963912345679');
        DB::table('tables')->insert([
            [
                'captain_id' => $otherCaptain->id,
                'name' => 'Transferred Table',
                'number' => 1,
                'code' => '#TRANS01',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'captain_id' => $captain->id,
                'name' => 'Removed Table',
                'number' => 2,
                'code' => '#REMOV2',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.captains.edit', $captain))
            ->assertOk()
            ->assertSee('Transferred Table')
            ->assertSee('الطاولات المسندة');

        $this->actingAs($admin, 'admin')
            ->put(route('admin.captains.update', $captain), [
                'name' => $captain->name,
                'phone' => $captain->phone,
                'tables' => [1],
            ])
            ->assertRedirect(route('admin.captains.index'));

        $this->assertDatabaseHas('tables', ['id' => 1, 'captain_id' => $captain->id]);
        $this->assertDatabaseHas('tables', ['id' => 2, 'captain_id' => null]);
    }

    public function test_admin_can_create_and_delete_captain_accounts(): void
    {
        DB::table('users')->insert([
            'name' => 'Test Admin',
            'email' => 'admin@example.test',
            'phone' => '+963900000001',
            'password' => Hash::make('admin-password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $admin = User::where('email', 'admin@example.test')->firstOrFail();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.captains.index'))
            ->assertOk()
            ->assertSee('إدارة حسابات الكباتن');

        $this->post(route('admin.captains.store'), [
            'name' => 'New Captain',
            'country_code' => '+963',
            'phone' => '912345678',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('admin.captains.index'));

        $captain = Captain::where('phone', '+963912345678')->firstOrFail();
        $this->delete(route('admin.captains.destroy', $captain))
            ->assertRedirect(route('admin.captains.index'));
        $this->assertDatabaseMissing('captains', ['id' => $captain->id]);
    }

    public function test_table_updates_are_broadcast_to_the_assigned_captain_channel(): void
    {
        $captain = $this->createCaptain('Test Captain', '+963912345678');
        DB::table('tables')->insert([
            'id' => 1,
            'captain_id' => $captain->id,
            'name' => 'Assigned Table',
            'number' => 1,
            'code' => '#ASSIGN1',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $channels = (new CashierDashboardUpdated(1))->broadcastOn();
        $this->assertCount(3, $channels);
        $this->assertInstanceOf(PrivateChannel::class, $channels[2]);
        $this->assertSame('private-captain.'.$captain->id.'.tables', $channels[2]->name);

        $this->post(route('captain.login.action'), [
            'phone' => '912345678',
            'country_code' => '+963',
            'password' => 'password123',
        ])->assertRedirect(route('captain.tables'));
        $this->postJson('/broadcasting/auth', [
            'socket_id' => '123.456',
            'channel_name' => 'private-captain.'.$captain->id.'.tables',
        ])->assertOk()->assertJsonStructure(['auth']);
    }

    public function test_shared_login_page_includes_captain_login_tab(): void
    {
        $this->get(route('admin.login', ['type' => 'captain']))
            ->assertOk()
            ->assertSee('دخول الكابتن')
            ->assertSee(route('captain.login.action'));
    }
}
