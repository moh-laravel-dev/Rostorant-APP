@extends('admin.layouts.main-layout')

@section('content')
    <div class="mx-auto max-w-7xl space-y-7 pb-8" dir="rtl">

        {{-- Header --}}
        <div
            class="relative isolate overflow-hidden rounded-3xl bg-gradient-to-br from-[var(--restaurant-color-hex-174c3c)] via-[var(--restaurant-color-hex-1c604b)] to-[var(--restaurant-color-hex-10382c)] p-6 text-white shadow-xl sm:p-8">
            <div class="pointer-events-none absolute -left-12 -top-16 h-52 w-52 rounded-full border-[30px] border-white/5">
            </div>
            <div class="pointer-events-none absolute -bottom-24 right-1/3 h-64 w-64 rounded-full bg-emerald-300/10 blur-3xl">
            </div>

            <div class="relative flex flex-col justify-between gap-6 sm:flex-row sm:items-center">
                <div class="min-w-0">
                    <div
                        class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-xs font-bold text-emerald-100">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        لوحة التحكم / الإعدادات
                    </div>

                    <h1 class="text-2xl font-black leading-relaxed sm:text-3xl">
                        إعدادات خدمات الطاولات
                    </h1>

                    <p class="mt-2 max-w-xl text-sm leading-7 text-white/75 sm:text-base">
                        تحكّم بخدمات مطعمك وأسعارها وعملاتها، وفعّل أو عطّل الخدمات بكل سهولة.
                    </p>
                </div>

                <div
                    class="flex h-20 w-20 shrink-0 items-center justify-center self-start rounded-3xl border border-white/15 bg-white/10 shadow-inner sm:h-24 sm:w-24 sm:self-center">
                    <svg class="h-11 w-11 sm:h-14 sm:w-14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 4h16v4H4zM6 8v12h12V8M9 12h6M9 16h6M8 4V2m8 2V2" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Alerts --}}
        @if (session('status'))
            <div
                class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold leading-6 text-emerald-800 shadow-sm">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4 4L19 6" />
                    </svg>
                </span>
                <p class="pt-1">{{ session('status') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div
                class="flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-semibold leading-6 text-red-700 shadow-sm">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-red-100">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v4m0 4h.01M10.3 3.9 1.8 18.5A1.7 1.7 0 003.3 21h17.4a1.7 1.7 0 001.5-2.5L13.7 3.9a2 2 0 00-3.4 0z" />
                    </svg>
                </span>
                <p class="pt-1">{{ $errors->first() }}</p>
            </div>
        @endif

        {{-- Statistics --}}
        @php
            $totalServices = count($services);
            $activeServices = collect($services)->filter(fn($service) => $service['active'] ?? true)->count();
            $inactiveServices = $totalServices - $activeServices;
        @endphp

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div
                class="group flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-[var(--restaurant-color-hex-174c3c)] transition group-hover:bg-[var(--restaurant-color-hex-174c3c)] group-hover:text-white">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                            d="M4 4h16v16H4zM8 8h8M8 12h8M8 16h5" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500">إجمالي الخدمات</p>
                    <p class="mt-1 text-3xl font-black tabular-nums text-gray-800">{{ $totalServices }}</p>
                </div>
            </div>

            <div
                class="group flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-green-50 text-green-700 transition group-hover:bg-green-600 group-hover:text-white">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m5 12 4 4L19 6" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500">الخدمات المفعّلة</p>
                    <p class="mt-1 text-3xl font-black tabular-nums text-green-700">{{ $activeServices }}</p>
                </div>
            </div>

            <div
                class="group flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-red-50 text-red-600 transition group-hover:bg-red-500 group-hover:text-white">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 6l12 12M18 6 6 18" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500">الخدمات المعطّلة</p>
                    <p class="mt-1 text-3xl font-black tabular-nums text-red-600">{{ $inactiveServices }}</p>
                </div>
            </div>
        </div>

        {{-- Add Service --}}
        <section class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm">
            <div
                class="flex items-center gap-4 border-b border-gray-100 bg-gradient-to-l from-emerald-50/80 to-white p-5 sm:p-6">
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[var(--restaurant-color-hex-174c3c)] text-white shadow-lg shadow-emerald-900/10">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 5v14M5 12h14" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold text-gray-800">إضافة خدمة جديدة</h2>
                    <p class="mt-1 text-sm text-gray-500">أدخل تفاصيل الخدمة لتكون جاهزة للإدارة.</p>
                </div>
            </div>

            <form action="{{ route('admin.settings.store') }}" method="POST"
                class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 sm:p-6 lg:grid-cols-4">
                @csrf

                <div>
                    <label for="new-name" class="mb-2 block text-sm font-bold text-gray-700">اسم الخدمة</label>
                    <input id="new-name" name="name" type="text" value="{{ old('name') }}"
                        placeholder="مثال: خدمة الإنترنت" maxlength="255" required
                        class="min-h-12 w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm outline-none transition placeholder:text-gray-400 focus:border-[var(--restaurant-color-hex-174c3c)] focus:bg-white focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10">
                </div>

                <div>
                    <label for="new-value" class="mb-2 block text-sm font-bold text-gray-700">السعر</label>
                    <div class="relative">
                        <input id="new-value" name="value" type="number" value="{{ old('value', 0) }}"
                            min="0" step="1" required
                            class="min-h-12 w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 pl-16 text-sm tabular-nums outline-none transition focus:border-[var(--restaurant-color-hex-174c3c)] focus:bg-white focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">السعر</span>
                    </div>
                </div>

                <div>
                    <label for="new-currency" class="mb-2 block text-sm font-bold text-gray-700">العملة</label>
                    <input id="new-currency" name="currency" type="text" value="{{ old('currency', 'ل.س') }}"
                        maxlength="10" required
                        class="min-h-12 w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm uppercase outline-none transition focus:border-[var(--restaurant-color-hex-174c3c)] focus:bg-white focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10">
                    <p class="mt-1.5 text-xs text-gray-400">مثال: ل.س أو USD</p>
                </div>

                <div class="flex items-end">
                    <button type="submit"
                        class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-[var(--restaurant-color-hex-174c3c)] px-5 py-3 text-sm font-extrabold text-white shadow-md shadow-emerald-900/10 transition hover:-translate-y-0.5 hover:bg-[var(--restaurant-color-hex-10382c)] hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/20">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 5v14M5 12h14" />
                        </svg>
                        إضافة الخدمة
                    </button>
                </div>
            </form>
        </section>

        {{-- Services List --}}
        <section class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm">
            <div
                class="flex flex-col gap-3 border-b border-gray-100 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                <div>
                    <h2 class="text-lg font-extrabold text-gray-800">الخدمات الحالية</h2>
                    <p class="mt-1 text-sm text-gray-500">عدّل بيانات كل خدمة أو تحكّم بحالة توفرها.</p>
                </div>

                <span
                    class="inline-flex w-fit items-center gap-2 rounded-full bg-emerald-50 px-4 py-2 text-sm font-extrabold text-[var(--restaurant-color-hex-174c3c)]">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    {{ $totalServices }} خدمة
                </span>
            </div>

            @forelse ($services as $service)
                @php
                    $isActive = $service['active'] ?? true;
                @endphp

                <article class="border-b border-gray-100 p-5 last:border-b-0 transition hover:bg-gray-50/50 sm:p-6">
                    <div class="mb-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex min-w-0 items-center gap-3">
                            <div
                                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl {{ $isActive ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600' }}">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                        d="M4 4h16v4H4zM6 8v12h12V8M9 12h6M9 16h6" />
                                </svg>
                            </div>

                            <div class="min-w-0">
                                <h3 class="break-words text-base font-extrabold text-gray-800">
                                    {{ $service['name'] ?? 'خدمة بدون اسم' }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ number_format((float) ($service['value'] ?? 0)) }}
                                    <span class="font-semibold">{{ $service['currency'] ?? 'ل.س' }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <span
                                class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-extrabold {{ $isActive ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                <span
                                    class="h-2 w-2 rounded-full {{ $isActive ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                {{ $isActive ? 'الخدمة مفعّلة' : 'الخدمة معطّلة' }}
                            </span>

                            <form action="{{ route('admin.settings.toggle-status', $service['id']) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <button type="submit" role="switch" aria-checked="{{ $isActive ? 'true' : 'false' }}"
                                    aria-label="{{ $isActive ? 'تعطيل الخدمة' : 'تفعيل الخدمة' }}"
                                    title="{{ $isActive ? 'تعطيل الخدمة' : 'تفعيل الخدمة' }}"
                                    class="relative inline-flex h-7 w-12 shrink-0 items-center rounded-full transition-colors duration-300 focus:outline-none focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/15 {{ $isActive ? 'bg-emerald-600' : 'bg-gray-300' }}">
                                    <span
                                        class="inline-block h-5 w-5 rounded-full bg-white shadow transition-transform duration-300 {{ $isActive ? '-translate-x-6' : '-translate-x-1' }}"></span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <form action="{{ route('admin.settings.update', $service['id']) }}" method="POST"
                        class="grid grid-cols-1 gap-4 rounded-2xl border border-gray-100 bg-gray-50/80 p-4 sm:grid-cols-2 lg:grid-cols-12 lg:items-end">
                        @csrf
                        @method('PUT')

                        <div class="sm:col-span-2 lg:col-span-5">
                            <label for="name-{{ $service['id'] }}" class="mb-2 block text-xs font-bold text-gray-600">اسم
                                الخدمة</label>
                            <input id="name-{{ $service['id'] }}" name="name" type="text"
                                value="{{ $service['name'] ?? '' }}" maxlength="255" required
                                class="min-h-11 w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-[var(--restaurant-color-hex-174c3c)] focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10">
                        </div>

                        <div class="lg:col-span-3">
                            <label for="value-{{ $service['id'] }}"
                                class="mb-2 block text-xs font-bold text-gray-600">السعر</label>
                            <input id="value-{{ $service['id'] }}" name="value" type="number"
                                value="{{ $service['value'] ?? 0 }}" min="0" step="1" required
                                class="min-h-11 w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm tabular-nums outline-none transition focus:border-[var(--restaurant-color-hex-174c3c)] focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10">
                        </div>

                        <div class="lg:col-span-2">
                            <label for="currency-{{ $service['id'] }}"
                                class="mb-2 block text-xs font-bold text-gray-600">العملة</label>
                            <input id="currency-{{ $service['id'] }}" name="currency" type="text"
                                value="{{ $service['currency'] ?? 'ل.س' }}" maxlength="10" required
                                class="min-h-11 w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm uppercase outline-none transition focus:border-[var(--restaurant-color-hex-174c3c)] focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10">
                        </div>

                        <div class="flex flex-wrap gap-2 sm:col-span-2 lg:col-span-2">
                            <button type="submit"
                                class="inline-flex min-h-11 flex-1 items-center justify-center gap-2 rounded-xl bg-[var(--restaurant-color-hex-174c3c)] px-4 py-2.5 text-sm font-extrabold text-white transition hover:bg-[var(--restaurant-color-hex-10382c)] focus:outline-none focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/15">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="m5 12 4 4L19 6" />
                                </svg>
                                حفظ
                            </button>
                        </div>
                    </form>

                    <div class="mt-3 flex justify-end">
                        <form action="{{ route('admin.settings.destroy', $service['id']) }}" method="POST"
                            onsubmit="return confirm('هل أنت متأكد من حذف هذه الخدمة؟');">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-red-200 bg-white px-4 py-2.5 text-xs font-bold text-red-600 transition hover:border-red-300 hover:bg-red-50 focus:outline-none focus:ring-4 focus:ring-red-100">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M4 7h16M10 11v6m4-6v6M5 7l1 14h12l1-14M9 7V4h6v3" />
                                </svg>
                                حذف الخدمة
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="px-5 py-14 text-center sm:py-20">
                    <div
                        class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-emerald-50 text-[var(--restaurant-color-hex-174c3c)]">
                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                d="M4 4h16v16H4zM8 8h8M8 12h8M8 16h5" />
                        </svg>
                    </div>

                    <h3 class="mt-5 text-lg font-extrabold text-gray-800">لا توجد خدمات حاليًا</h3>
                    <p class="mx-auto mt-2 max-w-sm text-sm leading-7 text-gray-500">
                        لم تتم إضافة أي خدمات بعد. ابدأ بإضافة أول خدمة من النموذج أعلاه.
                    </p>
                </div>
            @endforelse
        </section>
    </div>
@endsection
