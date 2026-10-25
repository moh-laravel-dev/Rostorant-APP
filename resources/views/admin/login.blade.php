{{-- resources/views/admin/login.blade.php --}}

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#174c3c">
    <title>تسجيل دخول الإدارة</title>

    @include('components.theme-mode')
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.10.5/build/css/intlTelInput.css">

    <style>
        :root {
            --primary: var(--restaurant-color-hex-174c3c);
            --primary-dark: var(--restaurant-color-hex-10382d);
            --primary-light: var(--restaurant-color-hex-eaf2ee);
            --gold: var(--restaurant-color-hex-d7a85b);
            --cream: var(--restaurant-color-hex-faf7f0);
        }

        .login-role-tab {
            color: #64748b;
        }

        .login-role-tab.is-active {
            color: var(--restaurant-color-hex-174c3c);
            background: white;
            box-shadow: 0 1px 3px rgb(15 23 42 / 12%);
        }

        * {
            box-sizing: border-box;
            font-family: 'Cairo', sans-serif;
        }

        html {
            min-height: 100%;
            scroll-behavior: smooth;
        }

        body {
            min-height: 100vh;
            margin: 0;
            background: var(--cream);
        }

        .login-background {
            background:
                radial-gradient(ellipse at 0% 0%, var(--restaurant-color-rgb-215-168-91-point-17), transparent 36%),
                radial-gradient(ellipse at 100% 100%, var(--restaurant-color-rgb-23-76-60-point-13), transparent 38%),
                linear-gradient(135deg, var(--restaurant-color-hex-fcfaf5) 0%, var(--restaurant-color-hex-f4f3eb) 50%, var(--restaurant-color-hex-f8f7f1) 100%);
        }

        .login-card {
            box-shadow:
                0 30px 80px var(--restaurant-color-rgb-16-56-45-point-10),
                0 8px 24px var(--restaurant-color-rgb-16-56-45-point-05);
        }

        .login-header {
            background:
                radial-gradient(circle at 100% 0%, var(--restaurant-color-rgb-215-168-91-point-15), transparent 36%),
                linear-gradient(145deg, var(--restaurant-color-hex-1b5945) 0%, var(--restaurant-color-hex-174c3c) 48%, var(--restaurant-color-hex-10382d) 100%);
        }

        .login-button {
            background: linear-gradient(135deg, var(--restaurant-color-hex-1b5945), var(--restaurant-color-hex-174c3c) 60%, var(--restaurant-color-hex-10382d));
            box-shadow: 0 8px 18px var(--restaurant-color-rgb-23-76-60-point-16);
            transition: background .25s ease, transform .25s ease, box-shadow .25s ease;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px var(--restaurant-color-rgb-23-76-60-point-23);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .login-input {
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        }

        .login-input:focus {
            border-color: var(--primary);
            background: var(--restaurant-color-hex-fff);
            box-shadow: 0 0 0 4px var(--restaurant-color-rgb-23-76-60-point-09);
            outline: none;
        }

        .gold-line {
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
        }

        .decorative-ring {
            pointer-events: none;
            position: absolute;
            border: 1px solid var(--restaurant-color-rgb-215-168-91-point-22);
            border-radius: 50%;
        }

        .back-home {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: var(--restaurant-color-rgb-255-255-255-point-9);
            border: 1px solid var(--restaurant-color-rgb-255-255-255-point-22);
            background: var(--restaurant-color-rgb-255-255-255-point-09);
            transition: background .2s ease, border-color .2s ease;
        }

        .back-home:hover {
            background: var(--restaurant-color-rgb-255-255-255-point-17);
            border-color: var(--restaurant-color-rgb-255-255-255-point-4);
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
            border: 1px solid var(--restaurant-color-hex-e5e7eb);
            border-radius: 12px;
            background: var(--restaurant-color-rgb-250-247-240-point-65);
            padding-left: 112px !important;
            text-align: left;
            color: var(--restaurant-color-hex-1f2937);
            font-size: 16px;
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        }

        .iti input#phone:focus {
            border-color: var(--primary);
            background: var(--restaurant-color-hex-fff);
            box-shadow: 0 0 0 4px var(--restaurant-color-rgb-23-76-60-point-09);
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
            background: var(--restaurant-color-rgb-23-76-60-point-06);
            transition: background .2s ease;
        }

        .iti__selected-country:hover,
        .iti__selected-country:focus {
            background: var(--restaurant-color-rgb-23-76-60-point-12);
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
            border: 1px solid var(--restaurant-color-hex-e5e7eb);
            border-radius: 14px;
            box-shadow: 0 15px 40px var(--restaurant-color-rgb-16-56-45-point-16);
            overflow: hidden;
        }

        .iti__search-input {
            width: calc(100% - 16px);
            margin: 8px;
            padding: 10px 30px;
            border: 1px solid var(--restaurant-color-hex-e5e7eb);
            border-radius: 8px;
            outline: none;
            direction: ltr;
            text-align: left;
        }

        .iti__search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--restaurant-color-rgb-23-76-60-point-08);
        }

        .iti__country-list {
            scrollbar-width: thin;
            scrollbar-color: var(--restaurant-color-hex-d7a85b) var(--restaurant-color-hex-faf7f0);
        }

        .iti__country.iti__highlight {
            background: var(--restaurant-color-hex-f0f5f2);
        }

        .iti__country-name {
            color: var(--restaurant-color-hex-374151);
        }

        .iti__dial-code {
            color: var(--primary);
            font-weight: 700;
        }

        @media (max-width: 480px) {
            .login-card {
                border-radius: 22px;
            }

            .login-header {
                padding: 30px 22px 28px;
            }

            .login-form-content {
                padding: 25px 20px;
            }

            .iti input#phone {
                padding-left: 112px !important;
                font-size: 15px;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
            }
        }

        [dir=rtl] .iti input.iti__tel-input,
        [dir=rtl] .iti input.iti__tel-input[type=text],
        [dir=rtl] .iti input.iti__tel-input[type=tel] {
            text-align: left;
            padding-left: 112px;
        }
    </style>
