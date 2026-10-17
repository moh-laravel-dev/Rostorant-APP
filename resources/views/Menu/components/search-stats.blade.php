            {{-- Search and quick stats --}}
            <div class="menu-feature relative z-10 mb-9 p-3 sm:p-4">
                <div class="flex flex-col gap-4 md:flex-row md:items-center">
                    <div class="relative min-w-0 flex-1">
                        <svg class="pointer-events-none absolute right-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <circle cx="11" cy="11" r="7" stroke-width="1.8" />
                            <path d="m16 16 4 4" stroke-width="1.8" stroke-linecap="round" />
                        </svg>

                        <input id="menu-search" type="search" autocomplete="off" placeholder="ابحث عن وجبتك المفضلة..."
                            class="menu-search">

                        <button id="clear-search" type="button"
                            class="absolute left-3 top-1/2 hidden -translate-y-1/2 rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-700"
                            aria-label="مسح البحث">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m18 6-12 12M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    {{-- Food Status Filter --}}
                    <div class="relative min-w-0 md:w-52">
                        <select id="food-status-filter"
                            class="w-full appearance-none rounded-2xl border border-[var(--restaurant-color-hex-dce8dd)] bg-white px-4 py-3.5 pl-10 text-sm font-bold text-[var(--restaurant-color-hex-174c3c)] shadow-sm outline-none transition duration-200 hover:border-[var(--restaurant-color-hex-8eae96)] focus:border-[var(--restaurant-color-hex-59816d)] focus:ring-4 focus:ring-[var(--restaurant-color-hex-59816d)]/10">
                            <option value="all">جميع الوجبات</option>
                            <option value="1">الوجبات المتاحة</option>
                            <option value="0">الوجبات غير المتاحة</option>
                        </select>

                        <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[var(--restaurant-color-hex-59816d)]"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="m7 10 5 5 5-5" />
                        </svg>
                    </div>
                    <div class="grid grid-cols-2 gap-3 md:w-auto md:min-w-[240px]">
                        <div class="rounded-xl bg-[var(--restaurant-color-hex-f4f7f2)] px-4 py-3">
                            <p class="text-[11px] font-bold text-gray-500">التصنيفات</p>
                            <p class="mt-1 text-lg font-black text-[var(--restaurant-color-hex-174c3c)]">{{ $categories->count() }}</p>
                        </div>
                        <div class="rounded-xl bg-[var(--restaurant-color-hex-f4f7f2)] px-4 py-3">
                            <p class="text-[11px] font-bold text-gray-500">الوجبات</p>
                            <p id="food-count-top" class="mt-1 text-lg font-black text-[var(--restaurant-color-hex-174c3c)]">
                                {{ $categories->sum(fn($category) => $category->foods->count()) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
