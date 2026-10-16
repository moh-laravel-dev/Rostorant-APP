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

                <div id="food-grid"
                    class="grid grid-cols-1 justify-items-center gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @forelse ($foodItems as $food)
                        <article data-is-active="{{ (int) $food->is_active }}"
                            class="food-card group relative w-full max-w-[330px] overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg {{ $food->is_active == 0 ? 'opacity-60 grayscale-[20%]' : '' }}"
                            data-category="{{ $food->menu_category_id }}" data-name="{{ mb_strtolower($food->name) }}">

                            {{-- Food Image --}}
                            <div
                                class="food-image-wrap relative aspect-[5/4] overflow-hidden bg-gradient-to-br from-[#f4f7f2] via-[#e8efe7] to-[#dce8dc]">

                                @if ($food->image)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($food->image) }}"
                                        alt="{{ $food->name }}" loading="lazy"
                                        class="block h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                                @else
                                    <div
                                        class="flex h-full w-full flex-col items-center justify-center gap-2 text-[#8ca294]">
                                        <div
                                            class="flex h-14 w-14 items-center justify-center rounded-2xl border border-white/80 bg-white/70 shadow-sm">
                                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.4">
                                                <rect x="3" y="3" width="18" height="18" rx="4" />
                                                <circle cx="8.5" cy="8.5" r="1.5" />
                                                <path d="m21 15-5-5L5 21" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <span class="text-[10px] font-bold">صورة الوجبة</span>
                                    </div>
                                @endif

                                {{-- Creative Image Overlay --}}
                                <div
                                    class="pointer-events-none absolute inset-0 bg-gradient-to-t from-[#10291e]/55 via-transparent to-white/10">
                                </div>

                                {{-- Category Badge --}}
                                <span
                                    class="absolute right-2 top-2 inline-flex max-w-[85%] items-center gap-1 truncate rounded-full border border-white/70 bg-white/90 px-2.5 py-1 text-[9px] font-extrabold text-[#174c3c] shadow-sm backdrop-blur-md">
                                    <svg class="h-3 w-3 shrink-0" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="m12 3 9 5-9 5-9-5 9-5Z" />
                                        <path d="m3 12 9 5 9-5" />
                                        <path d="m3 16 9 5 9-5" />
                                    </svg>
                                    {{ $food->menu_category_name }}
                                </span>

                                {{-- Availability Badge --}}
                                <span
                                    class="absolute bottom-2 right-2 inline-flex items-center gap-1 rounded-full border border-white/30 bg-black/35 px-2 py-1 text-[9px] font-bold text-white backdrop-blur-md">
                                    <span
                                        class="h-1.5 w-1.5 rounded-full {{ $food->is_active ? 'bg-emerald-400' : 'bg-amber-400' }}"></span>
                                    {{ $food->is_active ? 'متوفر الآن' : 'نفدت الكمية' }}
                                </span>
                            </div>

                            {{-- Food Details --}}
                            <div class="p-3 sm:p-3.5">

                                <h3 class="truncate text-sm font-black text-gray-900 sm:text-base">
                                    {{ $food->name }}
                                </h3>

                                <p class="mt-1.5 min-h-[2.5rem] text-xs leading-5 text-gray-500">
                                    {{ $food->description ?: 'طبق شهي محضّر بعناية، اختره وأضفه إلى طلبك.' }}
                                </p>

                                <div class="my-3 h-px bg-gradient-to-l from-transparent via-gray-200 to-transparent">
                                </div>

                                {{-- Price and Action --}}
                                <div class="flex items-center justify-between gap-2">

                                    <div class="min-w-0">
                                        <p class="text-[9px] font-bold text-gray-400">السعر</p>

                                        <div class="mt-0.5 flex items-baseline gap-1">
                                            <p class="whitespace-nowrap text-base font-black text-[#174c3c] sm:text-lg">
                                                {{ rtrim(rtrim(number_format((float) $food->price, 2, '.', ','), '0'), '.') }}
                                            </p>
                                            <span class="text-[9px] font-extrabold text-gray-500">ل.س</span>
                                        </div>
                                    </div>

                                    @if ($food->is_active == 1)
                                        <button type="button"
                                            class="food-add add-to-order inline-flex shrink-0 items-center justify-center gap-1.5 rounded-lg bg-[#174c3c] px-2.5 py-2 text-[10px] font-extrabold text-white shadow-sm transition-all duration-200 hover:bg-[#103a2d] hover:shadow-md active:scale-95 sm:px-3 sm:text-xs"
                                            data-id="{{ $food->id }}" data-name="{{ $food->name }}"
                                            data-price="{{ $food->price }}">

                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                                <path d="M12 5v14M5 12h14" />
                                            </svg>

                                            أضف للطلب
                                        </button>
                                    @else
                                        <span
                                            class="inline-flex shrink-0 items-center justify-center gap-1 rounded-lg border border-amber-200 bg-amber-50 px-2.5 py-2 text-[10px] font-extrabold text-amber-700 sm:text-xs">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                                                <circle cx="12" cy="12" r="9" />
                                                <path d="m9 9 6 6m0-6-6 6" />
                                            </svg>
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
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
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
