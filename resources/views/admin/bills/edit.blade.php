@extends('admin.layouts.main-layout')

@section('title', 'تعديل الفاتورة #' . $bill->number)

@section('content')
    <style>
        #bill-edit-page {
            --bill-edit-line: color-mix(in srgb, CanvasText 14%, Canvas);
            --bill-edit-surface: Canvas;
            --bill-edit-wash: color-mix(in srgb, CanvasText 4%, Canvas);
            --bill-edit-text: CanvasText;
            --bill-edit-muted: GrayText;
            color: var(--bill-edit-text);
        }

        html[data-theme="dark"] #bill-edit-page {
            --bill-edit-line: color-mix(in srgb, var(--brand-medium) 38%, var(--brand-dark));
            --bill-edit-surface: color-mix(in srgb, var(--brand-dark) 82%, black);
            --bill-edit-wash: color-mix(in srgb, var(--brand-medium) 17%, var(--brand-dark));
            --bill-edit-text: var(--theme-text);
            --bill-edit-muted: color-mix(in srgb, var(--theme-text) 65%, transparent);
        }

        #bill-edit-page .bill-edit-surface {
            background: var(--bill-edit-surface);
        }

        #bill-edit-page .bill-edit-line {
            border-color: var(--bill-edit-line);
        }

        #bill-edit-page .bill-edit-muted {
            color: var(--bill-edit-muted);
        }

        #bill-edit-page .bill-edit-icon {
            color: var(--color-emerald-800);
            background: color-mix(in srgb, var(--color-emerald-700) 10%, Canvas);
        }

        #bill-edit-page .bill-edit-status {
            display: inline-flex;
            align-items: center;
            border: 1px solid;
            border-radius: 9999px;
            padding: .2rem .55rem;
            font-size: .6875rem;
            font-weight: 800;
            line-height: 1.25;
            white-space: nowrap;
        }

        #bill-edit-page .bill-edit-status-pending {
            color: var(--color-amber-800);
            border-color: color-mix(in srgb, var(--color-amber-600) 38%, Canvas);
            background: color-mix(in srgb, var(--color-amber-500) 12%, Canvas);
        }

        #bill-edit-page .bill-edit-status-kitchen {
            color: var(--color-blue-800);
            border-color: color-mix(in srgb, var(--color-blue-600) 36%, Canvas);
            background: color-mix(in srgb, var(--color-blue-600) 10%, Canvas);
        }

        #bill-edit-page .bill-edit-status-table {
            color: var(--color-emerald-800);
            border-color: color-mix(in srgb, var(--color-emerald-600) 36%, Canvas);
            background: color-mix(in srgb, var(--color-emerald-600) 10%, Canvas);
        }

        #bill-edit-page .bill-edit-status-unknown {
            color: var(--bill-edit-muted);
            border-color: var(--bill-edit-line);
            background: var(--bill-edit-wash);
        }

        html[data-theme="dark"] #bill-edit-page .bill-edit-icon {
            color: var(--color-emerald-300);
            background: color-mix(in srgb, var(--color-emerald-400) 12%, var(--brand-dark));
        }

        html[data-theme="dark"] #bill-edit-page .bill-edit-status-pending {
            color: var(--color-amber-200);
            border-color: color-mix(in srgb, var(--color-amber-400) 34%, var(--brand-dark));
            background: color-mix(in srgb, var(--color-amber-950) 52%, var(--brand-dark));
        }

        html[data-theme="dark"] #bill-edit-page .bill-edit-status-kitchen {
            color: var(--color-blue-200);
            border-color: color-mix(in srgb, var(--color-blue-600) 58%, var(--brand-dark));
            background: color-mix(in srgb, var(--color-blue-800) 52%, var(--brand-dark));
        }

        html[data-theme="dark"] #bill-edit-page .bill-edit-status-table {
            color: var(--color-emerald-200);
            border-color: color-mix(in srgb, var(--color-emerald-400) 34%, var(--brand-dark));
            background: color-mix(in srgb, var(--color-emerald-900) 52%, var(--brand-dark));
        }

        #bill-edit-page .bill-edit-input {
            color: var(--bill-edit-text);
            background: var(--bill-edit-wash);
            border-color: var(--bill-edit-line);
        }

        #bill-edit-page .bill-edit-input:focus {
            border-color: var(--brand-accent);
            outline: 3px solid color-mix(in srgb, var(--brand-accent) 22%, transparent);
        }

        #bill-edit-page .bill-edit-delete {
            color: var(--color-red-700);
            border-color: color-mix(in srgb, var(--color-red-600) 30%, Canvas);
            background: color-mix(in srgb, var(--color-red-500) 7%, Canvas);
        }

        #bill-edit-page .bill-edit-delete:hover {
            background: color-mix(in srgb, var(--color-red-500) 14%, Canvas);
        }

        html[data-theme="dark"] #bill-edit-page .bill-edit-delete {
            color: var(--color-red-200);
            border-color: color-mix(in srgb, var(--color-red-400) 35%, var(--brand-dark));
            background: color-mix(in srgb, var(--color-red-950) 42%, var(--brand-dark));
        }

        html[data-theme="dark"] #bill-edit-page .bill-edit-delete:hover {
            background: color-mix(in srgb, var(--color-red-900) 60%, var(--brand-dark));
        }

        #bill-edit-page .bill-edit-error {
            border-color: color-mix(in srgb, var(--color-red-600) 28%, Canvas);
            background: color-mix(in srgb, var(--color-red-500) 8%, Canvas);
            color: color-mix(in srgb, var(--color-red-800) 84%, CanvasText);
        }

        html[data-theme="dark"] #bill-edit-page .bill-edit-error {
            border-color: color-mix(in srgb, var(--color-red-400) 32%, var(--brand-dark));
            background: color-mix(in srgb, var(--color-red-950) 54%, var(--brand-dark));
            color: var(--color-red-200);
        }

        @media (prefers-reduced-motion: reduce) {

            #bill-edit-page *,
            #bill-edit-page *::before,
            #bill-edit-page *::after {
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
            }
        }
    </style>

    <main id="bill-edit-page" dir="rtl" class="mx-auto w-full max-w-5xl space-y-6 p-4 pb-24 sm:p-6 sm:pb-10 lg:p-8">
        <header
            class="bills-heading relative isolate overflow-hidden rounded-[1.75rem] px-6 py-7 text-white shadow-xl sm:px-8 sm:py-9"
            style="background: radial-gradient(circle at 12% 10%, color-mix(in srgb, var(--brand-accent) 18%, transparent), transparent 29%), linear-gradient(120deg, var(--brand-dark), var(--brand-primary) 58%, var(--brand-medium));">
            <div aria-hidden="true"
                class="pointer-events-none absolute -left-10 -top-14 h-52 w-52 rounded-full border-[30px] border-white/[.06]">
            </div>
            <div class="relative flex flex-col justify-between gap-6 sm:flex-row sm:items-end">
                <div>
                    <p class="mb-3 text-xs font-extrabold tracking-wide text-emerald-100">إدارة الفواتير النشطة</p>
                    <h1 class="text-3xl font-black leading-tight sm:text-4xl">تعديل الفاتورة #{{ $bill->number }}</h1>
                    <h1 class="text-3xl font-black leading-tight sm:text-4xl">
                        رقم الطاولة {{ $bill->table?->number ?? 'غير محددة' }}
                    </h1>
                    <p class="mt-3 max-w-2xl text-sm leading-7 text-emerald-50/80">
                        عدّل كميات الأصناف الحالية فقط. ستُحدّث أسعار البنود والإجمالي تلقائيًا وفق الأسعار الحالية.
                    </p>
                </div>
                <div
                    class="flex items-center gap-3 border-t border-white/20 pt-4 sm:border-r sm:border-t-0 sm:pr-5 sm:pt-0">
                    <span
                        class="flex h-11 w-11 items-center justify-center rounded-xl border border-white/20 bg-white/10 text-amber-200"
                        aria-hidden="true">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m16.862 4.487 2.651 2.651M4 20l4.2-.84L19.7 7.66a1.875 1.875 0 0 0-2.65-2.65L5.55 16.51 4 20Z" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-xs font-bold text-emerald-100">الحالة</p>
                        <p class="mt-1 text-sm font-extrabold">نشطة</p>
                    </div>
                </div>
            </div>
        </header>

        @if ($errors->any())
            <div role="alert" class="bill-edit-error rounded-2xl border p-4 text-sm font-semibold leading-6">
                <p>تعذّر حفظ التعديلات. يرجى مراجعة البيانات التالية:</p>
                <ul class="mt-1 list-inside list-disc font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="bill-edit-surface overflow-hidden rounded-3xl border bill-edit-line shadow-sm">
            <div class="bill-edit-line border-b px-5 py-5 sm:px-7">
                <div class="flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <p class="text-[11px] font-extrabold tracking-wide text-[var(--brand-primary)]">محتويات الفاتورة</p>
                        <h2 class="mt-1 text-lg font-black">الأصناف والكميات</h2>
                    </div>
                    <p class="bill-edit-muted text-sm">
                        الإجمالي الحالي:
                        <strong
                            class="mr-1 font-black tabular-nums">{{ number_format((float) ($bill->total_price ?? 0), 0) }}</strong>
                        ل.س
                    </p>
                </div>
            </div>

            <form action="{{ route('admin.bills.update', $bill) }}" method="POST">
                @csrf
                @method('PUT')

                @if ($bill->orders->isEmpty())
                    <div class="px-5 py-12 text-center sm:px-8">
                        <span class="bill-edit-icon mx-auto flex h-14 w-14 items-center justify-center rounded-2xl"
                            aria-hidden="true">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7 3.75h7l4 4V20a.75.75 0 0 1-.75.75h-10.5A.75.75 0 0 1 6 20V4.5a.75.75 0 0 1 .75-.75Z" />
                                <path stroke-linecap="round" d="M14 4v4h4M9 13h6" />
                            </svg>
                        </span>
                        <h3 class="mt-4 font-extrabold">لا توجد طلبات في هذه الفاتورة</h3>
                        <p class="bill-edit-muted mt-1 text-sm">يمكنك العودة إلى قائمة الفواتير. لا توجد كميات لتعديلها.</p>
                    </div>
                @else
                    @php
                        $orderStatusLabels = [
                            '0' => 'قيد التأكيد',
                            '1' => 'في المطبخ',
                            '2' => 'على الطاولة',
                        ];
                        $orderStatusClasses = [
                            '0' => 'bill-edit-status-pending',
                            '1' => 'bill-edit-status-kitchen',
                            '2' => 'bill-edit-status-table',
                        ];
                    @endphp
                    <div class="divide-y bill-edit-line">
                        @foreach ($bill->orders as $order)
                            <article
                                class="grid gap-5 px-5 py-5 sm:grid-cols-[minmax(0,1fr)_10rem] sm:items-center sm:px-7">
                                <div class="min-w-0">
                                    <h3 class="truncate text-base font-black">
                                        {{ $order->food?->name ?? 'صنف غير متاح' }}
                                    </h3>
                                    <p class="bill-edit-muted mt-1 text-sm">
                                        سعر الوحدة الحالي:
                                        <span
                                            class="font-bold tabular-nums">{{ number_format((float) ($order->food?->price ?? 0), 0) }}</span>
                                        ل.س
                                    </p>
                                    <div class="bill-edit-muted mt-3 flex flex-wrap gap-x-4 gap-y-1 text-xs">
                                        <span>حالة الطلب:
                                            <span
                                                class="bill-edit-status {{ $orderStatusClasses[(string) $order->status] ?? 'bill-edit-status-unknown' }}">
                                                {{ $orderStatusLabels[(string) $order->status] ?? 'غير معروفة' }}
                                            </span>
                                        </span>
                                        <span>الوقت المتوقع: {{ $order->expected_time }}</span>
                                        @if ($order->note)
                                            <span>ملاحظة: {{ $order->note }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-end gap-3">
                                    <div class="min-w-0 flex-1">
                                        <label for="order-amount-{{ $order->id }}" class="mb-2 block text-sm font-bold">
                                            الكمية
                                        </label>
                                        <input id="order-amount-{{ $order->id }}" type="number"
                                            name="orders[{{ $order->id }}][amount]" min="1" step="1" required
                                            inputmode="numeric"
                                            value="{{ old('orders.' . $order->id . '.amount', $order->amount) }}"
                                            class="bill-edit-input min-h-12 w-full rounded-xl border px-4 py-3 text-sm font-bold tabular-nums outline-none transition">
                                        @error('orders.' . $order->id . '.amount')
                                            <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button type="submit" form="delete-order-{{ $order->id }}"
                                        aria-label="حذف {{ $order->food?->name ?? 'الطلب' }}"
                                        onclick="return confirm('هل أنت متأكد من حذف هذا الطلب؟');"
                                        class="bill-edit-delete inline-flex min-h-12 shrink-0 items-center justify-center gap-2 rounded-xl border px-3 text-sm font-bold transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                                        <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4 7h16M10 11v6m4-6v6M5.5 7l1 13h11l1-13M9 7V4h6v3" />
                                        </svg>
                                        <span>حذف</span>
                                    </button>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif

                <div
                    class="bill-edit-line flex flex-col-reverse gap-3 border-t px-5 py-5 sm:flex-row sm:justify-between sm:px-7">
                    <a href="{{ route('admin.bills.index') }}"
                        class="inline-flex min-h-11 items-center justify-center rounded-xl border bill-edit-line px-5 py-3 text-sm font-bold transition hover:bg-black/5 dark:hover:bg-white/5">
                        العودة إلى الفواتير
                    </a>
                    @if ($bill->orders->isNotEmpty())
                        <button type="submit"
                            class="inline-flex min-h-11 items-center justify-center rounded-xl bg-[var(--brand-primary)] px-6 py-3 text-sm font-bold text-white shadow-md transition hover:bg-[var(--brand-dark)] active:scale-[.99] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--brand-accent)]">
                            حفظ التعديلات
                        </button>
                    @endif
                </div>
            </form>
            @foreach ($bill->orders as $order)
                <form id="delete-order-{{ $order->id }}"
                    action="{{ route('admin.bills.orders.destroy', [$bill, $order]) }}" method="POST" hidden>
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        </section>
    </main>
@endsection
