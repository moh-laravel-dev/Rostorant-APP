        {{-- Order Bar --}}
        <div id="order-bar"
            class="menu-bottom-bar fixed inset-x-0 bottom-0 z-40 border-t border-gray-200/80 bg-white/95 p-2 shadow-[0_-8px_35px_var(--restaurant-color-rgb-0-0-0-point-08)] backdrop-blur-xl sm:p-4">

            <div class="mx-auto flex max-w-7xl flex-col gap-3 sm:flex-row sm:items-center sm:justify-between sm:gap-4">

                {{-- Order Summary --}}
                <div id= 'order-summary' class="grid min-w-10 grid-cols-2 gap-3">

                    {{-- Current Orders --}}
                    <div
                        class="flex min-w-0 items-center gap-2 rounded-xl border border-[var(--restaurant-color-hex-174c3c)]/10 bg-[var(--restaurant-color-hex-f4f8f4)] p-2 sm:gap-3 sm:p-3">
                        <div
                            class="relative flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[var(--restaurant-color-hex-e8f0e8)] text-[var(--restaurant-color-hex-174c3c)] sm:h-11 sm:w-11 sm:rounded-xl">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M7 2v7" />
                                <path d="M4 2v4a3 3 0 0 0 6 0V2" />
                                <path d="M7 9v13" />
                                <path d="M17 2a4 4 0 0 0-4 4v4h4V2Z" />
                                <path d="M17 10v12" />
                            </svg>

                            <span id="current-order-badge"
                                class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-[var(--restaurant-color-hex-d8b36c)] px-1 text-[10px] font-black text-[var(--restaurant-color-hex-26372b)]">
                                0
                            </span>
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] text-gray-500 sm:text-xs">الطلبات الحالية</p>

                            <p class="mt-1 text-xs font-black text-gray-900 sm:text-sm">
                                <span id="current-order-count">0</span>
                                <span class="text-[10px] font-medium text-gray-500 sm:text-xs">وجبة</span>
                            </p>

                            <p
                                class="mt-1 truncate text-xs font-black text-[var(--restaurant-color-hex-174c3c)] sm:text-sm">
                                <span id="current-order-total">0</span>
                                <span class="text-[10px] font-bold">ل.س</span>
                            </p>
                        </div>
                    </div>

                    {{-- Previous Orders --}}
                    <div id = "previousOrders"
                        class="flex min-w-0 items-center gap-2 rounded-xl border  border-[var(--restaurant-color-hex-174c3c)]/10 bg-[var(--restaurant-color-hex-f4f8f4)]  p-2 sm:gap-3 sm:p-3">
                        <div
                            class="relative flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[var(--restaurant-color-hex-e8f0e8)] text-[var(--restaurant-color-hex-174c3c)] sm:h-11 sm:w-11 sm:rounded-xl">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 8v4l3 2" />
                                <circle cx="12" cy="12" r="9" />
                            </svg>

                            <span id="previous-order-badge"
                                class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-amber-300 px-1 text-[10px] font-black text-amber-950">
                                0
                            </span>
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] text-gray-500 sm:text-xs">الطلبات السابقة</p>

                            <p class="mt-1 text-xs font-black text-gray-900 sm:text-sm">
                                <span id="previous-order-count">0</span>
                                <span class="text-[10px] font-medium text-gray-500 sm:text-xs">وجبة</span>
                            </p>

                            <p
                                class="mt-1 truncate text-xs font-black text-[var(--restaurant-color-hex-174c3c)] sm:text-sm">
                                <span id="previous-order-total">0</span>
                                <span class="text-[10px] font-bold">ل.س</span>
                            </p>
                        </div>
                    </div>

                </div>

                {{-- Desktop Total --}}
                <div class="hidden shrink-0 text-left sm:block">
                    <p class="text-xs text-gray-500">الإجمالي</p>
                    <p class="mt-1 whitespace-nowrap text-base font-black text-[var(--restaurant-color-hex-174c3c)]">
                        <span id="order-total-desktop">0</span>
                        <span class="text-xs font-bold">ل.س</span>
                    </p>
                </div>

                {{-- Action Buttons --}}
                <div
                    class="grid w-full grid-cols-1 gap-2 sm:flex sm:w-auto sm:shrink-0 sm:flex-wrap sm:justify-center sm:gap-3">

                    <button id="submit-order" type="button"
                        class="menu-btn flex min-w-0 w-full items-center justify-center gap-1.5 rounded-xl bg-[var(--restaurant-color-hex-174c3c)] px-2 py-3 text-[11px] text-white shadow-md shadow-[var(--restaurant-color-hex-174c3c)]/10 transition hover:bg-[var(--restaurant-color-hex-10382c)] min-[400px]:text-xs sm:w-auto sm:gap-2 sm:px-5 sm:text-sm">

                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7 2v7" />
                            <path d="M4 2v4a3 3 0 0 0 6 0V2" />
                            <path d="M7 9v13" />
                            <path d="M17 2a4 4 0 0 0-4 4v4h4V2Z" />
                            <path d="M17 10v12" />
                        </svg>

                        <span class="truncate">جميع الطلبات</span>

                        <span id="button-order-count"
                            class="flex h-5 min-w-5 shrink-0 items-center justify-center rounded-full bg-white/20 px-1 text-[10px]">
                            0
                        </span>
                    </button>

                </div>

            </div>
        </div>
