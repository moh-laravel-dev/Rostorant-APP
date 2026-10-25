<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#174c3c">
    <title>طاولاتي - الكابتن</title>
    @include('components.theme-mode')
    @vite(['resources/css/colors.css', 'resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 text-gray-800">
    <main class="mx-auto max-w-7xl space-y-6 p-4 sm:p-6 lg:p-8">
        <header
            class="flex flex-col gap-4 rounded-3xl bg-gradient-to-br from-[var(--restaurant-color-hex-174c3c)] to-[var(--restaurant-color-hex-10382c)] p-5 text-white shadow-lg sm:flex-row sm:items-center sm:justify-between sm:p-8">
            <div>
                <p class="text-sm font-semibold text-emerald-100/80">مرحباً، {{ auth('captain')->user()->name }}</p>
                <h1 class="mt-1 text-2xl font-extrabold sm:text-3xl">الطاولات المسندة إليّ</h1>
                <p class="mt-2 text-sm text-emerald-50/80">حالة الطاولات ورموزها تتحدث مباشرةً عند تغيّرها.</p>
            </div>
            <form action="{{ route('captain.logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="rounded-xl border border-white/25 bg-white/10 px-4 py-3 text-sm font-bold text-white transition hover:bg-white/20">
                    تسجيل الخروج
                </button>
            </form>
        </header>

        <div id="captain-live-status" role="status" class="text-xs text-gray-500">جاري الاتصال بالتحديثات المباشرة…
        </div>

        @if ($tables->isEmpty())
            <section class="rounded-2xl border border-gray-200 bg-white p-8 text-center text-gray-500">
                لم تُسند إليك أي طاولات بعد. تواصل مع الإدارة.
            </section>
        @else
            <section class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($tables as $table)
                    @php
                        $tableDialogId = 'captain-table-dialog-' . $table->id;
                    @endphp
                    <button type="button" data-table-dialog-open="{{ $tableDialogId }}" aria-haspopup="dialog"
                        aria-controls="{{ $tableDialogId }}"
                        aria-label="عرض تفاصيل طاولة {{ $table->number }}: {{ $table->name }}"
                        class="overflow-hidden rounded-2xl border text-right shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-700 {{ $table->status ? 'border-gray-200 bg-white' : 'border-red-200 bg-red-100' }}">
                        <span class="flex items-start justify-between gap-3 border-b border-gray-200/70 p-5">
                            <span>
                                <span class="block text-lg font-extrabold text-gray-800">{{ $table->name }}</span>
                                <span class="mt-1 block text-sm font-semibold text-gray-600">رقم الطاولة:
                                    {{ $table->number }}</span>
                            </span>
                            <span
                                class="rounded-full px-3 py-1 text-xs font-bold {{ $table->status ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-800' }}">
                                {{ $table->status ? 'متاحة' : 'مشغولة' }}
                            </span>
                        </span>
                        <span class="block space-y-4 p-5">
                            <span class="block text-center">
                                <img class="mx-auto h-40 w-40" alt="رمز QR للطاولة {{ $table->number }}"
                                    src="data:image/svg+xml;base64,{{ $table->qr_image }}">
                            </span>
                            <span
                                class="block rounded-xl {{ $table->status ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-800' }} p-3 text-center">
                                <span class="block text-xs font-bold text-gray-500">رمز الطاولة</span>
                                <span class="mt-1 block font-mono text-sm font-bold text-gray-800"
                                    dir="ltr">{{ $table->code }}</span>
                            </span>
                        </span>
                    </button>

                    <dialog id="{{ $tableDialogId }}"
                        class="fixed inset-0 m-auto max-h-[90vh] w-[calc(100%-2rem)] max-w-lg overflow-y-auto rounded-2xl p-0 shadow-2xl backdrop:bg-black/40 backdrop:backdrop-blur-sm">
                        <section class="overflow-hidden rounded-2xl {{ $table->status ? 'bg-white' : 'bg-red-100' }}">
                            <header class="flex items-start justify-between gap-4 border-b border-gray-200/70 p-5">
                                <div>
                                    <h2 class="text-xl font-extrabold text-gray-900">{{ $table->name }}</h2>
                                    <p class="mt-1 text-sm font-semibold text-gray-600">رقم الطاولة:
                                        {{ $table->number }}</p>
                                </div>
                                <button type="button" data-table-dialog-close
                                    class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-bold text-gray-700 hover:bg-gray-50"
                                    aria-label="إغلاق">إغلاق</button>
                            </header>
                            <div class="space-y-4 p-5">
                                <p class="text-center">
                                    <span
                                        class="inline-flex rounded-full px-4 py-2 text-sm font-bold {{ $table->status ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-800' }}">
                                        {{ $table->status ? 'متاحة' : 'مشغولة' }}
                                    </span>
                                </p>
                                <img class="mx-auto h-48 w-48" alt="رمز QR للطاولة {{ $table->number }}"
                                    src="data:image/svg+xml;base64,{{ $table->qr_image }}">
                                <div
                                    class="rounded-xl {{ $table->status ? 'bg-green-50' : 'bg-red-50' }} p-4 text-center">
                                    <p class="text-xs font-bold text-gray-600">رمز الطاولة</p>
                                    <p class="mt-1 break-all font-mono text-base font-bold text-gray-900"
                                        dir="ltr">{{ $table->code }}</p>
                                </div>
                                @if (!$table->status)
                                    <section class="space-y-4 border-t border-white/70 pt-4">
                                        <h3 class="text-lg font-extrabold text-gray-900">الفواتير والطلبات</h3>
                                        @if ($table->bills->isEmpty())
                                            <p class="rounded-xl bg-white p-4 text-sm text-gray-600">لا توجد فاتورة نشطة
                                                أو بانتظار الدفع لهذه الطاولة.</p>
                                        @else
                                            @foreach ($table->bills as $bill)
                                                @php
                                                    $billServices = collect($bill->services ?? [])->filter(
                                                        fn($service) => is_array($service) &&
                                                            filled($service['name'] ?? null) &&
                                                            is_numeric($service['price'] ?? null),
                                                    );
                                                    $ordersTotal = $bill->orders->sum(
                                                        fn($order) => (float) ($order->total_price ?? 0),
                                                    );
                                                    $servicesTotal = $billServices->sum(
                                                        fn($service) => (float) $service['price'],
                                                    );
                                                    $billTotal =
                                                        $bill->total_price !== null && $bill->total_price !== 0
                                                            ? (float) $bill->total_price
                                                            : $ordersTotal + $servicesTotal;
                                                @endphp
                                                <article
                                                    class="space-y-3 rounded-xl border border-gray-200 bg-white p-4">
                                                    <div
                                                        class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-200 pb-3">
                                                        <h4 class="font-bold text-sm text-gray-800">الفاتورة
                                                            {{ $bill->number }}</h4>
                                                        <span
                                                            class="rounded-full bg-white-50 px-3 py-1 text-xs font-bold text-gray-700">
                                                            {{ $bill->status === 'payment' ? 'بانتظار الدفع' : 'نشطة' }}
                                                        </span>
                                                    </div>

                                                    <div>
                                                        <h5 class="mb-2 text-sm font-extrabold text-gray-700">الطلبات
                                                        </h5>
                                                        @if ($bill->orders->isEmpty())
                                                            <p class="rounded-lg bg-white-50 p-3 text-sm text-gray-500">
                                                                لا توجد طلبات لهذه الفاتورة.</p>
                                                        @else
                                                            @foreach ($bill->orders as $order)
                                                                <div
                                                                    class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-100 py-2 last:border-0">
                                                                    <div>
                                                                        <p class="font-semibold text-gray-800">
                                                                            {{ $order->food?->name ?? 'صنف غير متوفر' }}
                                                                        </p>
                                                                        <p class="mt-0.5 text-xs text-gray-500">
                                                                            الكمية: {{ (int) ($order->amount ?? 0) }}
                                                                            ·
                                                                            {{ ['0' => 'بانتظار التأكيد', '1' => 'قيد التحضير', '2' => 'على الطاولة'][(string) $order->status] ?? 'حالة غير معروفة' }}
                                                                        </p>
                                                                        @if ($order->note)
                                                                            <p class="mt-1 text-xs text-gray-600">
                                                                                ملاحظة: {{ $order->note }}</p>
                                                                        @endif
                                                                    </div>
                                                                    <span class="text-sm font-bold text-gray-700">
                                                                        {{ number_format((float) ($order->total_price ?? 0), 2) }}
                                                                        ل.س
                                                                    </span>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>

                                                    <div>
                                                        <h5 class="mb-2 text-sm font-extrabold text-gray-700">الخدمات
                                                        </h5>
                                                        @if ($billServices->isEmpty())
                                                            <p class="rounded-lg bg-white-50 p-3 text-sm text-gray-500">
                                                                لا توجد خدمات مضافة.</p>
                                                        @else
                                                            @foreach ($billServices as $service)
                                                                <div
                                                                    class="flex items-center justify-between gap-2 border-b border-gray-100 py-2 last:border-0">
                                                                    <span
                                                                        class="text-sm text-gray-700">{{ $service['name'] }}</span>
                                                                    <span class="text-sm font-bold text-gray-700">
                                                                        {{ number_format((float) $service['price'], 2) }}
                                                                        {{ $service['currency'] ?? 'ل.س' }}
                                                                    </span>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>

                                                    <div
                                                        class="flex items-center justify-between border-t border-gray-300 pt-3">
                                                        <span class="font-extrabold text-gray-800">الإجمالي</span>
                                                        <span
                                                            class="text-lg font-black text-gray-900">{{ number_format($billTotal, 2) }}
                                                            ل.س</span>
                                                    </div>
                                                </article>
                                            @endforeach
                                        @endif
                                    </section>
                                @endif
                            </div>
                        </section>
                    </dialog>
                @endforeach
            </section>
        @endif
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const status = document.querySelector('#captain-live-status');
            let echoConnected = false;
            document.querySelectorAll('[data-table-dialog-open]').forEach(function(trigger) {
                const dialog = document.getElementById(trigger.dataset.tableDialogOpen);
                const closeButton = dialog?.querySelector('[data-table-dialog-close]');

                if (!dialog || !closeButton) {
                    return;
                }

                trigger.addEventListener('click', function() {
                    dialog.showModal();
                });
                closeButton.addEventListener('click', function() {
                    dialog.close();
                });
                dialog.addEventListener('click', function(event) {
                    if (event.target === dialog) {
                        dialog.close();
                    }
                });
            });
            const refreshDashboard = function() {
                if (!document.hidden) {
                    window.location.reload();
                }
            };
            const connectCaptainUpdates = function() {
                if (!window.Echo || echoConnected) {
                    return;
                }
                echoConnected = true;
                status.textContent = 'متصل — التحديثات مباشرة';
                window.Echo.private('captain.{{ auth('captain')->id() }}.tables')
                    .listen('.cashier.dashboard.updated', refreshDashboard);
            };

            connectCaptainUpdates();
            if (!window.Echo) {
                status.textContent = 'سيتم التحديث تلقائياً كل 15 ثانية.';
            }
            window.addEventListener('restaurant:echo-ready', connectCaptainUpdates);
            window.setInterval(refreshDashboard, 15000);
        });
    </script>
</body>

</html>
