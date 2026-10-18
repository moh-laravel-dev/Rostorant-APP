@extends('admin.layouts.main-layout')

@section('title', 'إدارة قائمة الطعام')
@section('content')
    <div class="space-y-6 p-3 sm:p-5 lg:p-7" dir="rtl">

        {{-- Hero Header --}}
        <div
            class="relative isolate overflow-hidden rounded-3xl bg-gradient-to-br from-[var(--restaurant-color-hex-10382c)] via-[var(--restaurant-color-hex-174c3c)] to-[var(--restaurant-color-hex-26745a)] p-5 text-white shadow-xl sm:p-8">
            <div
                class="pointer-events-none absolute -left-12 -top-20 h-64 w-64 rounded-full border-[35px] border-white/[0.04]">
            </div>
            <div class="pointer-events-none absolute -bottom-24 right-1/3 h-64 w-64 rounded-full bg-emerald-300/10 blur-3xl">
            </div>

            <div class="relative flex flex-col justify-between gap-6 xl:flex-row xl:items-center">
                <div>
                    <nav aria-label="مسار التنقل"
                        class="mb-5 flex flex-wrap items-center gap-2 text-xs font-medium text-emerald-100/75 sm:text-sm">
                        <a href="{{ route('admin.category-food.index') }}" class="transition hover:text-white">إدارة قائمة
                            الطعام</a>
                        <svg class="h-3.5 w-3.5 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6" />
                        </svg>
                        <span class="text-white">تصنيفات الطعام</span>
                    </nav>

                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border border-white/15 bg-white/10 shadow-inner backdrop-blur sm:h-[72px] sm:w-[72px]">
                            <svg class="h-8 w-8 sm:h-10 sm:w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 5.5A1.5 1.5 0 0 1 5.5 4h13A1.5 1.5 0 0 1 20 5.5v13a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 18.5v-13Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 9h8M8 13h5M8 17h3" />
                            </svg>
                        </div>
                        <div>
                            <p class="mb-1 text-xs font-bold tracking-widest text-emerald-100/75">إدارة المطعم</p>
                            <h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl lg:text-4xl">تصنيفات الطعام</h1>
                            <p class="mt-2 max-w-xl text-sm leading-6 text-emerald-50/80">
                                نظّم قائمتك، تحكّم بتوفر التصنيفات، وأدرج الوجبات بطريقة سهلة واحترافية.
                            </p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('admin.category-food.create') }}"
                    class="inline-flex w-full shrink-0 items-center justify-center gap-2 rounded-2xl bg-white px-5 py-3.5 text-sm font-extrabold text-[var(--restaurant-color-hex-174c3c)] shadow-lg transition hover:-translate-y-0.5 hover:bg-emerald-50 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-white/30 sm:w-auto">
                    <span
                        class="flex h-7 w-7 items-center justify-center rounded-lg bg-[var(--restaurant-color-hex-174c3c)]/10">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
                        </svg>
                    </span>
                    إضافة تصنيف جديد
                </a>
            </div>

            {{-- Summary Cards --}}
            <div
                class="relative mt-7 grid grid-cols-2 gap-3 border-t border-white/10 pt-5 sm:grid-cols-2 sm:gap-4 lg:grid-cols-4">

                {{-- Total Categories --}}
                <div class="min-w-0 rounded-2xl border border-white/10 bg-white/[0.07] p-3 backdrop-blur-sm sm:p-4">
                    <div class="flex items-center gap-2 text-xs font-medium text-emerald-100/80">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 5.5A1.5 1.5 0 0 1 5.5 4h13A1.5 1.5 0 0 1 20 5.5v13a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 18.5v-13Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 9h8M8 13h5" />
                        </svg>
                        <span>إجمالي التصنيفات</span>
                    </div>
                    <p class="mt-2 text-2xl font-extrabold tabular-nums sm:text-3xl">
                        {{ $categories->total() }}
                    </p>
                </div>
                {{-- Active Categories --}}
                <div class="min-w-0 rounded-2xl border border-white/10 bg-white/[0.07] p-3 backdrop-blur-sm sm:p-4">
                    <div class="flex items-center gap-2 text-xs font-medium text-emerald-100/80">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.8">
                            <circle cx="12" cy="12" r="9" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8 12 2.5 2.5L16 9" />
                        </svg>
                        <span>التصنيفات الفعالة</span>
                    </div>

                    <p class="mt-2 text-2xl font-extrabold tabular-nums sm:text-3xl">
                        {{ \App\Models\CategoryFood::where('is_active', true)->count() }}
                    </p>

                    <p class="mt-1 text-[11px] text-emerald-100/60">من إجمالي التصنيفات</p>
                </div>

                {{-- Inactive Categories --}}
                <div class="min-w-0 rounded-2xl border border-white/10 bg-white/[0.07] p-3 backdrop-blur-sm sm:p-4">
                    <div class="flex items-center gap-2 text-xs font-medium text-emerald-100/80">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.8">
                            <circle cx="12" cy="12" r="9" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 9l6 6m0-6-6 6" />
                        </svg>
                        <span>التصنيفات غير الفعالة</span>
                    </div>

                    <p class="mt-2 text-2xl font-extrabold tabular-nums sm:text-3xl">
                        {{ \App\Models\CategoryFood::where('is_active', false)->count() }}
                    </p>

                    <p class="mt-1 text-[11px] text-emerald-100/60">من إجمالي التصنيفات</p>
                </div>
                {{-- Current Page --}}
                <div class="min-w-0 rounded-2xl border border-white/10 bg-white/[0.07] p-3 backdrop-blur-sm sm:p-4">
                    <div class="flex items-center gap-2 text-xs font-medium text-emerald-100/80">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        <span>الصفحة الحالية</span>
                    </div>
                    <p class="mt-2 text-2xl font-extrabold tabular-nums sm:text-3xl">
                        {{ $categories->currentPage() }}
                        <span class="text-base font-medium text-emerald-100/60">
                            / {{ max(1, $categories->lastPage()) }}
                        </span>
                    </p>
                </div>

            </div>
        </div>
        {{-- Filters --}}
        <div class="overflow-hidden rounded-3xl border border-gray-200/80 bg-white shadow-sm" dir="rtl">
            <form method="GET" action="{{ route('admin.category-food.index') }}">
                @csrf
                <input type="hidden" name="limit" value="{{ request('limit', $categories->perPage()) }}">

                <div class="flex flex-col gap-4 border-b border-gray-100 p-4 sm:p-5 lg:flex-row lg:items-end">
                    {{-- Search --}}
                    <div class="min-w-0 flex-1">
                        <label for="search" class="mb-2 block text-sm font-bold text-gray-700">
                            البحث عن تصنيف
                        </label>

                        <div class="relative">
                            <svg class="pointer-events-none absolute right-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <circle cx="11" cy="11" r="7" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16 16 4 4" />
                            </svg>

                            <input type="search" id="search" name="search" value="{{ request('search') }}"
                                placeholder="ابحث باسم التصنيف أو الوصف..."
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 py-3 pr-10 pl-4 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-[var(--restaurant-color-hex-174c3c)] focus:bg-white focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10">
                        </div>
                    </div>

                    {{-- Status Filter --}}
                    <div class="w-full lg:w-48">
                        <label for="status" class="mb-2 block text-sm font-bold text-gray-700">
                            حالة التصنيف
                        </label>

                        <select id="status" name="status"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-3 text-sm text-gray-700 outline-none transition focus:border-[var(--restaurant-color-hex-174c3c)] focus:bg-white focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10">
                            <option value="">جميع الحالات</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                                فعال
                            </option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                غير فعال
                            </option>
                        </select>
                    </div>

                    {{-- Filter Actions --}}
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="submit"
                            class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-[var(--restaurant-color-hex-174c3c)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[var(--restaurant-color-hex-10382c)] focus:outline-none focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/15 sm:flex-none">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-4.3-4.3M19 10.5a8.5 8.5 0 1 1-17 0 8.5 8.5 0 0 1 17 0Z" />
                            </svg>
                            تطبيق
                        </button>

                        @if (request()->hasAny(['search', 'status']))
                            <a href="{{ route('admin.category-food.index', ['limit' => request('limit', $categories->perPage())]) }}"
                                class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-bold text-gray-600 transition hover:bg-gray-50 hover:text-red-600 sm:flex-none">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 6 12 12M18 6 6 18" />
                                </svg>
                                مسح الفلاتر
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Active Filter Indicators --}}
                @if (request()->filled('search') || request()->filled('status'))
                    <div class="flex flex-wrap items-center gap-2 bg-gray-50/70 px-4 py-3 sm:px-5">
                        <span class="text-xs font-semibold text-gray-500">الفلاتر المطبقة:</span>

                        @if (request()->filled('search'))
                            <span
                                class="inline-flex max-w-full items-center gap-1.5 rounded-lg border border-[var(--restaurant-color-hex-174c3c)]/10 bg-[var(--restaurant-color-hex-174c3c)]/5 px-2.5 py-1.5 text-xs font-semibold text-[var(--restaurant-color-hex-174c3c)]">
                                <icon name="search" size="xs" />
                                <span class="break-all">{{ request('search') }}</span>
                            </span>
                        @endif

                        @if (request('status') === 'active')
                            <span
                                class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-2.5 py-1.5 text-xs font-bold text-emerald-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                التصنيفات الفعالة
                            </span>
                        @elseif (request('status') === 'inactive')
                            <span
                                class="inline-flex items-center gap-1.5 rounded-lg bg-red-50 px-2.5 py-1.5 text-xs font-bold text-red-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                التصنيفات غير الفعالة
                            </span>
                        @endif
                    </div>
                @endif
            </form>
        </div>
        {{-- Flash Messages --}}
        @if (session('success'))
            <div
                class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 shadow-sm">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-extrabold">تمت العملية بنجاح</p>
                    <p class="mt-1 text-sm">{{ session('success') }}</p>
                </div>
                <button type="button" onclick="this.parentElement.remove()"
                    class="rounded-lg p-1 text-emerald-700/60 hover:bg-emerald-100 hover:text-emerald-900"
                    aria-label="إغلاق">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m6 6 12 12M18 6 6 18" />
                    </svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800 shadow-sm">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-100">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01" />
                        <circle cx="12" cy="12" r="9" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-extrabold">تعذر إتمام العملية</p>
                    <p class="mt-1 text-sm">{{ session('error') }}</p>
                </div>
                <button type="button" onclick="this.parentElement.remove()"
                    class="rounded-lg p-1 text-red-700/60 hover:bg-red-100 hover:text-red-900" aria-label="إغلاق">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m6 6 12 12M18 6 6 18" />
                    </svg>
                </button>
            </div>
        @endif

        {{-- Categories Table --}}
        <div class="overflow-hidden rounded-3xl border border-gray-200/80 bg-white shadow-sm">
            <div
                class="flex flex-col gap-3 border-b border-gray-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <h2 class="text-lg font-extrabold text-gray-800">قائمة التصنيفات</h2>
                    <p class="mt-1 text-xs text-gray-500">عرض وإدارة جميع تصنيفات الطعام.</p>
                </div>
                <div
                    class="inline-flex w-fit items-center gap-2 rounded-xl bg-gray-50 px-3 py-2 text-xs font-semibold text-gray-500 ring-1 ring-gray-200/80">
                    <span class="h-2 w-2 rounded-full bg-[var(--restaurant-color-hex-174c3c)]"></span>
                    {{ $categories->count() }} تصنيف في هذه الصفحة
                </div>
            </div>

            @if ($categories->count())
                {{-- Mobile Cards --}}
                <div class="space-y-3 p-3 sm:hidden">
                    @foreach ($categories as $category)
                        <article
                            class="overflow-hidden rounded-2xl border border-gray-200 bg-white transition hover:border-[var(--restaurant-color-hex-174c3c)]/25 hover:shadow-md">
                            <div class="flex gap-3 p-3">
                                <div
                                    class="relative h-24 w-24 shrink-0 overflow-hidden rounded-xl border border-gray-100 bg-gray-50">
                                    @if ($category->photo)
                                        <img src="{{ asset('storage/' . $category->photo) }}"
                                            alt="{{ $category->name }}" loading="lazy"
                                            class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-gray-300">
                                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="1.5">
                                                <rect x="3" y="3" width="18" height="18" rx="3" />
                                                <circle cx="8.5" cy="8.5" r="1.5" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m21 15-5-5L5 21" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <span class="text-[10px] font-bold text-gray-400">تصنيف
                                                #{{ $categories->firstItem() + $loop->index }}</span>
                                            <h3 class="mt-1 break-words text-sm font-extrabold text-gray-800">
                                                {{ $category->name }}</h3>
                                        </div>

                                        <span
                                            class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-2 py-1 text-[10px] font-bold {{ $category->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                            <span
                                                class="h-1.5 w-1.5 rounded-full {{ $category->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                            {{ $category->is_active ? 'فعال' : 'غير فعال' }}
                                        </span>
                                    </div>

                                    <p class="mt-2 text-xs leading-5 text-gray-500">
                                        {{ $category->description ? \Illuminate\Support\Str::limit($category->description, 65) : 'لا يوجد وصف لهذا التصنيف.' }}
                                    </p>

                                    <a href="{{ route('admin.category-food.food.index', $category) }}"
                                        class="mt-3 inline-flex items-center gap-1.5 rounded-lg bg-[var(--restaurant-color-hex-174c3c)]/5 px-2.5 py-1.5 text-xs font-bold text-[var(--restaurant-color-hex-174c3c)] transition hover:bg-[var(--restaurant-color-hex-174c3c)]/10">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                        </svg>
                                        {{ $category->foods_count ?? $category->foods()->count() }} وجبة
                                    </a>
                                </div>
                            </div>

                            <div
                                class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 bg-gray-50/60 px-3 py-2.5">
                                <form action="{{ route('admin.category-food.toggle-status', $category) }}"
                                    method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-bold transition {{ $category->is_active ? 'text-emerald-700 hover:bg-emerald-50' : 'text-red-700 hover:bg-red-50' }}">
                                        <span
                                            class="relative inline-flex h-5 w-9 items-center rounded-full transition {{ $category->is_active ? 'bg-emerald-600' : 'bg-gray-300' }}">
                                            <span
                                                class="absolute top-0.5 h-4 w-4 rounded-full bg-white shadow-sm transition-all {{ $category->is_active ? 'right-0.5' : 'right-[18px]' }}"></span>
                                        </span>
                                        {{ $category->is_active ? 'تعطيل' : 'تفعيل' }}
                                    </button>
                                </form>

                                <div class="flex items-center gap-1.5">
                                    <a href="{{ route('admin.category-food.show', $category) }}" title="عرض التفاصيل"
                                        aria-label="عرض التفاصيل"
                                        class="rounded-lg bg-white p-2 text-gray-600 ring-1 ring-gray-200 transition hover:bg-gray-100">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.category-food.edit', $category) }}" title="تعديل التصنيف"
                                        aria-label="تعديل التصنيف"
                                        class="rounded-lg bg-blue-50 p-2 text-blue-700 transition hover:bg-blue-100">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16 4 4 4M4 20l4-.8L19 8a2.12 2.12 0 0 0-3-3L5 16l-1 4Z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.category-food.destroy', $category) }}" method="POST"
                                        onsubmit="return confirm('هل أنت متأكد من حذف هذا التصنيف؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="حذف التصنيف" aria-label="حذف التصنيف"
                                            class="rounded-lg bg-red-50 p-2 text-red-700 transition hover:bg-red-100">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4 7h16M10 11v6m4-6v6M5 7l1 14h12l1-14M9 7V4h6v3" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Desktop Table --}}
                <div class="hidden overflow-x-auto sm:block">
                    <table class="w-full min-w-[900px] text-right text-sm">
                        <thead class="bg-gray-50/80 text-xs font-bold text-gray-500">
                            <tr>
                                <th class="whitespace-nowrap px-5 py-4">#</th>
                                <th class="whitespace-nowrap px-5 py-4">الصورة</th>
                                <th class="whitespace-nowrap px-5 py-4">اسم التصنيف</th>
                                <th class="px-5 py-4">الوصف</th>
                                <th class="whitespace-nowrap px-5 py-4">عدد الوجبات</th>
                                <th class="whitespace-nowrap px-5 py-4">الحالة</th>
                                <th class="whitespace-nowrap px-5 py-4 text-center">الإجراءات</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @foreach ($categories as $category)
                                <tr class="group transition hover:bg-emerald-50/30">
                                    <td class="px-5 py-4 text-xs font-semibold tabular-nums text-gray-400">
                                        {{ $categories->firstItem() + $loop->index }}
                                    </td>

                                    <td class="px-5 py-4">
                                        <div
                                            class="relative h-16 w-16 overflow-hidden rounded-2xl border border-gray-200 bg-gray-50 shadow-sm transition duration-300 group-hover:border-[var(--restaurant-color-hex-174c3c)]/30 group-hover:shadow-md">
                                            @if ($category->photo)
                                                <img src="{{ asset('storage/' . $category->photo) }}"
                                                    alt="{{ $category->name }}" loading="lazy"
                                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-110">
                                            @else
                                                <div class="flex h-full w-full items-center justify-center text-gray-300">
                                                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="1.5">
                                                        <rect x="3" y="3" width="18" height="18" rx="3" />
                                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m21 15-5-5L5 21" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="min-w-[170px] px-5 py-4">
                                        <div
                                            class="font-extrabold text-gray-800 transition group-hover:text-[var(--restaurant-color-hex-174c3c)]">
                                            {{ $category->name }}
                                        </div>
                                        <div class="mt-1 text-[11px] text-gray-400">تصنيف
                                            #{{ $categories->firstItem() + $loop->index }}</div>
                                    </td>

                                    <td class="min-w-[230px] max-w-[300px] px-5 py-4 text-gray-500"
                                        x-data="{ expanded: false }">
                                        @if ($category->description)
                                            <p class="break-words leading-6">
                                                <span
                                                    x-show="!expanded">{{ \Illuminate\Support\Str::limit($category->description, 55) }}</span>
                                                <span x-cloak x-show="expanded">{{ $category->description }}</span>
                                            </p>
                                            @if (mb_strlen($category->description) > 55)
                                                <button type="button" @click="expanded = !expanded"
                                                    class="mt-1 text-xs font-bold text-[var(--restaurant-color-hex-174c3c)] transition hover:underline">
                                                    <span x-show="!expanded">عرض المزيد</span>
                                                    <span x-cloak x-show="expanded">عرض أقل</span>
                                                </button>
                                            @endif
                                        @else
                                            <span class="text-gray-300">لا يوجد وصف</span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4">
                                        <a href="{{ route('admin.category-food.food.index', $category) }}"
                                            class="inline-flex items-center gap-2 rounded-xl border border-[var(--restaurant-color-hex-174c3c)]/10 bg-[var(--restaurant-color-hex-174c3c)]/5 px-3 py-2 font-bold text-[var(--restaurant-color-hex-174c3c)] transition hover:border-[var(--restaurant-color-hex-174c3c)]/20 hover:bg-[var(--restaurant-color-hex-174c3c)]/10">
                                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                                </svg>
                                            </span>
                                            <span
                                                class="tabular-nums">{{ $category->foods_count ?? $category->foods()->count() }}</span>
                                            <span class="text-xs font-medium">وجبة</span>
                                        </a>
                                    </td>

                                    <td class="px-5 py-4">
                                        <form action="{{ route('admin.category-food.toggle-status', $category) }}"
                                            method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                aria-label="{{ $category->is_active ? 'تعطيل التصنيف' : 'تفعيل التصنيف' }}"
                                                class="group/status inline-flex items-center gap-2 rounded-full px-3 py-2 transition {{ $category->is_active ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">
                                                <span
                                                    class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors {{ $category->is_active ? 'bg-emerald-600' : 'bg-gray-300' }}">
                                                    <span
                                                        class="absolute top-0.5 h-4 w-4 rounded-full bg-white shadow-sm transition-all {{ $category->is_active ? 'right-0.5' : 'right-[18px]' }}"></span>
                                                </span>
                                                <span
                                                    class="text-xs font-extrabold">{{ $category->is_active ? 'فعال' : 'غير فعال' }}</span>
                                            </button>
                                        </form>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('admin.category-food.show', $category) }}"
                                                class="rounded-xl bg-gray-50 p-2.5 text-gray-600 ring-1 ring-gray-200/70 transition hover:bg-gray-100 hover:text-gray-900"
                                                title="عرض التفاصيل" aria-label="عرض التفاصيل">
                                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg>
                                            </a>

                                            <a href="{{ route('admin.category-food.edit', $category) }}"
                                                class="rounded-xl bg-blue-50 p-2.5 text-blue-700 transition hover:bg-blue-100"
                                                title="تعديل التصنيف" aria-label="تعديل التصنيف">
                                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m16 4 4 4M4 20l4-.8L19 8a2.12 2.12 0 0 0-3-3L5 16l-1 4Z" />
                                                </svg>
                                            </a>

                                            <form action="{{ route('admin.category-food.destroy', $category) }}"
                                                method="POST"
                                                onsubmit="return confirm('هل أنت متأكد من حذف هذا التصنيف؟')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" title="حذف التصنيف" aria-label="حذف التصنيف"
                                                    class="rounded-xl bg-red-50 p-2.5 text-red-700 transition hover:bg-red-100">
                                                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M4 7h16M10 11v6m4-6v6M5 7l1 14h12l1-14M9 7V4h6v3" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                {{-- Empty State --}}
                <div class="px-5 py-16 text-center sm:py-20">
                    <div
                        class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-[var(--restaurant-color-hex-174c3c)]/5 text-[var(--restaurant-color-hex-174c3c)] ring-1 ring-[var(--restaurant-color-hex-174c3c)]/10">
                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 5.5A1.5 1.5 0 0 1 5.5 4h13A1.5 1.5 0 0 1 20 5.5v13a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 18.5v-13Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 9h8M8 13h5" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-extrabold text-gray-800">لا توجد تصنيفات حتى الآن</h3>
                    <p class="mx-auto mt-2 max-w-sm text-sm leading-6 text-gray-500">ابدأ بإضافة أول تصنيف لتنظيم الوجبات
                        والمشروبات في قائمتك.</p>
                    <a href="{{ route('admin.category-food.create') }}"
                        class="mt-6 inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--restaurant-color-hex-174c3c)] px-5 py-3 text-sm font-bold text-white shadow-md transition hover:-translate-y-0.5 hover:bg-[var(--restaurant-color-hex-10382c)]">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
                        </svg>
                        إضافة أول تصنيف
                    </a>
                </div>
            @endif
            {{-- Creative Pagination --}}
            <div class="mt-8 overflow-hidden rounded-3xl border border-gray-200/80 bg-white shadow-sm" dir="rtl">

                {{-- Top Accent --}}
                <div class="h-1 bg-gradient-to-l from-[var(--restaurant-color-hex-174c3c)] via-emerald-500 to-emerald-100">
                </div>

                <div class="p-4 sm:p-5">
                    <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">

                        {{-- Results Summary --}}
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[var(--restaurant-color-hex-174c3c)]/[0.07] text-[var(--restaurant-color-hex-174c3c)]">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.7">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 6.5A2.5 2.5 0 0 1 6.5 4h11A2.5 2.5 0 0 1 20 6.5v11a2.5 2.5 0 0 1-2.5 2.5h-11A2.5 2.5 0 0 1 4 17.5v-11Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 9h8M8 12h8M8 15h5" />
                                </svg>
                            </div>

                            <div class="min-w-0">
                                <p class="text-sm font-bold text-gray-800">عرض التصنيفات</p>
                                <p class="mt-1 text-xs text-gray-500">
                                    @if ($categories->total() > 0)
                                        عرض
                                        <span
                                            class="font-bold text-[var(--restaurant-color-hex-174c3c)]">{{ $categories->firstItem() }}</span>
                                        إلى
                                        <span
                                            class="font-bold text-[var(--restaurant-color-hex-174c3c)]">{{ $categories->lastItem() }}</span>
                                        من أصل
                                        <span class="font-bold text-gray-700">{{ $categories->total() }}</span>
                                        تصنيف
                                    @else
                                        لا توجد تصنيفات لعرضها
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between xl:justify-end">

                            {{-- Items Per Page --}}
                            <form method="GET" action="{{ route('admin.category-food.index') }}"
                                class="flex items-center justify-between gap-3 rounded-2xl border border-gray-200 bg-gray-50/80 p-2 sm:justify-start">
                                @csrf
                                @foreach (request()->except(['limit', 'page']) as $key => $value)
                                    @if (is_array($value))
                                        @foreach ($value as $subKey => $subValue)
                                            @if (!is_array($subValue))
                                                <input type="hidden" name="{{ $key }}[{{ $subKey }}]"
                                                    value="{{ $subValue }}">
                                            @endif
                                        @endforeach
                                    @elseif (is_scalar($value))
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endif
                                @endforeach

                                <label for="categories-limit"
                                    class="flex items-center gap-2 pr-1 text-xs font-semibold text-gray-600">
                                    <svg class="h-4 w-4 text-[var(--restaurant-color-hex-174c3c)]" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                                    </svg>
                                    عدد النتائج
                                </label>

                                <select id="categories-limit" name="limit" onchange="this.form.submit()"
                                    class="min-w-[76px] cursor-pointer rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm font-bold text-[var(--restaurant-color-hex-174c3c)] outline-none transition hover:border-[var(--restaurant-color-hex-174c3c)]/40 focus:border-[var(--restaurant-color-hex-174c3c)] focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10">
                                    @foreach ([5, 10, 25, 50, 100] as $limit)
                                        <option value="{{ $limit }}"
                                            {{ (int) $categories->perPage() === $limit ? 'selected' : '' }}>
                                            {{ $limit }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>

                            {{-- Pagination Navigation --}}
                            <div class="flex items-center justify-between gap-2 sm:justify-end">

                                {{-- Previous Page --}}
                                @if ($categories->onFirstPage())
                                    <span aria-disabled="true"
                                        class="inline-flex min-h-10 cursor-not-allowed items-center gap-2 rounded-xl border border-gray-200 bg-gray-50 px-3 text-sm font-semibold text-gray-300 sm:px-4">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6" />
                                        </svg>
                                        <span>السابق</span>
                                    </span>
                                @else
                                    <a href="{{ $categories->previousPageUrl() }}" rel="prev"
                                        aria-label="الصفحة السابقة"
                                        class="inline-flex min-h-10 items-center gap-2 rounded-xl border border-gray-200 bg-white px-3 text-sm font-semibold text-gray-700 transition hover:border-[var(--restaurant-color-hex-174c3c)]/30 hover:bg-[var(--restaurant-color-hex-174c3c)]/5 hover:text-[var(--restaurant-color-hex-174c3c)] focus:outline-none focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10 sm:px-4">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6 6-6-6" />
                                        </svg>
                                        <span>السابق</span>
                                    </a>
                                @endif

                                {{-- Page Indicator --}}
                                <div
                                    class="flex min-h-10 items-center gap-2 rounded-xl bg-[var(--restaurant-color-hex-174c3c)] px-4 text-sm font-bold text-white shadow-sm shadow-[var(--restaurant-color-hex-174c3c)]/15">
                                    <span>{{ $categories->currentPage() }}</span>
                                    <span class="text-emerald-200/70">من</span>
                                    <span>{{ max(1, $categories->lastPage()) }}</span>
                                </div>

                                {{-- Next Page --}}
                                @if ($categories->hasMorePages())
                                    <a href="{{ $categories->nextPageUrl() }}" rel="next"
                                        aria-label="الصفحة التالية"
                                        class="inline-flex min-h-10 items-center gap-2 rounded-xl border border-gray-200 bg-white px-3 text-sm font-semibold text-gray-700 transition hover:border-[var(--restaurant-color-hex-174c3c)]/30 hover:bg-[var(--restaurant-color-hex-174c3c)]/5 hover:text-[var(--restaurant-color-hex-174c3c)] focus:outline-none focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10 sm:px-4">
                                        <span>التالي</span>
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6" />
                                        </svg>
                                    </a>
                                @else
                                    <span aria-disabled="true"
                                        class="inline-flex min-h-10 cursor-not-allowed items-center gap-2 rounded-xl border border-gray-200 bg-gray-50 px-3 text-sm font-semibold text-gray-300 sm:px-4">
                                        <span>التالي</span>
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6" />
                                        </svg>
                                    </span>
                                @endif

                            </div>
                        </div>
                    </div>

                    {{-- Pagination Progress --}}
                    @php
                        $currentPage = $categories->currentPage();
                        $lastPage = max(1, $categories->lastPage());
                        $progress = min(100, ($currentPage / $lastPage) * 100);
                    @endphp

                    @if ($categories->total() > 0 && $lastPage > 1)
                        <div class="mt-5 border-t border-gray-100 pt-4">
                            <div class="mb-2 flex items-center justify-between gap-3 text-[11px] text-gray-400">
                                <span>تقدم الصفحات</span>
                                <span
                                    class="font-bold tabular-nums text-[var(--restaurant-color-hex-174c3c)]">{{ number_format($progress, 0) }}%</span>
                            </div>
                            <div class="h-1.5 overflow-hidden rounded-full bg-gray-100">
                                <div class="h-full rounded-full bg-gradient-to-l from-[var(--restaurant-color-hex-174c3c)] to-emerald-400 transition-all duration-300"
                                    style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
