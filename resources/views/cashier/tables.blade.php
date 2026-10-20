<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#174c3c">
    <title>طاولات المطعم - الكاشير</title>
    @include('components.theme-mode')
    @vite(['resources/css/colors.css', 'resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes cashier-count-zoom {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.3);
            }
        }

        [data-filter-count].cashier-count-zooming {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transform-origin: center;
            animation: cashier-count-zoom 800ms ease-in-out infinite;
        }

        html [data-filter-count].cashier-count-zooming {
            background: #e6810d !important;
        }

        html .hoverclass:hover {
            background: var(--color-emerald-800) !important;
            color: var(--color-gray-400) !important;
        }

        @media (prefers-reduced-motion: reduce) {
            [data-filter-count].cashier-count-zooming {
                animation: none;
                will-change: auto;
            }
        }
    </style>
</head>

<body class="min-h-screen bg-gray-50 text-gray-800">
    <main id="cashier-dashboard-main" class="mx-auto max-w-7xl space-y-6 p-4 pt-20 sm:p-6 sm:pt-20 lg:p-8">

        <header
            class="flex flex-col gap-4 rounded-3xl bg-gradient-to-br from-[var(--restaurant-color-hex-174c3c)] to-[var(--restaurant-color-hex-10382c)] p-5 text-white shadow-lg sm:flex-row sm:items-center sm:justify-between sm:p-8">
            <div>
                <p class="text-sm font-semibold text-emerald-100/80">
                    مرحباً، {{ auth('cashier')->user()->name }}
                </p>
                <h1 class="mt-1 text-2xl font-extrabold sm:text-3xl">
                    طاولات وفواتير المطعم
                </h1>
                <p class="mt-2 text-sm text-emerald-50/80">
                    عرض الفواتير والطلبات المسجلة على كل طاولة.
                </p>
            </div>

            <form action="{{ route('cashier.logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="rounded-xl border border-white/25 bg-white/10 px-4 py-3 text-sm font-bold text-white transition hover:bg-white/20">
                    تسجيل الخروج
                </button>
            </form>
        </header>

        @if (session('status'))
            <div role="status"
                class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-800">
                {{ session('status') }}
            </div>
        @endif

        @if ($tables->isEmpty())
            <section class="rounded-2xl border border-gray-200 bg-white p-8 text-center text-gray-500">
                لا توجد طاولات مسجلة في النظام.
            </section>
        @else
            {{-- Table Filters --}}
            <section class="rounded-2xl border border-gray-200 bg-white p-3 shadow-sm sm:p-4">
                <div class="flex justify-around gap-2 overflow-x-auto pb-1" role="tablist" aria-label="فلترة الطاولات">

                    <button type="button" data-table-filter="all" aria-pressed="true"
                        class="hoverclass inline-flex shrink-0 items-center gap-2 rounded-xl bg-[#174c3c] px-4 py-3 text-sm font-bold text-white transition">
                        <span>جميع الطاولات</span>
                        <span data-filter-count="all" class="rounded-full bg-white/20 px-2 py-0.5 text-xs">0</span>
                    </button>

                    <button type="button" data-table-filter="active" aria-pressed="false"
                        class="hoverclass inline-flex shrink-0 items-center gap-2 rounded-xl bg-gray-100 px-4 py-3 text-sm font-bold text-gray-600 transition hover:bg-emerald-50">
                        <span>فواتير مفتوحة</span>
                        <span data-filter-count="active" class="rounded-full bg-white px-2 py-0.5 text-xs">0</span>
                    </button>

                    <button type="button" data-table-filter="payment" aria-pressed="false"
                        class="hoverclass inline-flex shrink-0 items-center gap-2 rounded-xl bg-gray-100 px-4 py-3 text-sm font-bold text-gray-600 transition hover:bg-amber-50">
                        <span>بانتظار الحساب</span>
                        <span data-filter-count="payment" class="rounded-full bg-white px-2 py-0.5 text-xs">0</span>
                    </button>

                    <button type="button" data-table-filter="new-orders" aria-pressed="false"
                        class="hoverclass inline-flex shrink-0 items-center gap-2 rounded-xl bg-gray-100 px-4 py-3 text-sm font-bold text-gray-600 transition hover:bg-amber-50">
                        <span>طلبات جديدة</span>
                        <span data-filter-count="new-orders" class="rounded-full bg-white px-2 py-0.5 text-xs">0</span>
                    </button>

                    <button type="button" data-table-filter="empty" aria-pressed="false"
                        class="hoverclass inline-flex shrink-0 items-center gap-2 rounded-xl bg-gray-100 px-4 py-3 text-sm font-bold text-gray-600 transition hover:bg-gray-200">
                        <span>طاولات فارغة</span>
                        <span data-filter-count="empty" class="rounded-full bg-white px-2 py-0.5 text-xs">0</span>
                    </button>
                </div>

                <p id="table-filter-empty" class="hidden py-8 text-center text-sm font-semibold text-gray-500">
                    لا توجد طاولات تطابق هذا الفلتر.
                </p>
            </section>

            {{-- Tables Grid --}}
            <section data-masonry-grid class="grid grid-flow-row-dense gap-5 md:grid-cols-2 xl:grid-cols-3"
                style="grid-auto-rows: 1px">

                @foreach ($tables as $table)
                    @php
                        $tableOrders = $table->bills->flatMap(fn($bill) => $bill->orders);

                        $hasPendingConfirmation = $tableOrders->contains(fn($order) => (string) $order->status === '0');

                        $hasActiveBill = $table->bills->contains(fn($bill) => $bill->status === 'active');

                        $hasPaymentBill = $table->bills->contains(fn($bill) => $bill->status === 'payment');

                        $isEmptyTable = (bool) $table->status && $table->bills->isEmpty();
                    @endphp

                    <article data-masonry-item
                        data-has-pending-confirmation="{{ $hasPendingConfirmation ? 'true' : 'false' }}"
                        data-has-active-bill="{{ $hasActiveBill ? 'true' : 'false' }}"
                        data-has-payment-bill="{{ $hasPaymentBill ? 'true' : 'false' }}"
                        data-is-empty-table="{{ $isEmptyTable ? 'true' : 'false' }}" @class([
                            'min-w-0 overflow-hidden rounded-2xl border bg-white shadow-sm',
                            'border-amber-300 ring-2 ring-amber-200 shadow-amber-100' => $hasPendingConfirmation,
                            'border-gray-200' => !$hasPendingConfirmation,
                        ])>

                        {{-- Table Header --}}
                        <div class="flex items-center justify-between gap-3 border-b border-gray-100 p-5">
                            <div>
                                <p class="text-xs font-semibold text-gray-500">
                                    الطاولة رقم {{ $table->number }}
                                </p>
                                <h2 class="mt-1 text-lg font-extrabold text-gray-800">
                                    {{ $table->name }}
                                </h2>
                            </div>

                            <div class="flex shrink-0 flex-col items-end gap-2">
                                <span
                                    class="rounded-full px-3 py-1.5 text-xs font-bold {{ $table->status ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">
                                    {{ $table->status ? 'متاحة' : 'مشغولة' }}
                                </span>

                                @if ($hasPendingConfirmation)
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-800">
                                        <span class="size-2 animate-pulse rounded-full bg-amber-500"></span>
                                        طلب بانتظار التأكيد
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-4 p-5">

                            {{-- Bills --}}
                            @forelse ($table->bills as $bill)
                                @if ($bill->status === 'payment')
                                    <form action="{{ route('confirm.bill.finalize') }}" method="POST"
                                        class="flex justify-center" data-finalize-bill>
                                        @csrf
                                        @method('PATCH')

                                        <input type="hidden" name="billNumber" value="{{ $bill->number }}">

                                        <button type="submit"
                                            class="inline-flex w-[90%] items-center justify-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-800 disabled:cursor-not-allowed disabled:opacity-60">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            إنهاء الفاتورة
                                        </button>
                                    </form>
                                @endif

                                <section class="rounded-xl border border-gray-200 bg-gray-50/70 p-4">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <div>
                                            <p class="text-xs font-bold text-gray-500">
                                                فاتورة #{{ $bill->number }}
                                            </p>
                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ $bill->start_time
                                                    ? \Illuminate\Support\Carbon::parse($bill->start_time)->format('Y-m-d H:i')
                                                    : 'وقت البدء غير متوفر' }}
                                            </p>
                                        </div>

                                        <span
                                            class="rounded-full px-2.5 py-1 text-xs font-bold {{ $bill->status === 'payment' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                                            {{ $bill->status === 'payment' ? 'بانتظار الحساب' : 'فاتورة مفتوحة' }}
                                        </span>
                                    </div>

                                    <div class="mt-4 flex items-center justify-between border-t border-gray-200 pt-3">
                                        <span class="text-sm font-bold text-gray-600">الإجمالي</span>
                                        <span
                                            class="text-base font-extrabold text-[var(--restaurant-color-hex-174c3c)]">
                                            {{ number_format((float) $bill->orders->sum('total_price'), 0) }}
                                            ل.س
                                        </span>
                                    </div>

                                </section>
                            @empty
                                <p class="rounded-xl bg-gray-50 p-4 text-sm text-gray-500">
                                    لا توجد فاتورة مسجلة لهذه الطاولة.
                                </p>
                            @endforelse

                            @if ($table->bills->isNotEmpty())
                                @if ($tableOrders->isEmpty())
                                    <p class="rounded-xl bg-gray-50 p-4 text-sm text-gray-500">
                                        لا توجد طلبات على فواتير هذه الطاولة.
                                    </p>
                                @else
                                    <div class="space-y-4">

                                        {{-- Orders grouped by status --}}
                                        @foreach ($tableOrders->groupBy('status')->sortKeys() as $status => $statusOrders)
                                            <section class="rounded-xl border border-gray-200 bg-gray-50/70 p-4">

                                                <div class="flex items-center justify-between gap-2">
                                                    <h3 class="text-sm font-extrabold text-gray-700">
                                                        {{ config('status.order')[(string) $status] ?? $status }}
                                                    </h3>

                                                    <span class="text-xs font-semibold text-gray-500">
                                                        {{ $statusOrders->sum('amount') }} وجبة
                                                    </span>
                                                </div>

                                                @if ((string) $status === '0')
                                                    @php
                                                        $pendingOrdersDialogId = 'pending-orders-dialog-' . $table->id;
                                                    @endphp

                                                    <button type="button"
                                                        data-open-dialog="{{ $pendingOrdersDialogId }}"
                                                        class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-xl border border-amber-300 bg-white px-4 py-3 text-sm font-bold text-amber-800 shadow-sm transition hover:bg-amber-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-500">
                                                        <span>فتح الطلبات المعلّقة</span>
                                                        <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs">
                                                            {{ $statusOrders->count() }}
                                                        </span>
                                                    </button>

                                                    <dialog id="{{ $pendingOrdersDialogId }}"
                                                        aria-labelledby="{{ $pendingOrdersDialogId }}-title"
                                                        class="m-auto max-h-[calc(100dvh-2rem)] w-[calc(100%-2rem)] max-w-2xl overflow-hidden rounded-2xl border-0 bg-white p-0 shadow-2xl backdrop:bg-black/60 backdrop:backdrop-blur-sm">

                                                        <div class="flex max-h-[calc(100dvh-2rem)] flex-col">
                                                            <header
                                                                class="flex shrink-0 items-center justify-between gap-3 border-b border-gray-200 bg-amber-50 px-4 py-4 sm:px-6">
                                                                <div>
                                                                    <h4 id="{{ $pendingOrdersDialogId }}-title"
                                                                        class="text-base font-extrabold text-gray-800">
                                                                        الطلبات المعلّقة — الطاولة {{ $table->number }}
                                                                    </h4>
                                                                    <p class="mt-1 text-xs text-gray-600">
                                                                        أدخل وقت التحضير ثم أكّد كل طلب لإرساله إلى
                                                                        المطبخ.
                                                                    </p>
                                                                </div>

                                                                <form method="dialog">
                                                                    <button type="submit" aria-label="إغلاق النافذة"
                                                                        class="flex size-9 shrink-0 items-center justify-center rounded-full bg-white text-xl font-bold text-gray-600 shadow-sm transition hover:bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-500">
                                                                        &times;
                                                                    </button>
                                                                </form>
                                                            </header>

                                                            <div class="overflow-y-auto p-4 sm:p-6">
                                                @endif

                                                <ul
                                                    class="{{ (string) $status === '0' ? 'space-y-3' : 'mt-2 divide-y divide-gray-200' }}">
                                                    @foreach ($statusOrders->groupBy('food_id') as $foodOrders)
                                                        @php
                                                            $notesByValue = $foodOrders->groupBy(
                                                                fn($order) => filled($order->note)
                                                                    ? trim($order->note)
                                                                    : '',
                                                            );

                                                            $expectedSeconds = $foodOrders->sum(function ($order) {
                                                                $parts = array_map(
                                                                    'intval',
                                                                    explode(':', (string) $order->expected_time),
                                                                );

                                                                return count($parts) === 3
                                                                    ? $parts[0] * 3600 + $parts[1] * 60 + $parts[2]
                                                                    : 0;
                                                            });

                                                            $expectedDuration = sprintf(
                                                                '%02d:%02d',
                                                                intdiv($expectedSeconds, 3600),
                                                                intdiv($expectedSeconds % 3600, 60),
                                                            );
                                                        @endphp

                                                        <li
                                                            class="{{ (string) $status === '0' ? 'rounded-lg border border-amber-100 bg-white p-3' : 'flex items-start justify-between gap-3 py-3 first:pt-0 last:pb-0' }}">

                                                            <div class="min-w-0">
                                                                <p class="font-bold text-gray-800">
                                                                    {{ $foodOrders->first()->food?->name ?? 'وجبة محذوفة' }}
                                                                    <span class="text-gray-500">
                                                                        × {{ $foodOrders->sum('amount') }}
                                                                    </span>
                                                                </p>

                                                                @if ((string) $status !== '0' && $expectedSeconds > 0)
                                                                    <p
                                                                        class="mt-1 text-xs font-semibold text-emerald-700">
                                                                        مدة التحضير المجمّعة: {{ $expectedDuration }}
                                                                    </p>
                                                                @endif

                                                                @if ((string) $status === '0')
                                                                    <ul class="mt-3 space-y-3">
                                                                        @foreach ($foodOrders as $pendingOrder)
                                                                            <li
                                                                                class="rounded-lg border border-gray-200 bg-white p-3">
                                                                                <div
                                                                                    class="text-sm font-bold text-gray-800">
                                                                                    طلب #{{ $pendingOrder->id }}
                                                                                    <span class="text-gray-500">
                                                                                        × {{ $pendingOrder->amount }}
                                                                                    </span>
                                                                                </div>

                                                                                @if (filled($pendingOrder->note))
                                                                                    <p
                                                                                        class="mt-1 break-words text-xs text-gray-600">
                                                                                        ملاحظة:
                                                                                        {{ $pendingOrder->note }}
                                                                                    </p>
                                                                                @endif

                                                                                <form data-confirm-order
                                                                                    action="{{ route('cashier.orders.confirm', $pendingOrder) }}"
                                                                                    method="POST"
                                                                                    class="mt-3 flex flex-col gap-2 sm:flex-row sm:items-end">
                                                                                    @csrf

                                                                                    <label
                                                                                        class="block flex-1 text-xs font-bold text-gray-700">
                                                                                        مدة التحضير بالدقائق
                                                                                        <input type="number"
                                                                                            name="expected_minutes"
                                                                                            min="1"
                                                                                            max="50399" required
                                                                                            value="{{ old('expected_minutes', 0) }}"
                                                                                            inputmode="numeric"
                                                                                            placeholder="مثال: 62"
                                                                                            class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900 outline-none transition focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-200">
                                                                                    </label>

                                                                                    <button type="submit"
                                                                                        class="inline-flex shrink-0 items-center justify-center rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                                                                        تأكيد وإرسال للمطبخ
                                                                                    </button>
                                                                                </form>

                                                                                @error('expected_minutes')
                                                                                    <p role="alert"
                                                                                        class="mt-2 rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-700">
                                                                                        {{ $message }}
                                                                                    </p>
                                                                                @enderror
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                @else
                                                                    @foreach ($notesByValue as $note => $ordersWithNote)
                                                                        @if (filled($note))
                                                                            <p
                                                                                class="mt-1 break-words text-xs leading-5 text-gray-500">
                                                                                ملاحظة
                                                                                ({{ $ordersWithNote->sum('amount') }})
                                                                                :
                                                                                {{ $note }}
                                                                            </p>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </div>

                                                            <span class="shrink-0 text-sm font-bold text-gray-700">
                                                                {{ number_format((float) $foodOrders->sum('total_price'), 0) }}
                                                                ل.س
                                                            </span>
                                                        </li>
                                                    @endforeach
                                                </ul>

                                                @if ((string) $status === '0')
                                    </div>
                        </div>
                        </dialog>
                @endif

            </section>
        @endforeach

        </div>
        @endif
        @endif

        </div>
        </article>
        @endforeach

        </section>
        @endif
    </main>

    <div id="finalize-bill-modal"
        class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
        role="dialog" aria-modal="true" aria-labelledby="finalize-modal-title">

        <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-gray-900">

            <div class="p-6 text-center">
                <div
                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m0 3h.008M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" />
                    </svg>
                </div>

                <h2 id="finalize-modal-title" class="mt-4 text-xl font-extrabold text-gray-900 dark:text-white">
                    تأكيد إنهاء الفاتورة
                </h2>

                <p class="mt-2 text-sm leading-6 text-gray-600 dark:text-gray-300">
                    هل أنت متأكد من إنهاء الفاتورة
                    <span id="finalize-bill-number"
                        class="font-extrabold text-emerald-700 dark:text-emerald-400"></span>؟
                </p>

                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    تأكد من مراجعة الحساب قبل إغلاق الفاتورة.
                </p>

                <p id="finalize-bill-error" role="alert"
                    class="mt-3 hidden rounded-lg bg-red-50 p-3 text-sm font-semibold text-red-700 dark:bg-red-950/40 dark:text-red-300">
                </p>
            </div>

            <div class="flex gap-3 border-t border-gray-100 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/50">
                <button type="button" id="cancel-finalize-bill"
                    class="flex-1 rounded-xl border border-gray-300 px-4 py-3 text-sm font-bold text-gray-700 transition hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                    إلغاء
                </button>

                <button type="button" id="confirm-finalize-bill"
                    class="flex-1 rounded-xl bg-emerald-700 px-4 py-3 text-sm font-bold text-white transition hover:bg-emerald-800 disabled:cursor-not-allowed disabled:opacity-60">
                    <span data-confirm-label>نعم، إنهاء الفاتورة</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        (() => {
            let dashboardRefreshPromise = null;
            let masonryObserver = null;
            let grid = document.querySelector('[data-masonry-grid]');
            let activeTableFilter = 'all';

            const resizeCards = () => {
                if (!grid) return;

                const styles = getComputedStyle(grid);
                const rowGap = parseFloat(styles.rowGap) || 0;
                const rowHeight = parseFloat(styles.gridAutoRows) || 1;

                grid.querySelectorAll('[data-masonry-item]').forEach((card) => {
                    if (card.hidden) return;

                    const cardStyles = getComputedStyle(card);
                    const height = card.scrollHeight +
                        (parseFloat(cardStyles.borderTopWidth) || 0) +
                        (parseFloat(cardStyles.borderBottomWidth) || 0);

                    const rowSpan = Math.ceil(
                        (height + rowGap) / (rowHeight + rowGap)
                    );

                    card.style.gridRowEnd = `span ${rowSpan}`;
                });
            };

            const initializeMasonry = () => {
                masonryObserver?.disconnect();
                masonryObserver = null;

                grid = document.querySelector('[data-masonry-grid]');

                if (!grid) return;

                resizeCards();

                if ('ResizeObserver' in window) {
                    masonryObserver = new ResizeObserver(resizeCards);

                    grid.querySelectorAll('[data-masonry-item]').forEach((card) => {
                        masonryObserver.observe(card);
                    });
                }
            };

            // Table filters
            const matchesTableFilter = (card, filter) => {
                switch (filter) {
                    case 'active':
                        return card.dataset.hasActiveBill === 'true';

                    case 'payment':
                        return card.dataset.hasPaymentBill === 'true';

                    case 'new-orders':
                        return card.dataset.hasPendingConfirmation === 'true';

                    case 'empty':
                        return card.dataset.isEmptyTable === 'true';

                    default:
                        return true;
                }
            };

            const applyTableFilter = (filter = activeTableFilter) => {
                const main = document.getElementById('cashier-dashboard-main');

                if (!main) return;

                activeTableFilter = filter;

                const cards = main.querySelectorAll('[data-masonry-item]');
                const buttons = main.querySelectorAll('[data-table-filter]');
                const emptyMessage = main.querySelector('#table-filter-empty');

                let visibleCount = 0;

                cards.forEach((card) => {
                    const visible = matchesTableFilter(card, filter);

                    card.hidden = !visible;

                    if (visible) {
                        visibleCount++;
                    }
                });

                buttons.forEach((button) => {
                    const isActive = button.dataset.tableFilter === filter;

                    button.setAttribute('aria-pressed', String(isActive));

                    button.classList.toggle('bg-[#174c3c]', isActive);
                    button.classList.toggle('text-white', isActive);

                    button.classList.toggle('bg-gray-100', !isActive);
                    button.classList.toggle('text-gray-600', !isActive);
                });

                if (emptyMessage) {
                    emptyMessage.classList.toggle('hidden', visibleCount > 0);
                }

                resizeCards();
            };

            // const initializeTableFilters = () => {
            //     const main = document.getElementById('cashier-dashboard-main');

            //     if (!main) return;

            //     const cards = main.querySelectorAll('[data-masonry-item]');

            //     const counts = {
            //         all: cards.length,
            //         active: 0,
            //         payment: 0,
            //         'new-orders': 0,
            //         empty: 0,
            //     };

            //     cards.forEach((card) => {
            //         if (card.dataset.hasActiveBill === 'true') {
            //             counts.active++;
            //         }

            //         if (card.dataset.hasPaymentBill === 'true') {
            //             counts.payment++;
            //         }

            //         if (card.dataset.hasPendingConfirmation === 'true') {
            //             counts['new-orders']++;
            //         }

            //         if (card.dataset.isEmptyTable === 'true') {
            //             counts.empty++;
            //         }
            //     });

            //     Object.entries(counts).forEach(([filter, count]) => {
            //         const counter = main.querySelector(
            //             `[data-filter-count="${filter}"]`
            //         );

            //         if (counter) {
            //             counter.textContent = count;
            //         }
            //     });

            //     applyTableFilter(activeTableFilter);
            // };

            const previousCounts = {
                all: null,
                active: null,
                payment: null,
                'new-orders': null,
                empty: null,
            };
            let isInitialDashboardLoad = true;

            const initializeTableFilters = () => {
                const main = document.getElementById('cashier-dashboard-main');

                if (!main) return;

                const cards = main.querySelectorAll('[data-masonry-item]');

                const counts = {
                    all: cards.length,
                    active: 0,
                    payment: 0,
                    'new-orders': 0,
                    empty: 0,
                };

                cards.forEach((card) => {
                    if (card.dataset.hasActiveBill === 'true') {
                        counts.active++;
                    }

                    if (card.dataset.hasPaymentBill === 'true') {
                        counts.payment++;
                    }

                    if (card.dataset.hasPendingConfirmation === 'true') {
                        counts['new-orders']++;
                    }

                    if (card.dataset.isEmptyTable === 'true') {
                        counts.empty++;
                    }
                });

                Object.entries(counts).forEach(([filter, count]) => {
                    const counter = main.querySelector(
                        `[data-filter-count="${filter}"]`
                    );

                    if (!counter) return;

                    const previousCount = previousCounts[filter];

                    counter.textContent = count;

                    if (
                        (isInitialDashboardLoad && ['payment', 'new-orders'].includes(filter)) && count !==
                        0 ||
                        (previousCount !== null && count !== 0 && count !== previousCount)) {
                        console.log(filter, count, previousCount);

                        counter.classList.add('cashier-count-zooming');
                    }

                    previousCounts[filter] = count;
                });

                applyTableFilter(activeTableFilter);
            };
            // Event delegation keeps filters working after dashboard refreshes.
            document.addEventListener('click', (event) => {
                const filterButton = event.target.closest('[data-table-filter]');

                if (filterButton) {
                    const selectedFilter = filterButton.dataset.tableFilter;

                    // إيقاف التأثير الخاص بالتبويب الذي ضغطت عليه فقط.
                    const counter = filterButton.querySelector(
                        `[data-filter-count="${selectedFilter}"]`
                    );

                    counter?.classList.remove('cashier-count-zooming');

                    applyTableFilter(selectedFilter);
                    return;
                }

                const openButton = event.target.closest('[data-open-dialog]');

                if (openButton) {
                    const dialog = document.getElementById(
                        openButton.dataset.openDialog
                    );

                    if (!(dialog instanceof HTMLDialogElement)) {
                        console.error(
                            'Pending orders dialog could not be opened.',
                            openButton.dataset.openDialog
                        );
                        return;
                    }

                    dialog.showModal();
                    return;
                }

                const dialog = event.target.closest(
                    'dialog[id^="pending-orders-dialog-"]'
                );

                if (dialog && event.target === dialog) {
                    dialog.close();
                }
            });

            const refreshDashboard = () => {
                if (dashboardRefreshPromise) {
                    return dashboardRefreshPromise;
                }

                dashboardRefreshPromise = (async () => {
                    const response = await fetch(window.location.href, {
                        headers: {
                            'Accept': 'text/html',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        credentials: 'same-origin',
                        cache: 'no-store',
                    });

                    if (!response.ok) {
                        throw new Error(
                            `تعذر تحديث لوحة الكاشير (${response.status}).`
                        );
                    }

                    const html = await response.text();

                    const refreshedDocument = new DOMParser().parseFromString(
                        html,
                        'text/html'
                    );

                    const refreshedMain = refreshedDocument.getElementById(
                        'cashier-dashboard-main'
                    );

                    const currentMain = document.getElementById(
                        'cashier-dashboard-main'
                    );

                    if (!refreshedMain || !currentMain) {
                        throw new Error(
                            'تعذر العثور على محتوى لوحة الكاشير بعد التحديث.'
                        );
                    }

                    const openDialogId = document.querySelector(
                        'dialog[open]'
                    )?.id;

                    currentMain.innerHTML = refreshedMain.innerHTML;

                    initializeMasonry();
                    initializeTableFilters();

                    if (openDialogId) {
                        const refreshedDialog = document.getElementById(
                            openDialogId
                        );

                        if (refreshedDialog instanceof HTMLDialogElement) {
                            refreshedDialog.showModal();
                        }
                    }
                })();

                dashboardRefreshPromise = dashboardRefreshPromise
                    .catch((error) => {
                        console.error(
                            'Failed to update the cashier dashboard without reloading.',
                            error
                        );
                    })
                    .finally(() => {
                        dashboardRefreshPromise = null;
                    });

                return dashboardRefreshPromise;
            };



            // Finalize bill with confirmation modal, without page reload.
            let finalizeBillForm = null;
            let finalizingBill = false;

            const finalizeModal = document.getElementById('finalize-bill-modal');
            const finalizeBillNumber = document.getElementById('finalize-bill-number');
            const finalizeBillError = document.getElementById('finalize-bill-error');
            const finalizeConfirmButton = document.getElementById('confirm-finalize-bill');
            const finalizeCancelButton = document.getElementById('cancel-finalize-bill');
            const finalizeConfirmLabel = finalizeConfirmButton?.querySelector('[data-confirm-label]');

            const closeFinalizeBillModal = () => {
                if (!finalizeModal || finalizingBill) return;

                finalizeModal.classList.add('hidden');
                finalizeModal.classList.remove('flex');

                finalizeBillForm = null;
                finalizeBillError?.classList.add('hidden');

                document.body.classList.remove('overflow-hidden');
            };

            const openFinalizeBillModal = (form) => {
                finalizeBillForm = form;

                const billNumber = form.querySelector('[name="billNumber"]')?.value ?? '';

                finalizeBillNumber.textContent = `#${billNumber}`;
                finalizeBillError.textContent = '';
                finalizeBillError.classList.add('hidden');

                finalizeModal.classList.remove('hidden');
                finalizeModal.classList.add('flex');

                document.body.classList.add('overflow-hidden');
                finalizeCancelButton?.focus();
            };

            document.addEventListener('submit', (event) => {
                const form = event.target.closest('form[data-finalize-bill]');

                if (!form) return;

                event.preventDefault();

                if (finalizingBill) return;

                openFinalizeBillModal(form);
            });

            finalizeCancelButton?.addEventListener('click', closeFinalizeBillModal);

            finalizeModal?.addEventListener('click', (event) => {
                if (event.target === finalizeModal) {
                    closeFinalizeBillModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (
                    event.key === 'Escape' &&
                    finalizeModal &&
                    !finalizeModal.classList.contains('hidden')
                ) {
                    closeFinalizeBillModal();
                }
            });

            finalizeConfirmButton?.addEventListener('click', async () => {
                if (!finalizeBillForm || finalizingBill) return;

                const form = finalizeBillForm;
                const submitButton = form.querySelector('button[type="submit"]');

                finalizingBill = true;
                finalizeConfirmButton.disabled = true;
                finalizeCancelButton.disabled = true;

                if (submitButton) {
                    submitButton.disabled = true;
                }

                if (finalizeConfirmLabel) {
                    finalizeConfirmLabel.textContent = 'جارٍ إنهاء الفاتورة...';
                }

                finalizeBillError.textContent = '';
                finalizeBillError.classList.add('hidden');

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        credentials: 'same-origin',
                    });

                    const contentType = response.headers.get('content-type') || '';

                    if (!contentType.includes('application/json')) {
                        throw new Error(
                            response.status === 419 ?
                            'انتهت صلاحية الجلسة. حدّث الصفحة ثم حاول مجددًا.' :
                            'لم يُرجع الخادم استجابة JSON. تحقق من إعدادات الـ Controller.'
                        );
                    }

                    const result = await response.json();

                    if (!response.ok || result.success !== true) {
                        throw new Error(
                            result.message || 'تعذر إنهاء الفاتورة.'
                        );
                    }

                    finalizeModal.classList.add('hidden');
                    finalizeModal.classList.remove('flex');
                    document.body.classList.remove('overflow-hidden');

                    finalizeBillForm = null;

                    await refreshDashboard();

                } catch (error) {
                    console.error('Failed to finalize bill.', error);

                    finalizeBillError.textContent =
                        error.message || 'حدث خطأ أثناء إنهاء الفاتورة.';

                    finalizeBillError.classList.remove('hidden');

                } finally {
                    finalizingBill = false;
                    finalizeConfirmButton.disabled = false;
                    finalizeCancelButton.disabled = false;

                    if (finalizeConfirmLabel) {
                        finalizeConfirmLabel.textContent = 'نعم، إنهاء الفاتورة';
                    }

                    if (submitButton?.isConnected) {
                        submitButton.disabled = false;
                    }
                }
            });

            // Confirm pending orders
            document.addEventListener('submit', async (event) => {
                const form = event.target.closest('form[data-confirm-order]');

                if (!form) return;

                event.preventDefault();

                const submitButton = form.querySelector('button[type="submit"]');
                const buttonText = submitButton?.textContent;

                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.textContent = 'جارٍ التأكيد...';
                }

                try {
                    const response = await fetch(form.action, {
                        method: form.method.toUpperCase(),
                        body: new FormData(form),
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        credentials: 'same-origin',
                    });

                    const result = await response.json();

                    if (!response.ok || !result.success) {
                        const validationMessage = result.errors ?
                            Object.values(result.errors).flat().join(' ') :
                            result.message;

                        throw new Error(
                            validationMessage || 'تعذر تأكيد الطلب.'
                        );
                    }

                    await refreshDashboard();
                } catch (error) {
                    console.error(
                        'Failed to confirm order without reloading.',
                        error
                    );

                    let errorMessage = form.parentElement.querySelector(
                        '[data-confirm-error]'
                    );

                    if (!errorMessage) {
                        errorMessage = document.createElement('p');
                        errorMessage.dataset.confirmError = '';
                        errorMessage.setAttribute('role', 'alert');

                        errorMessage.className =
                            'mt-2 rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-700';

                        form.insertAdjacentElement('afterend', errorMessage);
                    }

                    errorMessage.textContent =
                        error.message || 'تعذر تأكيد الطلب.';

                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.textContent = buttonText;
                    }
                }
            });

            // Laravel Echo live updates
            const subscribeToCashierUpdates = () => {
                if (!window.Echo) {
                    console.error(
                        'Cashier live updates could not start because Laravel Echo is unavailable.'
                    );
                    return;
                }

                const channel = window.Echo.private('cashier.tables');

                channel
                    .subscribed(() => {
                        console.info('Cashier live updates connected.');
                    })
                    .error((error) => {
                        console.error(
                            'Cashier live updates channel subscription failed.',
                            error
                        );
                    })
                    .listen('.cashier.dashboard.updated', refreshDashboard);
            };

            if (document.readyState === 'loading') {
                document.addEventListener(
                    'DOMContentLoaded',
                    subscribeToCashierUpdates, {
                        once: true
                    }
                );
            } else {
                subscribeToCashierUpdates();
            }

            initializeMasonry();
            initializeTableFilters();

            if (!('ResizeObserver' in window)) {
                window.addEventListener('resize', resizeCards);
            }
        })();
    </script>

    <script>
        let finalizeBillForm = null;

        function confirmBillFinalization(event, billNumber) {
            event.preventDefault();

            finalizeBillForm = event.currentTarget;

            document.getElementById('finalize-bill-number').textContent = '#' + billNumber;

            const modal = document.getElementById('finalize-bill-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            return false;
        }

        function closeFinalizeBillModal() {
            const modal = document.getElementById('finalize-bill-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            finalizeBillForm = null;
        }

        function confirmFinalizeBill() {
            if (!finalizeBillForm) return;

            const form = finalizeBillForm;
            finalizeBillForm = null;

            const modal = document.getElementById('finalize-bill-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');

            HTMLFormElement.prototype.submit.call(form);
        }

        document.getElementById('finalize-bill-modal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeFinalizeBillModal();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeFinalizeBillModal();
            }
        });
    </script>
</body>

</html>
