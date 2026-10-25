<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminViewController extends Controller
{
    public function dashboard(): View
    {
        $totalOrders = Order::whereDate('created_at', today())->count();
        $activeOrders = Order::whereDate('created_at', today())
            ->whereHas('bill', fn ($query) => $query->whereIn('status', ['active', 'payment']))
            ->count();
        $totalSales = Bill::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total_price');
        $totalTables = Table::count();
        $occupiedTables = Table::where('status', false)->count();
        $recentOrders = Order::with(['bill.table', 'food'])
            ->whereDate('created_at', today())
            ->latest('created_at')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.dashboard', compact(
            'totalOrders',
            'activeOrders',
            'totalSales',
            'totalTables',
            'occupiedTables',
            'recentOrders',
        ));
    }

    public function login()
    {
        return view('admin.login');
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function reports(Request $request): View
    {
        $filters = $request->validate([
            'circle_year' => ['sometimes', 'integer', 'min:1900', 'max:2100'],
            'line_year' => ['sometimes', 'integer', 'min:1900', 'max:2100'],
            'line_month' => ['sometimes', 'integer', 'min:1', 'max:12'],
        ]);

        $circleYear = (int) ($filters['circle_year'] ?? now()->year);
        $lineYear = (int) ($filters['line_year'] ?? now()->year);
        $lineMonth = (int) ($filters['line_month'] ?? now()->month);
        $months = [
            'كانون الثاني',
            'شباط',
            'آذار',
            'نيسان',
            'أيار',
            'حزيران',
            'تموز',
            'آب',
            'أيلول',
            'تشرين الأول',
            'تشرين الثاني',
            'كانون الأول',
        ];

        $yearDailyTotals = Bill::query()
            ->whereYear('created_at', $circleYear)
            ->selectRaw('DATE(created_at) as bill_date, SUM(COALESCE(total_price, 0)) as total')
            ->groupBy('bill_date')
            ->pluck('total', 'bill_date');
        $yearlySales = array_fill(1, 12, 0.0);
        foreach ($yearDailyTotals as $date => $total) {
            $month = (int) substr((string) $date, 5, 2);
            $yearlySales[$month] += (float) $total;
        }

        $dailyTotals = Bill::query()
            ->whereYear('created_at', $lineYear)
            ->whereMonth('created_at', $lineMonth)
            ->selectRaw('DATE(created_at) as bill_date, SUM(COALESCE(total_price, 0)) as total')
            ->groupBy('bill_date')
            ->pluck('total', 'bill_date');
        $daysInMonth = now()->setDate($lineYear, $lineMonth, 1)->daysInMonth;
        $dailySales = array_fill(1, $daysInMonth, 0.0);
        foreach ($dailyTotals as $date => $total) {
            $day = (int) substr((string) $date, 8, 2);
            $dailySales[$day] = (float) $total;
        }

        return view('admin.reports.index', [
            'circleYear' => $circleYear,
            'lineYear' => $lineYear,
            'lineMonth' => $lineMonth,
            'months' => $months,
            'yearlySales' => $yearlySales,
            'dailySales' => $dailySales,
            'yearlySalesTotal' => array_sum($yearlySales),
            'monthlySalesTotal' => array_sum($dailySales),
        ]);
    }
}
