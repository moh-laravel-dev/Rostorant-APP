@extends('admin.layouts.main-layout')

@section('content')
    <div class="mx-auto w-full max-w-5xl space-y-6 p-3 sm:p-5 lg:p-7" dir="rtl">

        {{-- Header --}}
        <div class="relative overflow-hidden rounded-3xl bg-[#174c3c] p-6 text-white shadow-lg sm:p-8">
            <div class="pointer-events-none absolute -left-12 -top-16 h-48 w-48 rounded-full bg-white/5"></div>
            <div class="pointer-events-none absolute -bottom-20 right-1/3 h-56 w-56 rounded-full bg-white/5"></div>

            <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div
                        class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-semibold text-white/90">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        إدارة قائمة الطعام
                    </div>

                    <h1 class="text-2xl font-extrabold sm:text-3xl">تفاصيل التصنيف</h1>
                    <p class="mt-2 max-w-lg text-sm leading-7 text-white/75">
                        جميع المعلومات والإحصائيات الخاصة بتصنيف
                        <span class="font-bold text-white">{{ $category->name }}</span>
                    </p>
                </div>

                <a href="{{ route('admin.category-food.index') }}"
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm font-bold text-white transition hover:bg-white/20">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                    جميع التصنيفات
                </a>
            </div>
        </div>

        {{-- Statistics --}}
        @php
            $activeFoodsCount = $category->foods()->where('is_active', true)->count();
            $inactiveFoodsCount = $category->foods()->where('is_active', false)->count();
            $totalFoodsCount = $activeFoodsCount + $inactiveFoodsCount;

            $activePercentage = $totalFoodsCount > 0 ? round(($activeFoodsCount / $totalFoodsCount) * 100) : 0;
        @endphp

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

            {{-- Total --}}
            <div
                class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md">
                <div class="absolute -left-5 -top-5 h-24 w-24 rounded-full bg-gray-100 transition group-hover:scale-125">
                </div>

                <div class="relative flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-gray-500">إجمالي الوجبات</p>
                        <p class="mt-3 text-4xl font-extrabold tracking-tight text-gray-800">
                            {{ $totalFoodsCount }}
                        </p>
                        <p class="mt-2 text-xs text-gray-400">عدد الوجبات في هذا التصنيف</p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gray-100 text-gray-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M3 2v7a4 4 0 004 4h1v9m-1-9V2M7 2v5m14-5v8a4 4 0 01-4 4h-1v8m1-20v12" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Active --}}
            <div
                class="group relative overflow-hidden rounded-2xl border border-green-100 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md">
                <div class="absolute -left-5 -top-5 h-24 w-24 rounded-full bg-green-50 transition group-hover:scale-125">
                </div>

                <div class="relative flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-gray-500">الوجبات النشطة</p>
                        <p class="mt-3 text-4xl font-extrabold tracking-tight text-green-700">
                            {{ $activeFoodsCount }}
                        </p>
                        <p class="mt-2 text-xs text-green-700/70">متاحة للطلب حالياً</p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-green-50 text-green-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Inactive --}}
            <div
                class="group relative overflow-hidden rounded-2xl border border-red-100 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md">
                <div class="absolute -left-5 -top-5 h-24 w-24 rounded-full bg-red-50 transition group-hover:scale-125">
                </div>

                <div class="relative flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-gray-500">الوجبات غير النشطة</p>
                        <p class="mt-3 text-4xl font-extrabold tracking-tight text-red-600">
                            {{ $inactiveFoodsCount }}
                        </p>
                        <p class="mt-2 text-xs text-red-600/70">غير متاحة للطلب</p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50 text-red-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-5">

            {{-- Category Information --}}
            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm lg:col-span-3">

                <div class="flex items-center gap-3 border-b border-gray-100 px-5 py-4 sm:px-6">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#174c3c]/10 text-[#174c3c]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M4 4h16v16H4zM4 9h16M9 4v5" />
                        </svg>
                    </div>

                    <div>
                        <h2 class="font-extrabold text-gray-800">معلومات التصنيف</h2>
                        <p class="mt-1 text-xs text-gray-400">التفاصيل الأساسية للتصنيف</p>
                    </div>
                </div>

                <div class="space-y-6 p-5 sm:p-6">

                    {{-- Photo --}}
                    <div class="relative">
                        <div
                            class="relative mx-auto aspect-[16/9] w-full max-w-lg overflow-hidden rounded-2xl bg-gray-50 sm:mx-0">
                            @if ($category->photo)
                                <img src="{{ asset('storage/' . $category->photo) }}" alt="{{ $category->name }}"
                                    class="h-full w-full object-cover transition duration-500 hover:scale-105">
                            @else
                                <div class="flex h-full w-full flex-col items-center justify-center gap-3 text-gray-400">
                                    <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 4h16v16H4zM8 14l3-3 5 5m-5-2l2-2 3 3M9 8h.01" />
                                    </svg>
                                    <span class="text-sm">لا توجد صورة لهذا التصنيف</span>
                                </div>
                            @endif

                            <div
                                class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/75 via-black/25 to-transparent p-4 pt-12 sm:p-5 sm:pt-16">
                                <p class="text-lg font-extrabold text-white sm:text-xl">
                                    {{ $category->name }}
                                </p>
                                <p class="mt-1 text-xs text-white/75">تصنيف قائمة الطعام</p>
                            </div>
                        </div>
                    </div>

                    {{-- Name --}}
                    <div class="rounded-2xl border border-gray-100 bg-gray-50/70 p-4">
                        <p class="text-xs font-bold text-gray-400">اسم التصنيف</p>
                        <p class="mt-2 break-words text-base font-extrabold text-gray-800">
                            {{ $category->name }}
                        </p>
                    </div>

                    {{-- Description --}}
                    <div>
                        <div class="mb-2 flex items-center gap-2">
                            <span class="h-5 w-1 rounded-full bg-[#174c3c]"></span>
                            <h3 class="text-sm font-bold text-gray-700">وصف التصنيف</h3>
                        </div>

                        <div class="rounded-2xl border border-gray-100 bg-white p-4">
                            <p class="whitespace-pre-line break-words text-sm leading-7 text-gray-600">
                                {{ $category->description ?: 'لا يوجد وصف مضاف لهذا التصنيف حتى الآن.' }}
                            </p>
                        </div>
                    </div>

                    {{-- Category Status --}}
                    <div
                        class="flex flex-col gap-3 rounded-2xl border border-gray-100 p-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-bold text-gray-800">حالة التصنيف</p>
                            <p class="mt-1 text-xs leading-5 text-gray-500">تحدد ما إذا كان التصنيف مفعلاً في النظام.</p>
                        </div>

                        @if ($category->is_active)
                            <span
                                class="inline-flex w-fit items-center gap-2 rounded-full border border-green-100 bg-green-50 px-4 py-2 text-xs font-extrabold text-green-700">
                                <span class="h-2 w-2 animate-pulse rounded-full bg-green-500"></span>
                                تصنيف فعال
                            </span>
                        @else
                            <span
                                class="inline-flex w-fit items-center gap-2 rounded-full border border-red-100 bg-red-50 px-4 py-2 text-xs font-extrabold text-red-700">
                                <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                تصنيف غير فعال
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Food Overview --}}
            <div class="space-y-5 lg:col-span-2">

                {{-- Availability Overview --}}
                <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="mb-5 flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M12 3v18m9-9H3" />
                            </svg>
                        </div>

                        <div>
                            <h2 class="font-extrabold text-gray-800">توفر الوجبات</h2>
                            <p class="mt-1 text-xs text-gray-400">نظرة عامة على حالة الوجبات</p>
                        </div>
                    </div>

                    <div class="mb-3 flex items-center justify-between gap-3">
                        <span class="text-sm font-semibold text-gray-600">نسبة الوجبات النشطة</span>
                        <span class="text-sm font-extrabold text-[#174c3c]">{{ $activePercentage }}%</span>
                    </div>

                    <div class="h-3 overflow-hidden rounded-full bg-gray-100">
                        <div class="h-full rounded-full bg-gradient-to-l from-[#174c3c] to-emerald-500 transition-all duration-700"
                            style="width: {{ $activePercentage }}%">
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-3">
                        <div class="rounded-2xl bg-green-50 p-3">
                            <p class="text-xs font-semibold text-green-700">متاحة</p>
                            <p class="mt-2 text-2xl font-extrabold text-green-700">{{ $activeFoodsCount }}</p>
                        </div>

                        <div class="rounded-2xl bg-red-50 p-3">
                            <p class="text-xs font-semibold text-red-700">غير متاحة</p>
                            <p class="mt-2 text-2xl font-extrabold text-red-700">{{ $inactiveFoodsCount }}</p>
                        </div>
                    </div>

                    @if ($totalFoodsCount === 0)
                        <p class="mt-4 rounded-xl bg-gray-50 p-3 text-center text-xs leading-6 text-gray-500">
                            لا توجد وجبات مرتبطة بهذا التصنيف حتى الآن.
                        </p>
                    @endif
                </div>

                {{-- Quick Actions --}}
                <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
                    <h2 class="font-extrabold text-gray-800">الإجراءات السريعة</h2>
                    <p class="mt-1 text-xs text-gray-400">إدارة التصنيف ووجباته</p>

                    <div class="mt-5 space-y-3">

                        <a href="{{ route('admin.category-food.food.index', $category) }}"
                            class="group flex items-center gap-3 rounded-2xl border border-[#174c3c]/15 bg-[#174c3c]/5 p-4 transition hover:border-[#174c3c]/30 hover:bg-[#174c3c]/10">
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#174c3c] text-white transition group-hover:scale-105">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M3 5h18M3 12h18M3 19h18" />
                                </svg>
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-extrabold text-[#174c3c]">إدارة الوجبات</p>
                                <p class="mt-1 text-xs leading-5 text-gray-500">عرض وإدارة جميع وجبات التصنيف</p>
                            </div>

                            <svg class="h-4 w-4 shrink-0 text-[#174c3c] transition group-hover:-translate-x-1"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>

                        <a href="{{ route('admin.category-food.edit', $category) }}"
                            class="group flex items-center gap-3 rounded-2xl border border-blue-100 bg-blue-50/60 p-4 transition hover:bg-blue-50">
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700 transition group-hover:scale-105">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M16.862 4.487l1.687-1.688a2.1 2.1 0 113.05 2.9L9.832 17.467 5 19l1.533-4.832L16.862 4.487z" />
                                </svg>
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-extrabold text-blue-800">تعديل التصنيف</p>
                                <p class="mt-1 text-xs leading-5 text-gray-500">تغيير الاسم أو الصورة أو الوصف</p>
                            </div>

                            <svg class="h-4 w-4 shrink-0 text-blue-700 transition group-hover:-translate-x-1"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>

                        <a href="{{ route('admin.category-food.index') }}"
                            class="group flex items-center gap-3 rounded-2xl border border-gray-200 bg-gray-50 p-4 transition hover:bg-gray-100">
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gray-200 text-gray-600 transition group-hover:scale-105">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-extrabold text-gray-700">العودة للتصنيفات</p>
                                <p class="mt-1 text-xs leading-5 text-gray-500">عرض جميع تصنيفات الطعام</p>
                            </div>

                            <svg class="h-4 w-4 shrink-0 text-gray-500 transition group-hover:-translate-x-1"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>

                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div
            class="flex flex-col items-center justify-between gap-2 rounded-2xl border border-gray-100 bg-white px-5 py-4 text-center sm:flex-row sm:text-right">
            <p class="text-xs leading-6 text-gray-500">
                <span class="font-bold text-[#174c3c]">{{ $category->name }}</span>
                <span class="mx-1 text-gray-300">|</span>
                ملخص بيانات التصنيف
            </p>

            <a href="{{ route('admin.category-food.index') }}"
                class="inline-flex items-center gap-2 text-xs font-bold text-[#174c3c] transition hover:text-[#10382c]">
                العودة إلى القائمة
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>

    </div>
@endsection