</head>

<body
    class="login-background relative flex min-h-screen items-center justify-center overflow-x-hidden px-4 py-8 sm:px-6 sm:py-12">

    {{-- Background Decorations --}}
    <div class="pointer-events-none fixed inset-0 overflow-hidden" aria-hidden="true">
        <div class="decorative-ring -right-28 -top-28 h-80 w-80"></div>
        <div class="decorative-ring -right-16 -top-16 h-56 w-56"></div>
        <div class="decorative-ring -bottom-32 -left-28 h-96 w-96"
            style="border-color: var(--restaurant-color-rgb-23-76-60-point-12)"></div>
        <div class="decorative-ring -bottom-16 -left-12 h-64 w-64"
            style="border-color: var(--restaurant-color-rgb-23-76-60-point-09)"></div>
        <div class="absolute left-[12%] top-[18%] h-2 w-2 rounded-full bg-[var(--restaurant-color-hex-d7a85b)]/50">
        </div>
        <div class="absolute bottom-[22%] right-[10%] h-3 w-3 rounded-full bg-[var(--restaurant-color-hex-174c3c)]/20">
        </div>
    </div>

    <main class="relative z-10 mx-auto w-full max-w-md">

        {{-- Brand --}}
        <div class="mb-6 text-center sm:mb-8">
            <div
                class="mb-3 inline-flex items-center gap-2 rounded-full border border-[var(--restaurant-color-hex-174c3c)]/10 bg-white/70 px-4 py-2 shadow-sm">
                <span class="h-2 w-2 rounded-full bg-[var(--restaurant-color-hex-d7a85b)]"></span>
                <span class="text-xs font-bold tracking-wide text-[var(--restaurant-color-hex-174c3c)]">بوابة الإدارة
                    الرسمية</span>
            </div>
            <p class="text-sm font-medium text-gray-500">
                تسجيل دخول آمن وسهل إلى لوحة التحكم
            </p>
        </div>

        <section class="login-card overflow-hidden rounded-3xl border border-white/90 bg-white">

            {{-- Header --}}
            <header
                class="login-header relative overflow-hidden px-7 pb-8 pt-8 text-center text-white sm:px-9 sm:pb-9 sm:pt-9">

                <div
                    class="pointer-events-none absolute -left-12 -top-12 h-40 w-40 rounded-full border border-white/10">
                </div>
                <div
                    class="pointer-events-none absolute -left-5 -top-5 h-24 w-24 rounded-full border border-[var(--restaurant-color-hex-d7a85b)]/30">
                </div>
                <div
                    class="pointer-events-none absolute -bottom-16 -right-8 h-36 w-36 rounded-full border border-white/10">
                </div>

                <a href="{{ route('welcome') }}"
                    class="back-home absolute right-4 top-4 z-20 rounded-xl px-3 py-2 text-xs font-bold sm:right-6 sm:top-6 sm:px-3.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    الرئيسية
                </a>

                <div
                    class="relative mx-auto mb-5 mt-8 flex h-[76px] w-[76px] items-center justify-center rounded-2xl border border-[var(--restaurant-color-hex-d7a85b)]/60 bg-white/10 shadow-lg backdrop-blur sm:mt-5 sm:h-20 sm:w-20">
                    <span class="text-4xl font-black text-[var(--restaurant-color-hex-d7a85b)]">A</span>
                    <span
                        class="absolute -bottom-1.5 -left-1.5 h-5 w-5 rounded-full border-2 border-[var(--restaurant-color-hex-174c3c)] bg-[var(--restaurant-color-hex-d7a85b)]"></span>
                </div>

                <p class="mb-2 text-xs font-bold tracking-[3px] text-[var(--restaurant-color-hex-e8c98e)]">
                    ADMIN PORTAL
                </p>

                <h1 class="text-2xl font-extrabold sm:text-3xl">
                    أهلاً بعودتك
                </h1>

                <p class="mx-auto mt-3 max-w-xs text-sm leading-7 text-white/75">
                    سجّل دخولك للمتابعة والوصول إلى لوحة التحكم.
                </p>

                <div class="gold-line mx-auto mt-6 h-px w-28"></div>
            </header>

            {{-- Form Content --}}
            <div class="login-form-content px-6 py-7 sm:px-9 sm:py-8">

                @if (session('status'))
                    <div role="status"
                        class="mb-5 flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                        <span class="mt-0.5 font-bold">✓</span>
                        <p>{{ session('status') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div role="alert"
                        class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        <span class="mt-0.5 font-bold">!</span>
                        <p>{{ $errors->first() }}</p>
                    </div>
                @endif

                @php
                    $requestedLoginTab = old('login_type', request()->query('type'));
                    $loginTabLocked = in_array($requestedLoginTab, ['admin', 'cashier', 'captain'], true);
                    $activeLoginTab = $loginTabLocked ? $requestedLoginTab : 'admin';
                @endphp
                <div class="mb-6 grid grid-cols-3 gap-2 rounded-xl bg-gray-100 p-1" role="tablist"
                    aria-label="نوع تسجيل الدخول" data-login-tab-locked="{{ $loginTabLocked ? 'true' : 'false' }}">
                    <button type="button" id="admin-login-tab" role="tab" aria-controls="admin-login-panel"
                        aria-selected="{{ $activeLoginTab === 'admin' ? 'true' : 'false' }}"
                        class="login-role-tab rounded-lg px-3 py-3 text-sm font-bold transition {{ $activeLoginTab === 'admin' ? 'is-active' : '' }}"
                        data-login-tab="admin">دخول الأدمن</button>
                    <button type="button" id="cashier-login-tab" role="tab" aria-controls="cashier-login-panel"
                        aria-selected="{{ $activeLoginTab === 'cashier' ? 'true' : 'false' }}"
                        class="login-role-tab rounded-lg px-3 py-3 text-sm font-bold transition {{ $activeLoginTab === 'cashier' ? 'is-active' : '' }}"
                        data-login-tab="cashier">دخول الكاشير</button>
                    <button type="button" id="captain-login-tab" role="tab" aria-controls="captain-login-panel"
                        aria-selected="{{ $activeLoginTab === 'captain' ? 'true' : 'false' }}"
                        class="login-role-tab rounded-lg px-3 py-3 text-sm font-bold transition {{ $activeLoginTab === 'captain' ? 'is-active' : '' }}"
                        data-login-tab="captain">دخول الكابتن</button>
                </div>

                <div id="admin-login-panel" role="tabpanel" aria-labelledby="admin-login-tab"
                    {{ $activeLoginTab !== 'admin' ? 'hidden' : '' }}>
                    <form id="admin-login-form" method="POST" action="{{ route('admin.login.action') }}"
                        class="space-y-5">
                        @csrf
                        <input type="hidden" name="login_type" value="admin">

                        {{-- Phone --}}
                        <div>
                            <label for="phone"
                                class="mb-2 block text-sm font-bold text-[var(--restaurant-color-hex-174c3c)]">
                                رقم الهاتف
                            </label>

                            <div class="relative">
                                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
                                    required autofocus autocomplete="tel" inputmode="numeric"
                                    placeholder="أدخل رقم الهاتف"
                                    class="login-input w-full rounded-xl border border-gray-200 bg-[var(--restaurant-color-hex-faf7f0)]/60  py-3.5 text-left text-gray-800 outline-none placeholder:text-gray-400">

                                <input type="hidden" name="country_code" id="country_code"
                                    value="{{ old('country_code', '+963') }}">
                            </div>

                            @error('phone')
                                <p class="mt-1.5 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div x-data="{ showPassword: false }">
                            <div class="mb-2 flex items-center justify-between gap-3">
                                <label for="password"
                                    class="block text-sm font-bold text-[var(--restaurant-color-hex-174c3c)]">
                                    كلمة المرور
                                </label>

                                <a href="{{ route('password.request') }}"
                                    class="text-xs font-bold text-[var(--restaurant-color-hex-174c3c)] transition hover:text-[var(--restaurant-color-hex-b28743)]">
                                    نسيت كلمة المرور؟
                                </a>
                            </div>

                            <div class="relative">
                                <span
                                    class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-[var(--restaurant-color-hex-174c3c)]/60">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.8">
                                        <rect x="4" y="10" width="16" height="11" rx="2" />
                                        <path d="M8 10V7a4 4 0 0 1 8 0v3" />
                                        <path d="M12 14v3" />
                                    </svg>
                                </span>

                                <input id="password" :type="showPassword ? 'text' : 'password'" name="password"
                                    required autocomplete="current-password" placeholder="أدخل كلمة المرور"
                                    dir="ltr"
                                    class="login-input w-full rounded-xl border border-gray-200 bg-[var(--restaurant-color-hex-faf7f0)]/60 py-3.5 pl-20 pr-12 text-left text-gray-800 outline-none placeholder:text-gray-400">

                                <button type="button" @click="showPassword = !showPassword"
                                    :aria-label="showPassword ? 'إخفاء كلمة المرور' : 'إظهار كلمة المرور'"
                                    class="absolute left-2 top-1/2 z-20 -translate-y-1/2 rounded-lg px-2.5 py-2 text-xs font-bold text-[var(--restaurant-color-hex-174c3c)] transition hover:bg-[var(--restaurant-color-hex-174c3c)]/10 focus:outline-none focus:ring-2 focus:ring-[var(--restaurant-color-hex-174c3c)]/30"
                                    x-text="showPassword ? 'إخفاء' : 'إظهار'">
                                </button>
                            </div>

                            @error('password')
                                <p class="mt-1.5 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Remember Me --}}
                        {{--
                    <label class="flex cursor-pointer items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="remember" value="1" @checked(old('remember'))
                            class="h-4 w-4 rounded border-gray-300 accent-[var(--restaurant-color-hex-174c3c)] focus:ring-[var(--restaurant-color-hex-174c3c)]">
                        <span>تذكّرني</span>
                    </label>
                    --}}

                        {{-- Submit --}}
                        <button type="submit"
                            class="login-button group flex min-h-[54px] w-full items-center justify-center gap-3 rounded-xl px-5 py-3.5 font-bold text-white focus:outline-none focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/20">
                            <span>تسجيل الدخول</span>

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 transition-transform duration-200 group-hover:-translate-x-1"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14" stroke-linecap="round" />
                                <path d="m12 5 7 7-7 7" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </form>
                </div>

                <div id="cashier-login-panel" role="tabpanel" aria-labelledby="cashier-login-tab"
                    {{ $activeLoginTab === 'cashier' ? '' : 'hidden' }}>
                    <form id="cashier-login-form" method="POST" action="{{ route('cashier.login.action') }}"
                        class="space-y-5">
                        @csrf
                        <input type="hidden" name="login_type" value="cashier">

                        <div>
                            <label for="cashier-phone"
                                class="mb-2 block text-sm font-bold text-[var(--restaurant-color-hex-174c3c)]">
                                رقم الهاتف
                            </label>
                            <input id="cashier-phone" type="tel" name="phone"
                                value="{{ old('cashier_phone', old('phone')) }}" required autocomplete="tel"
                                inputmode="numeric" placeholder="أدخل رقم الهاتف"
                                class="login-input w-full rounded-xl border border-gray-200 bg-[var(--restaurant-color-hex-faf7f0)]/60 px-4 py-3.5 text-left text-gray-800 outline-none placeholder:text-gray-400">
                            <input type="hidden" name="country_code" id="cashier_country_code"
                                value="{{ old('cashier_country_code', old('country_code', '+963')) }}">
                        </div>

                        <div x-data="{ showPassword: false }">
                            <label for="cashier-password"
                                class="mb-2 block text-sm font-bold text-[var(--restaurant-color-hex-174c3c)]">
                                كلمة المرور
                            </label>
                            <div class="relative">
                                <span
                                    class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-[var(--restaurant-color-hex-174c3c)]/60">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.8">
                                        <rect x="4" y="10" width="16" height="11" rx="2" />
                                        <path d="M8 10V7a4 4 0 0 1 8 0v3" />
                                        <path d="M12 14v3" />
                                    </svg>
                                </span>
                                <input id="cashier-password" :type="showPassword ? 'text' : 'password'"
                                    name="password" required autocomplete="current-password"
                                    placeholder="أدخل كلمة المرور" dir="ltr"
                                    class="login-input w-full rounded-xl border border-gray-200 bg-[var(--restaurant-color-hex-faf7f0)]/60 py-3.5 pl-20 pr-12 text-left text-gray-800 outline-none placeholder:text-gray-400">
                                <button type="button" @click="showPassword = !showPassword"
                                    :aria-label="showPassword ? 'إخفاء كلمة المرور' : 'إظهار كلمة المرور'"
                                    class="absolute left-2 top-1/2 z-20 -translate-y-1/2 rounded-lg px-2.5 py-2 text-xs font-bold text-[var(--restaurant-color-hex-174c3c)] transition hover:bg-[var(--restaurant-color-hex-174c3c)]/10 focus:outline-none focus:ring-2 focus:ring-[var(--restaurant-color-hex-174c3c)]/30"
                                    x-text="showPassword ? 'إخفاء' : 'إظهار'"></button>
                            </div>
                        </div>

                        <button type="submit"
                            class="login-button group flex min-h-[54px] w-full items-center justify-center gap-3 rounded-xl px-5 py-3.5 font-bold text-white focus:outline-none focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/20">
                            <span>دخول لوحة الكاشير</span>
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 transition-transform duration-200 group-hover:-translate-x-1"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14" stroke-linecap="round" />
                                <path d="m12 5 7 7-7 7" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </form>
                </div>

                <div id="captain-login-panel" role="tabpanel" aria-labelledby="captain-login-tab"
                    {{ $activeLoginTab === 'captain' ? '' : 'hidden' }}>
                    <form id="captain-login-form" method="POST" action="{{ route('captain.login.action') }}"
                        class="space-y-5">
                        @csrf
                        <input type="hidden" name="login_type" value="captain">

                        <div>
                            <label for="captain-phone"
                                class="mb-2 block text-sm font-bold text-[var(--restaurant-color-hex-174c3c)]">
                                رقم الهاتف
                            </label>
                            <input id="captain-phone" type="tel" name="phone"
                                value="{{ old('captain_phone', old('phone')) }}" required autocomplete="tel"
                                inputmode="numeric" placeholder="أدخل رقم الهاتف"
                                class="login-input w-full rounded-xl border border-gray-200 bg-[var(--restaurant-color-hex-faf7f0)]/60 px-4 py-3.5 text-left text-gray-800 outline-none placeholder:text-gray-400">
                            <input type="hidden" name="country_code" id="captain_country_code"
                                value="{{ old('captain_country_code', old('country_code', '+963')) }}">
                        </div>

                        <div x-data="{ showPassword: false }">
                            <label for="captain-password"
                                class="mb-2 block text-sm font-bold text-[var(--restaurant-color-hex-174c3c)]">
                                كلمة المرور
                            </label>
                            <div class="relative">
                                <span
                                    class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-[var(--restaurant-color-hex-174c3c)]/60">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.8">
                                        <rect x="4" y="10" width="16" height="11" rx="2" />
                                        <path d="M8 10V7a4 4 0 0 1 8 0v3" />
                                        <path d="M12 14v3" />
                                    </svg>
                                </span>
                                <input id="captain-password" :type="showPassword ? 'text' : 'password'"
                                    name="password" required autocomplete="current-password"
                                    placeholder="أدخل كلمة المرور" dir="ltr"
                                    class="login-input w-full rounded-xl border border-gray-200 bg-[var(--restaurant-color-hex-faf7f0)]/60 py-3.5 pl-20 pr-12 text-left text-gray-800 outline-none placeholder:text-gray-400">
                                <button type="button" @click="showPassword = !showPassword"
                                    :aria-label="showPassword ? 'إخفاء كلمة المرور' : 'إظهار كلمة المرور'"
                                    class="absolute left-2 top-1/2 z-20 -translate-y-1/2 rounded-lg px-2.5 py-2 text-xs font-bold text-[var(--restaurant-color-hex-174c3c)] transition hover:bg-[var(--restaurant-color-hex-174c3c)]/10 focus:outline-none focus:ring-2 focus:ring-[var(--restaurant-color-hex-174c3c)]/30"
                                    x-text="showPassword ? 'إخفاء' : 'إظهار'"></button>
                            </div>
                        </div>

                        <button type="submit"
                            class="login-button group flex min-h-[54px] w-full items-center justify-center gap-3 rounded-xl px-5 py-3.5 font-bold text-white focus:outline-none focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/20">
                            <span>دخول لوحة الكابتن</span>
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 transition-transform duration-200 group-hover:-translate-x-1"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14" stroke-linecap="round" />
                                <path d="m12 5 7 7-7 7" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </form>
                </div>

                {{-- Security Notice --}}
                <div
                    class="mt-6 flex items-center justify-center gap-2 rounded-xl border border-[var(--restaurant-color-hex-174c3c)]/[0.08] bg-[var(--restaurant-color-hex-174c3c)]/[0.035] px-3 py-3">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 shrink-0 text-[var(--restaurant-color-hex-174c3c)]/70" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="m9 12 2 2 4-4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="text-center text-[11px] font-semibold leading-5 text-gray-500">
                        هذه البوابة مخصصة للموظفين المصرّح لهم فقط
                    </span>
                </div>

                {{-- Support --}}
                <div class="mt-6 flex items-center gap-3">
                    <div class="h-px flex-1 bg-gray-100"></div>
                    <span class="text-[10px] font-semibold text-gray-400">الدعم الفني</span>
                    <div class="h-px flex-1 bg-gray-100"></div>
                </div>

                <p class="mt-4 text-center text-xs leading-7 text-gray-500">
                    هل تواجه مشكلة في الدخول؟
                    <a href="mailto:muhamedawad2000@gmail.com"
                        class="font-bold text-[var(--restaurant-color-hex-174c3c)] underline decoration-[var(--restaurant-color-hex-d7a85b)] underline-offset-4 transition hover:text-[var(--restaurant-color-hex-b28743)]">
                        تواصل معنا
                    </a>
                </p>
            </div>
        </section>

        {{-- Footer --}}
        <footer class="mt-6 text-center">
            <p class="text-xs leading-6 text-gray-500">
                © {{ date('Y') }} لوحة الإدارة. جميع الحقوق محفوظة.
            </p>
        </footer>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.10.5/build/js/intlTelInput.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginTabStorageKey = 'restaurant-login-tab';
            const tabList = document.querySelector('[role="tablist"][data-login-tab-locked]');
            const adminPanel = document.querySelector('#admin-login-panel');
            const cashierPanel = document.querySelector('#cashier-login-panel');
            const captainPanel = document.querySelector('#captain-login-panel');
            const phoneForms = {
                admin: {
                    phone: '#phone',
                    countryCode: '#country_code',
                    form: '#admin-login-form'
                },
                cashier: {
                    phone: '#cashier-phone',
                    countryCode: '#cashier_country_code',
                    form: '#cashier-login-form'
                },
                captain: {
                    phone: '#captain-phone',
                    countryCode: '#captain_country_code',
                    form: '#captain-login-form'
                },
            };
            const initializedPhoneForms = {};

            function selectLoginTab(role) {
                document.querySelectorAll('.login-role-tab').forEach(function(tab) {
                    const selected = tab.dataset.loginTab === role;
                    tab.classList.toggle('is-active', selected);
                    tab.setAttribute('aria-selected', selected ? 'true' : 'false');
                });
                adminPanel.hidden = role !== 'admin';
                cashierPanel.hidden = role !== 'cashier';
                captainPanel.hidden = role !== 'captain';
            }

            function initializePhoneForm(role) {
                const fields = phoneForms[role];
                if (!fields || initializedPhoneForms[role] || !window.intlTelInput) {
                    return;
                }

                const phoneInput = document.querySelector(fields.phone);
                const countryCodeInput = document.querySelector(fields.countryCode);
                const loginForm = document.querySelector(fields.form);

                if (!phoneInput || !countryCodeInput || !loginForm) {
                    return;
                }

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
                    countryCodeInput.value = country && country.dialCode ?
                        '+' + country.dialCode :
                        '';
                }

                phoneInput.addEventListener('input', function() {
                    this.value = this.value.replace(/\D/g, '');
                });
                phoneInput.addEventListener('countrychange', updateCountryCode);
                loginForm.addEventListener('submit', function() {
                    updateCountryCode();
                    phoneInput.value = phoneInput.value.replace(/\D/g, '');
                });
                updateCountryCode();
                initializedPhoneForms[role] = true;
            }

            document.querySelectorAll('.login-role-tab').forEach(function(tab) {
                tab.addEventListener('click', function() {
                    const selectedRole = tab.dataset.loginTab;
                    selectLoginTab(selectedRole);
                    try {
                        localStorage.setItem(loginTabStorageKey, selectedRole);
                    } catch (error) {
                        console.warn('Could not save the login tab preference.', error);
                    }
                    window.setTimeout(function() {
                        initializePhoneForm(selectedRole);
                    }, 0);
                });
            });

            let selectedRole = document.querySelector('.login-role-tab.is-active')?.dataset.loginTab || 'admin';
            if (tabList.dataset.loginTabLocked !== 'true') {
                try {
                    const savedRole = localStorage.getItem(loginTabStorageKey);
                    if (savedRole === 'admin' || savedRole === 'cashier' || savedRole === 'captain') {
                        selectedRole = savedRole;
                    }
                } catch (error) {
                    console.warn('Could not read the saved login tab preference.', error);
                }
                selectLoginTab(selectedRole);
            }
            initializePhoneForm(selectedRole);
        });
    </script>

</body>

</html>
