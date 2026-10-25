@extends('admin.layouts.main-layout')

@section('title', 'تفاصيل الفاتورة #' . $bill->number)

@section('content')
    @php
        $statusLabels = [
            'active' => 'نشطة',
            'payment' => 'بانتظار الدفع',
            'completed' => 'مكتملة',
        ];
        $statusClasses = [
            'active' => 'bill-show-status-active',
            'payment' => 'bill-show-status-payment',
            'completed' => 'bill-show-status-completed',
        ];
        $orderStatusLabels = [
            '0' => 'قيد التأكيد',
            '1' => 'في المطبخ',
            '2' => 'على الطاولة',
        ];
        $orderStatusClasses = [
            '0' => 'bill-show-order-pending',
            '1' => 'bill-show-order-kitchen',
            '2' => 'bill-show-order-served',
        ];
        $createdAt = $bill->created_at ? \Illuminate\Support\Carbon::parse($bill->created_at) : null;
        $startedAt = $bill->start_time ? \Illuminate\Support\Carbon::parse($bill->start_time) : null;
        $endedAt = $bill->end_time ? \Illuminate\Support\Carbon::parse($bill->end_time) : null;
        $storedTotal = $bill->total_price;
        $services = is_array($bill->services)
            ? array_values(
                array_filter(
                    $bill->services,
                    fn($service) => is_array($service) &&
                        is_string($service['name'] ?? null) &&
                        trim($service['name']) !== '' &&
                        is_numeric($service['price'] ?? null) &&
                        is_finite((float) $service['price']),
                ),
            )
            : [];
        $services = array_map(function ($service) {
            $currency = $service['currency'] ?? null;
            $service['currency'] = is_string($currency) && trim($currency) !== '' ? trim($currency) : 'ل.س';

            return $service;
        }, $services);
        $servicesTotals = collect($services)
            ->groupBy('currency')
            ->map(fn($currencyServices) => $currencyServices->sum(fn($service) => (float) $service['price']));
        $servicesUseBillCurrency = collect($services)->every(fn($service) => $service['currency'] === 'ل.س');
        $servicesTotal = $servicesTotals->get('ل.س', 0);
        $ordersTotal = $bill->orders->sum(fn($order) => (float) ($order->total_price ?? 0));
        $hasTotalDifference =
            $storedTotal !== null &&
            $servicesUseBillCurrency &&
            (int) round((float) $storedTotal * 100) !== (int) round(($ordersTotal + $servicesTotal) * 100);
        $quantityTotal = $bill->orders->sum(fn($order) => (int) ($order->amount ?? 0));
    @endphp

    <style>
        #bill-show-page {
            --bill-show-line: color-mix(in srgb, CanvasText 13%, Canvas);
            --bill-show-surface: Canvas;
            --bill-show-wash: color-mix(in srgb, CanvasText 4%, Canvas);
            --bill-show-text: CanvasText;
            --bill-show-muted: GrayText;
            color: var(--bill-show-text);
        }

        html[data-theme="dark"] #bill-show-page {
            --bill-show-line: color-mix(in srgb, var(--brand-medium) 38%, var(--brand-dark));
            --bill-show-surface: color-mix(in srgb, var(--brand-dark) 82%, black);
            --bill-show-wash: color-mix(in srgb, var(--brand-medium) 17%, var(--brand-dark));
            --bill-show-text: var(--theme-text);
            --bill-show-muted: color-mix(in srgb, var(--theme-text) 66%, transparent);
        }

        #bill-show-page .bill-show-surface {
            background: var(--bill-show-surface);
        }

        #bill-show-page .bill-show-wash {
            background: var(--bill-show-wash);
        }

        #bill-show-page .bill-show-line {
            border-color: var(--bill-show-line);
        }

        #bill-show-page .bill-show-muted {
            color: var(--bill-show-muted);
        }

        #bill-show-page .bill-show-hero {
            background:
                radial-gradient(ellipse at 12% 8%, color-mix(in srgb, var(--brand-accent) 26%, transparent), transparent 34%),
                linear-gradient(118deg, var(--brand-dark), var(--brand-primary) 62%, var(--brand-medium));
            box-shadow: 0 24px 58px color-mix(in srgb, var(--brand-dark) 26%, transparent);
        }

        #bill-show-page .bill-show-ticket {
            border: 1px solid color-mix(in srgb, white 22%, transparent);
            background: color-mix(in srgb, white 8%, transparent);
            box-shadow: inset 0 1px color-mix(in srgb, white 12%, transparent);
            backdrop-filter: blur(6px);
        }

        #bill-show-page .bill-show-status,
        #bill-show-page .bill-show-order-status {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            width: fit-content;
            border: 1px solid;
            border-radius: .75rem;
            padding: .35rem .65rem;
            font-size: .72rem;
            font-weight: 800;
            line-height: 1.2;
            white-space: nowrap;
        }

        #bill-show-page .bill-show-status-active {
            color: var(--color-emerald-800);
            border-color: color-mix(in srgb, var(--color-emerald-600) 36%, Canvas);
            background: color-mix(in srgb, var(--color-emerald-500) 12%, Canvas);
        }

        #bill-show-page .bill-show-status-payment {
            color: var(--color-amber-800);
            border-color: color-mix(in srgb, var(--color-amber-600) 36%, Canvas);
            background: color-mix(in srgb, var(--color-amber-500) 12%, Canvas);
        }

        #bill-show-page .bill-show-status-completed {
            color: var(--color-slate-700);
            border-color: color-mix(in srgb, var(--color-slate-500) 36%, Canvas);
            background: color-mix(in srgb, var(--color-slate-500) 10%, Canvas);
        }

        #bill-show-page .bill-show-status-unknown,
        #bill-show-page .bill-show-order-unknown {
            color: var(--bill-show-muted);
            border-color: var(--bill-show-line);
            background: var(--bill-show-wash);
        }

        #bill-show-page .bill-show-order-pending {
            color: var(--color-amber-800);
            border-color: color-mix(in srgb, var(--color-amber-600) 36%, Canvas);
            background: color-mix(in srgb, var(--color-amber-500) 12%, Canvas);
        }

        #bill-show-page .bill-show-order-kitchen {
            color: var(--color-blue-800);
            border-color: color-mix(in srgb, var(--color-blue-600) 36%, Canvas);
            background: color-mix(in srgb, var(--color-blue-600) 10%, Canvas);
        }

        #bill-show-page .bill-show-order-served {
            color: var(--color-emerald-800);
            border-color: color-mix(in srgb, var(--color-emerald-600) 36%, Canvas);
            background: color-mix(in srgb, var(--color-emerald-600) 10%, Canvas);
        }

        html[data-theme="dark"] #bill-show-page .bill-show-status-active,
        html[data-theme="dark"] #bill-show-page .bill-show-order-served {
            color: var(--color-emerald-200);
            border-color: color-mix(in srgb, var(--color-emerald-400) 34%, var(--brand-dark));
            background: color-mix(in srgb, var(--color-emerald-900) 52%, var(--brand-dark));
        }

        html[data-theme="dark"] #bill-show-page .bill-show-status-payment,
        html[data-theme="dark"] #bill-show-page .bill-show-order-pending {
            color: var(--color-amber-200);
            border-color: color-mix(in srgb, var(--color-amber-400) 34%, var(--brand-dark));
            background: color-mix(in srgb, var(--color-amber-950) 52%, var(--brand-dark));
        }

        html[data-theme="dark"] #bill-show-page .bill-show-status-completed {
            color: var(--color-slate-200);
            border-color: color-mix(in srgb, var(--color-slate-400) 36%, var(--brand-dark));
            background: color-mix(in srgb, var(--color-slate-800) 70%, var(--brand-dark));
        }

        html[data-theme="dark"] #bill-show-page .bill-show-status-unknown,
        html[data-theme="dark"] #bill-show-page .bill-show-order-unknown {
            color: var(--theme-text-muted);
            border-color: var(--theme-border-strong);
            background: var(--theme-surface-raised);
        }

        html[data-theme="dark"] #bill-show-page .bill-show-order-kitchen {
            color: var(--color-blue-200);
            border-color: color-mix(in srgb, var(--color-blue-600) 58%, var(--brand-dark));
            background: color-mix(in srgb, var(--color-blue-800) 52%, var(--brand-dark));
        }

        #bill-show-page .bill-show-action {
            transition: background-color .18s ease, color .18s ease, border-color .18s ease, transform .18s ease;
        }

        #bill-show-page .bill-show-action:hover {
            transform: translateY(-1px);
        }

        #bill-show-page .bill-show-item {
            transition: background-color .18s ease, border-color .18s ease;
        }

        #bill-show-page .bill-show-placeholder {
            background:
                radial-gradient(circle at 80% 18%, color-mix(in srgb, var(--brand-accent) 20%, transparent), transparent 38%),
                linear-gradient(145deg, color-mix(in srgb, var(--brand-light) 72%, Canvas), var(--bill-show-wash));
            color: var(--brand-primary);
        }

        html[data-theme="dark"] #bill-show-page .bill-show-placeholder {
            color: var(--color-emerald-300);
        }

        @media (max-width: 639px) {
            #bill-show-page .bill-show-hero {
                border-radius: 1.35rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            #bill-show-page *,
            #bill-show-page *::before,
            #bill-show-page *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
            }
        }
    </style>

    <main id="bill-show-page" dir="rtl"
        class="mx-auto w-full max-w-6xl space-y-5 p-4 pb-24 sm:space-y-6 sm:p-6 sm:pb-10 lg:p-8">
        <nav aria-label="مسار التنقل">
            <a href="{{ route('admin.bills.index') }}"
                class="bill-show-action inline-flex min-h-10 items-center gap-2 rounded-xl px-3 text-sm font-bold text-[var(--brand-primary)] hover:bg-[var(--brand-light)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--brand-accent)]">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6" />
                </svg>
                العودة إلى الفواتير
            </a>
        </nav>

        <header
            class="bill-show-hero relative isolate overflow-hidden rounded-[1.8rem] px-5 py-6 text-white sm:px-8 sm:py-9">
            <div aria-hidden="true"
                class="pointer-events-none absolute -left-12 -top-16 h-56 w-56 rounded-full border-[34px] border-white/[.06]">
            </div>
            <div aria-hidden="true"
                class="pointer-events-none absolute bottom-0 left-[34%] h-36 w-36 rounded-full bg-white/[.04] blur-2xl">
            </div>
            <div class="relative grid items-end gap-7 lg:grid-cols-[1fr_auto]">
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span
                            class="rounded-full border border-white/20 bg-white/10 px-3 py-1 text-[11px] font-extrabold tracking-wide text-white/85">سجل
                            الفاتورة</span>
                        <span class="bill-show-status {{ $statusClasses[$bill->status] ?? 'bill-show-status-unknown' }}">
                            <span class="h-1.5 w-1.5 rounded-full bg-current" aria-hidden="true"></span>
                            {{ $statusLabels[$bill->status] ?? 'حالة غير معروفة' }}
                        </span>
                    </div>
                    <p class="mt-5 text-sm font-bold text-emerald-50/75">فاتورة رقم</p>
                    <p class="mt-1 text-xl font-black tracking-tight">#{{ $bill->number ?? '—' }}</p>
                    <p class="mt-3 text-sm text-emerald-50/80">الرمز <span
                            class="font-mono font-bold tracking-wider">{{ $bill->code ?: 'غير متوفر' }}</span></p>
                </div>
                <div class="bill-show-ticket relative min-w-0 rounded-2xl p-5 sm:min-w-64 sm:p-6">
                    <span aria-hidden="true"
                        class="absolute -right-2 top-1/2 h-4 w-4 -translate-y-1/2 rounded-full bg-[var(--surface)]"></span>
                    <span aria-hidden="true"
                        class="absolute -left-2 top-1/2 h-4 w-4 -translate-y-1/2 rounded-full bg-[var(--surface)]"></span>
                    <p class="text-xs font-bold text-emerald-50/75">إجمالي الفاتورة المحفوظ</p>
                    <p class="mt-2 text-3xl font-black tabular-nums sm:text-4xl">
                        {{ $storedTotal === null ? '—' : number_format((float) $storedTotal, 2) }}
                        <span class="text-sm font-bold text-emerald-50/75">ل.س</span>
                    </p>
                    <div
                        class="mt-4 flex flex-wrap gap-x-4 gap-y-2 border-t border-dashed border-white/25 pt-3 text-xs text-emerald-50/75">
                        <span>{{ $bill->orders->count() }} {{ $bill->orders->count() === 1 ? 'طلب' : 'طلبات' }}</span>
                        <span>{{ $quantityTotal }} قطعة</span>
                    </div>
                </div>
            </div>
            <div
                class="relative mt-7 grid gap-3 border-t border-white/15 pt-4 text-sm sm:grid-cols-2 lg:max-w-4xl lg:grid-cols-3">
                <div class="flex items-center gap-2 text-emerald-50/80">
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7 3.75v3m10-3v3M4.5 9h15M5.5 5.5h13A1.5 1.5 0 0 1 20 7v11.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 18.5V7a1.5 1.5 0 0 1 1.5-1.5Z" />
                    </svg>
                    <span>أُنشئت:</span>
                    @if ($createdAt)
                        <time class="font-bold text-white"
                            datetime="{{ $createdAt->toIso8601String() }}">{{ $createdAt->format('Y/m/d · H:i') }}</time>
                    @else
                        <span class="font-bold text-white">غير محدد</span>
                    @endif
                </div>
                <div class="flex items-center gap-2 text-emerald-50/80">
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" aria-hidden="true">
                        <circle cx="12" cy="12" r="9" />
                        <path stroke-linecap="round" d="M12 7v5l3 2" />
                    </svg>
                    <span>بدأت:</span>
                    @if ($startedAt)
                        <time class="font-bold text-white"
                            datetime="{{ $startedAt->toIso8601String() }}">{{ $startedAt->format('Y/m/d · H:i') }}</time>
                    @else
                        <span class="font-bold text-white">غير محدد</span>
                    @endif
                </div>
                <div class="flex items-center gap-2 text-emerald-50/80">
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7 3.75v3m10-3v3M4.5 9h15M5.5 5.5h13A1.5 1.5 0 0 1 20 7v11.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 18.5V7a1.5 1.5 0 0 1 1.5-1.5Z" />
                    </svg>
                    <span>انتهت:</span>
                    @if ($endedAt)
                        <time class="font-bold text-white"
                            datetime="{{ $endedAt->toIso8601String() }}">{{ $endedAt->format('Y/m/d · H:i') }}</time>
                    @else
                        <span class="font-bold text-white">غير محدد</span>
                    @endif
                </div>
            </div>
        </header>

        <div class="grid items-start gap-5 lg:grid-cols-[minmax(0,1fr)_19rem]">
            <section aria-labelledby="bill-orders-title" class="min-w-0 space-y-4">
                <div class="flex flex-wrap items-end justify-between gap-3 px-1">
                    <div>
                        <p class="bill-show-muted text-xs font-bold">محتويات الحساب</p>
                        <h2 id="bill-orders-title" class="mt-1 text-xl font-black sm:text-2xl">تفاصيل الطلبات</h2>
                    </div>
                    @if ($bill->orders->isNotEmpty())
                        <span class="bill-show-muted text-sm font-bold">{{ $bill->orders->count() }} صنف ·
                            {{ $quantityTotal }} قطعة</span>
                    @endif
                </div>

                @forelse ($bill->orders as $order)
                    @php
                        $food = $order->food;
                        $orderStatus = (string) ($order->status ?? '');
                        $orderStatusLabel = $orderStatusLabels[$orderStatus] ?? 'حالة غير معروفة';
                        $expectedTime = $order->expected_time ?: null;
                    @endphp
                    <article
                        class="bill-show-surface bill-show-line bill-show-item grid min-w-0 gap-4 overflow-hidden rounded-2xl border p-4 sm:grid-cols-[5.5rem_minmax(0,1fr)] sm:p-5">
                        <div
                            class="bill-show-placeholder relative flex h-24 w-full shrink-0 items-center justify-center overflow-hidden rounded-xl sm:h-24 sm:w-22">
                            @if ($food && $food->image)
                                <img src="{{ asset('storage/' . ltrim($food->image, '/')) }}"
                                    alt="صورة {{ $food->name ?: 'الطبق' }}" loading="lazy"
                                    class="absolute inset-0 h-full w-full object-cover">
                            @else
                                <svg class="h-10 w-10" viewBox="0 0 48 48" fill="none" aria-hidden="true">
                                    <path d="M8 25h32c-1.7 9.1-7.2 14-16 14S9.7 34.1 8 25Z" fill="currentColor"
                                        opacity=".22" />
                                    <path
                                        d="M6 24h36M10 18c1.5-2.3 3.7-2.3 5.2 0m4-3c1.4-2.2 3.4-2.2 4.8 0m4 3c1.5-2.3 3.7-2.3 5.2 0M12 40h24"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                </svg>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-start justify-between gap-x-4 gap-y-2">
                                <div class="min-w-0">
                                    <h3 class="truncate text-base font-black sm:text-lg">
                                        {{ $food?->name ?: 'طبق غير متوفر' }}</h3>
                                    @if ($food?->categoryFood?->name)
                                        <p class="bill-show-muted mt-1 text-xs font-bold">{{ $food->categoryFood->name }}
                                        </p>
                                    @endif
                                </div>
                                <span
                                    class="bill-show-order-status {{ $orderStatusClasses[$orderStatus] ?? 'bill-show-order-unknown' }}">
                                    {{ $orderStatusLabel }}
                                </span>
                            </div>
                            @if ($food?->description)
                                <p class="bill-show-muted mt-2 line-clamp-2 text-sm leading-6">{{ $food->description }}
                                </p>
                            @endif
                            <div
                                class="bill-show-line mt-4 grid grid-cols-2 gap-x-4 gap-y-3 border-t pt-3 text-xs sm:grid-cols-4">
                                <div>
                                    <p class="bill-show-muted font-bold">الكمية</p>
                                    <p class="mt-1 text-sm font-black tabular-nums">{{ (int) ($order->amount ?? 0) }}</p>
                                </div>
                                <div>
                                    <p class="bill-show-muted font-bold">سعر الوحدة</p>
                                    <p class="mt-1 text-sm font-bold tabular-nums">
                                        {{ $food?->price === null ? '—' : number_format((float) $food->price, 2) }} <span
                                            class="bill-show-muted">ل.س</span>
                                    </p>
                                </div>
                                <div>
                                    <p class="bill-show-muted font-bold">الوقت المتوقع</p>
                                    <p class="mt-1 text-sm font-bold tabular-nums">{{ $expectedTime ?: 'غير محدد' }}</p>
                                </div>
                                <div>
                                    <p class="bill-show-muted font-bold">إجمالي الاصناف</p>
                                    <p class="mt-1 text-sm font-black tabular-nums">
                                        {{ $order->total_price === null ? '—' : number_format((float) $order->total_price, 2) }}
                                        <span class="bill-show-muted">ل.س</span>
                                    </p>
                                </div>
                            </div>
                            @if ($order->note)
                                <p class="bill-show-wash mt-3 rounded-xl px-3 py-2 text-xs leading-5">
                                    <span class="font-extrabold">ملاحظة:</span> {{ $order->note }}
                                </p>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="bill-show-surface bill-show-line rounded-2xl border px-5 py-12 text-center">
                        <span class="bill-show-placeholder mx-auto flex h-14 w-14 items-center justify-center rounded-2xl"
                            aria-hidden="true">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 3.75h8l4 4v12.5a.75.75 0 0 1-.75.75h-10.5A.75.75 0 0 1 6 20.25V4.5a.75.75 0 0 1 .75-.75Z" />
                                <path stroke-linecap="round" d="M14 4v4h4M9 13h6M9 16.5h4" />
                            </svg>
                        </span>
                        <h3 class="mt-4 font-black">لا توجد طلبات في هذه الفاتورة</h3>
                        <p class="bill-show-muted mt-1 text-sm">لم تُسجّل أي بنود مرتبطة بهذا الحساب.</p>
                    </div>
                @endforelse
            </section>

            <aside class="space-y-4">
                <section aria-labelledby="bill-table-title"
                    class="bill-show-surface bill-show-line overflow-hidden rounded-2xl border">
                    <div class="bill-show-wash border-b bill-show-line px-5 py-4">
                        <p class="bill-show-muted text-xs font-bold">مكان الخدمة</p>
                        <h2 id="bill-table-title" class="mt-1 text-lg font-black">الطاولة</h2>
                    </div>
                    <div class="p-5">
                        @if ($bill->table)
                            <div class="flex items-center gap-3">
                                <span
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[var(--brand-light)] text-[var(--brand-primary)]">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.7" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 10h18M5 10v9m14-9v9M7 10l1-5h8l1 5M9 19v2m6-2v2" />
                                    </svg>
                                </span>
                                <div class="min-w-0">
                                    <p class="truncate font-black">{{ $bill->table->name ?: 'طاولة بدون اسم' }}</p>
                                    <p class="bill-show-muted mt-1 text-sm">
                                        {{ $bill->table->number ? 'طاولة ' . $bill->table->number : ($bill->table_id ? 'رقم ' . $bill->table_id : 'رقم غير متوفر') }}
                                    </p>
                                </div>
                            </div>
                            <div class="bill-show-line mt-5 border-t pt-4">
                                <p class="bill-show-muted text-xs font-bold">حالة الطاولة</p>
                                <p class="mt-1 text-sm font-bold">{{ $bill->table->status ? 'متاحة' : 'مشغولة' }}</p>
                            </div>
                        @else
                            <p class="font-bold">غير مرتبطة بطاولة</p>
                            <p class="bill-show-muted mt-1 text-sm leading-6">قد تكون الطاولة غير معيّنة أو حُذفت بعد إنشاء
                                الفاتورة.</p>
                        @endif
                    </div>
                </section>

                <section aria-labelledby="bill-total-title"
                    class="bill-show-surface bill-show-line rounded-2xl border p-5">
                    <p class="bill-show-muted text-xs font-bold">مراجعة المبالغ</p>
                    <h2 id="bill-total-title" class="mt-1 text-lg font-black">ملخص الحساب</h2>
                    <dl class="mt-5 space-y-4">
                        <div class="flex items-start justify-between gap-3">
                            <dt class="bill-show-muted text-sm">مجموع البنود</dt>
                            <dd class="text-sm font-black tabular-nums">{{ number_format($ordersTotal, 2) }} ل.س</dd>
                        </div>
                        @foreach ($services as $service)
                            <div class="flex items-start justify-between gap-3">
                                <dt class="bill-show-muted text-sm">{{ $service['name'] }}</dt>
                                <dd class="text-sm font-black tabular-nums">
                                    {{ number_format((float) $service['price'], 2) }} {{ $service['currency'] }}
                                </dd>
                            </div>
                        @endforeach
                        @foreach ($servicesTotals as $currency => $currencyTotal)
                            <div class="flex items-start justify-between gap-3">
                                <dt class="bill-show-muted text-sm">مجموع الخدمات ({{ $currency }})</dt>
                                <dd class="text-sm font-black tabular-nums">
                                    {{ number_format($currencyTotal, 2) }} {{ $currency }}
                                </dd>
                            </div>
                        @endforeach
                        <div class="flex items-start justify-between gap-3">
                            <dt class="bill-show-muted text-sm">إجمالي الفاتورة</dt>
                            <dd class="text-sm font-black tabular-nums">
                                {{ $storedTotal === null ? '—' : number_format((float) $storedTotal, 2) }} ل.س</dd>
                        </div>
                        <div class="bill-show-line flex items-start justify-between gap-3 border-t pt-4">
                            <dt class="bill-show-muted text-sm">عدد القطع</dt>
                            <dd class="text-sm font-black tabular-nums">{{ $quantityTotal }}</dd>
                        </div>
                    </dl>
                    @if ($hasTotalDifference)
                        <p role="status"
                            class="mt-4 rounded-xl border border-amber-600/25 bg-amber-400 px-3 py-2 text-xs font-bold leading-5 text-red-900">
                            يوجد اختلاف بين الإجمالي المحفوظ ومجموع الطلبات ورسوم الخدمات بعملة ل.س؛ تم عرض كل مبلغ كما هو
                            دون استبدال أحدهما بالآخر.
                        </p>
                    @elseif ($storedTotal !== null && !$servicesUseBillCurrency)
                        <p role="status"
                            class="mt-4 rounded-xl border border-amber-600/25 bg-amber-500/10 px-3 py-2 text-xs font-bold leading-5 text-amber-800 dark:text-amber-200">
                            تعذرت مقارنة الإجمالي تلقائيًا لأن رسوم الخدمات تتضمن عملة غير ل.س.
                        </p>
                    @endif
                </section>
            </aside>
        </div>

        <footer class="flex flex-col gap-3 border-t bill-show-line pt-5 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('admin.bills.index') }}"
                class="bill-show-action inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border bill-show-line px-4 py-2.5 text-sm font-extrabold hover:bg-[var(--bill-show-wash)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--brand-accent)]">
                العودة إلى قائمة الفواتير
            </a>
            @if ($bill->status === 'active')
                <a href="{{ route('admin.bills.edit', $bill) }}"
                    class="bill-show-action inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-[var(--brand-primary)] px-5 py-2.5 text-sm font-extrabold text-white shadow-md hover:bg-[var(--brand-dark)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--brand-accent)]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m16.862 4.487 2.651 2.651M4 20l4.2-.84L19.7 7.66a1.875 1.875 0 0 0-2.65-2.65L5.55 16.51 4 20Z" />
                    </svg>
                    تعديل الطلبات
                </a>
            @endif
        </footer>
    </main>
@endsection
