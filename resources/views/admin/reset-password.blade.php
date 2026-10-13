
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استعادة كلمة المرور</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #174c3c;
            --primary-dark: #10382d;
            --gold: #d7a85b;
            --cream: #faf7f0;
        }

        * {
            font-family: 'Cairo', sans-serif;
            box-sizing: border-box;
        }

        body {
            background: var(--cream);
        }

        .login-background {
            background:
                radial-gradient(circle at 10% 15%, rgba(215, 168, 91, .18), transparent 30%),
                radial-gradient(circle at 90% 85%, rgba(23, 76, 60, .15), transparent 35%),
                var(--cream);
        }

        .login-card {
            box-shadow: 0 25px 80px rgba(16, 56, 45, .12);
        }

        .login-button {
            background: var(--primary);
            transition: all .25s ease;
        }

        .login-button:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(23, 76, 60, .2);
        }

        .login-input {
            transition: all .2s ease;
        }

        .login-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(23, 76, 60, .09);
        }

        .gold-line {
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
        }
    </style>
</head>

<body class="login-background flex min-h-screen items-center justify-center overflow-x-hidden px-4 py-10">

    <div class="fixed -right-24 -top-24 h-72 w-72 rounded-full border border-[#d7a85b]/20"></div>
    <div class="fixed -right-16 -top-16 h-56 w-56 rounded-full border border-[#d7a85b]/20"></div>
    <div class="fixed -bottom-28 -left-24 h-80 w-80 rounded-full border border-[#174c3c]/10"></div>
    <div class="fixed -bottom-16 -left-12 h-56 w-56 rounded-full border border-[#174c3c]/10"></div>

    <main class="relative z-10 w-full max-w-md">

        <div class="login-card overflow-hidden rounded-3xl border border-white bg-white">

            {{-- Header --}}
            <div class="relative overflow-hidden bg-[#174c3c] px-8 pb-9 pt-10 text-center text-white">

                <div class="absolute -left-10 -top-10 h-36 w-36 rounded-full border border-white/10"></div>
                <div class="absolute -left-5 -top-5 h-24 w-24 rounded-full border border-[#d7a85b]/30"></div>

                <div
                    class="relative mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-2xl border border-[#d7a85b]/60 bg-white/10 shadow-lg backdrop-blur">
                    <span class="text-4xl font-extrabold text-[#d7a85b]">A</span>
                </div>

                <p class="mb-2 text-xs font-bold tracking-[3px] text-[#d7a85b]">
                    بوابة الإدارة
                </p>

                <h1 class="text-3xl font-extrabold">
                    استعادة كلمة المرور
                </h1>

                <p class="mt-3 text-sm leading-7 text-white/70">
                    أدخل بريدك الإلكتروني لإرسال رابط إعادة تعيين كلمة المرور.
                </p>

                <div class="gold-line mx-auto mt-7 h-px w-28"></div>
            </div>

            {{-- Form --}}
            <div class="px-6 py-8 sm:px-9">

                @if (session('status'))
                    <div class="mb-5 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-sm font-bold text-[#174c3c]">
                            البريد الإلكتروني
                        </label>

                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            autofocus autocomplete="email" placeholder="أدخل البريد الإلكتروني" dir="ltr"
                            class="login-input w-full rounded-xl border border-gray-200 bg-[#faf7f0]/60 px-4 py-3.5 text-left text-gray-800 outline-none placeholder:text-gray-400">

                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="login-button flex w-full items-center justify-center gap-3 rounded-xl px-5 py-4 font-bold text-white">
                        <span>إرسال رابط إعادة التعيين</span>

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </button>
                </form>

                <a href="{{ route('admin.login') }}"
                    class="mt-6 block text-center text-sm font-bold text-[#174c3c] hover:text-[#b28743]">
                    العودة إلى تسجيل الدخول
                </a>
            </div>
        </div>

        <p class="mt-6 text-center text-xs leading-6 text-gray-500">
            © {{ date('Y') }} لوحة الإدارة. جميع الحقوق محفوظة.
        </p>

    </main>

</body>

</html>

