<?php

namespace App\Http\Controllers;

use App\Events\CashierDashboardUpdated;
use App\Events\CashierEndBill;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BillController extends Controller
{
    private function filePath(): string
    {
        return storage_path('app/table_services.json');
    }

    public function joneToBill()
    {
        return view('Menu.join-to-bill');
    }

    public function billFinalize(Request $request)
    {
        $validated = $request->validate([
            'billNumber' => ['required', 'string'],
        ], [
            'billNumber.required' => 'رقم الفاتورة مطلوب.',
            'billNumber.string' => 'رقم الفاتورة غير صالح.',
        ]);

        $bill = Bill::where('number', $validated['billNumber'])->first();

        if (! $bill) {
            return response()->json([
                'message' => 'الفاتورة غير موجودة.',
            ], 404);
        }

        $bill->update(['status' => 'payment']);
        event(new CashierDashboardUpdated($bill->table->id));

        return response()->json([
            'message' => 'تم إرسال طلب الفاتورة بنجاح.',
        ], 200);
    }

    public function confirmBillFinalize(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'billNumber' => ['required', 'string', 'exists:bills,number'],
        ], [
            'billNumber.required' => 'رقم الفاتورة مطلوب.',
            'billNumber.string' => 'رقم الفاتورة غير صالح.',
            'billNumber.exists' => 'الفاتورة غير موجودة.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $bill = Bill::where('number', $request->billNumber)->first();

        if ($bill->status !== 'payment') {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن إنهاء الفاتورة بالحالة الحالية.',
            ], Response::HTTP_BAD_REQUEST);
        }
        $path = $this->filePath();

        $servicesPrice = 0;
        $services = [];

        if (File::exists($path)) {
            $servicesPrice = collect(json_decode(File::get($path), true) ?? [])
                ->filter(fn ($service) => $service['active'] ?? false)
                ->sum(fn ($service) => $service['value'] ?? 0);
            $services = collect(json_decode(File::get($path), true) ?? [])
                ->filter(fn ($service) => $service['active'] ?? false)
                ->map(fn ($service) => [
                    'name' => $service['name'] ?? '',
                    'price' => ($service['value'] ?? 0),
                    'currency' => $service['currency'],
                ])
                ->values()
                ->all();
        }

        $totalPrice = $bill->orders()->sum('total_price') + $servicesPrice;
        DB::transaction(function () use ($bill, $totalPrice, $services): void {
            $bill->update([
                'status' => 'completed',
                'total_price' => $totalPrice,
                'services' => $services,
                'end_time' => now(),
            ]);

            $bill->table->update([
                'status' => 1,
            ]);
        });

        DB::table('sessions')->where('table_id', $bill->table->id)->delete();

        event(new CashierDashboardUpdated($bill->table->id));
        event(new CashierEndBill($bill->number));

        return response()->json([
            'success' => true,
            'message' => 'تم إنهاء الفاتورة بنجاح.',
        ], Response::HTTP_OK);
    }
}
