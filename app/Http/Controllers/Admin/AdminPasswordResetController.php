<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminPasswordResetMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminPasswordResetController extends Controller
{
    public function create()
    {
        return view('admin.reset-password');
    }

    public function edit(Request $request, string $token)
    {

        $row = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$row || !Hash::check($token, $row->token)) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'رابط إعادة تعيين كلمة المرور غير صالح.',
                ]);
        }

        if (
            Carbon::parse($row->created_at)->addMinutes(
                5
            )->isPast()
        ) {
            // DB::table('password_reset_tokens')
            //     ->where('email', $request->email)
            //     ->delete();

            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'انتهت صلاحية رابط إعادة تعيين كلمة المرور. يرجى طلب رابط جديد.',
                ]);
        }

        return view('emails.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }




    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.email' => 'يرجى إدخال بريد إلكتروني صالح.',
            'email.required' => 'يرجى إدخال البريد الإلكتروني.',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $token = Str::random(64);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                [
                    'email' => $request->email,
                    'token' => Hash::make($token),
                    'created_at' => Carbon::now(),
                ]
            );

            $resetUrl = route('password.reset', [
                'token' => $token,
                'email' => $request->email,
            ]);
            Mail::to($user->email)->send(
                new AdminPasswordResetMail($resetUrl)
            );
            // أرسل رابط إعادة التعيين إلى البريد الإلكتروني هنا.
        }
        return back()->with([
            'status' => 'سيتم ارسال الرايط للايميل الذي تم إدخاله إذا كان موجودًا في قاعدة البيانات.',
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'token.required' => 'رمز إعادة تعيين كلمة المرور مطلوب.',
            'email.required' => 'يرجى إدخال البريد الإلكتروني.',
            'email.email' => 'يرجى إدخال بريد إلكتروني صالح.',
            'password.required' => 'يرجى إدخال كلمة المرور الجديدة.',
            'password.min' => 'يجب ألا تقل كلمة المرور عن 8 أحرف.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
        ]);


        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'لم يتم العثور على الحساب.',
                ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();
        // تسجيل خروجه من الـ Admin Guard إذا كان مسجلاً
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();


        return redirect()
            ->route('admin.login')
            ->with('status', 'تم تغيير كلمة المرور بنجاح. يمكنك تسجيل الدخول الآن.');
    }

}