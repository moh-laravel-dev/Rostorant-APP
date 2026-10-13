<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعادة تعيين كلمة المرور</title>
</head>

<body style="margin:0; padding:0; font-family:Arial,sans-serif; background:#f3f6f4;">

    <div
        style="min-height:100vh; display:flex; align-items:center; justify-content:center; padding:20px; box-sizing:border-box;">
        <div
            style="width:100%; max-width:430px; background:#fff; padding:35px; border-radius:16px; box-shadow:0 8px 30px rgba(0,0,0,0.08); box-sizing:border-box;">

            <div style="text-align:center; margin-bottom:30px;">
                <h2 style="color:#174c3c; margin-bottom:10px;">إعادة تعيين كلمة المرور</h2>
                <p style="color:#777; font-size:14px; line-height:1.8;">
                    أدخل كلمة المرور الجديدة لتحديث كلمة مرور حسابك.
                </p>
            </div>

            @if ($errors->any())
                <div style="background:#fff0f0; color:#b42318; padding:12px; border-radius:8px; margin-bottom:20px;">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div style="margin-bottom:20px;">
                    <label for="password" style="display:block; margin-bottom:8px; color:#333;">
                        كلمة المرور الجديدة
                    </label>

                    <div style="position:relative;">
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                            placeholder="أدخل كلمة المرور الجديدة"
                            style="width:100%; padding:13px 45px 13px 13px; border:1px solid #ddd; border-radius:8px; box-sizing:border-box; outline-color:#174c3c;">

                        <button type="button" onclick="togglePassword('password', this)" aria-label="إظهار كلمة المرور"
                            style="position:absolute; left:12px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#174c3c; font-size:13px;">إظهار</button>
                    </div>
                </div>

                <div style="margin-bottom:25px;">
                    <label for="password_confirmation" style="display:block; margin-bottom:8px; color:#333;">
                        تأكيد كلمة المرور
                    </label>

                    <div style="position:relative;">
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            autocomplete="new-password" placeholder="أعد إدخال كلمة المرور"
                            style="width:100%; padding:13px 45px 13px 13px; border:1px solid #ddd; border-radius:8px; box-sizing:border-box; outline-color:#174c3c;">

                        <button type="button" onclick="togglePassword('password_confirmation', this)"
                            aria-label="إظهار تأكيد كلمة المرور"
                            style="position:absolute; left:12px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#174c3c; font-size:13px;">إظهار</button>
                    </div>
                </div>

                <button type="submit"
                    style="width:100%; padding:14px; background:#174c3c; color:#fff; border:none; border-radius:8px; font-size:16px; font-weight:bold; cursor:pointer;">
                    تحديث كلمة المرور
                </button>
            </form>

            <div style="text-align:center; margin-top:25px;">
                <a href="{{ route('admin.login') }}" style="color:#174c3c; text-decoration:none; font-size:14px;">
                    العودة إلى تسجيل الدخول
                </a>
            </div>

        </div>
    </div>

    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const isHidden = input.type === 'password';

            input.type = isHidden ? 'text' : 'password';
            button.textContent = isHidden ? 'إخفاء' : 'إظهار';
            button.setAttribute(
                'aria-label',
                isHidden ? 'إخفاء كلمة المرور' : 'إظهار كلمة المرور'
            );
        }
    </script>

</body>

</html>
