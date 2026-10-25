<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CaptainDashboardController extends Controller
{
    public function index(): View
    {
        $tables = Table::query()
            ->with([
                'bills' => fn ($query) => $query
                    ->whereIn('status', ['active', 'payment'])
                    ->with('orders.food')
                    ->orderByDesc('created_at'),
            ])
            ->where('captain_id', Auth::guard('captain')->id())
            ->orderBy('number')
            ->get()
            ->map(function (Table $table): Table {
                $qrTarget = url(env('QR_URL').ltrim($table->code, '#'));
                $table->setAttribute('qr_image', base64_encode(
                    QrCode::format('svg')->size(180)->margin(1)->generate($qrTarget)
                ));

                return $table;
            });

        return view('captain.tables', compact('tables'));
    }
}
