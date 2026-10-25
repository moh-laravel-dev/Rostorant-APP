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
                $q->Where('number', 'like', "%{$search}%");
                // ->orwhere('code', 'like', "%{$search}%")
            });
        }

        $tables = $query->orderBy('number')->get();

        return view('tables.index', compact('tables'));
    }

    public function qr(Table $table)
    {
        $qrCode = $this->generateQrCode($table);

        return view('tables.qr', compact('table', 'qrCode'));
    }

    // public function qrPrint(Table $table)
    // {
    //     $qrCode = base64_encode(
    //         QrCode::format('svg')
    //             ->size(300)
    //             ->margin(2)
    //             ->generate(url(env('QR_URL') . $table->code))
    //     );
    //
    //
    // }
    public function edit(string $code)
    {
        $table = Table::where('code', $code)->firstOrFail();
        $qrCode = $this->generateQrCode($table);

        return view('tables.edit', compact('table', 'qrCode'));
    }

    private function generateQrCode(Table $table): string
    {
        return base64_encode(
            QrCode::format('svg')
                ->size(300)
                ->margin(2)
                ->generate(url(env('QR_URL').ltrim($table->code, '#')))
        );
    }

    public function create()
    {
        $nextNumber = (int) Table::max('number') + 1;

        return view('tables.create', compact('nextNumber'));

        return view('tables.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'status' => 'required|boolean',
            ],
            [
                'name.required' => 'حقل الاسم مطلوب.',
                'name.string' => 'حقل الاسم يجب أن يكون نصًا.',
                'name.max' => 'حقل الاسم يجب ألا يتجاوز 255 حرفًا.',
                'status.required' => 'حقل الحالة مطلوب.',
                'status.boolean' => 'حقل الحالة يجب أن يكون صحيحًا أو خاطئًا.',
            ]
        );

        $nextNumber = (int) Table::max('number') + 1;
        $name = trim($request->input('name'));

        Table::create([
            'number' => $nextNumber,
            'name' => $name,
            'status' => $request->status,
        ]);

        return redirect()->route('tables.index')->with('success', 'تم إضافة الطاولة بنجاح.');
    }

    public function update(Request $request, string $code)
    {
        $table = Table::where('code', $code)->firstOrFail();

        $request->validate(
            [
                'name' => 'required|string|max:255',
                'status' => 'required|boolean',
            ],
            [
                'name.required' => 'حقل الاسم مطلوب.',
                'name.string' => 'حقل الاسم يجب أن يكون نصًا.',
                'name.max' => 'حقل الاسم يجب ألا يتجاوز 255 حرفًا.',
                'status.required' => 'حقل الحالة مطلوب.',
                'status.boolean' => 'حقل الحالة يجب أن يكون صحيحًا أو خاطئًا.',
            ]
        );
        $name = trim($request->input('name'));
        $table->update(['name' => $name, 'status' => $request->status]);

        return redirect()->route('tables.edit', $table->code)->with('status', 'تم تحديث بيانات الطاولة بنجاح.');
    }

    public function destroy(string $code)
    {
        $table = Table::where('code', $code)->firstOrFail();
        if (! $table->status) {
            return redirect()->route('tables.edit', $table->code)->withErrors(['لا يمكن حذف الطاولة لأنها مشغولة حاليًا.']);
        }
        $table->delete();

        return redirect()->route('tables.index')->with('success', 'تم حذف الطاولة بنجاح.');
    }
}
