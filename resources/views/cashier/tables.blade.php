<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#174c3c">
    <title>طاولات المطعم - الكاشير</title>
    @include('components.theme-mode')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 text-gray-800">
    <main id="cashier-dashboard-main" class="mx-auto max-w-7xl space-y-6 p-4 pt-20 sm:p-6 sm:pt-20 lg:p-8">
        <header
            class="flex flex-col gap-4 rounded-3xl bg-gradient-to-br from-[var(--restaurant-color-hex-174c3c)] to-[var(--restaurant-color-hex-10382c)] p-5 text-white shadow-lg sm:flex-row sm:items-center sm:justify-between sm:p-8">
            <div>
                <p class="text-sm font-semibold text-emerald-100/80">مرحباً، {{ auth('cashier')->user()->name }}</p>
                <h1 class="mt-1 text-2xl font-extrabold sm:text-3xl">طاولات وفواتير المطعم</h1>
                <p class="mt-2 text-sm text-emerald-50/80">عرض الفواتير والطلبات المسجلة على كل طاولة.</p>
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
            <section class="grid grid-flow-row-dense gap-5 md:grid-cols-2 xl:grid-cols-3" data-masonry-grid
                style="grid-auto-rows: 1px">
                @foreach ($tables as $table)
                    @php
                        $tableOrders = $table->bills->flatMap(fn($bill) => $bill->orders);
                        $hasPendingConfirmation = $tableOrders->contains(fn($order) => (string) $order->status === '0');
                    @endphp
                    <article data-masonry-item
                        data-has-pending-confirmation="{{ $hasPendingConfirmation ? 'true' : 'false' }}"
                        @class([
                            'min-w-0 overflow-hidden rounded-2xl border bg-white shadow-sm',
                            'border-amber-300 ring-2 ring-amber-200 shadow-amber-100' => $hasPendingConfirmation,
                            'border-gray-200' => !$hasPendingConfirmation,
                        ])>
                        <div class="flex items-center justify-between gap-3 border-b border-gray-100 p-5">
                            <div>
                                <p class="text-xs font-semibold text-gray-500">الطاولة رقم {{ $table->number }}</p>
                                <h2 class="mt-1 text-lg font-extrabold text-gray-800">{{ $table->name }}</h2>
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
                            @forelse ($table->bills as $bill)
                                <section class="rounded-xl border border-gray-200 bg-gray-50/70 p-4">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <div>
                                            <p class="text-xs font-bold text-gray-500">فاتورة #{{ $bill->number }}</p>
                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ $bill->start_time ? \Illuminate\Support\Carbon::parse($bill->start_time)->format('Y-m-d H:i') : 'وقت البدء غير متوفر' }}
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
                                            {{ number_format((float) $bill->orders->sum('total_price'), 0) }} ل.س
                                        </span>
                                    </div>
                                </section>
                            @empty
                                <p class="rounded-xl bg-gray-50 p-4 text-sm text-gray-500">لا توجد فاتورة مسجلة لهذه
                                    الطاولة.</p>
                            @endforelse

                            @if ($table->bills->isNotEmpty())
                                @if ($tableOrders->isEmpty())
                                    <p class="rounded-xl bg-gray-50 p-4 text-sm text-gray-500">لا توجد طلبات على فواتير
                                        هذه الطاولة.</p>
                                @else
                                    <div class="space-y-4">
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
                                                        <span
                                                            class="rounded-full bg-amber-100 px-2 py-0.5 text-xs">{{ $statusOrders->count() }}</span>
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
                                                                    <p class="mt-1 text-xs text-gray-600">أدخل وقت
                                                                        التحضير ثم أكّد كل طلب لإرساله إلى المطبخ.</p>
                                                                </div>
                                                                <form method="dialog">
                                                                    @csrf
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
                                                                    <span class="text-gray-500">×
                                                                        {{ $foodOrders->sum('amount') }}</span>
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
                                                                                    <span class="text-gray-500">×
                                                                                        {{ $pendingOrder->amount }}</span>
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
                                                                                        {{ $message }}</p>
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
    <script>
        (() => {
            let dashboardRefreshPromise = null;
            let masonryObserver = null;
            let grid = document.querySelector('[data-masonry-grid]');
            const resizeCards = () => {
                if (!grid) return;

                const styles = getComputedStyle(grid);
                const rowGap = parseFloat(styles.rowGap) || 0;
                const rowHeight = parseFloat(styles.gridAutoRows) || 1;

                grid.querySelectorAll('[data-masonry-item]').forEach((card) => {
                    const cardStyles = getComputedStyle(card);
                    const height = card.scrollHeight +
                        (parseFloat(cardStyles.borderTopWidth) || 0) +
                        (parseFloat(cardStyles.borderBottomWidth) || 0);
                    const rowSpan = Math.ceil((height + rowGap) / (rowHeight + rowGap));
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
                    grid.querySelectorAll('[data-masonry-item]').forEach((card) => masonryObserver.observe(card));
                }
            };

            const refreshDashboard = () => {
                if (dashboardRefreshPromise) return dashboardRefreshPromise;

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
                        throw new Error(`تعذر تحديث لوحة الكاشير (${response.status}).`);
                    }

                    const html = await response.text();
                    const refreshedDocument = new DOMParser().parseFromString(html, 'text/html');
                    const refreshedMain = refreshedDocument.getElementById('cashier-dashboard-main');
                    const currentMain = document.getElementById('cashier-dashboard-main');

                    if (!refreshedMain || !currentMain) {
                        throw new Error('تعذر العثور على محتوى لوحة الكاشير بعد التحديث.');
                    }

                    const openDialogId = document.querySelector('dialog[open]')?.id;
                    currentMain.innerHTML = refreshedMain.innerHTML;
                    initializeMasonry();

                    if (openDialogId) {
                        const refreshedDialog = document.getElementById(openDialogId);
                        if (refreshedDialog instanceof HTMLDialogElement) {
                            refreshedDialog.showModal();
                        }
                    }
                })();

                dashboardRefreshPromise = dashboardRefreshPromise
                    .catch((error) => {
                        console.error('Failed to update the cashier dashboard without reloading.', error);
                    })
                    .finally(() => {
                        dashboardRefreshPromise = null;
                    });

                return dashboardRefreshPromise;
            };

            document.addEventListener('click', (event) => {
                const openButton = event.target.closest('[data-open-dialog]');
                if (openButton) {
                    const dialog = document.getElementById(openButton.dataset.openDialog);
                    if (!(dialog instanceof HTMLDialogElement)) {
                        console.error('Pending orders dialog could not be opened.', openButton.dataset
                            .openDialog);
                        return;
                    }

                    dialog.showModal();
                    return;
                }

                const dialog = event.target.closest('dialog[id^="pending-orders-dialog-"]');
                if (dialog && event.target === dialog) {
                    dialog.close();
                }
            });

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
                        throw new Error(validationMessage || 'تعذر تأكيد الطلب.');
                    }

                    await refreshDashboard();
                } catch (error) {
                    console.error('Failed to confirm order without reloading.', error);
                    let errorMessage = form.parentElement.querySelector('[data-confirm-error]');
                    if (!errorMessage) {
                        errorMessage = document.createElement('p');
                        errorMessage.dataset.confirmError = '';
                        errorMessage.setAttribute('role', 'alert');
                        errorMessage.className =
                            'mt-2 rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-700';
                        form.insertAdjacentElement('afterend', errorMessage);
                    }
                    errorMessage.textContent = error.message || 'تعذر تأكيد الطلب.';

                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.textContent = buttonText;
                    }
                }
            });

            const subscribeToCashierUpdates = () => {
                if (!window.Echo) {
                    console.error('Cashier live updates could not start because Laravel Echo is unavailable.');
                    return;
                }

                const channel = window.Echo.private('cashier.tables');

                channel
                    .subscribed(() => console.info('Cashier live updates connected.'))
                    .error((error) => console.error('Cashier live updates channel subscription failed.', error))
                    .listen('.cashier.dashboard.updated', refreshDashboard);
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', subscribeToCashierUpdates, {
                    once: true
                });
            } else {
                subscribeToCashierUpdates();
            }

            initializeMasonry();
            if (!('ResizeObserver' in window)) {
                window.addEventListener('resize', resizeCards);
            }
        })();
    </script>
</body>

</html>
