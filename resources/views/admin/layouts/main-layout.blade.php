<!DOCTYPE html>
<html lang="ar" dir="rtl" x-data="{
    sidebarOpen: window.innerWidth >= 768,
    mobile: window.innerWidth < 768,
    profileOpen: false,

    init() {
        this.handleResize = () => {
            const isMobile = window.innerWidth < 768;

            if (isMobile !== this.mobile) {
                this.sidebarOpen = !isMobile;
                this.profileOpen = false;
            }

            this.mobile = isMobile;
        };

        window.addEventListener('resize', this.handleResize);
    }
}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المطعم</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            overflow-x: hidden;
        }
    </style>
</head>

<body class="min-h-screen bg-gray-100 text-gray-800">

    {{-- Mobile Sidebar Overlay --}}
    <div x-cloak x-show="mobile && sidebarOpen" @click="sidebarOpen = false" x-transition.opacity
        class="fixed inset-0 z-40 bg-black/50 md:hidden">
    </div>

    <div class="min-h-screen">

        {{-- Sidebar --}}
        <aside
            class="fixed inset-y-0 right-0 z-50 flex flex-col overflow-hidden bg-slate-900 text-white shadow-xl transition-all duration-300 md:sticky md:top-0 md:float-right md:h-screen md:shadow-none"
            :class="mobile
                ?
                (sidebarOpen ? 'translate-x-0 w-64' : 'translate-x-full w-64') :
                (sidebarOpen ? 'translate-x-0 w-64' : 'translate-x-0 w-20')">

            {{-- Sidebar Header --}}
            <div class="flex h-20 shrink-0 items-center justify-between border-b border-slate-700 px-4">

                <div x-show="sidebarOpen || mobile" x-transition.opacity class="flex min-w-0 items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-teal-500 text-xl">
                        🍽️
                    </div>

                    <div class="min-w-0">
                        <h1 class="whitespace-nowrap text-lg font-bold">مطعمي</h1>
                        <p class="whitespace-nowrap text-xs text-slate-400">نظام إدارة المطعم</p>
                    </div>
                </div>

                <button type="button" @click="sidebarOpen = !sidebarOpen"
                    class="hidden rounded-lg p-2 text-slate-300 transition hover:bg-slate-800 hover:text-white md:block"
                    :class="!sidebarOpen ? 'mx-auto' : ''" aria-label="طي أو توسيع القائمة">
                    <svg class="h-5 w-5 transition-transform duration-300" :class="sidebarOpen ? '' : 'rotate-180'"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <button type="button" @click="sidebarOpen = false"
                    class="rounded-lg p-2 text-slate-300 hover:bg-slate-800 md:hidden" aria-label="إغلاق القائمة">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Sidebar Navigation --}}
            <nav class="flex-1 space-y-2 overflow-y-auto px-3 py-5">

                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-3 transition
        {{ request()->routeIs('admin.dashboard')
            ? 'bg-teal-500 text-white hover:bg-teal-600'
            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                    :class="!sidebarOpen && !mobile ? 'justify-center' : ''" title="الرئيسية">

                    <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10.5L12 3l9 7.5M5 9v11h14V9M9 20v-6h6v6" />
                    </svg>

                    <span x-show="sidebarOpen || mobile" x-transition.opacity class="whitespace-nowrap">
                        الرئيسية
                    </span>
                </a>

                <a href="#"
                    class="flex items-center gap-3 rounded-xl px-3 py-3 text-slate-300 transition hover:bg-slate-800 hover:text-white"
                    :class="!sidebarOpen && !mobile ? 'justify-center' : ''" title="الطلبات">
                    <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4h18v16H3zM7 8h10M7 12h10M7 16h6" />
                    </svg>
                    <span x-show="sidebarOpen || mobile" x-transition.opacity class="whitespace-nowrap">الطلبات</span>
                </a>


                <a href="{{ route('admin.category-food.index') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-3 transition
        {{ request()->routeIs('admin.category-food.*')
            ? 'bg-teal-500 text-white hover:bg-teal-600'
            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                    :class="!sidebarOpen && !mobile ? 'justify-center' : ''" title="المنيو">

                    <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>

                    <span x-show="sidebarOpen || mobile" x-transition.opacity class="whitespace-nowrap">
                        المنيو
                    </span>
                </a>

                <a href="#"
                    class="flex items-center gap-3 rounded-xl px-3 py-3 text-slate-300 transition hover:bg-slate-800 hover:text-white"
                    :class="!sidebarOpen && !mobile ? 'justify-center' : ''" title="التصنيفات">
                    <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h6v6h-6z" />
                    </svg>
                    <span x-show="sidebarOpen || mobile" x-transition.opacity class="whitespace-nowrap">التصنيفات</span>
                </a>

                {{-- <a href="{{ route('tables.index') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-3 text-slate-300 transition hover:bg-slate-800 hover:text-white"
                    :class="!sidebarOpen && !mobile ? 'justify-center' : ''" title="الطاولات">
                    <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 10h16M6 10v9m12-9v9M3 7h18v3H3z" />
                    </svg>
                    <span x-show="sidebarOpen || mobile" x-transition.opacity class="whitespace-nowrap">الطاولات</span>
                </a> --}}
                <a href="{{ route('tables.index') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-3 transition
        {{ request()->routeIs('tables.*')
            ? 'bg-teal-500 text-white hover:bg-teal-600'
            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                    :class="!sidebarOpen && !mobile ? 'justify-center' : ''" title="الطاولات">

                    <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 10h16M6 10v9m12-9v9M3 7h18v3H3z" />
                    </svg>

                    <span x-show="sidebarOpen || mobile" x-transition.opacity class="whitespace-nowrap">
                        الطاولات
                    </span>
                </a>
                <a href="#"
                    class="flex items-center gap-3 rounded-xl px-3 py-3 text-slate-300 transition hover:bg-slate-800 hover:text-white"
                    :class="!sidebarOpen && !mobile ? 'justify-center' : ''" title="التقارير">
                    <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 19V5m0 14h16M8 15v-4m4 4V7m4 8v-6" />
                    </svg>
                    <span x-show="sidebarOpen || mobile" x-transition.opacity
                        class="whitespace-nowrap">التقارير</span>
                </a>

                <a href="{{ route('admin.settings.index') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-3 transition
        {{ request()->routeIs('admin.settings.*')
            ? 'bg-teal-500 text-white hover:bg-teal-600'
            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                    :class="!sidebarOpen && !mobile ? 'justify-center' : ''" title="الإعدادات">

                    <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8a4 4 0 100 8 4 4 0 000-8zM19.4 15a1.7 1.7 0 00.3 1.9l.1.1-1.5 2.6-.2-.1a1.7 1.7 0 00-1.8.2l-.2.1h-3l-.1-.2a1.7 1.7 0 00-1.5-1l-.2-.1-2.6 1.5-.1-.2a1.7 1.7 0 00-1.9-.3l-.1.1-2.6-1.5.1-.2a1.7 1.7 0 00-.2-1.8l-.1-.2v-3l.2-.1a1.7 1.7 0 001-1.5l.1-.2-1.5-2.6.2-.1a1.7 1.7 0 00.3-1.9l-.1-.1 1.5-2.6.2.1a1.7 1.7 0 001.8-.2l.2-.1h3l.1.2a1.7 1.7 0 001.5 1l.2.1 2.6-1.5.1.2a1.7 1.7 0 001.9.3l.1-.1 2.6 1.5-.1.2a1.7 1.7 0 00.2 1.8l.1.2v3l-.2.1a1.7 1.7 0 00-1 1.5l-.1.2 1.5 2.6-.2.1z" />
                    </svg>

                    <span x-show="sidebarOpen || mobile" x-transition.opacity class="whitespace-nowrap">
                        الإعدادات
                    </span>
                </a>

            </nav>

            {{-- Sidebar Footer --}}
            <div class="shrink-0 border-t border-slate-700 p-3">
                <div class="flex items-center gap-3 rounded-xl bg-slate-800 p-3"
                    :class="!sidebarOpen && !mobile ? 'justify-center' : ''">
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-teal-500 font-bold text-white">
                        A
                    </div>

                    <div x-show="sidebarOpen || mobile" x-transition.opacity class="min-w-0">
                        <p class="truncate text-sm font-semibold">مدير المطعم</p>
                        <p class="truncate text-xs text-slate-400">مدير النظام</p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="min-h-screen min-w-0 transition-all duration-300"
            :class="mobile ? 'mr-0' : (sidebarOpen ? 'md:mr-64' : 'md:mr-20')">

            <div class="p-3 sm:p-5 lg:p-8">

                {{-- Responsive Header --}}
                <header class="mb-6 rounded-2xl bg-white p-3 shadow-sm sm:p-5">
                    <div class="flex items-center justify-between gap-2 sm:gap-4">

                        <div class="flex min-w-0 items-center gap-2 sm:gap-3">
                            <button type="button" @click="sidebarOpen = !sidebarOpen"
                                class="shrink-0 rounded-xl bg-slate-900 p-2 text-white transition hover:bg-slate-800 md:hidden"
                                aria-label="فتح القائمة">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>

                            <div class="min-w-0">
                                <h2 class="truncate text-base font-bold text-gray-800 sm:text-2xl">
                                    لوحة تحكم المطعم
                                </h2>
                                <p class="mt-1 hidden text-sm text-gray-500 sm:block">
                                    مرحباً بك في نظام إدارة المطعم
                                </p>
                            </div>
                        </div>

                        <div class="flex shrink-0 items-center gap-2 sm:gap-3 lg:gap-4">

                            {{-- Search
                            <div class="relative hidden lg:block">
                                <input type="search" placeholder="ابحث هنا..."
                                    class="w-40 rounded-xl border border-gray-200 bg-gray-50 py-2.5 pe-10 ps-4 text-sm outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100 xl:w-64">

                                <svg class="absolute right-3 top-3 h-4 w-4 text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="7" stroke-width="2" />
                                    <path stroke-linecap="round" stroke-width="2" d="m16 16 4 4" />
                                </svg>
                            </div> --}}

                            {{-- Notifications --}}
                            <button type="button"
                                class="relative shrink-0 rounded-xl border border-gray-200 p-2 text-gray-500 transition hover:bg-gray-50 sm:p-2.5"
                                aria-label="الإشعارات">
                                <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18 8a6 6 0 00-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4" />
                                </svg>
                                <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-red-500"></span>
                            </button>

                            {{-- Profile Dropdown --}}
                            <div class="relative" @click.outside="profileOpen = false"
                                @keydown.escape.window="profileOpen = false">

                                <button type="button" @click="profileOpen = !profileOpen"
                                    :aria-expanded="profileOpen" aria-label="قائمة الملف الشخصي"
                                    class="flex items-center gap-1 rounded-xl border border-gray-200 p-1.5 transition hover:bg-gray-50 sm:gap-3 sm:p-2">

                                    <div
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-teal-100 font-bold text-teal-700 sm:h-10 sm:w-10">
                                        A
                                    </div>

                                    <div class="hidden text-right sm:block">
                                        <p class="max-w-32 truncate text-sm font-semibold text-gray-800">
                                            مدير المطعم
                                        </p>
                                        <p class="text-xs text-gray-500">مدير النظام</p>
                                    </div>

                                    <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200"
                                        :class="profileOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="m6 9 6 6 6-6" />
                                    </svg>
                                </button>

                                {{-- Dropdown Menu --}}
                                <div x-cloak x-show="profileOpen" x-transition.origin.top.left
                                    class="absolute left-0 top-full z-[60] mt-2 w-52 overflow-hidden rounded-xl border border-gray-100 bg-white py-2 shadow-xl sm:w-56">

                                    <div class="border-b border-gray-100 px-4 py-3 sm:hidden">
                                        <p class="text-sm font-semibold text-gray-800">مدير المطعم</p>
                                        <p class="mt-1 text-xs text-gray-500">مدير النظام</p>
                                    </div>

                                    <a href="#"
                                        class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 transition hover:bg-gray-50">
                                        <svg class="h-5 w-5 shrink-0 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="8" r="4" stroke-width="2" />
                                            <path stroke-linecap="round" stroke-width="2"
                                                d="M5 21v-2a7 7 0 0114 0v2" />
                                        </svg>
                                        الملف الشخصي
                                    </a>

                                    {{--
                                    <a href="#"
                                        class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 transition hover:bg-gray-50">
                                        <svg class="h-5 w-5 shrink-0 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="3" stroke-width="2" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19.4 15a1.7 1.7 0 00.3 1.9l.1.1-1.5 2.6-.2-.1a1.7 1.7 0 00-1.8.2l-.2.1h-3l-.1-.2a1.7 1.7 0 00-1.5-1l-.2-.1-2.6 1.5-.1-.2a1.7 1.7 0 00-1.9-.3l-.1.1-2.6-1.5.1-.2a1.7 1.7 0 00-.2-1.8l-.1-.2v-3l.2-.1a1.7 1.7 0 001-1.5l.1-.2-1.5-2.6.2-.1a1.7 1.7 0 00.3-1.9l-.1-.1 1.5-2.6.2.1a1.7 1.7 0 001.8-.2l.2-.1h3l.1.2a1.7 1.7 0 001.5 1l.2.1 2.6-1.5.1.2a1.7 1.7 0 001.9.3l.1-.1 2.6 1.5-.1.2a1.7 1.7 0 00.2 1.8l.1.2v3l-.2.1a1.7 1.7 0 00-1 1.5l-.1.2 1.5 2.6-.2.1z" />
                                        </svg>
                                        الإعدادات
                                    </a>
                                    --}}

                                    <div class="my-2 border-t border-gray-100"></div>

                                    <form method="POST" action="{{ route('admin.logout') }}">
                                        @csrf

                                        <button type="submit"
                                            class="flex w-full items-center gap-3 px-4 py-3 text-sm text-red-600 transition hover:bg-red-50">
                                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2-2h-4M10 17l5-5-5-5M15 12H3" />
                                            </svg>
                                            تسجيل الخروج
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                @yield('content')

                <footer class="mt-8 text-center text-xs text-gray-400">
                    جميع الحقوق محفوظة © {{ date('Y') }} — نظام إدارة المطعم
                </footer>

            </div>
        </main>
    </div>
    @stack('scripts')
</body>

</html>
