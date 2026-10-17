{{-- resources/views/layouts/app.blade.php --}}

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'قائمة الطعام') | {{ env('APP_NAME') }}</title>

    <meta name="description" content="@yield('description', 'اكتشف قائمة الطعام واختر وجبتك المفضلة.')">
    <meta name="theme-color" content="#174c3c">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('components.theme-mode')
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Tajawal', 'Segoe UI', Tahoma, Arial, sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="min-h-screen bg-[var(--restaurant-color-hex-f7f9f7)] text-gray-800 antialiased">



    {{-- Page Content --}}
    <main class="min-h-[70vh]">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="mt-16 border-t border-gray-200 bg-[var(--restaurant-color-hex-10382c)] text-white">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">

                {{-- Restaurant Info --}}
                <div>
                    <h3 class="mb-4 text-lg font-black">{{ env('APP_NAME') }}</h3>
                    <p class="text-sm leading-7 text-white/70">
                        أهلاً وسهلاً بكم، نسعد بخدمتكم وتقديم أشهى الوجبات المحضّرة بعناية
                        لتستمتعوا بتجربة طعام مميزة.
                    </p>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h3 class="mb-4 text-base font-extrabold">روابط سريعة</h3>
                    <ul class="space-y-3 text-sm text-white/70">
                        <li>
                            <a href="#home" class="transition hover:text-white">الرئيسية</a>
                        </li>
                        <li>
                            <a href="#categories" class="transition hover:text-white">قائمة الطعام</a>
                        </li>
                        <li>
                            <a href="#categories" class="transition hover:text-white">التصنيفات</a>
                        </li>
                        <li>
                            <a href="#contact" class="transition hover:text-white">تواصل معنا</a>
                        </li>
                    </ul>
                </div>

                {{-- Contact Information --}}
                <div id="contact">
                    <h3 class="mb-4 text-base font-extrabold">معلومات التواصل</h3>

                    <ul class="space-y-4 text-sm text-white/70">

                        @if (config('communication.whatsapp'))
                            <li>
                                <a href="https://wa.me/{{ preg_replace('/\D/', '', config('communication.whatsapp')) }}"
                                    target="_blank" rel="noopener noreferrer"
                                    class="flex items-center gap-3 transition hover:text-white">
                                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M21 11.5a8.4 8.4 0 0 1-12.8 7.2L3 20l1.3-4.9A8.4 8.4 0 1 1 21 11.5Z" />
                                        <path
                                            d="M8.5 9.5c.5 2 2 3.5 4 4l1.3-1 2 1c-.3 1.2-1.3 2-2.5 2-3.5-.5-6.5-3.5-7-7 0-1.2.8-2.2 2-2.5l1 2-0.8 1.5Z" />
                                    </svg>
                                    <span dir="ltr">{{ config('communication.whatsapp') }}</span>
                                </a>
                            </li>
                        @endif

                        @if (config('communication.phone'))
                            <li>
                                <a href="tel:{{ config('communication.phone') }}"
                                    class="flex items-center gap-3 transition hover:text-white">
                                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path
                                            d="M22 16.9v3a2 2 0 0 1-2.2 2A19.8 19.8 0 0 1 11.2 19a19.4 19.4 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.5 2.1L8 9.9a16 16 0 0 0 6 6l1.2-1.3a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.9.7a2 2 0 0 1 1.8 2.1Z" />
                                    </svg>
                                    <span dir="ltr">{{ config('communication.phone') }}</span>
                                </a>
                            </li>
                        @endif

                        @if (config('communication.email'))
                            <li>
                                <a href="mailto:{{ config('communication.email') }}"
                                    class="flex items-center gap-3 break-all transition hover:text-white">
                                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <rect x="3" y="5" width="18" height="14" rx="2" />
                                        <path d="m3 7 9 6 9-6" />
                                    </svg>
                                    <span>{{ config('communication.email') }}</span>
                                </a>
                            </li>
                        @endif

                        @if (config('communication.location'))
                            <li>
                                <a href="{{ config('communication.location') }}" target="_blank"
                                    rel="noopener noreferrer"
                                    class="flex items-center gap-3 transition hover:text-white">
                                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z" />
                                        <circle cx="12" cy="10" r="2.5" />
                                    </svg>
                                    <span>موقع المطعم على الخريطة</span>
                                </a>
                            </li>
                        @endif

                    </ul>
                </div>

                {{-- Opening Hours --}}
                <div>
                    <h3 class="mb-4 text-base font-extrabold">أوقات الدوام</h3>

                    <div class="space-y-4 text-sm text-white/70">
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="17" rx="2" />
                                <path d="M16 2v4M8 2v4M3 10h18" />
                            </svg>

                            <div>
                                <p class="mb-2 font-bold text-white">أيام العمل</p>
                                <p class="leading-7">
                                    {{ implode(
                                        '، ',
                                        array_map(
                                            fn($day) => match ($day) {
                                                'every day' => 'كل يوم',
                                                'saturday' => 'السبت',
                                                'sunday' => 'الأحد',
                                                'monday' => 'الإثنين',
                                                'tuesday' => 'الثلاثاء',
                                                'wednesday' => 'الأربعاء',
                                                'thursday' => 'الخميس',
                                                'friday' => 'الجمعة',
                                                default => $day,
                                            },
                                            config('communication.open_days', []),
                                        ),
                                    ) }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9" />
                                <path d="M12 7v5l3 2" />
                            </svg>

                            <div>
                                <p class="mb-2 font-bold text-white">ساعات العمل</p>
                                <p dir="ltr" class="text-right">
                                    {{ config('communication.open_time', '09:00') }}
                                    —
                                    {{ config('communication.close_time', '23:00') }}
                                </p>
                            </div>
                        </div>

                        <p class="text-xs leading-6 text-white/50">
                            نرحب بكم خلال أوقات العمل المحددة.
                        </p>
                    </div>
                </div>

            </div>

            {{-- Footer Bottom --}}
            <div class="mt-10 border-t border-white/15 pt-6">
                <div
                    class="flex flex-col items-center justify-between gap-3 text-center text-xs text-white/50 sm:flex-row sm:text-right">
                    <p>
                        © {{ date('Y') }} جميع الحقوق محفوظة.
                    </p>

                    <p>
                        صُنع بكل عناية لتقديم تجربة أفضل لكم.
                    </p>
                </div>
            </div>

        </div>
    </footer>

    @stack('scripts')

</body>

</html>
