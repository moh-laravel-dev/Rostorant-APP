<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Captain;
use App\Models\Table;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CaptainController extends Controller
{
    public function index(): View
    {
        $captains = Captain::withCount('tables')->orderBy('name')->get();

        return view('admin.captains.index', compact('captains'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'country_code' => ['required', 'string', 'regex:/^\+[1-9]\d{0,3}$/'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^\d+$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $phone = $validated['country_code'].$validated['phone'];
        validator(
            ['phone' => $phone],
            ['phone' => ['required', Rule::unique('captains', 'phone')]],
            ['phone.unique' => 'رقم الهاتف مسجل مسبقًا لحساب كابتن.']
        )->validate();

        Captain::create([
            'name' => trim($validated['name']),
            'phone' => $phone,
            'password' => $validated['password'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.captains.index')->with('status', 'تم إنشاء حساب الكابتن بنجاح.');
    }

    public function edit(Captain $captain): View
    {
        $tables = Table::orderBy('number')->where('captain_id', $captain->id)->orWhere('captain_id', null)->get();
        $assignedTableIds = $captain->tables()->pluck('id')->all();

        return view('admin.captains.edit', compact('captain', 'tables', 'assignedTableIds'));
    }

    public function update(Request $request, Captain $captain): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^\+[1-9]\d{7,14}$/', Rule::unique('captains', 'phone')->ignore($captain->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'tables' => ['sometimes', 'array'],
            'tables.*' => ['integer', 'distinct', 'exists:tables,id'],
        ], [
            'phone.regex' => 'أدخل رقم الهاتف الدولي كاملًا، مثل +963912345678.',
            'phone.unique' => 'رقم الهاتف مسجل مسبقًا لحساب كابتن آخر.',
            'tables.*.exists' => 'إحدى الطاولات المختارة غير موجودة.',
        ]);

        DB::transaction(function () use ($captain, $validated): void {
            $attributes = [
                'name' => trim($validated['name']),
                'phone' => $validated['phone'],
            ];
            if (filled($validated['password'] ?? null)) {
                $attributes['password'] = $validated['password'];
            }
            $captain->update($attributes);

            $tableIds = $validated['tables'] ?? [];
            Table::where('captain_id', $captain->id)->update(['captain_id' => null]);
            if ($tableIds !== []) {
                Table::whereIn('id', $tableIds)->update(['captain_id' => $captain->id]);
            }
        });

        return redirect()->route('admin.captains.index')->with('status', 'تم تحديث حساب الكابتن والطاولات المسندة إليه.');
    }

    public function toggleStatus(Captain $captain): RedirectResponse
    {
        $captain->update(['is_active' => ! $captain->is_active]);

        return redirect()->route('admin.captains.index')->with(
            'status',
            $captain->is_active ? 'تم تفعيل حساب الكابتن.' : 'تم إيقاف حساب الكابتن.'
        );
    }

    public function destroy(Captain $captain): RedirectResponse
    {
        $captain->delete();

        return redirect()->route('admin.captains.index')->with('status', 'تم حذف حساب الكابتن وإلغاء إسناد طاولاته.');
    }
}
