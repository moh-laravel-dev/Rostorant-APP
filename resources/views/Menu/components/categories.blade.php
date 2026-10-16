            {{-- Categories --}}
            <section id="categories-section" class="mb-10 scroll-mt-24">
                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <div class="mb-2 flex items-center gap-2">
                            <span class="h-1 w-7 rounded-full bg-[#d8b36c]"></span>
                            <p class="text-xs font-extrabold tracking-wide text-[#59816d]">اكتشف نكهاتنا</p>
                        </div>
                        <h2 class="menu-section-title text-xl sm:text-2xl">ماذا تشتهي اليوم؟</h2>
                        <p class="mt-2 text-sm leading-6 text-gray-500">اختر التصنيف المناسب واستكشف أشهى وجباتنا.</p>
                    </div>

                    <div
                        class="flex items-center gap-2 self-start rounded-xl border border-[#e5ebe3] bg-white px-3 py-2 sm:self-auto">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#eaf1e9] text-[#174c3c]">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </span>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400">عدد التصنيفات</p>
                            <p class="text-sm font-black text-[#174c3c]">{{ $categories->count() }} <span
                                    class="text-xs font-medium text-gray-500">تصنيف</span></p>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div
                        class="pointer-events-none absolute inset-y-0 left-0 z-10 w-5 bg-gradient-to-r from-[#f7f8f5] to-transparent">
                    </div>

                    <div class="flex items-center justify-between gap-4 py-2">
                        <div class="flex items-center gap-2">
                            <span class="h-1 w-8 rounded-full bg-[#45a099]"></span>
                            <span class="h-1 w-2 rounded-full bg-[#45a099]/30"></span>
                        </div>

                        <button type="button" onclick="scrollCategories()"
                            class="group ml-6 inline-flex shrink-0 items-center gap-2 rounded-full border border-[#174c3c]/10 bg-gradient-to-l from-[#174c3c]/[0.06] to-[#45a099]/[0.12] py-2 pl-3 pr-4 text-sm font-bold text-[#174c3c] shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-[#45a099]/40 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-[#45a099]/40">
                            <span>تابع للمزيد</span>
                            <span
                                class="flex h-7 w-7 items-center justify-center rounded-full bg-[#174c3c] text-white transition-colors duration-300 group-hover:bg-[#45a099]">
                                <svg class="h-4 w-4 rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14" />
                                    <path d="m12 5 7 7-7 7" />
                                </svg>
                            </span>
                        </button>
                    </div>

                    <div id="categories" class="flex snap-x snap-mandatory gap-3 overflow-x-auto px-1 py-3 pl-7"
                        style="scrollbar-width: thin; scrollbar-color: #cbd8ca transparent;" role="group"
                        aria-label="تصفية الوجبات حسب التصنيف">
                        <button type="button" data-category="all" aria-pressed="true"
                            class="menu-category active group min-w-[125px] snap-start flex-col !items-start !gap-3 !rounded-2xl !p-4">
                            <span class="flex w-full items-center justify-between gap-3">
                                <span
                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#e8f0e8] text-[#174c3c] transition group-[.active]:bg-white/15 group-[.active]:text-[#e8c98c]">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                </span>
                                <svg class="h-4 w-4 opacity-50 transition group-[.active]:opacity-100" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </span>
                            <span class="text-sm font-black">كل الأصناف</span>
                            <span
                                class="text-xs font-bold opacity-70">{{ $categories->sum(fn($category) => $category->foods->count()) }}
                                وجبة</span>
                        </button>

                        @foreach ($categories as $category)
                            <button type="button" data-category="{{ $category->id }}" aria-pressed="false"
                                class="menu-category group min-w-[125px] snap-start flex-col !items-start !gap-3 !rounded-2xl !p-4">
                                <span class="flex w-full items-center justify-between gap-3">
                                    <span
                                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#f3f5f0] text-[#59816d] transition group-[.active]:bg-white/15 group-[.active]:text-[#e8c98c]">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                            <path
                                                d="M3 10h18M5 10V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v4M5 10v10M19 10v10M3 15h18" />
                                        </svg>
                                    </span>
                                    <span class="w-full truncate text-right text-sm font-black"
                                        title="{{ $category->name }}">{{ $category->name }}</span>
                                </span>
                                <span class="text-xs font-bold opacity-70">{{ $category->foods->count() }} وجبة</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </section>
