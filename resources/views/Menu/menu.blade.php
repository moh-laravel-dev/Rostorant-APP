{{-- resources/views/Menu/menu.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'قائمة الطعام')

@section('content')
    <div class="restaurant-menu min-h-screen overflow-hidden bg-[#f7f8f5] pb-28 text-gray-800" dir="rtl">

        <style>
            html {
                scroll-behavior: smooth;
            }

            .restaurant-menu {
                --menu-green: #174c3c;
                --menu-dark: #10382c;
                --menu-gold: #e8c98c;
                --menu-muted: #708078;
                scroll-behavior: smooth;
            }

            .restaurant-menu #categories {
                scroll-behavior: smooth;
                -webkit-overflow-scrolling: touch;
                scroll-snap-type: x mandatory;
            }

            .restaurant-menu #categories>* {
                scroll-snap-align: start;
            }

            .restaurant-menu .menu-hero {
                background:
                    radial-gradient(ellipse at 10% 15%, rgba(232, 201, 140, .13), transparent 32%),
                    radial-gradient(ellipse at 85% 100%, rgba(94, 171, 126, .17), transparent 36%),
                    linear-gradient(125deg, #09271e 0%, #174c3c 55%, #286b50 100%);
            }

            .restaurant-menu .menu-glass {
                border: 1px solid rgba(255, 255, 255, .15);
                background: rgba(255, 255, 255, .07);
                backdrop-filter: blur(12px);
            }

            .restaurant-menu .menu-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 9px;
                border-radius: 14px;
                padding: 13px 18px;
                font-size: 13px;
                font-weight: 800;
                transition: transform .2s ease, background .2s ease, box-shadow .2s ease;
            }

            .restaurant-menu .menu-btn:active {
                transform: scale(.97);
            }

            .restaurant-menu .menu-category {
                position: relative;
                flex-shrink: 0;
                align-items: center;
                justify-content: center;
                gap: 9px;
                border: 1px solid #e5eae4;
                border-radius: 15px;
                background: #fff;
                padding: 12px 17px;
                color: #647168;
                font-size: 13px;
                font-weight: 800;
                transition: all .22s ease;
            }

            .restaurant-menu .menu-category:hover {
                border-color: #a9c5b2;
                color: var(--menu-green);
                transform: translateY(-1px);
            }

            .restaurant-menu .menu-category.active {
                border-color: var(--menu-green);
                background: var(--menu-green);
                color: #fff;
                box-shadow: 0 7px 18px rgba(23, 76, 60, .16);
            }

            .restaurant-menu .food-card {
                min-width: 0;
                border: 1px solid #e9eee8;
                border-radius: 22px;
                background: #fff;
                box-shadow: 0 3px 12px rgba(20, 55, 38, .025);
                transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
            }

            .restaurant-menu .food-card:hover {
                transform: translateY(-5px);
                border-color: #d5e3d8;
                box-shadow: 0 18px 38px rgba(20, 55, 38, .09);
            }

            .restaurant-menu .food-image-wrap {
                position: relative;
                overflow: hidden;
                background: linear-gradient(135deg, #eef2eb, #e4eae1);
            }

            .restaurant-menu .food-image {
                height: 100%;
                width: 100%;
                object-fit: cover;
                transition: transform .65s cubic-bezier(.2, .75, .25, 1);
            }

            .restaurant-menu .food-card:hover .food-image {
                transform: scale(1.07);
            }

            .restaurant-menu .food-card.hidden {
                display: none !important;
            }

            .restaurant-menu .menu-search {
                width: 100%;
                min-height: 54px;
                border: 1px solid transparent;
                border-radius: 15px;
                background: #f6f8f5;
                padding: 14px 48px 14px 45px;
                color: #1c392d;
                font-size: 14px;
                outline: none;
                transition: all .2s ease;
            }

            .restaurant-menu .menu-search:focus {
                border-color: rgba(23, 76, 60, .24);
                background: #fff;
                box-shadow: 0 0 0 4px rgba(23, 76, 60, .055);
            }

            .restaurant-menu .menu-search::placeholder {
                color: #9aa69d;
            }

            .restaurant-menu .menu-section-title {
                color: #183d2e;
                font-weight: 900;
                letter-spacing: -.025em;
            }

            .restaurant-menu .menu-feature {
                border: 1px solid #e5eae3;
                border-radius: 22px;
                background: #fff;
                box-shadow: 0 8px 30px rgba(20, 55, 38, .045);
            }

            .restaurant-menu .food-add {
                display: inline-flex;
                min-height: 42px;
                flex-shrink: 0;
                align-items: center;
                justify-content: center;
                gap: 7px;
                border-radius: 12px;
                background: var(--menu-green);
                padding: 10px 13px;
                color: #fff;
                font-size: 12px;
                font-weight: 800;
                transition: all .2s ease;
            }

            .restaurant-menu .food-add:hover {
                background: var(--menu-dark);
                box-shadow: 0 5px 14px rgba(23, 76, 60, .15);
            }

            .restaurant-menu .food-add:disabled {
                cursor: not-allowed;
                opacity: .6;
            }

            .restaurant-menu .menu-bottom-bar {
                padding-bottom: max(12px, env(safe-area-inset-bottom));
            }

            .restaurant-menu .menu-dot-pattern {
                background-image: radial-gradient(rgba(255, 255, 255, .15) 1px, transparent 1px);
                background-size: 17px 17px;
            }

            .restaurant-menu .menu-divider {
                height: 1px;
                background: linear-gradient(to left, #dfe8df, transparent);
            }

            #orders-modal {
                overscroll-behavior: contain;
            }

            #orders-modal .orders-dialog {
                max-height: calc(100dvh - 24px);
                animation: ordersModalIn .2s ease-out;
            }

            #orders-modal .confirm-order-button:disabled {
                cursor: not-allowed;
                opacity: .5;
                box-shadow: none;
            }

            @keyframes ordersModalIn {
                from {
                    opacity: 0;
                    transform: translateY(12px) scale(.98);
                }

                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            @media (max-width: 640px) {
                .restaurant-menu .menu-category {
                    padding: 11px 14px;
                }

                .restaurant-menu .food-card {
                    border-radius: 18px;
                }

                .restaurant-menu .food-add {
                    padding: 10px;
                }
            }

            @media (prefers-reduced-motion: reduce) {

                html,
                .restaurant-menu,
                .restaurant-menu #categories {
                    scroll-behavior: auto !important;
                }

                .restaurant-menu *,
                .restaurant-menu *::before,
                .restaurant-menu *::after {
                    animation-duration: .01ms !important;
                    transition-duration: .01ms !important;
                }
            }
        </style>

        {{-- Hero --}}
        <section class="menu-hero relative isolate overflow-hidden text-white">
            <div class="menu-dot-pattern pointer-events-none absolute inset-0 -z-10 opacity-30"></div>
            <div
                class="pointer-events-none absolute -left-24 -top-28 -z-10 h-80 w-80 rounded-full border-[42px] border-white/[0.035]">
            </div>
            <div
                class="pointer-events-none absolute -bottom-40 right-[25%] -z-10 h-96 w-96 rounded-full bg-emerald-200/[0.06] blur-3xl">
            </div>

            <div
                class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-12 sm:px-8 sm:py-16 lg:grid-cols-[1.1fr_.9fr] lg:gap-14 lg:py-20">
                <div class="relative z-10">
                    <div
                        class="mb-6 inline-flex items-center gap-2.5 rounded-full border border-white/15 bg-white/[0.07] px-4 py-2.5 text-xs font-bold text-emerald-100">
                        <span class="relative flex h-2.5 w-2.5">
                            <span
                                class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-300 opacity-50"></span>
                            <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-300"></span>
                        </span>
                        تجربة طعام تستحق التجربة
                    </div>

                    <h1 class="max-w-2xl text-4xl font-black leading-[1.35] tracking-tight sm:text-5xl lg:text-6xl">
                        لحظتك المفضلة
                        <span class="block text-[#e8c98c]">تبدأ من هنا.</span>
                    </h1>

                    <p class="mt-5 max-w-xl text-sm leading-8 text-white/70 sm:text-base">
                        من أول اختيار إلى آخر لقمة، اكتشف أطباقنا واختر ما يناسب ذوقك بكل سهولة.
                    </p>

                    <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                        <a href="#menu-items"
                            class="menu-btn bg-[#e8c98c] text-[#26372b] shadow-lg shadow-black/10 hover:-translate-y-0.5 hover:bg-[#f3dba9]">
                            اكتشف قائمة الطعام
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 12H5m7 7-7-7 7-7" />
                            </svg>
                        </a>

                        <a href="#categories"
                            class="menu-btn border border-white/15 bg-white/[0.06] text-white hover:bg-white/10">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            تصفح التصنيفات
                        </a>
                    </div>

                    <div class="mt-9 flex flex-wrap items-center gap-x-6 gap-y-3 border-t border-white/10 pt-6">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/[0.08] text-[#e8c98c]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 3v18M3 12h18" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-white">خيارات متنوعة</p>
                                <p class="mt-1 text-[10px] text-white/45">لجميع الأذواق</p>
                            </div>
                        </div>

                        <div class="h-8 w-px bg-white/10"></div>

                        <div class="flex items-center gap-2.5">
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/[0.08] text-[#e8c98c]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="9" />
                                    <path d="m9 12 2 2 4-4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-white">طلب سهل</p>
                                <p class="mt-1 text-[10px] text-white/45">بخطوات بسيطة</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Decorative Hero Card --}}
                <div class="relative mx-auto hidden w-full max-w-md lg:block">
                    <div class="absolute inset-6 rounded-[2.5rem] bg-emerald-200/10 blur-2xl"></div>

                    <div class="menu-glass relative rounded-[2rem] p-4 shadow-2xl">
                        <div class="overflow-hidden rounded-[1.5rem] bg-[#f8f6ef] p-5 text-[#174c3c]">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs font-bold text-[#7e8d80]">من قائمتنا إلى مائدتك</p>
                                    <h2 class="mt-1 text-2xl font-black">اختر ما تحب</h2>
                                </div>

                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#174c3c] text-[#e8c98c]">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 3v6a3 3 0 0 0 6 0V3M6 3v18M15 3v18m0-18c3 2 4 5 4 8h-4" />
                                    </svg>
                                </div>
                            </div>

                            <div class="my-5 space-y-3">
                                <div
                                    class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-3 shadow-sm">
                                    <div
                                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-[#e7eee5] text-[#174c3c]">
                                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M4 11h16l-2 9H6l-2-9Zm4 0a4 4 0 0 1 8 0M3 7h18" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-extrabold">أطباق لكل مزاج</p>
                                        <p class="mt-1 text-xs text-gray-500">اكتشف النكهات المفضلة لديك</p>
                                    </div>
                                    <svg class="mr-auto h-4 w-4 shrink-0 text-[#9aab9d]" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="m9 18 6-6-6-6" />
                                    </svg>
                                </div>

                                <div
                                    class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-3 shadow-sm">
                                    <div
                                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-[#f5ead4] text-[#95743a]">
                                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 3v18M3 12h18" />
                                            <circle cx="12" cy="12" r="8" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-extrabold">طلبك على ذوقك</p>
                                        <p class="mt-1 text-xs text-gray-500">أضف اختياراتك بكل سهولة</p>
                                    </div>
                                    <svg class="mr-auto h-4 w-4 shrink-0 text-[#9aab9d]" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="m9 18 6-6-6-6" />
                                    </svg>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 rounded-xl bg-[#e7eee5] p-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-[#174c3c]">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m5 12 4 4L19 6" />
                                    </svg>
                                </span>
                                <p class="text-xs font-extrabold">كل ما تحتاجه، في مكان واحد.</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="mt-5 hidden rounded-2xl border border-white/15 bg-[#e8c98c] px-4 py-3 text-[#26372b] shadow-xl xl:block">
                        <p class="text-[10px] font-bold">تجربة مميزة</p>
                        <p class="mt-1 text-sm font-black">اختيارك يصنع الفرق</p>
                    </div>
                </div>
            </div>
        </section>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-8 sm:py-10">

            {{-- Search and quick stats --}}
            <div class="menu-feature relative z-10 mb-9 p-3 sm:p-4">
                <div class="flex flex-col gap-4 md:flex-row md:items-center">
                    <div class="relative min-w-0 flex-1">
                        <svg class="pointer-events-none absolute right-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <circle cx="11" cy="11" r="7" stroke-width="1.8" />
                            <path d="m16 16 4 4" stroke-width="1.8" stroke-linecap="round" />
                        </svg>

                        <input id="menu-search" type="search" autocomplete="off" placeholder="ابحث عن وجبتك المفضلة..."
                            class="menu-search">

                        <button id="clear-search" type="button"
                            class="absolute left-3 top-1/2 hidden -translate-y-1/2 rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-700"
                            aria-label="مسح البحث">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m18 6-12 12M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-3 md:w-auto md:min-w-[240px]">
                        <div class="rounded-xl bg-[#f4f7f2] px-4 py-3">
                            <p class="text-[11px] font-bold text-gray-500">التصنيفات</p>
                            <p class="mt-1 text-lg font-black text-[#174c3c]">{{ $categories->count() }}</p>
                        </div>
                        <div class="rounded-xl bg-[#f4f7f2] px-4 py-3">
                            <p class="text-[11px] font-bold text-gray-500">الوجبات</p>
                            <p id="food-count-top" class="mt-1 text-lg font-black text-[#174c3c]">
                                {{ $categories->sum(fn($category) => $category->foods->count()) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Categories --}}
            <section id="categories-section" class="mb-10 scroll-mt-24">
                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <div class="mb-2 flex items-center gap-2">
                            <span class="h-1 w-7 rounded-full bg-[#d8b36c]"></span>
                            <p class="text-xs font-extrabold tracking-wide text-[#59816d]">اكتشف نكهاتنا</p>
                        </div>
                        <h2 class="menu-section-title text-xl sm:text-2xl">ماذا تشتهي اليوم؟</h2>
                        <p class="mt-2 text-sm leading-6 text-gray-500">اختر التصنيف المناسب واستكشف أشهى وجباتنا.</p>
                    </div>

                    <div
                        class="flex items-center gap-2 self-start rounded-xl border border-[#e5ebe3] bg-white px-3 py-2 sm:self-auto">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#eaf1e9] text-[#174c3c]">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </span>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400">عدد التصنيفات</p>
                            <p class="text-sm font-black text-[#174c3c]">{{ $categories->count() }} <span
                                    class="text-xs font-medium text-gray-500">تصنيف</span></p>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div
                        class="pointer-events-none absolute inset-y-0 left-0 z-10 w-5 bg-gradient-to-r from-[#f7f8f5] to-transparent">
                    </div>

                    <div class="flex items-center justify-between gap-4 py-2">
                        <div class="flex items-center gap-2">
                            <span class="h-1 w-8 rounded-full bg-[#45a099]"></span>
                            <span class="h-1 w-2 rounded-full bg-[#45a099]/30"></span>
                        </div>

                        <button type="button" onclick="scrollCategories()"
                            class="group ml-6 inline-flex shrink-0 items-center gap-2 rounded-full border border-[#174c3c]/10 bg-gradient-to-l from-[#174c3c]/[0.06] to-[#45a099]/[0.12] py-2 pl-3 pr-4 text-sm font-bold text-[#174c3c] shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-[#45a099]/40 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-[#45a099]/40">
                            <span>تابع للمزيد</span>
                            <span
                                class="flex h-7 w-7 items-center justify-center rounded-full bg-[#174c3c] text-white transition-colors duration-300 group-hover:bg-[#45a099]">
                                <svg class="h-4 w-4 rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14" />
                                    <path d="m12 5 7 7-7 7" />
                                </svg>
                            </span>
                        </button>
                    </div>

                    <div id="categories" class="flex snap-x snap-mandatory gap-3 overflow-x-auto px-1 py-3 pl-7"
                        style="scrollbar-width: thin; scrollbar-color: #cbd8ca transparent;" role="group"
                        aria-label="تصفية الوجبات حسب التصنيف">
                        <button type="button" data-category="all" aria-pressed="true"
                            class="menu-category active group min-w-[125px] snap-start flex-col !items-start !gap-3 !rounded-2xl !p-4">
                            <span class="flex w-full items-center justify-between gap-3">
                                <span
                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#e8f0e8] text-[#174c3c] transition group-[.active]:bg-white/15 group-[.active]:text-[#e8c98c]">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                </span>
                                <svg class="h-4 w-4 opacity-50 transition group-[.active]:opacity-100" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </span>
                            <span class="text-sm font-black">كل الأصناف</span>
                            <span
                                class="text-xs font-bold opacity-70">{{ $categories->sum(fn($category) => $category->foods->count()) }}
                                وجبة</span>
                        </button>

                        @foreach ($categories as $category)
                            <button type="button" data-category="{{ $category->id }}" aria-pressed="false"
                                class="menu-category group min-w-[125px] snap-start flex-col !items-start !gap-3 !rounded-2xl !p-4">
                                <span class="flex w-full items-center justify-between gap-3">
                                    <span
                                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#f3f5f0] text-[#59816d] transition group-[.active]:bg-white/15 group-[.active]:text-[#e8c98c]">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                            <path
                                                d="M3 10h18M5 10V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v4M5 10v10M19 10v10M3 15h18" />
                                        </svg>
                                    </span>
                                    <span class="w-full truncate text-right text-sm font-black"
                                        title="{{ $category->name }}">{{ $category->name }}</span>
                                </span>
                                <span class="text-xs font-bold opacity-70">{{ $category->foods->count() }} وجبة</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- Menu heading --}}
            <section id="menu-items" class="scroll-mt-24">
                <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <div class="mb-2 flex items-center gap-2">
                            <span class="h-1 w-7 rounded-full bg-[#d8b36c]"></span>
                            <p class="text-xs font-extrabold text-[#59816d]">اختيارات تستحق التجربة</p>
                        </div>
                        <h2 class="menu-section-title text-2xl sm:text-3xl">قائمة الطعام</h2>
                        <p class="mt-2 text-sm leading-6 text-gray-500">تصفح الأصناف، اختر وجباتك، واجمع طلبك في مكان واحد.
                        </p>
                    </div>

                    <span id="food-count"
                        class="inline-flex items-center gap-2 rounded-xl border border-[#dfe9df] bg-white px-4 py-2.5 text-sm font-extrabold text-[#174c3c]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 10h18M5 10V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v4M5 10v10M19 10v10M3 15h18" />
                        </svg>
                        {{ $categories->sum(fn($category) => $category->foods->count()) }} وجبة
                    </span>
                </div>

                @php
                    $foodItems = $categories->flatMap(function ($category) {
                        return $category->foods->map(function ($food) use ($category) {
                            $food->menu_category_id = $category->id;
                            $food->menu_category_name = $category->name;
                            return $food;
                        });
                    });
                @endphp

                <div id="food-grid" class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @forelse ($foodItems as $food)
                        <article class="food-card group" data-category="{{ $food->menu_category_id }}"
                            data-name="{{ mb_strtolower($food->name) }}">
                            <div class="food-image-wrap relative aspect-square overflow-hidden">
                                @if ($food->image)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($food->image) }}"
                                        alt="{{ $food->name }}" loading="lazy"
                                        class="block h-full w-full object-contain">
                                @else
                                    <div
                                        class="flex h-full w-full flex-col items-center justify-center gap-3 text-[#9bad9f]">
                                        <div
                                            class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/80 shadow-sm">
                                            <svg class="h-9 w-9" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor">
                                                <rect x="3" y="3" width="18" height="18" rx="3"
                                                    stroke-width="1.4" />
                                                <circle cx="8.5" cy="8.5" r="1.5" stroke-width="1.4" />
                                                <path d="m21 15-5-5L5 21" stroke-width="1.4" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <span class="text-xs font-bold">صورة الوجبة</span>
                                    </div>
                                @endif

                                <div
                                    class="pointer-events-none absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-black/25 to-transparent">
                                </div>
                                <span
                                    class="absolute right-3 top-3 max-w-[80%] truncate rounded-lg border border-white/70 bg-white/95 px-3 py-1.5 text-[11px] font-extrabold text-[#174c3c] shadow-sm backdrop-blur">{{ $food->menu_category_name }}</span>
                            </div>

                            <div class="p-4 sm:p-5">
                                <h3 class="truncate text-base font-black text-gray-900 sm:text-lg">{{ $food->name }}
                                </h3>
                                <p class="mt-2 min-h-[3rem] text-sm leading-6 text-gray-500">
                                    {{ $food->description ?: 'طبق شهي محضّر بعناية، اختره وأضفه إلى طلبك.' }}</p>
                                <div class="my-4 menu-divider"></div>

                                <div class="flex items-center justify-between gap-2">
                                    <div class="min-w-0">
                                        <p class="text-[11px] font-bold text-gray-400">السعر</p>
                                        <p class="mt-1 whitespace-nowrap text-lg font-black text-[#174c3c]">
                                            {{ rtrim(rtrim(number_format((float) $food->price, 2, '.', ','), '0'), '.') }}
                                            <span class="text-[11px] font-bold text-gray-500">ل.س</span>
                                        </p>
                                    </div>

                                    <button type="button" class="food-add add-to-order" data-id="{{ $food->id }}"
                                        data-name="{{ $food->name }}" data-price="{{ $food->price }}">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round">
                                            <path d="M12 5v14M5 12h14" />
                                        </svg>
                                        أضف للطلب
                                    </button>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div
                            class="col-span-full rounded-3xl border border-dashed border-gray-300 bg-white px-5 py-16 text-center">
                            <div
                                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-[#e8efe8] text-[#174c3c]">
                                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 7h16M4 12h16M4 17h16" />
                                </svg>
                            </div>
                            <h3 class="font-extrabold text-gray-800">قائمة الطعام فارغة حالياً</h3>
                            <p class="mt-2 text-sm leading-6 text-gray-500">ستظهر الوجبات هنا عند إضافتها وتفعيلها.</p>
                        </div>
                    @endforelse
                </div>

                <div id="no-results"
                    class="mt-5 hidden rounded-3xl border border-dashed border-gray-300 bg-white px-5 py-14 text-center">
                    <div
                        class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#f0f4ef] text-[#174c3c]">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m16 16 4 4" />
                        </svg>
                    </div>
                    <h3 class="font-extrabold text-gray-800">لم نعثر على نتائج</h3>
                    <p class="mt-2 text-sm text-gray-500">جرّب البحث باسم آخر أو اختر تصنيفاً مختلفاً.</p>
                    <button id="reset-filters" type="button"
                        class="mt-5 rounded-xl bg-[#174c3c] px-5 py-3 text-xs font-extrabold text-white transition hover:bg-[#10382c]">
                        عرض جميع الوجبات
                    </button>
                </div>
            </section>
        </main>

        {{-- Order Bar --}}
        <div id="order-bar"
            class="menu-bottom-bar fixed inset-x-0 bottom-0 z-40 border-t border-gray-200/80 bg-white/95 p-2 shadow-[0_-8px_35px_rgba(0,0,0,.08)] backdrop-blur-xl sm:p-4">

            <div class="mx-auto flex max-w-7xl flex-col gap-3 sm:flex-row sm:items-center sm:justify-between sm:gap-4">

                {{-- Order Summary --}}
                <div class="flex min-w-0 items-center justify-between gap-3 sm:justify-start">
                    <div class="flex min-w-0 items-center gap-3">
                        <div
                            class="relative flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#e8f0e8] text-[#174c3c] sm:h-11 sm:w-11">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 3h2l2.2 11.2a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 1.9-1.4L21 8H6" />
                                <circle cx="10" cy="20" r="1" />
                                <circle cx="18" cy="20" r="1" />
                            </svg>

                            <span id="order-badge"
                                class="absolute -right-1.5 -top-1.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-[#d8b36c] px-1 text-[10px] font-black text-[#26372b]">
                                0
                            </span>
                        </div>

                        <div class="min-w-0">
                            <p class="text-[11px] text-gray-500 sm:text-xs">طلبك الحالي</p>
                            <p class="mt-1 text-xs font-black text-gray-900 sm:text-sm">
                                <span id="order-count">0</span>
                                <span class="text-[10px] font-medium text-gray-500 sm:text-xs">وجبة</span>
                            </p>
                        </div>
                    </div>

                    {{-- Total --}}
                    <div class="shrink-0 text-left sm:hidden">
                        <p class="text-[10px] text-gray-500">الإجمالي</p>
                        <p class="mt-1 whitespace-nowrap text-sm font-black text-[#174c3c]">
                            <span id="order-total">0</span>
                            <span class="text-[10px] font-bold">ل.س</span>
                        </p>
                    </div>
                </div>

                {{-- Desktop Total --}}
                <div class="hidden shrink-0 text-left sm:block">
                    <p class="text-xs text-gray-500">الإجمالي</p>
                    <p class="mt-1 whitespace-nowrap text-base font-black text-[#174c3c]">
                        <span id="order-total-desktop">0</span>
                        <span class="text-xs font-bold">ل.س</span>
                    </p>
                </div>

                {{-- Action Buttons --}}
                <div class="grid w-full grid-cols-2 gap-2 sm:flex sm:w-auto sm:shrink-0 sm:gap-3">

                    <button id="submit-order" type="button"
                        class="menu-btn flex min-w-0 items-center justify-center gap-1.5 rounded-xl bg-[#174c3c] px-2 py-3 text-[11px] text-white shadow-md shadow-[#174c3c]/10 transition hover:bg-[#10382c] sm:gap-2 sm:px-5 sm:text-sm">
                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 3h2l2.2 11.2a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 1.9-1.4L21 8H6" />
                            <circle cx="10" cy="20" r="1" />
                            <circle cx="18" cy="20" r="1" />
                        </svg>

                        <span class="truncate">الطلبات الجديدة</span>

                        <span id="button-order-count"
                            class="flex h-5 min-w-5 shrink-0 items-center justify-center rounded-full bg-white/20 px-1 text-[10px]">
                            0
                        </span>
                    </button>

                    <button id="old-order" type="button"
                        class="menu-btn flex min-w-0 items-center justify-center gap-1.5 rounded-xl bg-[#174c3c] px-2 py-3 text-[11px] text-white shadow-md shadow-[#174c3c]/10 transition hover:bg-[#10382c] sm:gap-2 sm:px-5 sm:text-sm">
                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 3h2l2.2 11.2a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 1.9-1.4L21 8H6" />
                            <circle cx="10" cy="20" r="1" />
                            <circle cx="18" cy="20" r="1" />
                        </svg>

                        <span class="truncate">الطلبات السابقة</span>

                        <span
                            class="flex h-5 min-w-5 shrink-0 items-center justify-center rounded-full bg-white/20 px-1 text-[10px]">
                            {{ collect($data ?? [])->sum(fn($item) => (int) ($item['amount'] ?? ($item->amount ?? 0))) }}
                        </span>
                    </button>

                </div>
            </div>
        </div>


    </div>
    </div>
    </div>

    {{-- Orders Modal --}}
    <div id="orders-modal"
        class="fixed inset-0 z-[100] hidden items-center justify-center overflow-y-auto bg-black/55 p-3 backdrop-blur-sm sm:p-5"
        role="dialog" aria-modal="true" aria-labelledby="orders-modal-title" aria-hidden="true">
        <div class="orders-dialog my-auto flex w-full max-w-lg flex-col overflow-hidden rounded-3xl bg-white shadow-2xl">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between gap-4 border-b border-gray-100 bg-[#f7f8f5] p-4 sm:p-6">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#174c3c] text-[#e8c98c]">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 3h2l2.2 11.2a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 1.9-1.4L21 8H6" />
                            <circle cx="10" cy="20" r="1" />
                            <circle cx="18" cy="20" r="1" />
                        </svg>
                    </div>
                    <div>
                        <h2 id="orders-modal-title" class="text-lg font-black text-[#174c3c] sm:text-xl">قائمة طلباتي
                        </h2>
                        <p class="mt-1 text-xs text-gray-500 sm:text-sm">راجع وجباتك وعدّل الكميات قبل المتابعة</p>
                    </div>
                </div>

                <button id="close-orders-modal" type="button"
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-gray-500 transition hover:bg-red-50 hover:text-red-600"
                    aria-label="إغلاق نافذة الطلبات">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round">
                        <path d="m18 6-12 12M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Modal Items --}}
            <div id="orders-list" class="min-h-[180px] space-y-3 overflow-y-auto p-4 sm:max-h-[50dvh] sm:p-5">

                @if (!empty($data) && count($data) > 0)
                    <div class="mb-5 space-y-3">
                        <h3 class="border-b border-gray-200 pb-3 text-sm font-extrabold text-[#174c3c]">
                            الطلبات المضافة مسبقاً
                        </h3>

                        @foreach ($data as $item)
                            <div
                                class="flex items-center justify-between gap-3 rounded-2xl border border-gray-100 bg-gray-50 p-3 sm:p-4">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate font-bold text-gray-800">
                                        {{ $item['food_name'] }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        سعر الوجبة: {{ number_format($item['food_price'], 2) }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        الكمية: {{ $item['amount'] }}
                                    </p>
                                </div>

                                <div class="shrink-0 text-left">
                                    <p class="font-extrabold text-[#174c3c]">
                                        {{ number_format($item['total_price'], 2) }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        الإجمالي
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div id="orders-empty" class="flex flex-col items-center justify-center px-4 py-8 text-center">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[#e8f0e8] text-[#174c3c]">
                        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 3h2l2.2 11.2a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 1.9-1.4L21 8H6" />
                            <circle cx="10" cy="20" r="1" />
                            <circle cx="18" cy="20" r="1" />
                        </svg>
                    </div>

                    <p class="mt-4 font-extrabold text-gray-800">سلتك فارغة</p>
                    <p class="mt-1 text-sm text-gray-500">
                        أضف وجبات من القائمة لتظهر هنا.
                    </p>

                    <button id="browse-menu" type="button"
                        class="mt-4 rounded-xl bg-[#e8f0e8] px-4 py-2.5 text-xs font-extrabold text-[#174c3c] transition hover:bg-[#dce9dc]">
                        تصفح قائمة الطعام
                    </button>
                </div>
            </div>

            {{-- Basket Footer and Confirm Button --}}
            <div class="border-t border-gray-100 bg-[#f9faf7] p-4 sm:p-5">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <span class="text-sm font-bold text-gray-600">الإجمالي النهائي</span>
                    <span class="whitespace-nowrap text-lg font-black text-[#174c3c]">
                        <span id="modal-order-total">0</span>
                        <span class="text-xs">ل.س</span>
                    </span>
                </div>

                <button id="confirm-order-list" type="button" disabled
                    class="confirm-order-button flex w-full items-center justify-center gap-2 rounded-2xl bg-[#174c3c] px-5 py-4 text-sm font-black text-white shadow-lg shadow-[#174c3c]/15 transition hover:bg-[#10382c] focus:outline-none focus:ring-4 focus:ring-[#174c3c]/20">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="m5 12 4 4L19 6" />
                    </svg>
                    تأكيد الطلب
                </button>

                <p class="mt-3 text-center text-xs leading-5 text-gray-400">
                    عند التأكيد سيتم تفريغ السلة من الوجبات المحددة.
                </p>
            </div>
        </div>
    </div>
    </div>
    {{-- confirm --}}
    <div id="confirm-order-modal"
        class="fixed inset-0 z-[100] hidden items-center justify-center bg-gray-950/60 p-4 backdrop-blur-sm"
        role="dialog" aria-modal="true" aria-labelledby="confirm-order-title">

        <div id="confirm-order-card" class="w-full max-w-md overflow-hidden rounded-[28px] bg-white shadow-2xl">

            <div
                class="relative overflow-hidden bg-gradient-to-br from-[#174c3c] to-[#10382c] px-6 pb-8 pt-8 text-center text-white">
                <div class="absolute -right-10 -top-10 h-36 w-36 rounded-full bg-white/5"></div>
                <div class="absolute -bottom-12 -left-8 h-32 w-32 rounded-full bg-white/5"></div>

                <div
                    class="relative mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-3xl border border-white/20 bg-white/10 shadow-inner">
                    <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 3h2l2.4 12.2a2 2 0 0 0 2 1.6h8.8a2 2 0 0 0 2-1.6L22 7H6" />
                        <circle cx="10" cy="21" r="1" />
                        <circle cx="19" cy="21" r="1" />
                        <path d="m10 10 2 2 4-4" />
                    </svg>
                </div>

                <h2 id="confirm-order-title" class="relative text-2xl font-black">
                    جاهز لتأكيد طلبك؟
                </h2>

                <p class="relative mt-2 text-sm leading-6 text-white/75">
                    راجع تفاصيل طلبك قبل التأكيد النهائي.
                </p>
            </div>

            <div class="p-6">
                <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-sm font-medium text-gray-500">عدد الأصناف</span>
                        <span id="confirm-order-items-count"
                            class="rounded-xl bg-[#e8f0e8] px-3 py-1.5 text-sm font-black text-[#174c3c]">
                            0
                        </span>
                    </div>

                    <div class="mt-4 flex items-center justify-between gap-3">
                        <span class="text-sm font-medium text-gray-500">الكمية الإجمالية</span>
                        <span id="confirm-order-quantity" class="text-sm font-bold text-gray-800">0</span>
                    </div>

                    <div class="my-4 border-t border-dashed border-gray-200"></div>

                    <div class="flex items-center justify-between gap-3">
                        <span class="font-bold text-gray-700">الإجمالي النهائي</span>
                        <span class="text-xl font-black text-[#174c3c]">
                            <span id="confirm-order-total">0</span>
                            <span class="text-xs">ل.س</span>
                        </span>
                    </div>
                </div>

                <div class="mt-5 flex items-start gap-3 rounded-xl bg-amber-50 p-3 text-amber-800">
                    <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 8v4m0 4h.01" />
                    </svg>
                    <p class="text-xs leading-5">
                        تأكد من الأصناف والكميات قبل المتابعة.
                    </p>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <button id="cancel-confirm-order" type="button"
                        class="rounded-2xl border border-gray-200 bg-white px-4 py-3.5 text-sm font-bold text-gray-600 transition hover:bg-gray-50">
                        العودة للتعديل
                    </button>

                    <button id="approve-confirm-order"
                        class="flex items-center justify-center gap-2 rounded-2xl bg-[#174c3c] px-4 py-3.5 text-sm font-black text-white shadow-lg shadow-[#174c3c]/20 transition hover:bg-[#10382c] active:scale-[0.98]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="m5 12 4 4L19 6" />
                        </svg>
                        تأكيد الطلب
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('menu-search');
            const clearSearch = document.getElementById('clear-search');
            const categoryButtons = [...document.querySelectorAll('.menu-category')];
            const cards = [...document.querySelectorAll('.food-card')];
            const noResults = document.getElementById('no-results');
            const foodCount = document.getElementById('food-count');
            const foodCountTop = document.getElementById('food-count-top');

            const orderBar = document.getElementById('order-bar');
            const orderCount = document.getElementById('order-count');
            const orderBadge = document.getElementById('order-badge');
            const buttonOrderCount = document.getElementById('button-order-count');
            const orderTotal = document.getElementById('order-total');

            const ordersModal = document.getElementById('orders-modal');
            const ordersList = document.getElementById('orders-list');
            const ordersEmpty = document.getElementById('orders-empty');
            const modalOrderTotal = document.getElementById('modal-order-total');
            const confirmOrderList = document.getElementById('confirm-order-list');

            const confirmOrderModal = document.getElementById('confirm-order-modal');
            const confirmOrderItemsCount = document.getElementById('confirm-order-items-count');
            const confirmOrderQuantity = document.getElementById('confirm-order-quantity');
            const confirmOrderTotal = document.getElementById('confirm-order-total');
            const approveConfirmOrder = document.getElementById('approve-confirm-order');
            const cancelConfirmOrder = document.getElementById('cancel-confirm-order');








            const order = new Map();

            let selectedCategory = 'all';
            let previousFocusedElement = null;
            let isConfirming = false;

            const normalize = value => String(value ?? '')
                .toLocaleLowerCase('ar')
                .replace(/[أإآ]/g, 'ا')
                .replace(/ى/g, 'ي')
                .replace(/ة/g, 'ه')
                .replace(/ؤ/g, 'و')
                .replace(/ئ/g, 'ي')
                .trim();

            function formatPrice(value) {
                return Number(value).toLocaleString('en-US', {
                    maximumFractionDigits: 2
                });
            }

            function filterFoods() {
                const query = normalize(searchInput?.value);
                let visibleCount = 0;

                cards.forEach(card => {
                    const matchesCategory =
                        selectedCategory === 'all' ||
                        card.dataset.category === selectedCategory;

                    const matchesSearch = normalize(card.dataset.name).includes(query);
                    const visible = matchesCategory && matchesSearch;

                    card.classList.toggle('hidden', !visible);

                    if (visible) visibleCount++;
                });

                if (foodCount) foodCount.textContent = `${visibleCount} وجبة`;
                if (foodCountTop) foodCountTop.textContent = visibleCount;
                if (noResults) noResults.classList.toggle('hidden', visibleCount !== 0);

                if (clearSearch) {
                    clearSearch.classList.toggle('hidden', !searchInput?.value.length);
                }
            }

            function activateCategory(button) {
                selectedCategory = button.dataset.category;

                categoryButtons.forEach(item => {
                    const active = item === button;

                    item.classList.toggle('active', active);
                    item.setAttribute('aria-pressed', active ? 'true' : 'false');
                });

                filterFoods();
            }

            categoryButtons.forEach(button => {
                button.addEventListener('click', () => activateCategory(button));
            });

            searchInput?.addEventListener('input', filterFoods);

            clearSearch?.addEventListener('click', () => {
                if (!searchInput) return;

                searchInput.value = '';
                filterFoods();
                searchInput.focus();
            });

            document.getElementById('reset-filters')?.addEventListener('click', () => {
                if (searchInput) searchInput.value = '';

                const allButton = categoryButtons.find(
                    button => button.dataset.category === 'all'
                );

                if (allButton) {
                    activateCategory(allButton);
                } else {
                    filterFoods();
                }
            });

            function updateConfirmButton() {
                if (!confirmOrderList) return;

                confirmOrderList.disabled = order.size === 0 || isConfirming;
            }

            function renderOrders() {
                if (!ordersList || !ordersEmpty) return;

                ordersList.querySelectorAll('.order-item').forEach(item => item.remove());

                ordersEmpty.classList.toggle('hidden', order.size > 0);

                order.forEach(item => {
                    const row = document.createElement('div');

                    row.className =
                        'order-item flex items-start gap-2 rounded-2xl border border-gray-100 bg-white p-3 sm:gap-3 sm:p-4';

                    const details = document.createElement('div');
                    details.className = 'min-w-0 flex-1';

                    const name = document.createElement('p');
                    name.className = 'break-words text-sm font-extrabold text-gray-800';
                    name.textContent = item.name;

                    const priceDetails = document.createElement('div');
                    priceDetails.className = 'mt-3 grid grid-cols-2 gap-2 sm:grid-cols-3';

                    function createPriceBox(
                        label,
                        value,
                        classes = 'rounded-xl bg-gray-50 p-2'
                    ) {
                        const box = document.createElement('div');
                        box.className = classes;

                        const labelElement = document.createElement('p');
                        labelElement.className = 'text-[10px] font-medium text-gray-500';
                        labelElement.textContent = label;

                        const valueElement = document.createElement('p');
                        valueElement.className =
                            'mt-1 break-words text-xs font-bold text-gray-800';
                        valueElement.textContent = value;

                        box.append(labelElement, valueElement);

                        return box;
                    }

                    priceDetails.append(
                        createPriceBox('سعر القطعة', `${formatPrice(item.price)} ل.س`),
                        createPriceBox('الكمية', item.quantity),
                        createPriceBox(
                            'إجمالي الصنف',
                            `${formatPrice(item.price * item.quantity)} ل.س`,
                            'col-span-2 rounded-xl bg-[#e8f0e8] p-2 sm:col-span-1'
                        )
                    );

                    details.append(name, priceDetails);

                    const actions = document.createElement('div');
                    actions.className = 'flex shrink-0 flex-col items-center gap-2';

                    const quantityControls = document.createElement('div');
                    quantityControls.className = 'flex items-center gap-1.5 sm:gap-2';

                    const decrease = document.createElement('button');
                    decrease.type = 'button';
                    decrease.className =
                        'flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-base font-black text-gray-700 transition hover:bg-gray-200';
                    decrease.textContent = '−';
                    decrease.setAttribute('aria-label', `تقليل كمية ${item.name}`);

                    decrease.addEventListener('click', () => {
                        if (item.quantity > 1) {
                            item.quantity--;
                        } else {
                            order.delete(item.id);
                        }

                        updateOrderSummary();
                    });

                    const quantity = document.createElement('span');
                    quantity.className = 'min-w-5 text-center text-sm font-black text-gray-800';
                    quantity.textContent = item.quantity;

                    const increase = document.createElement('button');
                    increase.type = 'button';
                    increase.className =
                        'flex h-8 w-8 items-center justify-center rounded-lg bg-[#e8f0e8] text-base font-black text-[#174c3c] transition hover:bg-[#d9e8d9]';
                    increase.textContent = '+';
                    increase.setAttribute('aria-label', `زيادة كمية ${item.name}`);

                    increase.addEventListener('click', () => {
                        item.quantity++;
                        updateOrderSummary();
                    });

                    quantityControls.append(decrease, quantity, increase);

                    const remove = document.createElement('button');
                    remove.type = 'button';
                    remove.className =
                        'flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-red-500 transition hover:bg-red-50';
                    remove.setAttribute('aria-label', `حذف ${item.name} من الطلب`);

                    remove.innerHTML = `
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6h18M8 6V4h8v2m3 0-1 14H6L5 6m4 4v6m6-6v6"/>
                    </svg>
                `;

                    remove.addEventListener('click', () => {
                        order.delete(item.id);
                        updateOrderSummary();
                    });

                    actions.append(quantityControls, remove);
                    row.append(details, actions);
                    ordersList.appendChild(row);
                });
            }

            function updateOrderSummary() {
                let count = 0;
                let total = 0;

                order.forEach(item => {
                    count += item.quantity;
                    total += item.price * item.quantity;
                });

                if (orderCount) orderCount.textContent = count;
                if (orderBadge) orderBadge.textContent = count;
                if (buttonOrderCount) buttonOrderCount.textContent = count;
                if (orderTotal) orderTotal.textContent = formatPrice(total);
                if (modalOrderTotal) modalOrderTotal.textContent = formatPrice(total);

                // if (orderBar) {
                //     orderBar.classList.toggle('hidden', count === 0);
                // }

                updateConfirmButton();
                renderOrders();
            }

            function openOrdersModal() {
                if (!ordersModal) return;

                previousFocusedElement = document.activeElement;

                renderOrders();

                ordersModal.classList.remove('hidden');
                ordersModal.classList.add('flex');
                ordersModal.setAttribute('aria-hidden', 'false');

                document.body.classList.add('overflow-hidden');

                document.getElementById('close-orders-modal')?.focus();
            }

            function closeOrdersModal() {
                if (!ordersModal) return;

                ordersModal.classList.add('hidden');
                ordersModal.classList.remove('flex');
                ordersModal.setAttribute('aria-hidden', 'true');

                document.body.classList.remove('overflow-hidden');

                if (
                    previousFocusedElement &&
                    typeof previousFocusedElement.focus === 'function'
                ) {
                    previousFocusedElement.focus();
                }
            }

            document.querySelectorAll('.add-to-order').forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.dataset.id;
                    const name = button.dataset.name;
                    const price = Number(button.dataset.price);

                    if (!id || !name || !Number.isFinite(price) || price < 0) return;

                    if (order.has(id)) {
                        order.get(id).quantity++;
                    } else {
                        order.set(id, {
                            id,
                            name,
                            price,
                            quantity: 1
                        });
                    }

                    updateOrderSummary();

                    const originalHtml = button.innerHTML;

                    button.innerHTML = `
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="m5 12 4 4L19 6"/>
                    </svg>
                    تمت الإضافة
                `;

                    button.classList.add('!bg-emerald-700');
                    button.setAttribute('aria-label', `تمت إضافة ${name}`);

                    window.setTimeout(() => {
                        button.innerHTML = originalHtml;
                        button.classList.remove('!bg-emerald-700');
                        button.setAttribute('aria-label',
                            `إضافة ${name} إلى الطلب`);
                    }, 1000);
                });
            });

            document.getElementById('submit-order')
                ?.addEventListener('click', openOrdersModal);

            document.getElementById('close-orders-modal')
                ?.addEventListener('click', closeOrdersModal);

            document.getElementById('browse-menu')?.addEventListener('click', () => {
                closeOrdersModal();

                document.getElementById('menu-items')?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });

            ordersModal?.addEventListener('click', event => {
                if (event.target === ordersModal) {
                    closeOrdersModal();
                }
            });

            function openConfirmOrderModal() {
                if (order.size === 0 || isConfirming || !confirmOrderModal) return;

                let quantity = 0;
                let total = 0;

                order.forEach(item => {
                    quantity += item.quantity;
                    total += item.price * item.quantity;
                });

                if (confirmOrderItemsCount) {
                    confirmOrderItemsCount.textContent = order.size;
                }

                if (confirmOrderQuantity) {
                    confirmOrderQuantity.textContent = quantity;
                }

                if (confirmOrderTotal) {
                    confirmOrderTotal.textContent = formatPrice(total);
                }

                confirmOrderModal.classList.remove('hidden');
                confirmOrderModal.classList.add('flex');
                confirmOrderModal.setAttribute('aria-hidden', 'false');

                cancelConfirmOrder?.focus();
            }

            function closeConfirmOrderModal() {
                if (!confirmOrderModal) return;

                confirmOrderModal.classList.add('hidden');
                confirmOrderModal.classList.remove('flex');
                confirmOrderModal.setAttribute('aria-hidden', 'true');

                confirmOrderList?.focus();
            }

            confirmOrderList?.addEventListener('click', openConfirmOrderModal);

            cancelConfirmOrder?.addEventListener('click', closeConfirmOrderModal);

            confirmOrderModal?.addEventListener('click', event => {
                if (event.target === confirmOrderModal) {
                    closeConfirmOrderModal();
                }
            });

            document.addEventListener('keydown', event => {
                if (event.key !== 'Escape') return;

                if (confirmOrderModal?.classList.contains('flex')) {
                    closeConfirmOrderModal();
                    return;
                }

                if (ordersModal && !ordersModal.classList.contains('hidden')) {
                    closeOrdersModal();
                }
            });

            const billNumber = @json($billNumber);

            approveConfirmOrder?.addEventListener('click', async () => {
                if (isConfirming || order.size === 0) return;

                const items = [...order.values()].map(item => ({
                    id: item.id,
                    quantity: item.quantity
                }));

                isConfirming = true;
                approveConfirmOrder.disabled = true;
                approveConfirmOrder.textContent = 'جارٍ إرسال الطلب...';

                try {
                    const response = await fetch(@json(route('bill.new.orders')), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]')
                                ?.content ?? ''
                        },
                        body: JSON.stringify({
                            billNumber: billNumber,
                            items: items
                        })
                    });

                    const contentType = response.headers.get('content-type') || '';
                    const result = contentType.includes('application/json') ?
                        await response.json() : {
                            message: await response.text()
                        };

                    if (!response.ok) {
                        if (response.status === 422 && result.errors) {
                            throw new Error(
                                Object.values(result.errors).flat().join('\n')
                            );
                        }

                        if (response.status === 419) {
                            throw new Error(
                                'انتهت صلاحية الجلسة. حدّث الصفحة وحاول مجدداً.');
                        }

                        throw new Error(result.message || 'فشل في إرسال الطلب.');
                    }

                    updateOrderSummary();
                    closeConfirmOrderModal();
                    closeOrdersModal();
                    window.location.reload();
                    // window.alert(result.message || 'تم إرسال الطلب بنجاح.');

                } catch (error) {
                    console.error('Failed to send order:', error);
                    window.alert(error.message || 'حدث خطأ أثناء إرسال الطلب.');
                } finally {
                    isConfirming = false;

                    approveConfirmOrder.disabled = order.size === 0;
                    approveConfirmOrder.innerHTML = `
            <svg class="h-5 w-5" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="m5 12 4 4L19 6"/>
            </svg>
            تأكيد الطلب
        `;

                    updateOrderSummary();
                }
            });
            filterFoods();
            updateOrderSummary();
        });

        function scrollCategories() {
            const categories = document.getElementById('categories');
            if (!categories) return;

            const direction =
                getComputedStyle(categories).direction === 'rtl' ? -1 : 1;

            categories.scrollBy({
                left: direction * Math.max(180, categories.clientWidth * 0.7),
                behavior: 'smooth'
            });
        }
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const button = document.getElementById('old-order');
            const oldOrders = @json($data ?? []);
            let countdownIntervals = [];

            if (!button) return;

            button.addEventListener('click', function() {
                let modal = document.getElementById('old-orders-modal');

                if (!modal) {
                    modal = document.createElement('div');
                    modal.id = 'old-orders-modal';
                    modal.className =
                        'fixed inset-0 z-[9999] hidden items-center justify-center bg-black/50 p-3 sm:p-5';
                    modal.setAttribute('dir', 'rtl');

                    modal.innerHTML = `
                    <div class="w-full max-w-4xl overflow-hidden rounded-2xl bg-white shadow-2xl">
                        <div class="flex items-center justify-between border-b border-gray-100 p-4 sm:p-5">
                            <div>
                                <h2 class="text-lg font-bold text-[#174c3c] sm:text-xl">
                                    الطلبات السابقة
                                </h2>
                                <p class="mt-1 text-sm text-gray-500">
                                    عدد الأصناف: ${Array.isArray(oldOrders) ? oldOrders.length : 0}
                                </p>
                            </div>

                            <button type="button" id="close-old-orders"
                                class="flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100 text-2xl text-gray-600 hover:bg-gray-200"
                                aria-label="إغلاق">&times;</button>
                        </div>

                        <div id="old-orders-content"
                            class="max-h-[70vh] space-y-3 overflow-y-auto p-4 sm:p-5"></div>

                        <div class="flex justify-end border-t border-gray-100 p-4">
                            <button type="button" id="close-old-orders-footer"
                                class="rounded-xl bg-[#174c3c] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#10382c]">
                                إغلاق
                            </button>
                        </div>
                    </div>
                `;

                    document.body.appendChild(modal);
                }

                const content = modal.querySelector('#old-orders-content');

                function closeModal() {
                    countdownIntervals.forEach(function(interval) {
                        clearInterval(interval);
                    });

                    countdownIntervals = [];

                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.body.classList.remove('overflow-hidden');
                }

                modal.querySelector('#close-old-orders').onclick = closeModal;
                modal.querySelector('#close-old-orders-footer').onclick = closeModal;

                modal.onclick = function(event) {
                    if (event.target === modal) {
                        closeModal();
                    }
                };

                if (!modal.dataset.escapeBound) {
                    document.addEventListener('keydown', function(event) {
                        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                            closeModal();
                        }
                    });

                    modal.dataset.escapeBound = 'true';
                }

                countdownIntervals.forEach(function(interval) {
                    clearInterval(interval);
                });

                countdownIntervals = [];
                content.innerHTML = '';

                if (!Array.isArray(oldOrders) || oldOrders.length === 0) {
                    content.innerHTML = `
                    <div class="py-10 text-center font-semibold text-gray-600">
                        لا توجد طلبات سابقة.
                    </div>
                `;
                } else {
                    oldOrders.forEach(function(item, index) {
                        const name = item.food_name ?? item.name ?? 'صنف غير معروف';
                        const status = item.status ?? 'غير معروف';
                        const price = Number(item.food_price ?? item.price ?? 0);
                        const quantity = Number(item.amount ?? item.quantity ?? 1);
                        const total = Number(item.total_price ?? price * quantity);
                        const createdAt = item.created_at;
                        const expectedTime = item.expected_time;

                        const normalizedStatus = String(status).trim().toLowerCase();

                        // Hide the countdown only for orders on the table.
                        const hideTimer = normalizedStatus === 'على الطاولة';

                        const statusColors = {
                            pending: 'bg-amber-100 text-amber-800',
                            new: 'bg-blue-100 text-blue-800',
                            processing: 'bg-indigo-100 text-indigo-800',
                            preparing: 'bg-indigo-100 text-indigo-800',
                            in_kitchen: 'bg-orange-100 text-orange-800',
                            completed: 'bg-green-100 text-green-800',
                            done: 'bg-green-100 text-green-800',
                            cancelled: 'bg-red-100 text-red-800',
                            canceled: 'bg-red-100 text-red-800',
                            paid: 'bg-emerald-100 text-emerald-800',
                            'على الطاولة': 'bg-emerald-100 text-emerald-800'
                        };

                        const row = document.createElement('div');
                        row.className =
                            'flex flex-col gap-3 rounded-xl border border-gray-100 bg-gray-50 p-4 sm:flex-row sm:items-center sm:justify-between';

                        const details = document.createElement('div');
                        details.className = 'min-w-0 flex-1';

                        const title = document.createElement('h3');
                        title.className = 'break-words font-bold text-gray-800';
                        title.textContent = String(name);

                        const meta = document.createElement('p');
                        meta.className = 'mt-1 text-sm text-gray-500';
                        meta.textContent =
                            `السعر: ${price.toLocaleString('ar')} × الكمية: ${quantity.toLocaleString('ar')}`;

                        const badge = document.createElement('span');
                        badge.className =
                            `mt-2 inline-flex items-center rounded-full px-3 py-1 text-xs font-bold ${
                            statusColors[normalizedStatus] ?? 'bg-gray-100 text-gray-700'
                        }`;
                        badge.textContent = String(status);

                        details.append(title, meta, badge);

                        // Create and display the countdown only when appropriate.
                        let timer = null;

                        if (!hideTimer) {
                            timer = document.createElement('p');
                            timer.id = `old-order-timer-${index}`;
                            timer.className =
                                'mt-3 rounded-lg bg-amber-50 p-3 text-sm font-bold text-amber-800';

                            details.appendChild(timer);
                        }

                        if (createdAt) {
                            const createdElement = document.createElement('p');
                            createdElement.className = 'mt-2 text-xs text-gray-400';
                            createdElement.textContent = `وقت إنشاء الطلب: ${createdAt}`;
                            details.appendChild(createdElement);
                        }

                        const totalElement = document.createElement('div');
                        totalElement.className = 'shrink-0 font-bold text-[#174c3c]';
                        totalElement.textContent =
                            `الإجمالي: ${total.toLocaleString('ar')} ل.س`;

                        row.append(details, totalElement);
                        content.appendChild(row);

                        // Parse Laravel date formats, including AM/PM.
                        function parseDate(value) {
                            if (!value) return null;

                            const cleanValue = String(value).trim();

                            // Handle YYYY-MM-DD HH:mm:ss AM/PM explicitly.
                            const match = cleanValue.match(
                                /^(\d{4})-(\d{2})-(\d{2})[ T]+(\d{1,2}):(\d{2}):(\d{2})\s*(AM|PM)$/i
                            );

                            if (match) {
                                const [, y, mo, d, h, mi, s, period] = match;
                                let hours = Number(h);

                                if (hours < 1 || hours > 12) return null;

                                if (period.toUpperCase() === 'PM' && hours !== 12) {
                                    hours += 12;
                                }

                                if (period.toUpperCase() === 'AM' && hours === 12) {
                                    hours = 0;
                                }

                                return new Date(
                                    Number(y),
                                    Number(mo) - 1,
                                    Number(d),
                                    hours,
                                    Number(mi),
                                    Number(s)
                                );
                            }

                            // Handle standard ISO/SQL date formats.
                            const fallback = new Date(cleanValue.replace(/-/g, '/'));

                            if (!Number.isNaN(fallback.getTime())) {
                                return fallback;
                            }

                            return null;
                        }

                        // Parse numeric minutes or HH:MM:SS duration.
                        function parseDuration(timeValue) {
                            if (
                                timeValue === null ||
                                timeValue === undefined ||
                                String(timeValue).trim() === ''
                            ) {
                                return null;
                            }

                            const str = String(timeValue).trim();

                            if (str.includes(':')) {
                                const parts = str.split(':');

                                if (parts.length === 3) {
                                    const hours = Number(parts[0]);
                                    const minutes = Number(parts[1]);
                                    const seconds = Number(parts[2]);

                                    if (
                                        !Number.isFinite(hours) ||
                                        !Number.isFinite(minutes) ||
                                        !Number.isFinite(seconds)
                                    ) {
                                        return null;
                                    }

                                    return hours * 60 + minutes + seconds / 60;
                                }

                                return null;
                            }

                            const num = Number(str);

                            return Number.isFinite(num) ? num : null;
                        }

                        // Do not calculate or display a countdown for "على الطاولة".
                        if (hideTimer) {
                            return;
                        }

                        const createdDate = parseDate(createdAt);
                        const duration = parseDuration(expectedTime);

                        if (
                            !createdDate ||
                            duration === null ||
                            !Number.isFinite(duration) ||
                            duration < 0
                        ) {
                            timer.textContent =
                                'الوقت المتبقي غير متوفر: تحقق من created_at و expected_time';
                            return;
                        }

                        const deadline = createdDate.getTime() + duration * 60 * 1000;

                        function updateTimer() {
                            const remaining = Math.ceil((deadline - Date.now()) / 1000);

                            if (remaining <= 0) {
                                timer.textContent = 'انتهى الوقت المحدد للتحضير';
                                timer.className =
                                    'mt-3 rounded-lg bg-red-100 p-3 text-sm font-bold text-red-700';

                                return false;
                            }

                            const hours = Math.floor(remaining / 3600);
                            const minutes = Math.floor((remaining % 3600) / 60);
                            const seconds = remaining % 60;

                            timer.textContent =
                                `⏳ الوقت المتبقي: ${
                                hours > 0 ? hours + ' ساعة و ' : ''
                            }${minutes} دقيقة و ${seconds} ثانية`;

                            timer.className =
                                'mt-3 rounded-lg bg-green-100 p-3 text-sm font-bold text-green-800';

                            return true;
                        }

                        if (updateTimer()) {
                            const interval = setInterval(function() {
                                if (
                                    !modal.isConnected ||
                                    modal.classList.contains('hidden') ||
                                    !updateTimer()
                                ) {
                                    clearInterval(interval);

                                    countdownIntervals = countdownIntervals.filter(
                                        function(current) {
                                            return current !== interval;
                                        }
                                    );
                                }
                            }, 1000);

                            countdownIntervals.push(interval);
                        }
                    });
                }

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.classList.add('overflow-hidden');
            });
        });
    </script>



@endsection
