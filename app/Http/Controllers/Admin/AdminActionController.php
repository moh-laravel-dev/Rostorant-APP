<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;


class AdminActionController extends Controller
{
    public function login(AdminLoginRequest $request)
    {


        $credentials = $request->validated();

        $remember = $request->boolean('remember');
        $credentials['phone'] = $request->country_code . $credentials['phone'];

        unset($credentials['remember']);
        if (!auth('admin')->attempt($credentials, $remember)) {
            return back()
                ->withErrors([
                    'phone' => 'رقم الهاتف أو كلمة المرور غير صحيحة.',
                ])
                ->onlyInput('phone');
        }

        $request->session()->regenerate();
        ;
        return redirect()
            ->intended(route('admin.dashboard'))
            ->with('status', 'تم تسجيل الدخول بنجاح.');
    }

    public function logout()
    {
        auth('admin')->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with('status', 'تم تسجيل الخروج بنجاح.');
    }
}