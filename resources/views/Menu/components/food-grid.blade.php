            {{-- Menu heading --}}
            <section id="menu-items" class="scroll-mt-24">
                <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <div class="mb-2 flex items-center gap-2">
                            <span class="h-1 w-7 rounded-full bg-[#d8b36c]"></span>
                            <p class="text-xs font-extrabold text-[#59816d]">اختيارات تستحق التجربة</p>
                        </div>
                        <h2 class="menu-section-title text-2xl sm:text-3xl">قائمة الطعام</h2>
                        <p class="mt-2 text-sm leading-6 text-gray-500">تصفح الأصناف، اختر وجباتك، واجمع طلبك في مكان
                            واحد.
                        </p>
                    </div>

                    <span id="food-count"
                        class="inline-flex items-center gap-2 rounded-xl border border-[#dfe9df] bg-white px-4 py-2.5 text-sm font-extrabold text-[#174c3c]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 10h18M5 10V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v4M5 10v10M19 10v10M3 15h18" />
                        </svg>
                        {{ $categories->sum(fn($category) => $category->foods->count()) }} وجبة
                    </span>
                </div>

                @php
                    $foodItems = $categories->flatMap(function ($category) {
                        return $category->foods->map(function ($food) use ($category) {
                            $food->menu_category_id = $category->id;
                            $food->menu_category_name = $category->name;
                            return $food;
                        });
                    });
                @endphp

                <div id="food-grid" class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @forelse ($foodItems as $food)
                        <article data-is-active="{{ (int) $food->is_active }}" class="food-card group {{ $food->is_active == 0 ? 'opacity-30' : '' }}"
                            data-category="{{ $food->menu_category_id }}" data-name="{{ mb_strtolower($food->name) }}">
                            <div class="food-image-wrap relative aspect-square overflow-hidden">
                                @if ($food->image)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($food->image) }}"
                                        alt="{{ $food->name }}" loading="lazy"
                                        class="block h-full w-full object-contain">
                                @else
                                    <div
                                        class="flex h-full w-full flex-col items-center justify-center gap-3 text-[#9bad9f]">
                                        <div
                                            class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/80 shadow-sm">
                                            <svg class="h-9 w-9" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor">
                                                <rect x="3" y="3" width="18" height="18" rx="3"
                                                    stroke-width="1.4" />
                                                <circle cx="8.5" cy="8.5" r="1.5" stroke-width="1.4" />
                                                <path d="m21 15-5-5L5 21" stroke-width="1.4" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <span class="text-xs font-bold">صورة الوجبة</span>
                                    </div>
                                @endif

                                <div
                                    class="pointer-events-none absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-black/25 to-transparent">
                                </div>
                                <span
                                    class="absolute right-3 top-3 max-w-[80%] truncate rounded-lg border border-white/70 bg-white/95 px-3 py-1.5 text-[11px] font-extrabold text-[#174c3c] shadow-sm backdrop-blur">{{ $food->menu_category_name }}</span>
                            </div>

                            <div class="p-4 sm:p-5">
                                <h3 class="truncate text-base font-black text-gray-900 sm:text-lg">{{ $food->name }}
                                </h3>
                                <p class="mt-2 min-h-[3rem] text-sm leading-6 text-gray-500">
                                    {{ $food->description ?: 'طبق شهي محضّر بعناية، اختره وأضفه إلى طلبك.' }}</p>
                                <div class="my-4 menu-divider"></div>

                                <div class="flex items-center justify-between gap-2">
                                    <div class="min-w-0">
                                        <p class="text-[11px] font-bold text-gray-400">السعر</p>
                                        <p class="mt-1 whitespace-nowrap text-lg font-black text-[#174c3c]">
                                            {{ rtrim(rtrim(number_format((float) $food->price, 2, '.', ','), '0'), '.') }}
                                            <span class="text-[11px] font-bold text-gray-500">ل.س</span>
                                        </p>
                                    </div>
                                    @if ($food->is_active == 1)
                                        <button type="button" class="food-add add-to-order"
                                            data-id="{{ $food->id }}" data-name="{{ $food->name }}"
                                            data-price="{{ $food->price }}">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                                <path d="M12 5v14M5 12h14" />
                                            </svg>
                                            أضف للطلب
                                        </button>
                                    @else
                                        <span
                                            class="inline-flex items-center justify-center rounded-xl border border-amber-200 bg-amber-50 px-4 py-2.5 text-xs font-extrabold text-amber-700">
                                            نفدت الكمية
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @empty
                        <div
                            class="col-span-full rounded-3xl border border-dashed border-gray-300 bg-white px-5 py-16 text-center">
                            <div
                                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-[#e8efe8] text-[#174c3c]">
                                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 7h16M4 12h16M4 17h16" />
                                </svg>
                            </div>
                            <h3 class="font-extrabold text-gray-800">قائمة الطعام فارغة حالياً</h3>
                            <p class="mt-2 text-sm leading-6 text-gray-500">ستظهر الوجبات هنا عند إضافتها وتفعيلها.</p>
                        </div>
                    @endforelse
                </div>

                <div id="no-results"
                    class="mt-5 hidden rounded-3xl border border-dashed border-gray-300 bg-white px-5 py-14 text-center">
                    <div
                        class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#f0f4ef] text-[#174c3c]">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m16 16 4 4" />
                        </svg>
                    </div>
                    <h3 class="font-extrabold text-gray-800">لم نعثر على نتائج</h3>
                    <p class="mt-2 text-sm text-gray-500">جرّب البحث باسم آخر أو اختر تصنيفاً مختلفاً.</p>
                    <button id="reset-filters" type="button"
                        class="mt-5 rounded-xl bg-[#174c3c] px-5 py-3 text-xs font-extrabold text-white transition hover:bg-[#10382c]">
                        عرض جميع الوجبات
                    </button>
                </div>
            </section>
