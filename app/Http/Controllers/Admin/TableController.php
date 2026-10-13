<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TableController extends Controller
{

    public function index(Request $request)
{
    $query = Table::query();

    if ($request->filled('search')) {
        $search = trim($request->search);

        $query->where(function ($q) use ($search) {
            $q->Where('number', 'like', "%{$search}%")->orwhere('code', 'like', "%{$search}%")
                ;
        });
    }

    $tables = $query->orderBy('number')->get();

    return view('tables.index', compact('tables'));
}

    public function qr(Table $table)
    {
        $qrCode = base64_encode(
            QrCode::format('svg')
                ->size(300)
                ->margin(2)
                ->generate(url(env('QR_URL') . $table->code))
        );

        return view('tables.qr', compact('table', 'qrCode'));
    }
    public function qrPrint(Table $table)
    {
        $qrCode = base64_encode(
            QrCode::format('svg')
                ->size(300)
                ->margin(2)
                ->generate(url(env('QR_URL') . $table->code))
        );


    }

}