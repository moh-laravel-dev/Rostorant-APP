
{{-- resources/views/layouts/app.blade.php --}}

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'قائمة الطعام') | مطعمنا</title>

    <meta name="description" content="@yield('description', 'اكتشف قائمة الطعام واختر وجبتك المفضلة.')">
    <meta name="theme-color" content="#174c3c">

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

<body class="min-h-screen bg-[#f7f9f7] text-gray-800 antialiased">

    {{-- Navbar --}}
    <header class="sticky top-0 z-50 border-b border-gray-100 bg-white/95 shadow-sm backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-8">

            <a href="{{ route('menu.index') }}"
               class="flex min-w-0 items-center gap-3">

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#174c3c] text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M3 3v6a3 3 0 0 0 6 0V3M6 3v18M15 3v18m0-18c3 2 4 5 4 8h-4"/>
                    </svg>
                </div>

                <div class="min-w-0">
                    <span class="block truncate text-lg font-extrabold text-[#174c3c]">
                        مطعمنا
                    </span>
                    <span class="block truncate text-xs text-gray-500">
                        نكهة تستحق التجربة
                    </span>
                </div>
            </a>

            <nav class="flex items-center gap-2 sm:gap-5">
                <a href="{{ route('menu.index') }}"
                   class="rounded-lg px-3 py-2 text-sm font-bold text-[#174c3c] transition hover:bg-[#174c3c]/5">
                    قائمة الطعام
                </a>

                <a href="#contact"
                   class="hidden rounded-lg px-3 py-2 text-sm font-semibold text-gray-600 transition hover:bg-gray-100 hover:text-[#174c3c] sm:inline-flex">
                    تواصل معنا
                </a>
            </nav>

        </div>
    </header>

    {{-- Page Content --}}
    <main class="min-h-[70vh]">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer id="contact" class="mt-12 bg-[#10382c] text-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:grid-cols-2 sm:px-8 lg:grid-cols-3">

            <div>
                <h2 class="text-xl font-extrabold">مطعمنا</h2>
                <p class="mt-3 max-w-sm text-sm leading-7 text-white/70">
                    نسعى لتقديم تجربة طعام مميزة تجمع بين الجودة والطعم الرائع والخدمة الممتازة.
                </p>
            </div>

            <div>
                <h3 class="font-bold">روابط سريعة</h3>
                <ul class="mt-3 space-y-3 text-sm text-white/70">
                    <li>
                        <a href="{{ route('menu.index') }}" class="transition hover:text-white">
                            قائمة الطعام
                        </a>
                    </li>
                    <li>
                        <a href="#contact" class="transition hover:text-white">
                            تواصل معنا
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="font-bold">ساعات العمل</h3>
                <p class="mt-3 text-sm leading-7 text-white/70">
                    نرحب بكم يومياً.
                    <br>
                    نتطلع إلى خدمتكم.
                </p>
            </div>

        </div>

        <div class="border-t border-white/10 px-4 py-4 text-center text-xs text-white/60">
            © {{ date('Y') }} مطعمنا. جميع الحقوق محفوظة.
        </div>
    </footer>

    @stack('scripts')

</body>
</html>
