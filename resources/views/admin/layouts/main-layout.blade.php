<!DOCTYPE html>
<html lang="ar" dir="rtl" x-data="adminLayout()" x-init="init()">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#10382c">

    <title>@yield('title', 'لوحة تحكم المطعم')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        :root {
            --brand-dark: #10382c;
            --brand-primary: #174c3c;
            --brand-medium: #26745a;
            --brand-light: #e8f4ee;
            --brand-accent: #34b69a;
            --surface: #f5f7f6;
        }

        html {
            scroll-behavior: smooth;
            scrollbar-gutter: stable;
        }

        body {
            min-height: 100vh;
            overflow-x: clip;
            background: var(--surface);
        }

        button,
        a,
        input,
        select {
            -webkit-tap-highlight-color: transparent;
        }

        button:focus-visible,
        a:focus-visible,
        input:focus-visible,
        select:focus-visible {
            outline: 3px solid rgb(52 182 154 / 45%);
            outline-offset: 3px;
        }

        .sidebar-scroll {
            scrollbar-width: thin;
            scrollbar-color: #47685c transparent;
        }

        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #47685c;
            border-radius: 20px;
        }

        .sidebar-brand {
            background:
                radial-gradient(circle at 10% 10%, rgb(52 182 154 / 15%), transparent 40%),
                linear-gradient(145deg, #10382c, #174c3c 65%, #1a5743);
        }

        .sidebar-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 48px;
            padding: 12px;
            border: 1px solid transparent;
            border-radius: 15px;
            color: #b6c8bf;
            transition:
                background-color .2s ease,
                color .2s ease,
                border-color .2s ease,
                transform .2s ease;
        }

        .sidebar-link:hover {
            color: #fff;
            background: rgb(255 255 255 / 6%);
            border-color: rgb(255 255 255 / 5%);
        }

        .sidebar-link:active {
            transform: scale(.985);
        }

        .sidebar-link.active {
            color: #fff;
            background: linear-gradient(110deg, #26745a, #174c3c);
            border-color: rgb(110 231 183 / 18%);
            box-shadow: 0 5px 16px rgb(0 0 0 / 10%);
        }

        .sidebar-link.active::before {
            content: "";
            position: absolute;
            right: -13px;
            top: 25%;
            width: 4px;
            height: 50%;
            border-radius: 4px 0 0 4px;
            background: #6ee7b7;
        }

        .sidebar-link.active .nav-icon {
            color: #a7f3d0;
        }

        .nav-icon {
            width: 21px;
            height: 21px;
            flex-shrink: 0;
            transition: transform .2s ease;
        }

        .sidebar-link:hover .nav-icon {
            transform: scale(1.08);
        }

        .sidebar-tooltip {
            position: absolute;
            right: calc(100% + 12px);
            top: 50%;
            z-index: 70;
            transform: translateY(-50%);
            white-space: nowrap;
            border: 1px solid rgb(255 255 255 / 10%);
            border-radius: 9px;
            background: #10382c;
            padding: 8px 12px;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            box-shadow: 0 8px 25px rgb(0 0 0 / 15%);
            pointer-events: none;
        }

        .admin-header {
            background: rgb(255 255 255 / 92%);
            border: 1px solid rgb(255 255 255 / 90%);
            box-shadow:
                0 4px 20px rgb(16 56 44 / 4%),
                0 1px 3px rgb(16 56 44 / 3%);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        .header-action {
            display: inline-flex;
            height: 43px;
            width: 43px;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            border: 1px solid #e8eeea;
            border-radius: 14px;
            background: #fff;
            color: #60736a;
            transition: all .2s ease;
        }

        .header-action:hover {
            color: var(--brand-primary);
            background: #f0f8f4;
            border-color: #d5e9dd;
        }

        .brand-button {
            background: linear-gradient(135deg, #26745a, #174c3c);
            box-shadow: 0 5px 12px rgb(23 76 60 / 15%);
            transition: all .2s ease;
        }

        .brand-button:hover {
            background: linear-gradient(135deg, #1d634b, #10382c);
            box-shadow: 0 7px 17px rgb(23 76 60 / 20%);
        }

        .dropdown-menu {
            box-shadow:
                0 18px 45px rgb(16 56 44 / 12%),
                0 2px 8px rgb(16 56 44 / 5%);
        }

        @media (max-width: 767px) {
            .admin-header {
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
            }
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body class="min-h-screen text-slate-800 antialiased">

    {{-- Mobile Overlay --}}
    <div x-cloak x-show="mobile && sidebarOpen" x-transition.opacity.duration.200ms @click="closeSidebar()"
        class="fixed inset-0 z-40 bg-[#071b14]/60 backdrop-blur-[3px] md:hidden">
    </div>

    <div class="min-h-screen">

        {{-- Sidebar --}}
        <aside id="admin-sidebar"
            class="fixed inset-y-0 right-0 z-50 flex flex-col overflow-hidden
                   border-l border-white/[.06] bg-[#10382c] text-white
                   shadow-2xl transition-[width,transform] duration-300 ease-in-out
                   md:sticky md:top-0 md:float-right md:h-screen md:shadow-none"
            :class="mobile
                ?
                (sidebarOpen ?
                    'translate-x-0 w-72 max-w-[85vw]' :
                    'translate-x-full w-72 max-w-[85vw]') :
                (sidebarOpen ?
                    'translate-x-0 w-64' :
                    'translate-x-0 w-20')">

            {{-- Brand Header --}}
            <div
                class="sidebar-brand relative flex h-24 shrink-0 items-center
                        justify-between border-b border-white/[.08] px-4">

                <div
                    class="absolute inset-x-0 top-0 h-[3px]
                            bg-gradient-to-l from-emerald-300 via-teal-400 to-emerald-700">
                </div>

                <a href="{{ route('admin.dashboard') }}" class="flex min-w-0 items-center gap-3" aria-label="الرئيسية">

                    <div x-show="sidebarOpen || mobile" x-transition.opacity.duration.150ms
                        class="flex h-12 w-12 shrink-0 items-center justify-center
                                rounded-2xl border border-white/15
                                bg-white/10 text-2xl shadow-inner backdrop-blur-sm">
                        🍽️
                    </div>

                    <div x-show="sidebarOpen || mobile" x-transition.opacity.duration.150ms class="min-w-0">
                        <h1 class="whitespace-nowrap text-xl font-extrabold tracking-tight">
                            مطعمي
                        </h1>
                        <p class="mt-1 whitespace-nowrap text-[11px] font-medium text-emerald-100/70">
                            نظام إدارة المطعم
                        </p>
                    </div>
                </a>

                {{-- Desktop Collapse --}}
                <button type="button" @click="toggleSidebar()"
                    class="hidden shrink-0 rounded-xl border border-white/10
                           bg-white/5 p-2 text-emerald-100/80
                           transition hover:bg-white/10 hover:text-white md:inline-flex"
                    :class="!sidebarOpen ? 'mx-auto' : ''" :title="sidebarOpen ? 'تصغير القائمة' : 'توسيع القائمة'"
                    :aria-label="sidebarOpen ? 'تصغير القائمة' : 'توسيع القائمة'">

                    <svg class="h-5 w-5 transition-transform duration-300" :class="sidebarOpen ? '' : 'rotate-180'"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m15 18-6-6 6-6" />
                    </svg>
                </button>

                {{-- Mobile Close --}}
                <button type="button" @click="closeSidebar()"
                    class="rounded-xl border border-white/10 bg-white/5 p-2
                           text-emerald-100 transition hover:bg-white/10 hover:text-white md:hidden"
                    aria-label="إغلاق القائمة">

                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Sidebar Navigation --}}
            <nav class="sidebar-scroll flex-1 space-y-2 overflow-x-hidden overflow-y-auto px-3 py-6">

                <p x-show="sidebarOpen || mobile"
                    class="mb-4 px-3 text-[10px] font-extrabold uppercase tracking-[.18em] text-emerald-100/45">
                    القائمة الرئيسية
                </p>

                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}" @click="navigateSidebar()"
                    class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    :class="!sidebarOpen && !mobile ? 'justify-center px-0' : ''" title="الرئيسية">

                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m3 10 9-7 9 7M5 9v11h14V9M9 20v-6h6v6" />
                    </svg>

                    <span x-show="sidebarOpen || mobile" class="whitespace-nowrap text-sm font-semibold">الرئيسية</span>

                    <span x-cloak x-show="!sidebarOpen && !mobile" class="sidebar-tooltip">الرئيسية</span>
                </a>

                {{-- Orders --}}
                <a href="#" @click="navigateSidebar()" class="sidebar-link"
                    :class="!sidebarOpen && !mobile ? 'justify-center px-0' : ''" title="الطلبات">

                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16v16H4zM8 8h8M8 12h8M8 16h5" />
                    </svg>

                    <span x-show="sidebarOpen || mobile" class="whitespace-nowrap text-sm font-semibold">الطلبات</span>

                    <span x-cloak x-show="!sidebarOpen && !mobile" class="sidebar-tooltip">الطلبات</span>
                </a>

                {{-- Food Menu --}}
                <a href="{{ route('admin.category-food.index') }}" @click="navigateSidebar()"
                    class="sidebar-link {{ request()->routeIs('admin.category-food.*') ? 'active' : '' }}"
                    :class="!sidebarOpen && !mobile ? 'justify-center px-0' : ''" title="المنيو">

                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>

                    <span x-show="sidebarOpen || mobile" class="whitespace-nowrap text-sm font-semibold">المنيو</span>

                    <span x-cloak x-show="!sidebarOpen && !mobile" class="sidebar-tooltip">المنيو</span>
                </a>

                {{-- Categories --}}
                {{-- <a href="#" @click="navigateSidebar()" class="sidebar-link"
                    :class="!sidebarOpen && !mobile ? 'justify-center px-0' : ''" title="التصنيفات">

                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h6v6h-6z" />
                    </svg>

                    <span x-show="sidebarOpen || mobile"
                        class="whitespace-nowrap text-sm font-semibold">التصنيفات</span>

                    <span x-cloak x-show="!sidebarOpen && !mobile" class="sidebar-tooltip">التصنيفات</span>
                </a> --}}

                {{-- Tables --}}
                <a href="{{ route('tables.index') }}" @click="navigateSidebar()"
                    class="sidebar-link {{ request()->routeIs('tables.*') ? 'active' : '' }}"
                    :class="!sidebarOpen && !mobile ? 'justify-center px-0' : ''" title="الطاولات">

                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 10h16M6 10v10m12-10v10M3 7h18v3H3z" />
                    </svg>

                    <span x-show="sidebarOpen || mobile"
                        class="whitespace-nowrap text-sm font-semibold">الطاولات</span>

                    <span x-cloak x-show="!sidebarOpen && !mobile" class="sidebar-tooltip">الطاولات</span>
                </a>

                {{-- Reports --}}
                <a href="#" @click="navigateSidebar()" class="sidebar-link"
                    :class="!sidebarOpen && !mobile ? 'justify-center px-0' : ''" title="التقارير">

                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 19V5m0 14h16M8 15v-4m4 4V7m4 8v-6" />
                    </svg>

                    <span x-show="sidebarOpen || mobile"
                        class="whitespace-nowrap text-sm font-semibold">التقارير</span>

                    <span x-cloak x-show="!sidebarOpen && !mobile" class="sidebar-tooltip">التقارير</span>
                </a>

                {{-- Divider --}}
                <div class="my-5 border-t border-white/[.09]"></div>

                <p x-show="sidebarOpen || mobile"
                    class="mb-4 px-3 text-[10px] font-extrabold uppercase tracking-[.18em] text-emerald-100/45">
                    إدارة النظام
                </p>

                {{-- Settings --}}
                <a href="{{ route('admin.settings.index') }}" @click="navigateSidebar()"
                    class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
                    :class="!sidebarOpen && !mobile ? 'justify-center px-0' : ''" title="الإعدادات">

                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.8">
                        <circle cx="12" cy="12" r="3" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m19.4 15 .1.1-1.5 2.6-.2-.1a1.7 1.7 0 0 0-1.8.2l-.2.1h-3l-.1-.2a1.7 1.7 0 0 0-1.5-1l-.2-.1-2.6 1.5-.1-.2a1.7 1.7 0 0 0-1.9-.3l-.1.1-2.6-1.5.1-.2a1.7 1.7 0 0 0-.2-1.8l-.1-.2v-3l.2-.1a1.7 1.7 0 0 0 1-1.5l.1-.2-1.5-2.6.2-.1a1.7 1.7 0 0 0 .3-1.9l-.1-.1 1.5-2.6.2.1a1.7 1.7 0 0 0 1.8-.2l.2-.1h3l.1.2a1.7 1.7 0 0 0 1.5 1l.2.1 2.6-1.5.1.2a1.7 1.7 0 0 0 1.9.3l.1-.1 2.6 1.5-.1.2a1.7 1.7 0 0 0 .2 1.8l.1.2v3l-.2.1a1.7 1.7 0 0 0-1 1.5l-.1.2 1.5 2.6-.2.1Z" />
                    </svg>

                    <span x-show="sidebarOpen || mobile"
                        class="whitespace-nowrap text-sm font-semibold">الإعدادات</span>

                    <span x-cloak x-show="!sidebarOpen && !mobile" class="sidebar-tooltip">الإعدادات</span>
                </a>
            </nav>

            {{-- Sidebar Footer --}}
            <div class="shrink-0 border-t border-white/[.09] p-3">

                <div class="flex items-center gap-3 rounded-2xl border border-white/[.07]
                            bg-white/[.045] p-3"
                    :class="!sidebarOpen && !mobile ? 'justify-center' : ''">

                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center
                                rounded-xl bg-gradient-to-br from-emerald-300 to-teal-600
                                text-sm font-extrabold text-[#10382c] shadow-md">
                        A
                    </div>

                    <div x-show="sidebarOpen || mobile" class="min-w-0 flex-1">
                        <p class="truncate text-sm font-bold text-white">مدير المطعم</p>
                        <div class="mt-1 flex items-center gap-1.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                            <p class="truncate text-[11px] text-emerald-100/60">مدير النظام</p>
                        </div>
                    </div>
                </div>

                <p x-show="sidebarOpen || mobile" class="mt-3 text-center text-[10px] text-emerald-100/35">
                    نظام إدارة المطعم © {{ date('Y') }}
                </p>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="min-h-screen min-w-0 transition-[margin] duration-300 ease-in-out"
            :class="mobile ? 'mr-0' : (sidebarOpen ? 'md:mr-64' : 'md:mr-20')">

            <div class="min-h-screen min-w-0 p-3 sm:p-5 lg:p-7 xl:p-8">

                {{-- Header --}}
                <header
                    class="admin-header sticky top-3 z-30 mb-6 rounded-3xl p-3.5
                               sm:top-4 sm:mb-7 sm:p-5">

                    <div class="flex items-center justify-between gap-3">

                        {{-- Page Heading --}}
                        <div class="flex min-w-0 items-center gap-3">

                            {{-- Mobile Sidebar Toggle --}}
                            <button type="button" @click="toggleMobileSidebar()"
                                class="brand-button flex h-11 w-11 shrink-0 items-center
                                       justify-center rounded-2xl text-white md:hidden"
                                :aria-expanded="sidebarOpen" aria-controls="admin-sidebar"
                                :aria-label="sidebarOpen ? 'إغلاق القائمة' : 'فتح القائمة'">

                                <svg x-show="!sidebarOpen" class="h-5 w-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                </svg>

                                <svg x-cloak x-show="sidebarOpen" class="h-5 w-5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>

                            <div class="min-w-0">
                                <div class="flex min-w-0 flex-wrap items-center gap-2 sm:gap-3">
                                    <h2
                                        class="max-w-full truncate text-base font-extrabold
                                               tracking-tight text-[#10382c] sm:text-2xl">
                                        @yield('page-heading', 'لوحة تحكم المطعم')
                                    </h2>

                                    <span
                                        class="hidden items-center gap-1.5 rounded-full
                                                 border border-emerald-100 bg-emerald-50
                                                 px-2.5 py-1 text-[10px] font-bold
                                                 text-[#174c3c] sm:inline-flex">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        لوحة الإدارة
                                    </span>
                                </div>

                                <p class="mt-1 hidden text-xs text-slate-500 sm:block sm:text-sm">
                                    مرحباً بك، نتمنى لك يوماً مليئاً بالإنجاز.
                                </p>
                            </div>
                        </div>

                        {{-- Header Actions --}}
                        <div class="flex shrink-0 items-center gap-2 sm:gap-3">

                            {{-- Notifications --}}
                            <div class="relative" @click.outside="notificationsOpen = false"
                                @keydown.escape.window="notificationsOpen = false">

                                <button type="button"
                                    @click="notificationsOpen = !notificationsOpen; profileOpen = false"
                                    class="header-action relative" :aria-expanded="notificationsOpen"
                                    aria-label="الإشعارات" title="الإشعارات">

                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.7">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4" />
                                    </svg>

                                    <span
                                        class="absolute right-2 top-2 h-2.5 w-2.5
                                                 rounded-full border-2 border-white bg-rose-500"></span>
                                </button>

                                <div x-cloak x-show="notificationsOpen" x-transition.origin.top.left.duration.150ms
                                    class="dropdown-menu absolute left-0 top-full z-[60] mt-3
                                           w-72 max-w-[calc(100vw-2rem)] overflow-hidden
                                           rounded-2xl border border-slate-100 bg-white">

                                    <div
                                        class="flex items-center justify-between border-b
                                                border-slate-100 bg-slate-50/80 px-4 py-3.5">
                                        <h3 class="text-sm font-extrabold text-[#10382c]">
                                            الإشعارات
                                        </h3>
                                        <span
                                            class="rounded-full bg-emerald-50 px-2 py-1
                                                     text-[10px] font-bold text-emerald-700">
                                            التنبيهات
                                        </span>
                                    </div>

                                    <div class="p-5 text-center">
                                        <div
                                            class="mx-auto flex h-12 w-12 items-center
                                                    justify-center rounded-2xl bg-emerald-50
                                                    text-[#174c3c]">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="1.6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4" />
                                            </svg>
                                        </div>

                                        <p class="mt-3 text-sm font-bold text-slate-700">
                                            مركز الإشعارات
                                        </p>
                                        <p class="mt-1 text-xs leading-5 text-slate-400">
                                            ستظهر هنا إشعارات النظام عند تفعيلها.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Profile Dropdown --}}
                            <div class="relative" @click.outside="profileOpen = false"
                                @keydown.escape.window="profileOpen = false">

                                <button type="button" @click="profileOpen = !profileOpen; notificationsOpen = false"
                                    class="flex items-center gap-2 rounded-2xl border
                                           border-slate-200 bg-white p-1.5 transition
                                           hover:border-emerald-200 hover:bg-emerald-50/40
                                           sm:gap-3 sm:p-2"
                                    :aria-expanded="profileOpen" aria-label="قائمة الملف الشخصي">

                                    <div
                                        class="flex h-9 w-9 shrink-0 items-center justify-center
                                                rounded-xl bg-gradient-to-br from-emerald-300
                                                to-teal-700 text-sm font-extrabold text-white
                                                shadow-sm sm:h-10 sm:w-10">
                                        A
                                    </div>

                                    <div class="hidden text-right sm:block">
                                        <p class="max-w-32 truncate text-sm font-extrabold text-[#10382c]">
                                            مدير المطعم
                                        </p>
                                        <p class="mt-0.5 text-[11px] font-medium text-slate-500">
                                            مدير النظام
                                        </p>
                                    </div>

                                    <svg class="hidden h-4 w-4 shrink-0 text-slate-400
                                                transition-transform duration-200 sm:block"
                                        :class="profileOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                                    </svg>
                                </button>

                                {{-- Profile Menu --}}
                                <div x-cloak x-show="profileOpen" x-transition.origin.top.left.duration.150ms
                                    class="dropdown-menu absolute left-0 top-full z-[60] mt-3
                                           w-60 max-w-[calc(100vw-2rem)] overflow-hidden
                                           rounded-2xl border border-slate-100 bg-white py-2">

                                    <div
                                        class="border-b border-slate-100 bg-gradient-to-l
                                                from-emerald-50/80 to-white px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-11 w-11 shrink-0 items-center
                                                        justify-center rounded-xl bg-[#174c3c]
                                                        text-sm font-extrabold text-white">
                                                A
                                            </div>

                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-extrabold text-[#10382c]">
                                                    مدير المطعم
                                                </p>
                                                <p class="mt-1 text-xs text-slate-500">
                                                    مدير النظام
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <a href="#"
                                        class="flex items-center gap-3 px-4 py-3 text-sm
                                               font-medium text-slate-600 transition
                                               hover:bg-emerald-50 hover:text-[#174c3c]">

                                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="1.7">
                                            <circle cx="12" cy="8" r="4" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M5 21v-2a7 7 0 0 1 14 0v2" />
                                        </svg>

                                        الملف الشخصي
                                    </a>

                                    <div class="mx-3 border-t border-slate-100"></div>

                                    <form method="POST" action="{{ route('admin.logout') }}">
                                        @csrf

                                        <button type="submit"
                                            class="flex w-full items-center gap-3 px-4 py-3
                                                   text-sm font-bold text-rose-600 transition
                                                   hover:bg-rose-50">

                                            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3" />
                                            </svg>

                                            تسجيل الخروج
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                {{-- Main Page Content --}}
                <div class="min-w-0">
                    @yield('content')
                </div>

                {{-- Footer --}}
                <footer class="mt-8 border-t border-emerald-900/[.07] py-5">
                    <div class="flex flex-col items-center justify-center gap-2 text-center sm:flex-row sm:gap-3">
                        <div class="flex items-center gap-2">
                            <span
                                class="flex h-7 w-7 items-center justify-center rounded-lg
                                         bg-[#174c3c]/[.07] text-sm">
                                🍽️
                            </span>

                            <p class="text-xs font-bold text-[#174c3c]">
                                نظام إدارة المطعم
                            </p>
                        </div>

                        <span class="hidden text-slate-300 sm:inline">•</span>

                        <p class="text-[11px] font-medium text-slate-400">
                            جميع الحقوق محفوظة © {{ date('Y') }}
                        </p>
                    </div>
                </footer>
            </div>
        </main>
    </div>

    {{-- Sidebar & Header State --}}
    <script>
        function adminLayout() {
            return {
                sidebarOpen: false,
                mobile: window.innerWidth < 768,
                profileOpen: false,
                notificationsOpen: false,

                init() {
                    const storageKey = 'restaurant_sidebar_open';

                    // Desktop remembers the previous sidebar preference.
                    // Mobile starts closed on each full page navigation.
                    this.mobile = window.innerWidth < 768;

                    if (this.mobile) {
                        this.sidebarOpen = false;
                    } else {
                        const saved = localStorage.getItem(storageKey);
                        this.sidebarOpen = saved === null ? true : saved === 'true';
                    }

                    this.handleResize = () => {
                        const isMobile = window.innerWidth < 768;

                        if (isMobile !== this.mobile) {
                            this.mobile = isMobile;
                            this.profileOpen = false;
                            this.notificationsOpen = false;

                            if (isMobile) {
                                this.sidebarOpen = false;
                            } else {
                                const saved = localStorage.getItem(storageKey);
                                this.sidebarOpen = saved === null ? true : saved === 'true';
                            }
                        }
                    };

                    this.handleKeydown = (event) => {
                        if (event.key === 'Escape') {
                            this.profileOpen = false;
                            this.notificationsOpen = false;

                            if (this.mobile && this.sidebarOpen) {
                                this.closeSidebar();
                            }
                        }
                    };

                    window.addEventListener('resize', this.handleResize);
                    window.addEventListener('keydown', this.handleKeydown);

                    this.$cleanup = () => {
                        window.removeEventListener('resize', this.handleResize);
                        window.removeEventListener('keydown', this.handleKeydown);
                    };
                },

                toggleSidebar() {
                    if (this.mobile) {
                        this.toggleMobileSidebar();
                        return;
                    }

                    this.sidebarOpen = !this.sidebarOpen;

                    localStorage.setItem(
                        'restaurant_sidebar_open',
                        String(this.sidebarOpen)
                    );
                },

                toggleMobileSidebar() {
                    if (!this.mobile) return;

                    this.sidebarOpen = !this.sidebarOpen;
                    this.profileOpen = false;
                    this.notificationsOpen = false;
                },

                closeSidebar() {
                    this.sidebarOpen = false;
                },

                navigateSidebar() {
                    if (this.mobile) {
                        this.closeSidebar();
                    }

                    this.profileOpen = false;
                    this.notificationsOpen = false;
                }
            };
        }
    </script>

    @stack('scripts')
</body>

</html>

