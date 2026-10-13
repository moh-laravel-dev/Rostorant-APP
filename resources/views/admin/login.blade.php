{{-- resources/views/admin/login.blade.php --}}

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول الإدارة</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.10.5/build/css/intlTelInput.css">

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

        [x-cloak] {
            display: none !important;
        }

        .iti {
            display: block;
            width: 100%;
            direction: ltr;
        }

        .iti input#phone {
            width: 100%;
            height: 54px;
            box-sizing: border-box;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: rgba(250, 247, 240, 0.6);
            padding-left: 120px !important;
            padding-right: 16px !important;
            text-align: left;
            color: #1f2937;
            font-size: 18px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .iti input#phone:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(23, 76, 60, 0.09);
            outline: none;
        }

        .iti__country-container {
            left: 0 !important;
            right: auto !important;
            padding: 5px;
        }

        .iti__selected-country {
            height: 42px;
            padding: 0 9px;
            border-radius: 8px;
            background: rgba(23, 76, 60, 0.06);
            transition: background 0.2s ease;
        }

        .iti__selected-country:hover,
        .iti__selected-country:focus {
            background: rgba(23, 76, 60, 0.12);
        }

        .iti__selected-country-primary {
            gap: 6px;
        }

        .iti__arrow {
            border-top-color: var(--primary);
        }

        .iti__dropdown-content {
            z-index: 100;
            margin-top: 6px;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 15px 40px rgba(16, 56, 45, 0.16);
            overflow: hidden;
        }

        [dir=rtl] .iti__search-input {
            padding: 10px 30px;
        }

        .iti__search-input {
            width: calc(100% - 16px);
            margin: 8px;
            padding: 10px 30px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            outline: none;
            direction: ltr;
            text-align: left;
        }

        .iti__search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(23, 76, 60, 0.08);
        }

        .iti__country-list {
            scrollbar-width: thin;
            scrollbar-color: #d7a85b #faf7f0;
        }

        [dir=rtl] .iti__search-icon {
            right: 20px;
        }

        .iti__country.iti__highlight {
            background: #f0f5f2;
        }

        .iti__country-name {
            color: #374151;
        }

        .iti__dial-code {
            color: var(--primary);
            font-weight: 700;
        }

        @media (max-width: 400px) {
            .iti input#phone {
                padding-left: 115px !important;
            }
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
                    أهلاً بعودتك
                </h1>

                <p class="mt-3 text-sm leading-7 text-white/70">
                    سجّل دخولك للوصول إلى لوحة التحكم.
                </p>

                <div class="gold-line mx-auto mt-7 h-px w-28"></div>
            </div>

            {{-- Form --}}
            <div class="px-6 py-8 sm:px-9">

                @if (session('status'))
                    <div
                        class="mb-5 flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                        <span class="mt-0.5">✓</span>
                        <p>{{ session('status') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div
                        class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        <span class="mt-0.5">!</span>
                        <p>{{ $errors->first() }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.action') }}" class="space-y-5">
                    @csrf

                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="mb-2 block text-sm font-bold text-[#174c3c]">
                            رقم الهاتف
                        </label>

                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required
                            autofocus autocomplete="tel" inputmode="numeric" placeholder="أدخل رقم الهاتف"
                            class="login-input w-full rounded-xl border border-gray-200 bg-[#faf7f0]/60 px-4 py-3.5 text-left text-gray-800 outline-none placeholder:text-gray-400">

                        <input type="hidden" name="country_code" id="country_code"
                            value="{{ old('country_code', '+963') }}">

                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div x-data="{ showPassword: false }">
                        <label for="password" class="mb-2 block text-sm font-bold text-[#174c3c]">
                            كلمة المرور
                        </label>

                        <div class="relative w-full">
                            <span
                                class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-[#174c3c]/60">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="4" y="10" width="16" height="11" rx="2" />
                                    <path d="M8 10V7a4 4 0 0 1 8 0v3" />
                                    <path d="M12 14v3" />
                                </svg>
                            </span>

                            <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required
                                autocomplete="current-password" placeholder="أدخل كلمة المرور" dir="ltr"
                                class="login-input w-full rounded-xl border border-gray-200 bg-[#faf7f0]/60 py-3.5 pl-20 pr-12 text-left text-gray-800 outline-none placeholder:text-gray-400">

                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute left-2 top-1/2 z-20 -translate-y-1/2 whitespace-nowrap rounded-lg px-2 py-2 text-xs font-bold text-[#174c3c] hover:bg-[#174c3c]/10"
                                x-text="showPassword ? 'إخفاء' : 'إظهار'"></button>
                        </div>

                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
            </div>

            {{-- Remember / Forgot --}}
            <div class="flex flex-wrap items-center justify-between gap-3">
                {{-- <label class="flex cursor-pointer items-center gap-2 text-sm text-gray-600">
                            <input type="checkbox" name="remember" value="1" @checked(old('remember'))
                                class="h-4 w-4 rounded border-gray-300 accent-[#174c3c] focus:ring-[#174c3c]">
                            <span>تذكّرني</span>
                        </label> --}}


                <a href="{{ route('password.request') }}"
                    class="text-sm font-bold text-[#174c3c] transition hover:text-[#b28743] mb-3 mr-3">
                    نسيت كلمة المرور؟
                </a>

            </div>

            {{-- Submit --}}
            <button type="submit"
                class="login-button flex w-full items-center justify-center gap-3 rounded-xl px-5 py-4 font-bold text-white">
                <span>تسجيل الدخول</span>

                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14" />
                    <path d="m12 5 7 7-7 7" />
                </svg>
            </button>
            </form>

            {{-- Support --}}
            <div class="mt-7 flex items-center gap-3">
                <div class="h-px flex-1 bg-gray-100"></div>

                <span class="text-[10px] font-semibold text-gray-400">
                    مخصص للموظفين المصرّح لهم
                </span>

                <div class="h-px flex-1 bg-gray-100"></div>
            </div>

            <p class="mt-5 text-center text-xs leading-7 text-gray-500">
                هل تواجه مشكلة في الدخول؟

                <a href="mailto:muhamedawad2000@gmail.com"
                    class="font-bold text-[#174c3c] underline decoration-[#d7a85b] underline-offset-4 hover:text-[#b28743]">
                    تواصل مع الدعم الفني
                </a>
            </p>
        </div>
        </div>

        {{-- Footer --}}
        <p class="mt-6 text-center text-xs leading-6 text-gray-500">
            © {{ date('Y') }} لوحة الإدارة. جميع الحقوق محفوظة.
        </p>

    </main>

</body>

<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.10.5/build/js/intlTelInput.min.js"></script>

<script>
    const phoneInput = document.querySelector('#phone');
    const countryCodeInput = document.querySelector('#country_code');

    const iti = window.intlTelInput(phoneInput, {
        initialCountry: 'sy',
        separateDialCode: true,
        nationalMode: true,
        showFlags: true,
        countrySearch: true,
        useFullscreenPopup: false,
        loadUtils: () => import(
            'https://cdn.jsdelivr.net/npm/intl-tel-input@25.10.5/build/js/utils.js'
        ),
    });

    function updateCountryCode() {
        const country = iti.getSelectedCountryData();
        countryCodeInput.value = country.dialCode ? '+' + country.dialCode : '';
    }

    phoneInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
    });

    phoneInput.addEventListener('countrychange', updateCountryCode);

    document.querySelector('form').addEventListener('submit', function() {
        updateCountryCode();
        phoneInput.value = phoneInput.value.replace(/\D/g, '');
    });

    updateCountryCode();
</script>

</html>
