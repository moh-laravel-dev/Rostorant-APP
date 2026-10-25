<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Bill;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ActiveBillEditTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::disableForeignKeyConstraints();
        foreach (['orders', 'foods', 'category_foods', 'bills', 'tables', 'users'] as $table) {
            Schema::dropIfExists($table);
        }
        Schema::enableForeignKeyConstraints();

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('password');
            $table->timestamps();
        });
        Schema::create('bills', function (Blueprint $table): void {
            $table->id();
            $table->string('number');
            $table->string('code');
            $table->string('status');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->unsignedBigInteger('table_id')->nullable();
            $table->decimal('total_price', 10, 2)->nullable();
            $table->json('services')->nullable();
            $table->timestamps();
        });
        Schema::create('tables', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('number');
            $table->string('code');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
        Schema::create('category_foods', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('photo')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        Schema::create('foods', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('category_food_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->string('image')->nullable();
            $table->timestamps();
        });
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('bill_id');
            $table->unsignedBigInteger('food_id');
            $table->unsignedInteger('amount');
            $table->string('status');
            $table->time('expected_time');
            $table->decimal('total_price', 10, 2);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        Schema::disableForeignKeyConstraints();
        foreach (['orders', 'foods', 'category_foods', 'bills', 'tables', 'users'] as $table) {
            Schema::dropIfExists($table);
        }
        Schema::enableForeignKeyConstraints();

        parent::tearDown();
    }

    private function signInAsAdmin(): void
    {
        DB::table('users')->insert([
            'name' => 'Test Admin',
            'email' => 'admin@example.test',
            'phone' => '+963900000000',
            'password' => 'password',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs(User::first(), 'admin');
    }

    private function createBill(string $number, string $status = 'active'): Bill
    {
        return Bill::create([
            'number' => $number,
            'code' => 'code-' . $number,
            'status' => $status,
            'total_price' => 0,
        ]);
    }

    private function createOrder(Bill $bill, int $foodId, int $amount = 1): Order
    {
        return Order::create([
            'bill_id' => $bill->id,
            'food_id' => $foodId,
            'amount' => $amount,
            'status' => '1',
            'expected_time' => '00:20:00',
            'total_price' => 0,
            'note' => 'بدون بصل',
        ]);
    }

    public function test_admin_can_update_active_bill_quantities_and_totals(): void
    {
        $this->signInAsAdmin();
        $bill = $this->createBill('1001');
        $foodId = DB::table('foods')->insertGetId([
            'name' => 'طبق اليوم',
            'price' => 8.50,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $order = $this->createOrder($bill, $foodId, 1);
        DB::table('foods')->where('id', $foodId)->update(['price' => 12.50]);

        $this->get(route('admin.bills.edit', $bill))
            ->assertOk()
            ->assertSee('طبق اليوم')
            ->assertSee('value="1"', false);

        $this->withoutMiddleware(VerifyCsrfToken::class)
            ->put(route('admin.bills.update', $bill), [
                'orders' => [$order->id => ['amount' => 3]],
            ])
            ->assertRedirect(route('admin.bills.index'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'amount' => 3,
            'total_price' => '37.50',
            'status' => '1',
            'expected_time' => '00:20:00',
            'note' => 'بدون بصل',
        ]);
        $this->assertDatabaseHas('bills', [
            'id' => $bill->id,
            'total_price' => '37.50',
        ]);
    }

    public function test_admin_dashboard_uses_current_order_bill_sales_and_table_data(): void
    {
        $this->signInAsAdmin();
        $availableTableId = DB::table('tables')->insertGetId([
            'name' => 'الداخلية',
            'number' => 1,
            'code' => '#TABLE1',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $occupiedTableId = DB::table('tables')->insertGetId([
            'name' => 'الخارجية',
            'number' => 2,
            'code' => '#TABLE2',
            'status' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $foodId = DB::table('foods')->insertGetId([
            'name' => 'وجبة حقيقية',
            'price' => 30,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $activeBill = $this->createBill('DASH-ACTIVE');
        $activeBill->update(['table_id' => $occupiedTableId]);
        $paymentBill = $this->createBill('DASH-PAYMENT', 'payment');
        $paymentBill->update(['table_id' => $availableTableId]);
        $completedBill = $this->createBill('DASH-COMPLETED', 'completed');
        $completedBill->update(['total_price' => 90]);

        foreach ([
            [$activeBill, '0', 10],
            [$paymentBill, '1', 20],
            [$completedBill, '2', 30],
        ] as [$bill, $status, $lineTotal]) {
            $order = $this->createOrder($bill, $foodId);
            DB::table('orders')->where('id', $order->id)->update([
                'status' => $status,
                'total_price' => $lineTotal,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $oldCompletedBill = $this->createBill('DASH-OLD-COMPLETED', 'completed');
        DB::table('bills')->where('id', $oldCompletedBill->id)->update([
            'total_price' => 500,
            'created_at' => today()->subDay(),
        ]);
        $oldOrder = $this->createOrder($oldCompletedBill, $foodId);
        DB::table('orders')->where('id', $oldOrder->id)->update([
            'status' => '2',
            'total_price' => 500,
            'created_at' => today()->subDay(),
        ]);

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('إجمالي طلبات اليوم')
            ->assertSee('>3</h4>', false)
            ->assertSee('>2', false)
            ->assertSee('90</h4>', false)
            ->assertSee('1 /')
            ->assertSee('طاولة 2')
            ->assertSee('وجبة حقيقية')
            ->assertSee('DASH-COMPLETED')
            ->assertDontSee('DASH-OLD-COMPLETED')
            ->assertSee(route('admin.bills.index'), false)
            ->assertDontSee('أحمد محمد')
            ->assertDontSee('+12% مقارنة بالشهر الماضي');
    }

    public function test_admin_dashboard_shows_only_today_orders_with_pagination(): void
    {
        $this->signInAsAdmin();
        $bill = $this->createBill('DASH-TODAY');

        foreach (range(1, 11) as $number) {
            $foodId = DB::table('foods')->insertGetId([
                'name' => $number === 1 ? 'أول وجبة اليوم' : 'وجبة اليوم ' . $number,
                'price' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $order = $this->createOrder($bill, $foodId);
            DB::table('orders')->where('id', $order->id)->update([
                'created_at' => today()->setTime(8, 0)->addMinutes($number),
            ]);
        }

        $yesterdayFoodId = DB::table('foods')->insertGetId([
            'name' => 'وجبة أمس',
            'price' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $yesterdayOrder = $this->createOrder($bill, $yesterdayFoodId);
        DB::table('orders')->where('id', $yesterdayOrder->id)->update([
            'created_at' => today()->subDay()->setTime(23, 59),
        ]);

        $firstPage = $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('طلبات اليوم')
            ->assertSee('عرض 1–10')
            ->assertSee('من أصل 11 طلب اليوم')
            ->assertSee('وجبة اليوم 11')
            ->assertSee('وجبة اليوم 2')
            ->assertDontSee('أول وجبة اليوم')
            ->assertDontSee('وجبة أمس')
            ->assertSee('page=2', false);

        $this->get(route('admin.dashboard', ['page' => 2]))
            ->assertOk()
            ->assertSee('عرض 11–11')
            ->assertSee('من أصل 11 طلب اليوم')
            ->assertSee('أول وجبة اليوم')
            ->assertDontSee('وجبة اليوم 2')
            ->assertDontSee('وجبة أمس');
    }

    public function test_admin_reports_show_monthly_yearly_and_daily_bill_totals_for_selected_periods(): void
    {
        $this->signInAsAdmin();

        $billAmounts = [
            ['REPORT-YEAR-JAN', '2025-01-03 10:00:00', 100],
            ['REPORT-YEAR-DEC', '2025-12-31 10:00:00', 200],
            ['REPORT-LINE-FIRST', '2026-02-01 09:00:00', 50],
            ['REPORT-LINE-FIRST-SECOND', '2026-02-01 11:00:00', 25],
            ['REPORT-LINE-LAST', '2026-02-28 15:00:00', 75],
            ['REPORT-OTHER-YEAR', '2024-02-01 10:00:00', 500],
        ];

        foreach ($billAmounts as [$number, $createdAt, $totalPrice]) {
            $bill = $this->createBill($number, 'completed');
            DB::table('bills')->where('id', $bill->id)->update([
                'created_at' => $createdAt,
                'total_price' => $totalPrice,
            ]);
        }

        $response = $this->get(route('admin.reports.index', [
            'circle_year' => 2025,
            'line_year' => 2026,
            'line_month' => 2,
        ]))->assertOk()
            ->assertSee('قيمة الفواتير حسب الشهر')
            ->assertSee('قيمة الفواتير لكل يوم')
            ->assertSee('كانون الثاني')
            ->assertSee('شباط');

        $this->assertSame(300.0, $response->viewData('yearlySalesTotal'));
        $this->assertSame(100.0, $response->viewData('yearlySales')[1]);
        $this->assertSame(200.0, $response->viewData('yearlySales')[12]);
        $this->assertSame(150.0, $response->viewData('monthlySalesTotal'));
        $this->assertSame(75.0, $response->viewData('dailySales')[1]);
        $this->assertSame(75.0, $response->viewData('dailySales')[28]);
        $this->assertCount(28, $response->viewData('dailySales'));
        $this->assertSame(2025, $response->viewData('circleYear'));
        $this->assertSame(2026, $response->viewData('lineYear'));
        $this->assertSame(2, $response->viewData('lineMonth'));
    }

    public function test_admin_reports_default_to_current_year_and_month_and_validate_filters(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-24 13:00:00'));
        $this->signInAsAdmin();

        $response = $this->get(route('admin.reports.index'))->assertOk();
        $this->assertSame(2026, $response->viewData('circleYear'));
        $this->assertSame(2026, $response->viewData('lineYear'));
        $this->assertSame(10, $response->viewData('lineMonth'));
        $this->assertCount(31, $response->viewData('dailySales'));

        $this->from(route('admin.reports.index'))
            ->get(route('admin.reports.index', ['circle_year' => 1800]))
            ->assertRedirect(route('admin.reports.index'))
            ->assertSessionHasErrors('circle_year');
    }

    public function test_quantity_must_be_a_positive_integer_and_is_kept_after_validation_error(): void
    {
        $this->signInAsAdmin();
        $bill = $this->createBill('1001');
        $foodId = DB::table('foods')->insertGetId([
            'name' => 'طبق اليوم',
            'price' => 12.50,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $order = $this->createOrder($bill, $foodId, 2);

        $this->withoutMiddleware(VerifyCsrfToken::class)
            ->from(route('admin.bills.edit', $bill))
            ->put(route('admin.bills.update', $bill), [
                'orders' => [$order->id => ['amount' => 0]],
            ])
            ->assertSessionHasErrors("orders.{$order->id}.amount");

        $this->get(route('admin.bills.edit', $bill))
            ->assertOk()
            ->assertSee('value="0"', false);
    }

    public function test_update_rejects_order_ids_from_another_bill(): void
    {
        $this->signInAsAdmin();
        $bill = $this->createBill('1001');
        $otherBill = $this->createBill('1002');
        $foodId = DB::table('foods')->insertGetId([
            'name' => 'طبق اليوم',
            'price' => 12.50,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $otherOrder = $this->createOrder($otherBill, $foodId, 2);

        $this->withoutMiddleware(VerifyCsrfToken::class)
            ->from(route('admin.bills.edit', $bill))
            ->put(route('admin.bills.update', $bill), [
                'orders' => [$otherOrder->id => ['amount' => 5]],
            ])
            ->assertSessionHasErrors('orders');

        $this->assertDatabaseHas('orders', [
            'id' => $otherOrder->id,
            'bill_id' => $otherBill->id,
            'amount' => 2,
        ]);
    }

    public function test_payment_bill_cannot_be_edited_or_updated(): void
    {
        $this->signInAsAdmin();
        $bill = $this->createBill('1001', 'payment');

        $this->get(route('admin.bills.edit', $bill))->assertNotFound();
        $this->withoutMiddleware(VerifyCsrfToken::class)
            ->put(route('admin.bills.update', $bill), ['orders' => []])
            ->assertNotFound();
    }

    public function test_show_requires_admin_authentication(): void
    {
        $bill = $this->createBill('1001');

        $this->get(route('admin.bills.show', $bill))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_delete_order_from_active_bill_and_bill_total_is_recalculated(): void
    {
        $this->signInAsAdmin();
        $bill = $this->createBill('1001');
        $foodId = DB::table('foods')->insertGetId([
            'name' => 'طبق اليوم',
            'price' => 12.50,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $deletedOrder = $this->createOrder($bill, $foodId, 2);
        $remainingOrder = $this->createOrder($bill, $foodId, 1);
        DB::table('orders')->where('id', $deletedOrder->id)->update(['total_price' => 25]);
        DB::table('orders')->where('id', $remainingOrder->id)->update(['total_price' => 12.50]);
        $bill->update(['total_price' => 37.50]);

        $this->get(route('admin.bills.edit', $bill))
            ->assertOk()
            ->assertSee('form="delete-order-' . $deletedOrder->id . '"', false)
            ->assertSee('id="delete-order-' . $deletedOrder->id . '"', false)
            ->assertSee('حذف');

        $this->withoutMiddleware(VerifyCsrfToken::class)
            ->delete(route('admin.bills.orders.destroy', [$bill, $deletedOrder]))
            ->assertRedirect(route('admin.bills.edit', $bill))
            ->assertSessionHas('status');

        $this->assertDatabaseMissing('orders', ['id' => $deletedOrder->id]);
        $this->assertDatabaseHas('orders', [
            'id' => $remainingOrder->id,
            'bill_id' => $bill->id,
            'total_price' => '12.50',
        ]);
        $this->assertDatabaseHas('bills', [
            'id' => $bill->id,
            'total_price' => '12.50',
        ]);
    }

    public function test_order_from_another_bill_cannot_be_deleted(): void
    {
        $this->signInAsAdmin();
        $bill = $this->createBill('1001');
        $otherBill = $this->createBill('1002');
        $foodId = DB::table('foods')->insertGetId([
            'name' => 'طبق اليوم',
            'price' => 12.50,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $otherOrder = $this->createOrder($otherBill, $foodId, 2);
        DB::table('orders')->where('id', $otherOrder->id)->update(['total_price' => 25]);
        $otherBill->update(['total_price' => 25]);

        $this->withoutMiddleware(VerifyCsrfToken::class)
            ->delete(route('admin.bills.orders.destroy', [$bill, $otherOrder]))
            ->assertNotFound();

        $this->assertDatabaseHas('orders', [
            'id' => $otherOrder->id,
            'bill_id' => $otherBill->id,
            'total_price' => '25.00',
        ]);
        $this->assertDatabaseHas('bills', [
            'id' => $otherBill->id,
            'total_price' => '25.00',
        ]);
    }

    public function test_order_cannot_be_deleted_from_payment_bill(): void
    {
        $this->signInAsAdmin();
        $bill = $this->createBill('1001', 'payment');
        $foodId = DB::table('foods')->insertGetId([
            'name' => 'طبق اليوم',
            'price' => 12.50,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $order = $this->createOrder($bill, $foodId, 1);
        DB::table('orders')->where('id', $order->id)->update(['total_price' => 12.50]);
        $bill->update(['total_price' => 12.50]);

        $this->withoutMiddleware(VerifyCsrfToken::class)
            ->delete(route('admin.bills.orders.destroy', [$bill, $order]))
            ->assertNotFound();

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'bill_id' => $bill->id]);
        $this->assertDatabaseHas('bills', ['id' => $bill->id, 'total_price' => '12.50']);
    }

    public function test_only_active_bills_show_an_edit_link(): void
    {
        $this->signInAsAdmin();
        $activeBill = $this->createBill('1001');
        $paymentBill = $this->createBill('1002', 'payment');

        $this->get(route('admin.bills.index'))
            ->assertOk()
            ->assertSee(route('admin.bills.edit', $activeBill), false)
            ->assertDontSee(route('admin.bills.edit', $paymentBill), false);
    }

    public function test_all_bill_statuses_can_be_shown_with_table_and_order_details(): void
    {
        $this->signInAsAdmin();
        $tableId = DB::table('tables')->insertGetId([
            'name' => 'الحديقة',
            'number' => 7,
            'code' => '#TABLE7',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $categoryId = DB::table('category_foods')->insertGetId([
            'name' => 'المقبلات',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $foodId = DB::table('foods')->insertGetId([
            'category_food_id' => $categoryId,
            'name' => 'حمص',
            'description' => 'حمص طازج بزيت الزيتون',
            'price' => 4.50,
            'image' => 'foods/hummus.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $statusLabels = [
            'active' => 'نشطة',
            'payment' => 'بانتظار الدفع',
            'completed' => 'مكتملة',
        ];

        foreach (['active', 'payment', 'completed'] as $index => $status) {
            $bill = $this->createBill('200'.$index, $status);
            $bill->update([
                'table_id' => $tableId,
                'start_time' => '2026-10-22 12:30:00',
                'end_time' => $status === 'active' ? null : '2026-10-22 13:00:00',
                'total_price' => '9.00',
            ]);
            $order = $this->createOrder($bill, $foodId, 2);
            DB::table('orders')->where('id', $order->id)->update([
                'total_price' => '9.00',
                'expected_time' => '00:15:00',
            ]);

            $response = $this->get(route('admin.bills.show', $bill));
            $response->assertOk()
                ->assertSee('الحديقة')
                ->assertSee('طاولة 7')
                ->assertSee('حمص')
                ->assertSee('المقبلات')
                ->assertSee('حمص طازج بزيت الزيتون')
                ->assertSee('foods/hummus.jpg')
                ->assertSee($statusLabels[$status])
                ->assertSee('أُنشئت:')
                ->assertSee('200'.$index);

            if ($status === 'active') {
                $response->assertSee(route('admin.bills.edit', $bill), false);
            } else {
                $response->assertDontSee(route('admin.bills.edit', $bill), false);
            }
        }
    }

        public function test_bill_index_filters_by_any_combination_of_created_at_date_parts(): void
        {
            $this->signInAsAdmin();
            $dateBills = [];

            foreach ([
                ['DATE-LEAP', '2024-02-29 10:00:00'],
                ['DATE-MARCH', '2024-03-01 10:00:00'],
                ['DATE-OTHER-YEAR', '2023-02-28 10:00:00'],
                ['DATE-OTHER-DAY', '2024-02-28 10:00:00'],
            ] as [$number, $createdAt]) {
                $bill = $this->createBill($number);
                DB::table('bills')->where('id', $bill->id)->update(['created_at' => $createdAt]);
                $dateBills[$number] = $bill;
            }

            $filters = [
                [['year' => 2024], ['DATE-LEAP', 'DATE-MARCH'], ['DATE-OTHER-YEAR']],
                [['month' => 2], ['DATE-LEAP', 'DATE-OTHER-YEAR', 'DATE-OTHER-DAY'], ['DATE-MARCH']],
                [['day' => 29], ['DATE-LEAP'], ['DATE-MARCH', 'DATE-OTHER-YEAR', 'DATE-OTHER-DAY']],
                [['year' => 2024, 'month' => 2, 'day' => 29], ['DATE-LEAP'], ['DATE-MARCH', 'DATE-OTHER-YEAR', 'DATE-OTHER-DAY']],
            ];

            foreach ($filters as [$query, $included, $excluded]) {
                $response = $this->get(route('admin.bills.index', $query))->assertOk();

                foreach ($included as $number) {
                    $response->assertSee('data-bill-id="'.$dateBills[$number]->id.'"', false);
                }
                foreach ($excluded as $number) {
                    $response->assertDontSee('data-bill-id="'.$dateBills[$number]->id.'"', false);
                }
            }
        }

        public function test_bill_status_tabs_filter_records_and_preserve_search_date_and_page_size(): void
        {
            $this->signInAsAdmin();
            $paymentBills = [];

            foreach (range(1, 11) as $number) {
                $bill = $this->createBill('PAYMENT-FILTER-'.$number, 'payment');
                DB::table('bills')->where('id', $bill->id)->update(['created_at' => '2024-06-09 10:00:00']);
                $paymentBills[] = $bill;
            }

            $activeBill = $this->createBill('PAYMENT-FILTER-ACTIVE', 'active');
            DB::table('bills')->where('id', $activeBill->id)->update(['created_at' => '2024-06-09 10:00:00']);

            $query = [
                'status' => 'payment',
                'search' => 'PAYMENT-FILTER-',
                'year' => 2024,
                'month' => 6,
                'day' => 9,
                'per_page' => 10,
                'date_filter' => 1,
            ];

            $firstPage = $this->get(route('admin.bills.index', $query))
                ->assertOk()
                ->assertSee('data-bill-id="'.$paymentBills[0]->id.'"', false)
                ->assertDontSee('data-bill-id="'.$activeBill->id.'"', false)
                ->assertSee('page=2')
                ->assertSee('year=2024')
                ->assertSee('month=6')
                ->assertSee('day=9')
                ->assertSee('search=PAYMENT-FILTER-')
                ->assertSee('per_page=10');

            $activeTabQuery = array_merge(
                array_diff_key($query, ['status' => true]),
                ['status' => 'active']
            );
            $firstPage->assertSee(route('admin.bills.index', $activeTabQuery));

            $query['page'] = 2;
            $this->get(route('admin.bills.index', $query))
                ->assertOk()
                ->assertSee('data-bill-id="'.$paymentBills[10]->id.'"', false)
                ->assertDontSee('data-bill-id="'.$paymentBills[0]->id.'"', false)
                ->assertSee('السنة')
                ->assertSee('الشهر')
                ->assertSee('اليوم');
        }

        public function test_bill_index_rejects_out_of_range_or_impossible_date_parts(): void
        {
            $this->signInAsAdmin();

            $this->from(route('admin.bills.index'))
                ->get(route('admin.bills.index', ['month' => 13]))
                ->assertRedirect(route('admin.bills.index'))
                ->assertSessionHasErrors('month');

            $this->from(route('admin.bills.index'))
                ->get(route('admin.bills.index', ['month' => 2, 'day' => 30]))
                ->assertRedirect(route('admin.bills.index'))
                ->assertSessionHasErrors('day');

            $this->from(route('admin.bills.index'))
                ->get(route('admin.bills.index', ['year' => 2025, 'month' => 2, 'day' => 30]))
                ->assertRedirect(route('admin.bills.index'))
                ->assertSessionHasErrors('day');
        }
    public function test_show_handles_nullable_table_dates_missing_food_and_empty_orders(): void
    {
        $this->signInAsAdmin();
        $bill = $this->createBill('3001', 'completed');
        $this->createOrder($bill, 9999);

        $this->get(route('admin.bills.show', $bill))
            ->assertOk()
            ->assertSee('غير مرتبطة بطاولة')
            ->assertSee('طبق غير متوفر')
            ->assertSee('بدون بصل')
            ->assertDontSee('لا توجد طلبات في هذه الفاتورة')
            ->assertSee('غير محدد');

        $emptyBill = $this->createBill('3002', 'completed');
        $this->get(route('admin.bills.show', $emptyBill))
            ->assertOk()
            ->assertSee('لا توجد طلبات في هذه الفاتورة');
    }

    public function test_show_displays_persisted_services_and_subtotal_without_recalculating_bill_total(): void
    {
        $this->signInAsAdmin();
        $bill = $this->createBill('3251', 'completed');
        $bill->update([
            'total_price' => '400.00',
            'services' => [
                ['name' => 'سعر خدمة الطاولة', 'price' => 200, 'currency' => 'ل.س'],
                ['name' => 'سعر خدمة الماء', 'price' => 100, 'currency' => 'ل.س'],
                ['name' => 'سعر خدمة المناديل الورقية', 'price' => 50],
            ],
        ]);
        $foodId = DB::table('foods')->insertGetId([
            'name' => 'وجبة',
            'price' => 50,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $order = $this->createOrder($bill, $foodId);
        DB::table('orders')->where('id', $order->id)->update(['total_price' => '50.00']);

        $this->get(route('admin.bills.show', $bill))
            ->assertOk()
            ->assertSee('سعر خدمة الطاولة')
            ->assertSee('سعر خدمة الماء')
            ->assertSee('سعر خدمة المناديل الورقية')
            ->assertSee('200.00 ل.س')
            ->assertSee('100.00 ل.س')
            ->assertSee('50.00 ل.س')
            ->assertSee('مجموع الخدمات')
            ->assertSee('350.00 ل.س')
            ->assertSee('400.00 ل.س')
            ->assertDontSee('يوجد اختلاف بين الإجمالي المحفوظ ومجموع البنود');

        $this->assertDatabaseHas('bills', [
            'id' => $bill->id,
            'total_price' => '400.00',
        ]);
    }

    public function test_show_ignores_non_array_service_data(): void
    {
        $this->signInAsAdmin();
        $bill = $this->createBill('3252', 'completed');
        DB::table('bills')->where('id', $bill->id)->update(['services' => '"invalid"']);

        $this->get(route('admin.bills.show', $bill))
            ->assertOk()
            ->assertDontSee('مجموع الخدمات')
            ->assertSee('0.00 ل.س');
    }

    public function test_show_groups_service_totals_by_currency_and_skips_incomparable_total_warning(): void
    {
        $this->signInAsAdmin();
        $bill = $this->createBill('3253', 'completed');
        $bill->update([
            'total_price' => '150.00',
            'services' => [
                ['name' => 'خدمة محلية', 'price' => 100, 'currency' => 'ل.س'],
                ['name' => 'خدمة أجنبية', 'price' => 50, 'currency' => 'USD'],
            ],
        ]);

        $this->get(route('admin.bills.show', $bill))
            ->assertOk()
            ->assertSee('مجموع الخدمات (ل.س)')
            ->assertSee('100.00 ل.س')
            ->assertSee('مجموع الخدمات (USD)')
            ->assertSee('50.00 USD')
            ->assertSee('تعذرت مقارنة الإجمالي تلقائيًا لأن رسوم الخدمات تتضمن عملة غير ل.س.')
            ->assertDontSee('يوجد اختلاف بين الإجمالي المحفوظ');
    }

    public function test_show_uses_neutral_styles_for_unknown_statuses(): void
    {
        $this->signInAsAdmin();
        $bill = $this->createBill('3501', 'unexpected');
        $foodId = DB::table('foods')->insertGetId([
            'name' => 'طبق تجريبي',
            'price' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $order = $this->createOrder($bill, $foodId);
        DB::table('orders')->where('id', $order->id)->update(['status' => '9']);

        $this->get(route('admin.bills.show', $bill))
            ->assertOk()
            ->assertSee('bill-show-status-unknown')
            ->assertSee('bill-show-order-unknown')
            ->assertSee('حالة غير معروفة');
    }

    public function test_show_link_is_available_on_desktop_and_mobile_for_every_bill(): void
    {
        $this->signInAsAdmin();
        $activeBill = $this->createBill('4001');
        $paymentBill = $this->createBill('4002', 'payment');
        $completedBill = $this->createBill('4003', 'completed');

        $response = $this->get(route('admin.bills.index'))->assertOk();
        foreach ([$activeBill, $paymentBill, $completedBill] as $bill) {
            $url = route('admin.bills.show', $bill);
            $this->assertSame(2, substr_count($response->getContent(), 'href="'.$url.'"'));
        }

        $this->assertSame(2, substr_count($response->getContent(), 'href="'.route('admin.bills.edit', $activeBill).'"'));
        $this->assertSame(0, substr_count($response->getContent(), 'href="'.route('admin.bills.edit', $paymentBill).'"'));
        $this->assertSame(0, substr_count($response->getContent(), 'href="'.route('admin.bills.edit', $completedBill).'"'));
    }

    public function test_bills_index_paginates_records_and_keeps_global_summary_counts(): void
    {
        $this->signInAsAdmin();

        for ($number = 5001; $number <= 5015; $number++) {
            $this->createBill((string) $number);
        }
        $completedBill = $this->createBill('5016', 'completed');
        $completedBill->update(['total_price' => 45]);
        foreach (range(5006, 5015) as $number) {
            DB::table('bills')->where('number', (string) $number)->update(['created_at' => '2020-01-03 09:00:00']);
        }
        DB::table('bills')->where('number', '5016')->update(['created_at' => '2020-01-03 10:00:00']);

        $firstPage = $this->get(route('admin.bills.index', ['per_page' => 15, 'date_filter' => 1]))
            ->assertOk()
            ->assertSee('page=2', false)
            ->assertSee('per_page=15', false);
        $this->assertSame(16, $firstPage->viewData('billStats')->total_count);
        $this->assertSame(15, $firstPage->viewData('bills')->count());
        $this->assertSame(15, $firstPage->viewData('billStats')->active_count);
        $this->assertSame(1, $firstPage->viewData('billStats')->completed_count);
        $this->assertSame(45.0, (float) $firstPage->viewData('billStats')->total_revenue);

        $secondPage = $this->get(route('admin.bills.index', [
            'page' => 2,
            'per_page' => 15,
            'date_filter' => 1,
        ]))->assertOk();
        $this->assertSame(1, $secondPage->viewData('bills')->count());
        $secondPage->assertSee('5016', false);

        $filteredPage = $this->get(route('admin.bills.index', [
            'status' => 'completed',
            'search' => '5016',
            'per_page' => 25,
            'date_filter' => 1,
        ]))->assertOk()->assertSee('5016', false);
        $this->assertSame(1, $filteredPage->viewData('bills')->total());
        $this->assertSame(25, $filteredPage->viewData('perPage'));

        $dateFilteredPage = $this->get(route('admin.bills.index', [
            'date_filter' => 1,
            'year' => 2020,
            'month' => 1,
            'day' => 3,
            'per_page' => 10,
        ]))->assertOk()
            ->assertSee('page=2', false)
            ->assertSee('year=2020', false)
            ->assertSee('month=1', false)
            ->assertSee('day=3', false)
            ->assertSee('name="year"', false)
            ->assertSee('name="month"', false)
            ->assertSee('name="day"', false);
        $this->assertSame(11, $dateFilteredPage->viewData('bills')->total());
        $this->assertSame('2020', (string) $dateFilteredPage->viewData('year'));
        $this->assertSame('1', (string) $dateFilteredPage->viewData('month'));
        $this->assertSame('3', (string) $dateFilteredPage->viewData('day'));

        $dateFilteredSecondPage = $this->get(route('admin.bills.index', [
            'date_filter' => 1,
            'year' => 2020,
            'month' => 1,
            'day' => 3,
            'per_page' => 10,
            'page' => 2,
        ]))->assertOk();
        $this->assertSame(1, $dateFilteredSecondPage->viewData('bills')->count());
        $dateFilteredSecondPage->assertSee('5016', false);
    }

    public function test_bills_index_defaults_to_current_month_and_scopes_summary_and_status_counts(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-24 13:00:00'));
        $this->signInAsAdmin();

        $thisMonthActive = $this->createBill('CURRENT-MONTH-ACTIVE', 'active');
        $thisMonthCompleted = $this->createBill('CURRENT-MONTH-COMPLETED', 'completed');
        $lastMonthPayment = $this->createBill('LAST-MONTH-PAYMENT', 'payment');
        DB::table('bills')->where('id', $thisMonthActive->id)->update([
            'created_at' => '2026-10-01 09:00:00',
        ]);
        DB::table('bills')->where('id', $thisMonthCompleted->id)->update([
            'created_at' => '2026-10-15 09:00:00',
            'total_price' => 125,
        ]);
        DB::table('bills')->where('id', $lastMonthPayment->id)->update([
            'created_at' => '2026-09-30 09:00:00',
        ]);

        $currentMonth = $this->get(route('admin.bills.index'))->assertOk();
        $this->assertSame(2026, (int) $currentMonth->viewData('year'));
        $this->assertSame(10, (int) $currentMonth->viewData('month'));
        $this->assertSame(2, (int) $currentMonth->viewData('billStats')->total_count);
        $this->assertSame(1, (int) $currentMonth->viewData('billStats')->active_count);
        $this->assertSame(0, (int) $currentMonth->viewData('billStats')->payment_count);
        $this->assertSame(1, (int) $currentMonth->viewData('billStats')->completed_count);
        $this->assertSame(125.0, (float) $currentMonth->viewData('billStats')->total_revenue);
        $currentMonth->assertSee('data-bill-id="'.$thisMonthActive->id.'"', false)
            ->assertSee('data-bill-id="'.$thisMonthCompleted->id.'"', false)
            ->assertDontSee('data-bill-id="'.$lastMonthPayment->id.'"', false);

        $selectedMonth = $this->get(route('admin.bills.index', [
            'date_filter' => 1,
            'year' => 2026,
            'month' => 9,
        ]))->assertOk();
        $this->assertSame(1, (int) $selectedMonth->viewData('billStats')->total_count);
        $this->assertSame(0, (int) $selectedMonth->viewData('billStats')->active_count);
        $this->assertSame(1, (int) $selectedMonth->viewData('billStats')->payment_count);
        $this->assertSame(0, (int) $selectedMonth->viewData('billStats')->completed_count);
        $this->assertSame(0.0, (float) $selectedMonth->viewData('billStats')->total_revenue);
        $selectedMonth->assertSee(route('admin.bills.index', [
            'year' => 2026,
            'month' => 9,
            'per_page' => 10,
            'date_filter' => 1,
            'status' => 'payment',
        ]));

        $allDates = $this->get(route('admin.bills.index', ['date_filter' => 1]))->assertOk();
        $this->assertSame(3, (int) $allDates->viewData('billStats')->total_count);
        $this->assertSame(3, $allDates->viewData('bills')->total());
    }

    public function test_bills_index_rejects_unsupported_page_sizes(): void
    {
        $this->signInAsAdmin();

        $this->get(route('admin.bills.index', ['per_page' => 1000]))
            ->assertSessionHasErrors('per_page');
    }
}
