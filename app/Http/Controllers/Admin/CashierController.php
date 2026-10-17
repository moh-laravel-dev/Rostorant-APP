<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cashier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class CashierController extends Controller
{
    public function index(): View
    {
        $cashiers = Cashier::orderBy('name')->get();

        return view('admin.cashiers.index', compact('cashiers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'country_code' => ['required', 'string', 'regex:/^\+[1-9]\d{0,3}$/'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^\d+$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'يرجى إدخال اسم الكاشير.',
            'country_code.required' => 'يرجى اختيار رمز الدولة.',
            'phone.required' => 'يرجى إدخال رقم الهاتف.',
            'phone.regex' => 'رقم الهاتف يجب أن يحتوي على أرقام فقط.',
            'password.required' => 'يرجى إدخال كلمة المرور.',
            'password.min' => 'يجب أن تتكون كلمة المرور من 8 أحرف على الأقل.',
            'password.confirmed' => 'تأكيد كلمة المرور غير مطابق.',
        ]);

        $phone = $validated['country_code'] . $validated['phone'];
        Validator::make(
            ['phone' => $phone],
            ['phone' => ['required', Rule::unique('cashiers', 'phone')]],
            ['phone.unique' => 'رقم الهاتف مسجل مسبقًا لحساب كاشير.']
        )->validate();

        Cashier::create([
            'name' => trim($validated['name']),
            'phone' => $phone,
            'password' => $validated['password'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.cashiers.index')->with('status', 'تم إنشاء حساب الكاشير بنجاح.');
    }

    public function edit(Cashier $cashier): View
    {
        return view('admin.cashiers.edit', compact('cashier'));
    }

    public function update(Request $request, Cashier $cashier): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^\+[1-9]\d{7,14}$/'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'يرجى إدخال اسم الكاشير.',
            'phone.required' => 'يرجى إدخال رقم الهاتف مع رمز الدولة.',
            'phone.regex' => 'أدخل رقم الهاتف الدولي كاملاً، مثل +963912345678.',
            'password.min' => 'يجب أن تتكون كلمة المرور من 8 أحرف على الأقل.',
            'password.confirmed' => 'تأكيد كلمة المرور غير مطابق.',
        ]);

        Validator::make(
            ['phone' => $validated['phone']],
            ['phone' => ['required', Rule::unique('cashiers', 'phone')->ignore($cashier->id)]],
            ['phone.unique' => 'رقم الهاتف مسجل مسبقًا لحساب كاشير آخر.']
        )->validate();

        $attributes = [
            'name' => trim($validated['name']),
            'phone' => $validated['phone'],
        ];
        if (filled($validated['password'] ?? null)) {
            $attributes['password'] = $validated['password'];
        }

        $cashier->update($attributes);

        return redirect()->route('admin.cashiers.index')->with('status', 'تم تحديث حساب الكاشير بنجاح.');
    }

    public function toggleStatus(Cashier $cashier): RedirectResponse
    {
        $cashier->update(['is_active' => !$cashier->is_active]);

        return redirect()->route('admin.cashiers.index')->with(
            'status',
            $cashier->is_active ? 'تم تفعيل حساب الكاشير.' : 'تم إيقاف حساب الكاشير.'
        );
    }
}
