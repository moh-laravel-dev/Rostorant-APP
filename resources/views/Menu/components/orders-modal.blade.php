{{-- Orders Modal --}}
<div id="orders-modal"
    class="fixed inset-0 z-[100] hidden items-center justify-center overflow-y-auto bg-black/55 p-3 backdrop-blur-sm sm:p-5"
    role="dialog" aria-modal="true" aria-labelledby="orders-modal-title" aria-hidden="true">

    @php
        $previousOrdersTotal = collect($data ?? [])->sum(fn($item) => (float) ($item['total_price'] ?? 0));

        $servicesTotal = collect($services ?? [])->sum(fn($service) => (float) ($service['price'] ?? 0));

        $initialFinalTotal = $previousOrdersTotal + $servicesTotal;
    @endphp

    <div
        class="orders-dialog my-auto flex max-h-[94dvh] w-full max-w-lg flex-col overflow-hidden rounded-3xl bg-white shadow-2xl">

        {{-- Header --}}
        <div class="flex shrink-0 items-center justify-between gap-4 border-b border-gray-100 bg-[var(--restaurant-color-hex-f7f8f5)] p-4 sm:p-6">
            <div class="flex min-w-0 items-center gap-3">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[var(--restaurant-color-hex-174c3c)] text-[var(--restaurant-color-hex-e8c98c)]">
                    {{-- Food Icon --}}
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 11h18" />
                        <path d="M4 11a8 8 0 0 0 16 0" />
                        <path d="M7 7c1-1 2-1 3 0s2 1 3 0 2-1 3 0" />
                        <path d="M5 15h14" />
                    </svg>
                </div>

                <div class="min-w-0">
                    <h2 id="orders-modal-title" class="text-lg font-black text-[var(--restaurant-color-hex-174c3c)] sm:text-xl">
                        قائمة طلباتي
                    </h2>
                    <p class="mt-1 text-xs text-gray-500 sm:text-sm">
                        راجع وجباتك وخدمات الطاولة قبل تأكيد الطلب
                    </p>
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

        {{-- Modal Content --}}
        <div id="orders-list" class="min-h-0 flex-1 space-y-4 overflow-y-auto overscroll-contain p-4 sm:p-5">

            {{-- Previous Orders --}}
            <section id="previous-orders-section" class="space-y-3">
                <div class="flex items-center justify-between border-b border-gray-200 pb-3">
                    <div class="flex min-w-0 items-center gap-2">
                        <h3 class="text-sm font-extrabold text-[var(--restaurant-color-hex-174c3c)]">
                            الطلبات السابقة
                        </h3>

                        <span id="previous-orders-count"
                            class="rounded-full bg-[var(--restaurant-color-hex-e8f0e8)] px-3 py-1 text-xs font-bold text-[var(--restaurant-color-hex-174c3c)]">
                            {{ count($data ?? []) }} صنف
                        </span>
                    </div>

                    <button id="refresh-previous-orders" type="button"
                        class="inline-flex shrink-0 items-center gap-2 rounded-xl border border-[var(--restaurant-color-hex-174c3c)]/15 bg-white px-3 py-2 text-xs font-bold text-[var(--restaurant-color-hex-174c3c)] transition hover:bg-[var(--restaurant-color-hex-e8f0e8)] disabled:cursor-not-allowed disabled:opacity-50">
                        <svg id="refresh-previous-orders-icon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h5M20 20v-5h-5" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M5.64 9A7 7 0 0 1 18 6l2 3M4 15l2 3a7 7 0 0 0 12.36-3" />
                        </svg>

                        <span>تحديث الحالات</span>
                    </button>
                </div>

                <div id="previous-order-items" class="space-y-3"></div>

                <div id="previous-orders-empty"
                    class="{{ count($data ?? []) > 0 ? 'hidden' : '' }} rounded-xl border border-dashed border-gray-200 px-4 py-3 text-center text-xs text-gray-500">
                    لا توجد طلبات سابقة لهذه الفاتورة.
                </div>

                {{-- <div class="flex items-center justify-between rounded-xl bg-gray-50 px-3 py-2.5">
        <span class="text-xs font-bold text-gray-600">مجموع الطلبات السابقة</span>
        <span id="modal-previous-orders-total" class="text-sm font-extrabold text-gray-800">
            {{ number_format($previousOrdersTotal, 0) }} ل.س
        </span>
    </div> --}}
            </section>

            {{-- Current Orders: JavaScript renders items here --}}
            <section id="current-orders-section" class="hidden space-y-3">
                <div class="flex items-center justify-between border-b border-gray-200 pb-3">
                    <h3 class="text-sm font-extrabold text-[var(--restaurant-color-hex-174c3c)]">
                        الطلبات الحالية
                    </h3>

                    <span id="current-orders-count"
                        class="rounded-full bg-[var(--restaurant-color-hex-e8f0e8)] px-3 py-1 text-xs font-bold text-[var(--restaurant-color-hex-174c3c)]">
                        0 وجبة
                    </span>
                </div>

                <div id="current-order-items" class="space-y-3"></div>

                {{-- <div class="flex items-center justify-between rounded-xl bg-gray-50 px-3 py-2.5">
                    <span class="text-xs font-bold text-gray-600">مجموع الطلبات الحالية</span>
                    <span id="modal-current-orders-total" class="text-sm font-extrabold text-gray-800">
                        0 ل.س
                    </span>
                </div> --}}
            </section>

            {{-- Table Services --}}
            <section id="order-services" class="rounded-2xl border border-[var(--restaurant-color-hex-174c3c)]/10 bg-[var(--restaurant-color-hex-e8f0e8)]/50 p-4">

                <div class="mb-3 flex items-center justify-between border-b border-[var(--restaurant-color-hex-174c3c)]/10 pb-3">
                    <div class="flex items-center gap-2">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-white text-[var(--restaurant-color-hex-174c3c)]">
                            {{-- Services Icon --}}
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9" />
                                <path d="M12 8v8M8 12h8" />
                            </svg>
                        </div>

                        <h3 class="text-sm font-extrabold text-[var(--restaurant-color-hex-174c3c)]">
                            خدمات الطاولة
                        </h3>
                    </div>

                    <span class="text-xs text-gray-500">
                        {{ count($services ?? []) }} خدمة
                    </span>
                </div>

                @if (count($services ?? []) > 0)
                    <div class="space-y-3">
                        @foreach ($services as $service)
                            <div class="flex items-center justify-between gap-3">
                                <span class="break-words text-sm font-medium text-gray-700">
                                    {{ $service['name'] ?? 'خدمة' }}
                                </span>

                                <span class="shrink-0 text-sm font-extrabold text-[var(--restaurant-color-hex-174c3c)]">
                                    {{ number_format((float) ($service['price'] ?? 0), 0) }}
                                    {{ $service['currency'] ?? 'ل.س' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="py-2 text-sm text-gray-500">
                        لا توجد خدمات إضافية.
                    </p>
                @endif

                <div class="mt-3 flex items-center justify-between border-t border-[var(--restaurant-color-hex-174c3c)]/10 pt-3">
                    <span class="text-sm font-bold text-gray-600">مجموع الخدمات</span>
                    <span id="order-services-total" class="font-black text-[var(--restaurant-color-hex-174c3c)]">
                        {{ number_format($servicesTotal, 0) }} ل.س
                    </span>
                </div>
            </section>

            {{-- Empty Current Basket --}}
            <div id="orders-empty" class="flex flex-col items-center justify-center px-4 py-7 text-center">
                {{-- Food Icon --}}
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[var(--restaurant-color-hex-e8f0e8)] text-[var(--restaurant-color-hex-174c3c)]">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 11h18" />
                        <path d="M4 11a8 8 0 0 0 16 0" />
                        <path d="M7 7c1-1 2-1 3 0s2 1 3 0 2-1 3 0" />
                    </svg>
                </div>

                <p class="mt-3 font-extrabold text-gray-800">لا توجد وجبات جديدة</p>
                <p class="mt-1 text-sm text-gray-500">
                    يمكنك إضافة وجبات جديدة أو متابعة خدمات الطاولة.
                </p>

                <button id="browse-menu" type="button"
                    class="mt-4 rounded-xl bg-[var(--restaurant-color-hex-e8f0e8)] px-4 py-2.5 text-xs font-extrabold text-[var(--restaurant-color-hex-174c3c)] transition hover:bg-[var(--restaurant-color-hex-dce9dc)]">
                    تصفح قائمة الطعام
                </button>
            </div>
        </div>

        {{-- Footer --}}
        <div class="shrink-0 border-t border-gray-100 bg-[var(--restaurant-color-hex-f9faf7)] p-4 sm:p-5">
            <div class="mb- flex items-center justify-between gap-3">
                <span class="text-sm font-bold text-gray-600">الطلبات السابقة</span>
                <span id="footer-previous-orders-total" class="font-bold text-gray-700">
                    {{ number_format($previousOrdersTotal, 0) }} ل.س
                </span>
            </div>

            <div class="mb-3 flex items-center justify-between gap-3">
                <span class="text-sm font-bold text-gray-600">الطلبات الحالية</span>
                <span id="footer-current-orders-total" class="font-bold text-gray-700">
                    0 ل.س
                </span>
            </div>

            <div class="mb-4 flex items-center justify-between gap-3">
                <span class="text-sm font-bold text-gray-600">الخدمات</span>
                <span id="footer-services-total" class="font-bold text-gray-700">
                    {{ number_format($servicesTotal, 0) }} ل.س
                </span>
            </div>

            <div class="mb-4 flex items-center justify-between gap-3 border-t border-gray-200 pt-4">
                <div>
                    <span class="block text-sm font-extrabold text-gray-800">
                        الإجمالي النهائي
                    </span>
                    <span class="mt-1 block text-xs text-gray-500">
                        الطلبات السابقة + الطلبات الحالية + الخدمات
                    </span>
                </div>

                <span class="whitespace-nowrap text-xl font-black text-[var(--restaurant-color-hex-174c3c)]">
                    <span id="modal-order-total">{{ number_format($initialFinalTotal, 0) }}</span>
                    <span class="text-xs">ل.س</span>
                </span>
            </div>

            <button id="confirm-order-list" type="button" disabled
                class="confirm-order-button flex w-full items-center justify-center gap-2 rounded-2xl bg-[var(--restaurant-color-hex-174c3c)] px-5 py-4 text-sm font-black text-white shadow-lg shadow-[var(--restaurant-color-hex-174c3c)]/15 transition hover:bg-[var(--restaurant-color-hex-10382c)] focus:outline-none focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/20 disabled:cursor-not-allowed disabled:opacity-50">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="m5 12 4 4L19 6" />
                </svg>
                اضغط مطولاً لتأكيد الطلب
            </button>

            <p class="mt-3 text-center text-xs leading-5 text-gray-400">
                عند التأكيد سيتم إرسال الوجبات المحددة حالياً.
            </p>
        </div>
    </div>
</div>
