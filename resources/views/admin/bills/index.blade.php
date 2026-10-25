@extends('admin.layouts.main-layout')

@section('title', 'الفواتير')

@section('content')
    <style>
        #bills-page {
            --bills-line: color-mix(in srgb, CanvasText 13%, Canvas);
            --bills-wash: color-mix(in srgb, CanvasText 4%, Canvas);
            --bills-shadow: 0 18px 50px color-mix(in srgb, CanvasText 8%, transparent);
            --bills-heading-background:
                radial-gradient(circle at 12% 10%, color-mix(in srgb, var(--brand-accent) 18%, transparent), transparent 29%),
                linear-gradient(120deg, var(--brand-dark), var(--brand-primary) 58%, var(--brand-medium));
            color: CanvasText;
        }

        #bills-page .bills-heading {
            background: var(--bills-heading-background);
            box-shadow: 0 20px 45px color-mix(in srgb, var(--brand-dark) 22%, transparent);
        }

        #bills-page .bills-tab {
            color: GrayText;
            border: 1px solid transparent;
            transition: color .18s ease, background .18s ease, border-color .18s ease, transform .18s ease;
        }

        #bills-page .bills-tab:hover {
            color: var(--brand-primary);
            background: var(--bills-wash);
        }

        #bills-page .bills-tab[aria-current="page"] {
            color: var(--restaurant-color-hex-fff);
            background: var(--brand-primary);
            border-color: var(--brand-primary);
            box-shadow: 0 5px 14px color-mix(in srgb, var(--brand-primary) 22%, transparent);
        }

        #bills-page .bill-row {
            transition: background-color .16s ease;
        }

        #bills-page .bill-row:hover {
            background: var(--bills-wash);
        }

        html[data-theme="dark"] #bills-page .bills-table tbody {
            --bills-line: color-mix(in srgb, var(--brand-medium) 32%, var(--brand-dark));
            background-color: color-mix(in srgb, var(--brand-dark) 72%, var(--brand-dark));
            color: var(--theme-text);
        }

        html[data-theme="dark"] #bills-page .bills-table .bill-row:hover {
            background-color: color-mix(in srgb, var(--brand-medium) 38%, var(--brand-dark));
        }


        #bills-page .bills-primary-text {
            color: CanvasText;
        }

        #bills-page .bills-muted-text {
            color: GrayText;
        }

        #bills-page .bills-border {
            border-color: var(--bills-line);
        }

        #bills-page .bill-search {
            color: var(--theme-text);
        }

        #bills-page .bill-search::placeholder {
            color: var(--theme-text-muted);
        }

        #bills-page .bill-search:focus {
            border-color: var(--brand-accent);
            outline: 3px solid color-mix(in srgb, var(--brand-accent) 20%, transparent);
        }

        #bills-page .bills-control {
            background-color: var(--theme-surface-raised);
            color: var(--theme-text);
            color-scheme: light;
        }

        html[data-theme="dark"] #bills-page .bills-control {
            color-scheme: dark;
        }

        #bills-page .bills-control:focus-visible {
            border-color: var(--brand-accent);
            outline: 3px solid color-mix(in srgb, var(--brand-accent) 24%, transparent);
            outline-offset: 1px;
        }

        #bills-page .bills-field-error {
            color: var(--color-rose-600);
        }

        html[data-theme="dark"] #bills-page .bills-field-error {
            color: var(--color-rose-500);
        }

        #bills-page .bills-edit-action {
            color: var(--color-emerald-800);
        }

        html[data-theme="dark"] #bills-page .bills-edit-action {
            color: var(--color-emerald-300);
        }

        #bills-page .bills-edit-action:hover {
            color: var(--restaurant-color-hex-fff);
        }

        #bills-page .bills-success {
            border-color: color-mix(in srgb, var(--color-emerald-700) 28%, Canvas);
            background: color-mix(in srgb, var(--color-emerald-500) 10%, Canvas);
            color: color-mix(in srgb, var(--color-emerald-800) 82%, CanvasText);
        }

        html[data-theme="dark"] #bills-page .bills-success {
            border-color: color-mix(in srgb, var(--color-emerald-400) 30%, var(--brand-dark));
            background: color-mix(in srgb, var(--color-emerald-900) 42%, var(--brand-dark));
            color: var(--color-emerald-200);
        }

        #bills-page .bills-icon {
            background: color-mix(in srgb, var(--icon-tone) 20%, Canvas);
            color: var(--icon-tone);
        }

        #bills-page .bills-icon[data-tone="active"],
        #bills-page .bill-status[data-status="active"] {
            --icon-tone: var(--color-emerald-600);
            --status-tone: var(--color-emerald-600);
        }

        #bills-page .bills-icon[data-tone="payment"],
        #bills-page .bill-status[data-status="payment"] {
            --icon-tone: var(--color-amber-600);
            --status-tone: var(--color-amber-600);
        }

        #bills-page .bills-icon[data-tone="completed"],
        #bills-page .bill-status[data-status="completed"] {
            --icon-tone: var(--color-slate-600);
            --status-tone: var(--color-slate-600);
        }

        #bills-page .bill-status {
            border: 1px solid color-mix(in srgb, var(--status-tone) 34%, Canvas);
            background: color-mix(in srgb, var(--status-tone) 12%, Canvas);
            color: color-mix(in srgb, var(--status-tone) 72%, CanvasText);
        }

        #bills-page .bills-table thead {
            background: var(--bills-heading-background);
            color: var(--restaurant-color-hex-fff);
        }

        #bills-page .bills-table td,
        #bills-page .bills-table th {
            border-bottom: 1px solid var(--bills-line);
        }

        #bills-page .bills-pagination-link {
            display: inline-flex;
            min-width: 2.75rem;
            min-height: 2.75rem;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            border: 1px solid var(--bills-line);
            border-radius: .9rem;
            background: Canvas;
            padding: .625rem .875rem;
            color: CanvasText;
            font-size: .8125rem;
            font-weight: 800;
            line-height: 1;
            text-decoration: none;
            transition: color .18s ease, background-color .18s ease, border-color .18s ease, transform .18s ease;
        }

        #bills-page .bills-pagination-link:not([aria-disabled="true"]):hover {
            transform: translateY(-1px);
            border-color: color-mix(in srgb, var(--brand-primary) 42%, var(--bills-line));
            background: var(--bills-wash);
            color: var(--brand-primary);
        }

        #bills-page .bills-pagination-link:focus-visible {
            outline: 3px solid color-mix(in srgb, var(--brand-accent) 38%, transparent);
            outline-offset: 2px;
        }

        #bills-page .bills-pagination-link[aria-current="page"] {
            border-color: var(--brand-primary);
            background: var(--brand-primary);
            color: var(--restaurant-color-hex-fff);
            box-shadow: 0 5px 14px color-mix(in srgb, var(--brand-primary) 22%, transparent);
        }

        #bills-page .bills-pagination-link[aria-disabled="true"] {
            cursor: not-allowed;
            background: var(--bills-wash);
            color: GrayText;
            opacity: .72;
        }

        #bills-page .bills-page-ellipsis {
            display: inline-flex;
            min-width: 1.75rem;
            min-height: 2.75rem;
            align-items: center;
            justify-content: center;
            color: GrayText;
            font-weight: 800;
        }

        #bills-page .bills-mobile-page {
            min-width: 0;
            color: var(--theme-text-muted);
            font-size: .75rem;
            font-weight: 800;
            text-align: center;
            white-space: nowrap;
        }

        #bills-page [hidden] {
            display: none !important;
        }

        @media (prefers-reduced-motion: reduce) {

            #bills-page *,
            #bills-page *::before,
            #bills-page *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
            }
        }
    </style>

    <main id="bills-page"
        class="mx-auto w-full min-w-0 max-w-7xl space-y-6 overflow-x-clip p-4 pb-32 sm:p-6 sm:pb-10 lg:p-8" dir="rtl">
        @if (session('status'))
            <div role="status" class="bills-success rounded-2xl border px-5 py-4 text-sm font-bold">
                {{ session('status') }}
            </div>
        @endif

        <header
            class="bills-heading relative isolate overflow-hidden rounded-[1.75rem] px-6 py-7 text-white sm:px-8 sm:py-9">
            <div aria-hidden="true"
                class="pointer-events-none absolute -left-10 -top-14 h-52 w-52 rounded-full border-[30px] border-white/[.06]">
            </div>
            <div aria-hidden="true"
                class="pointer-events-none absolute -bottom-28 left-[34%] h-64 w-64 rounded-full border border-white/[.08]">
            </div>
            <div class="relative flex flex-col justify-between gap-7 sm:flex-row sm:items-end">
                <div class="max-w-2xl">
                    <p class="mb-3 flex items-center gap-2 text-xs font-extrabold tracking-wide text-emerald-100">
                        <span class="relative flex h-2.5 w-2.5">
                            <span
                                class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-300 opacity-50"></span>
                            <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-300"></span>
                        </span>
                        نظرة يومية على حركة المطعم
                    </p>
                    <h1 class="text-3xl font-black leading-tight sm:text-4xl">الفواتير</h1>
                    <p class="mt-3 max-w-xl text-sm leading-7 text-emerald-50/80 sm:text-base">
                        تابع الفواتير المفتوحة وطلبات الدفع، وارجع إلى سجل الحسابات المكتملة بسهولة.
                    </p>
                </div>
                <div
                    class="flex items-center gap-4 self-start border-t border-white/20 pt-4 sm:self-auto sm:border-r sm:border-t-0 sm:pr-6 sm:pt-0">
                    <span
                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border border-white/20 bg-white/10 text-amber-200"
                        aria-hidden="true">
                        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7 3.75h7l4 4V20a.75.75 0 0 1-.75.75h-10.5A.75.75 0 0 1 6 20V4.5a.75.75 0 0 1 .75-.75Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 4v4h4M9 13h6M9 16.5h6" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-xs font-bold text-emerald-100">إجمالي الفواتير في الفترة المحددة</p>
                        <p class="mt-0.5 text-5xl font-black leading-none tracking-tight tabular-nums sm:text-6xl"
                            aria-label="{{ $billStats->total_count }} فاتورة">{{ $billStats->total_count }}</p>
                    </div>
                </div>
            </div>
        </header>

        <section aria-label="ملخص الفواتير" class="grid gap-3 sm:grid-cols-3">
            <article class="bills-stat bg-white rounded-2xl border bills-border p-4 shadow-sm sm:p-5 bg-white">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="bills-muted-text text-xs font-bold">فواتير نشطة</p>
                        <p class="bills-primary-text mt-2 text-2xl font-black tabular-nums">{{ $billStats->active_count }}
                        </p>
                    </div>
                    <span class="bills-icon flex h-10 w-10 items-center justify-center rounded-xl" data-tone="active"
                        aria-hidden="true">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="8.5" />
                            <path stroke-linecap="round" d="M12 7v5l3 2" />
                        </svg>
                    </span>
                </div>
                <p class="bills-muted-text mt-3 text-xs">فواتير نشطة ضمن الفترة المحددة</p>
            </article>
            <article class="bg-white bills-stat bg-white rounded-2xl border bills-border p-4 shadow-sm sm:p-5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="bills-muted-text text-xs font-bold">بانتظار الدفع</p>
                        <p class="bills-primary-text mt-2 text-2xl font-black tabular-nums">{{ $billStats->payment_count }}
                        </p>
                    </div>
                    <span class="bills-icon flex h-10 w-10 items-center justify-center rounded-xl" data-tone="payment"
                        aria-hidden="true">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <rect x="3.5" y="5.5" width="17" height="13" rx="2" />
                            <path stroke-linecap="round" d="M3.5 10h17M7 14.5h3" />
                        </svg>
                    </span>
                </div>
                <p class="bills-muted-text mt-3 text-xs">طلبات دفع ضمن الفترة المحددة</p>
            </article>
            <article class="bg-white bills-stat bg-white rounded-2xl border bills-border p-4 shadow-sm sm:p-5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="bills-muted-text text-xs font-bold">إيراد الفواتير المكتملة</p>
                        <p class="bills-primary-text mt-2 text-2xl font-black tabular-nums">
                            {{ number_format((float) $billStats->total_revenue, 0) }} <span
                                class="bills-muted-text text-sm font-bold">ل.س</span></p>
                    </div>
                    <span class="bills-icon flex h-10 w-10 items-center justify-center rounded-xl" data-tone="completed"
                        aria-hidden="true">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 17.5 9 12l3.5 3.5L20 7" />
                            <path stroke-linecap="round" d="M15.5 7H20v4.5" />
                        </svg>
                    </span>
                </div>
                <p class="bills-muted-text mt-3 text-xs">{{ $billStats->completed_count }} فاتورة مكتملة ضمن الفترة المحددة
                </p>
            </article>
        </section>

        <section class="bills-panel bg-white overflow-hidden rounded-3xl border bills-border bg-white"
            style="box-shadow: var(--bills-shadow)">
            <div
                class="bills-border flex flex-col gap-4 border-b px-4 py-5 sm:px-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-[11px] font-extrabold tracking-wide text-[var(--brand-primary)]">سجل الحسابات</p>
                    <h2 class="bills-primary-text mt-1 text-lg font-black">كل الفواتير</h2>
                </div>
                <form method="GET" action="{{ route('admin.bills.index') }}" role="search"
                    class="relative block w-full lg:max-w-2xl">
                    <input type="hidden" name="status" value="{{ $statusFilter }}">
                    <input type="hidden" name="date_filter" value="1">
                    <div class="grid gap-3 sm:grid-cols-2">
                        <label class="relative block">
                            <span class="sr-only">ابحث برقم الفاتورة أو الطاولة</span>
                            <svg class="bills-muted-text pointer-events-none absolute right-3 top-1/4 sm:top-1/2 h-4 w-4 -translate-y-1/2"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                aria-hidden="true">
                                <circle cx="10.8" cy="10.8" r="6.8" />
                                <path stroke-linecap="round" d="m16 16 4.5 4.5" />
                            </svg>
                            <input type="search" name="search" value="{{ $search }}"
                                aria-label="ابحث برقم الفاتورة أو الطاولة"
                                class="bills-control bill-search bills-border w-full rounded-xl border py-2.5 pr-10 pl-4 text-sm transition"
                                placeholder="ابحث برقم الفاتورة أو الطاولة">
                        </label>
                        <fieldset class="min-w-0">
                            <legend class="bills-muted-text mb-1.5 text-xs font-bold">تاريخ إنشاء الفاتورة</legend>
                            <div class="grid grid-cols-3 gap-2">
                                <label class="min-w-0">
                                    <span class="bills-muted-text mb-1 block text-[11px] font-semibold">السنة</span>
                                    <input id="bills-year" type="number" name="year" value="{{ $year }}"
                                        min="1900" max="2100" step="1" inputmode="numeric"
                                        aria-label="سنة إنشاء الفاتورة"
                                        @if ($errors->has('year')) aria-invalid="true" aria-describedby="bills-year-error" @endif
                                        class="bills-control bills-border min-h-11 w-full min-w-0 rounded-xl border px-2 text-center text-sm tabular-nums">
                                    @error('year')
                                        <span id="bills-year-error" class="bills-field-error mt-1 block text-[11px] leading-4"
                                            role="alert">{{ $message }}</span>
                                    @enderror
                                </label>
                                <label class="min-w-0">
                                    <span class="bills-muted-text mb-1 block text-[11px] font-semibold">الشهر</span>
                                    <input id="bills-month" type="number" name="month" value="{{ $month }}"
                                        min="1" max="12" step="1" inputmode="numeric"
                                        aria-label="شهر إنشاء الفاتورة"
                                        @if ($errors->has('month')) aria-invalid="true" aria-describedby="bills-month-error" @endif
                                        class="bills-control bills-border min-h-11 w-full min-w-0 rounded-xl border px-2 text-center text-sm tabular-nums">
                                    @error('month')
                                        <span id="bills-month-error"
                                            class="bills-field-error mt-1 block text-[11px] leading-4"
                                            role="alert">{{ $message }}</span>
                                    @enderror
                                </label>
                                <label class="min-w-0">
                                    <span class="bills-muted-text mb-1 block text-[11px] font-semibold">اليوم</span>
                                    <input id="bills-day" type="number" name="day" value="{{ $day }}"
                                        min="1" max="31" step="1" inputmode="numeric"
                                        aria-label="يوم إنشاء الفاتورة"
                                        @if ($errors->has('day')) aria-invalid="true" aria-describedby="bills-day-error" @endif
                                        class="bills-control bills-border min-h-11 w-full min-w-0 rounded-xl border px-2 text-center text-sm tabular-nums">
                                    @error('day')
                                        <span id="bills-day-error" class="bills-field-error mt-1 block text-[11px] leading-4"
                                            role="alert">{{ $message }}</span>
                                    @enderror
                                </label>
                            </div>
                        </fieldset>
                    </div>
                    <div class="mt-2 flex flex-wrap items-center gap-2">
                        <label for="bills-per-page" class="bills-muted-text shrink-0 text-xs font-bold">عدد الفواتير في
                            الصفحة:</label>
                        <select id="bills-per-page" name="per_page"
                            class="bills-control bills-border min-h-10 rounded-xl border px-3 text-sm font-bold transition">
                            @foreach ([10, 15, 25, 50, 100] as $pageSize)
                                <option value="{{ $pageSize }}" @selected($perPage === $pageSize)>{{ $pageSize }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit"
                            class="min-h-10 rounded-xl bg-[var(--brand-primary)] px-4 text-sm font-bold text-white transition hover:-translate-y-px hover:opacity-90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--brand-accent)]">
                            تطبيق
                        </button>
                    </div>
                </form>
            </div>

            <div class="px-4 pt-4 sm:px-6 bg-white">
                <div class="grid grid-cols-2 gap-2 pb-1 sm:flex sm:flex-wrap" role="group" aria-label="تصفية الفواتير">
                    <a href="{{ route('admin.bills.index', array_merge($preservedFilters, ['status' => 'all'])) }}"
                        class="bills-tab inline-flex w-full items-center justify-center rounded-xl px-3 py-2.5 text-center text-sm font-extrabold sm:w-auto sm:px-4"
                        aria-current="{{ $statusFilter === 'all' ? 'page' : 'false' }}">
                        الكل <span class="mr-1 text-xs opacity-75"
                            data-filter-count="all">{{ $billStats->total_count }}</span>
                    </a>
                    <a href="{{ route('admin.bills.index', array_merge($preservedFilters, ['status' => 'active'])) }}"
                        class="bills-tab inline-flex w-full items-center justify-center rounded-xl px-3 py-2.5 text-center text-sm font-extrabold sm:w-auto sm:px-4"
                        aria-current="{{ $statusFilter === 'active' ? 'page' : 'false' }}">
                        نشطة <span class="mr-1 text-xs opacity-75"
                            data-filter-count="active">{{ $billStats->active_count }}</span>
                    </a>
                    <a href="{{ route('admin.bills.index', array_merge($preservedFilters, ['status' => 'payment'])) }}"
                        class="bills-tab inline-flex w-full items-center justify-center rounded-xl px-3 py-2.5 text-center text-sm font-extrabold sm:w-auto sm:px-4"
                        aria-current="{{ $statusFilter === 'payment' ? 'page' : 'false' }}">
                        بانتظار الدفع <span class="mr-1 text-xs opacity-75"
                            data-filter-count="payment">{{ $billStats->payment_count }}</span>
                    </a>
                    <a href="{{ route('admin.bills.index', array_merge($preservedFilters, ['status' => 'completed'])) }}"
                        class="bills-tab inline-flex w-full items-center justify-center rounded-xl px-3 py-2.5 text-center text-sm font-extrabold sm:w-auto sm:px-4"
                        aria-current="{{ $statusFilter === 'completed' ? 'page' : 'false' }}">
                        مكتملة <span class="mr-1 text-xs opacity-75"
                            data-filter-count="completed">{{ $billStats->completed_count }}</span>
                    </a>
                </div>
            </div>

            <div class="bills-table-wrap mt-3 hidden overflow-x-auto md:block">
                <table class="bills-table w-full min-w-[700px] border-collapse text-right">
                    <thead class="text-xs font-extrabold">
                        <tr>
                            <th scope="col" class="px-6 py-4">رقم الفاتورة</th>
                            <th scope="col" class="px-4 py-4">الطاولة</th>
                            <th scope="col" class="px-4 py-4">تاريخ البدء</th>
                            <th scope="col" class="px-4 py-4">المبلغ</th>
                            <th scope="col" class="px-6 py-4">الحالة</th>
                            <th scope="col" class="px-6 py-4">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse ($bills as $bill)
                            @php
                                $statusLabels = [
                                    'active' => 'نشطة',
                                    'payment' => 'بانتظار الدفع',
                                    'completed' => 'مكتملة',
                                ];
                                $billStatus = $bill->status;
                                $startedAt = $bill->start_time
                                    ? \Illuminate\Support\Carbon::parse($bill->start_time)
                                    : null;
                            @endphp
                            <tr class="bill-row bg-white" data-bill-row data-bill-id="{{ $bill->id }}"
                                data-status="{{ $billStatus }}"
                                data-search="{{ strtolower(($bill->number ?? '') . ' ' . ($bill->table_id ?? '')) }}">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span class="bills-primary-text font-extrabold">#{{ $bill->number }}</span>
                                    <span class="bills-muted-text mt-1 block text-[11px]">رمز {{ $bill->code }}</span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-4">
                                    <span
                                        class="bills-primary-text font-bold">{{ $bill->table_id ? 'طاولة ' . $bill->table_id : '—' }}</span>
                                </td>
                                <td class="bills-muted-text whitespace-nowrap px-4 py-4">
                                    @if ($startedAt)
                                        <time
                                            datetime="{{ $startedAt->toIso8601String() }}">{{ $startedAt->format('Y/m/d · H:i') }}</time>
                                    @else
                                        <span>غير محدد</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-4 py-4">
                                    <span
                                        class="bills-primary-text font-black tabular-nums">{{ number_format((float) ($bill->total_price ?? 0), 0) }}</span>
                                    <span class="bills-muted-text text-xs font-semibold">ل.س</span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span
                                        class="bill-status inline-flex items-center gap-2 rounded-lg px-2.5 py-1.5 text-xs font-extrabold"
                                        data-status="{{ in_array($billStatus, ['active', 'payment', 'completed'], true) ? $billStatus : 'completed' }}">
                                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                        {{ $statusLabels[$billStatus] ?? 'غير معروفة' }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.bills.show', $bill) }}"
                                            class="bills-edit-action inline-flex min-h-10 items-center gap-2 rounded-xl border border-emerald-700/20 bg-emerald-700/10 px-3.5 py-2 text-xs font-extrabold transition hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-700">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.25 12s3.5-6 9.75-6 9.75 6 9.75 6-3.5 6-9.75 6-9.75-6-9.75-6Z" />
                                                <circle cx="12" cy="12" r="2.5" />
                                            </svg>
                                            التفاصيل
                                        </a>
                                        @if ($billStatus === 'active')
                                            <a href="{{ route('admin.bills.edit', $bill) }}"
                                                class="bills-edit-action inline-flex min-h-10 items-center gap-2 rounded-xl border border-emerald-700/20 bg-emerald-700/10 px-3.5 py-2 text-xs font-extrabold transition hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-700">
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m16.862 4.487 2.651 2.651M4 20l4.2-.84L19.7 7.66a1.875 1.875 0 0 0-2.65-2.65L5.55 16.51 4 20Z" />
                                                </svg>
                                                تعديل
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <span class="bills-icon mx-auto flex h-14 w-14 items-center justify-center rounded-2xl"
                                        data-tone="active" aria-hidden="true">
                                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M7 3.75h7l4 4V20a.75.75 0 0 1-.75.75h-10.5A.75.75 0 0 1 6 20V4.5a.75.75 0 0 1 .75-.75Z" />
                                            <path stroke-linecap="round" d="M14 4v4h4M9 13h6M9 16.5h4" />
                                        </svg>
                                    </span>
                                    <p class="bills-primary-text mt-4 font-extrabold">
                                        {{ (int) $billStats->total_count === 0 ? 'لا توجد فواتير حتى الآن' : 'لا توجد نتائج مطابقة' }}
                                    </p>
                                    <p class="bills-muted-text mt-1 text-sm">
                                        {{ (int) $billStats->total_count === 0 ? 'ستظهر الفواتير هنا عند بدء استقبال الطلبات.' : 'جرّب تغيير الحالة أو تعديل كلمات البحث.' }}
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="bills-mobile-list mt-3 space-y-3 px-4 pb-4 md:hidden">
                @forelse ($bills as $bill)
                    @php
                        $billStatus = $bill->status;
                        $startedAt = $bill->start_time ? \Illuminate\Support\Carbon::parse($bill->start_time) : null;
                        $statusLabel =
                            [
                                'active' => 'نشطة',
                                'payment' => 'بانتظار الدفع',
                                'completed' => 'مكتملة',
                            ][$billStatus] ?? 'غير معروفة';
                    @endphp
                    <article class="bill-row bg-white-50 bills-border rounded-2xl border p-4" data-bill-row
                        data-bill-id="{{ $bill->id }}" data-status="{{ $billStatus }}"
                        data-search="{{ strtolower(($bill->number ?? '') . ' ' . ($bill->table_id ?? '')) }}">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h3 class="bills-primary-text truncate text-base font-black">#{{ $bill->number }}</h3>
                                <p class="bills-muted-text mt-1 truncate text-xs">رمز {{ $bill->code }}</p>
                            </div>
                            <span
                                class="bill-status inline-flex shrink-0 items-center gap-2 rounded-lg px-2.5 py-1.5 text-xs font-extrabold"
                                data-status="{{ in_array($billStatus, ['active', 'payment', 'completed'], true) ? $billStatus : 'completed' }}">
                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                {{ $statusLabel }}
                            </span>
                        </div>
                        <div class="bills-border mt-4 grid grid-cols-2 gap-x-4 gap-y-3 border-t pt-3">
                            <div>
                                <p class="bills-muted-text text-[11px] font-bold">الطاولة</p>
                                <p class="bills-primary-text mt-1 text-sm font-bold">
                                    {{ $bill->table_id ? 'طاولة ' . $bill->table_id : '—' }}</p>
                            </div>
                            <div>
                                <p class="bills-muted-text text-[11px] font-bold">المبلغ</p>
                                <p class="bills-primary-text mt-1 text-sm font-black tabular-nums">
                                    {{ number_format((float) ($bill->total_price ?? 0), 0) }} <span
                                        class="bills-muted-text text-xs font-semibold">ل.س</span></p>
                            </div>
                            <div class="col-span-2">
                                <p class="bills-muted-text text-[11px] font-bold">تاريخ البدء</p>
                                <p class="bills-primary-text mt-1 text-sm">
                                    @if ($startedAt)
                                        <time
                                            datetime="{{ $startedAt->toIso8601String() }}">{{ $startedAt->format('Y/m/d · H:i') }}</time>
                                    @else
                                        غير محدد
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-2">
                            <a href="{{ route('admin.bills.show', $bill) }}"
                                class="bills-edit-action inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-emerald-700/20 bg-emerald-700/10 px-3 py-2.5 text-sm font-extrabold transition hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-700">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 12s3.5-6 9.75-6 9.75 6 9.75 6-3.5 6-9.75 6-9.75-6-9.75-6Z" />
                                    <circle cx="12" cy="12" r="2.5" />
                                </svg>
                                التفاصيل
                            </a>
                            @if ($billStatus === 'active')
                                <a href="{{ route('admin.bills.edit', $bill) }}"
                                    class="bills-edit-action inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-emerald-700/20 bg-emerald-700/10 px-3 py-2.5 text-sm font-extrabold transition hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-700">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 2.651 2.651M4 20l4.2-.84L19.7 7.66a1.875 1.875 0 0 0-2.65-2.65L5.55 16.51 4 20Z" />
                                    </svg>
                                    تعديل الطلبات
                                </a>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="py-10 text-center">
                        <p class="bills-primary-text font-extrabold">
                            {{ (int) $billStats->total_count === 0 ? 'لا توجد فواتير حتى الآن' : 'لا توجد نتائج مطابقة' }}
                        </p>
                        <p class="bills-muted-text mt-1 text-sm">
                            {{ (int) $billStats->total_count === 0 ? 'ستظهر الفواتير هنا عند بدء استقبال الطلبات.' : 'جرّب تغيير الحالة أو تعديل كلمات البحث.' }}
                        </p>
                    </div>
                @endforelse
            </div>
            @if ($bills->total() > 0)
                <div
                    class="bills-border flex flex-col gap-4 border-t bg-white px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-center gap-3">
                        <span
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[var(--brand-primary)] text-white"
                            aria-hidden="true">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7 3.75h7l4 4V20a.75.75 0 0 1-.75.75h-10.5A.75.75 0 0 1 6 20V4.5a.75.75 0 0 1 .75-.75Z" />
                                <path stroke-linecap="round" d="M14 4v4h4M9 13h6M9 16.5h4" />
                            </svg>
                        </span>
                        <div>
                            <p class="bills-primary-text text-sm font-extrabold">
                                عرض <span class="tabular-nums">{{ $bills->firstItem() }}–{{ $bills->lastItem() }}</span>
                                <span class="bills-muted-text font-semibold">من</span>
                                <span class="tabular-nums">{{ $bills->total() }}</span> فاتورة
                            </p>
                            <p class="bills-muted-text mt-0.5 text-xs">
                                صفحة {{ $bills->currentPage() }} من {{ $bills->lastPage() }}
                            </p>
                        </div>
                    </div>
                    @if ($bills->hasPages())
                        @php
                            $pageWindowStart = max(1, $bills->currentPage() - 1);
                            $pageWindowEnd = min($bills->lastPage(), $bills->currentPage() + 1);
                        @endphp
                        <nav class="flex min-w-0 items-center justify-between gap-2 sm:justify-end"
                            aria-label="التنقل بين صفحات الفواتير">
                            @if ($bills->onFirstPage())
                                <span class="bills-pagination-link" role="link" aria-label="الصفحة السابقة"
                                    aria-disabled="true">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6" />
                                    </svg>
                                    <span class="hidden sm:inline">السابق</span>
                                </span>
                            @else
                                <a href="{{ $bills->previousPageUrl() }}" class="bills-pagination-link" rel="prev"
                                    aria-label="الصفحة السابقة">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6" />
                                    </svg>
                                    <span class="hidden sm:inline">السابق</span>
                                </a>
                            @endif

                            <span class="bills-mobile-page md:hidden" aria-current="page">
                                صفحة {{ $bills->currentPage() }} من {{ $bills->lastPage() }}
                            </span>
                            <div class="hidden items-center gap-1 md:flex" aria-label="رقم الصفحة">
                                @if ($pageWindowStart > 1)
                                    <a href="{{ $bills->url(1) }}" class="bills-pagination-link"
                                        aria-label="الصفحة 1">1</a>
                                    @if ($pageWindowStart > 2)
                                        <span class="bills-page-ellipsis" aria-hidden="true">…</span>
                                    @endif
                                @endif
                                @foreach ($bills->getUrlRange($pageWindowStart, $pageWindowEnd) as $page => $url)
                                    @if ($page === $bills->currentPage())
                                        <span class="bills-pagination-link" aria-current="page"
                                            aria-label="الصفحة {{ $page }}">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}" class="bills-pagination-link"
                                            aria-label="الصفحة {{ $page }}">{{ $page }}</a>
                                    @endif
                                @endforeach
                                @if ($pageWindowEnd < $bills->lastPage())
                                    @if ($pageWindowEnd < $bills->lastPage() - 1)
                                        <span class="bills-page-ellipsis" aria-hidden="true">…</span>
                                    @endif
                                    <a href="{{ $bills->url($bills->lastPage()) }}" class="bills-pagination-link"
                                        aria-label="الصفحة {{ $bills->lastPage() }}">{{ $bills->lastPage() }}</a>
                                @endif
                            </div>

                            @if ($bills->hasMorePages())
                                <a href="{{ $bills->nextPageUrl() }}" class="bills-pagination-link" rel="next"
                                    aria-label="الصفحة التالية">
                                    <span class="hidden sm:inline">التالي</span>
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6" />
                                    </svg>
                                </a>
                            @else
                                <span class="bills-pagination-link" role="link" aria-label="الصفحة التالية"
                                    aria-disabled="true">
                                    <span class="hidden sm:inline">التالي</span>
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6" />
                                    </svg>
                                </span>
                            @endif
                        </nav>
                    @endif
                </div>
            @endif
        </section>
    </main>
@endsection
