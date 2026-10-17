<?php

namespace App\Http\Controllers;

use App\Models\Cashier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CashierAuthController extends Controller
{
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'phone' => ['required', 'string', 'max:20', 'regex:/^\d+$/'],
            'country_code' => ['required', 'string', 'regex:/^\+[1-9]\d{0,3}$/'],
            'password' => ['required', 'string'],
        ], [
            'phone.required' => 'يرجى إدخال رقم الهاتف.',
            'phone.regex' => 'رقم الهاتف يجب أن يحتوي على أرقام فقط.',
            'country_code.required' => 'يرجى اختيار رمز الدولة.',
            'password.required' => 'يرجى إدخال كلمة المرور.',
        ]);

        $phone = $credentials['country_code'] . $credentials['phone'];
        $cashier = Cashier::where('phone', $phone)->first();

        if (!$cashier || !$cashier->is_active || !Hash::check($credentials['password'], $cashier->password)) {
            throw ValidationException::withMessages([
                'phone' => $cashier && !$cashier->is_active
                    ? 'تم إيقاف هذا الحساب. يرجى التواصل مع الإدارة.'
                    : 'رقم الهاتف أو كلمة المرور غير صحيحة.',
            ]);
        }

        Auth::guard('cashier')->login($cashier, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('cashier.tables'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('cashier')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('status', 'تم تسجيل الخروج بنجاح.');
    }
}
