{{-- resources/views/Menu/menu.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'قائمة الطعام')

@section('content')
    <div class="min-h-screen overflow-hidden bg-[#f7f8f5] pb-28 text-gray-800" dir="rtl">

        {{-- Hero --}}
        <section class="relative isolate overflow-hidden bg-[#10382c] text-white">
            <div class="absolute inset-0 -z-10 bg-gradient-to-bl from-[#286b50] via-[#174c3c] to-[#09271e]"></div>

            <div class="absolute -left-20 -top-24 -z-10 h-72 w-72 rounded-full border-[35px] border-white/[0.04]"></div>
            <div class="absolute -bottom-32 right-[35%] -z-10 h-80 w-80 rounded-full bg-emerald-300/[0.07] blur-3xl"></div>

            <div
                class="mx-auto grid max-w-7xl items-center gap-8 px-5 py-12 sm:px-8 sm:py-16 lg:grid-cols-[1.15fr_0.85fr] lg:py-20">
                <div>
                    <div
                        class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-bold text-emerald-100">
                        <span class="h-2 w-2 rounded-full bg-emerald-300"></span>
                        مذاق مميز في كل طبق
                    </div>

                    <h1 class="max-w-2xl text-4xl font-black leading-tight tracking-tight sm:text-5xl lg:text-6xl">
                        كل ما تشتهيه،
                        <span class="mt-2 block text-emerald-300">في قائمة واحدة.</span>
                    </h1>

                    <p class="mt-5 max-w-xl text-sm leading-8 text-white/70 sm:text-base">
                        اكتشف قائمتنا، اختر أطباقك المفضلة، واستمتع بتجربة طلب سهلة ومميزة.
                    </p>

                    <a href="#menu-items"
                        class="mt-7 inline-flex items-center gap-3 rounded-2xl bg-[#e8c98c] px-6 py-4 text-sm font-extrabold text-[#26372b] shadow-lg shadow-black/10 transition hover:-translate-y-0.5 hover:bg-[#f3dba9]">
                        استكشف قائمة الطعام
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M19 12H5m7 7-7-7 7-7" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </a>
                </div>

                <div class="relative mx-auto hidden w-full max-w-md lg:block">
                    <div class="absolute inset-4 rounded-[2.5rem] bg-emerald-200/10 blur-2xl"></div>
                    <div
                        class="relative rounded-[2rem] border border-white/15 bg-white/[0.07] p-5 shadow-2xl backdrop-blur-sm">
                        <div class="rounded-[1.5rem] bg-[#f7f4eb] p-5 text-[#174c3c]">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs font-bold text-gray-500">اختيارات اليوم</p>
                                    <h2 class="mt-1 text-2xl font-black">طازج ولذيذ</h2>
                                </div>
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#174c3c] text-white">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M3 3v6a3 3 0 0 0 6 0V3M6 3v18M15 3v18m0-18c3 2 4 5 4 8h-4"
                                            stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>

                            <div class="my-5 flex items-center gap-4 rounded-2xl bg-white p-3 shadow-sm">
                                <div
                                    class="flex h-16 w-16 shrink-0 items-center justify-center rounded-xl bg-[#e5eee6] text-[#174c3c]">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M4 11h16l-2 9H6l-2-9Zm4 0a4 4 0 0 1 8 0M3 7h18" stroke-width="1.6"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-extrabold">أطباق تناسب ذوقك</p>
                                    <p class="mt-1 text-xs leading-5 text-gray-500">تصفح الأصناف واختر ما تحب</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 text-xs font-bold text-[#174c3c]">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                اختر وجبتك وابدأ طلبك
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-8 sm:py-10">

            {{-- Search --}}
            <div class="relative z-10 -mt-1 mb-8 rounded-2xl border border-gray-100 bg-white p-3 shadow-sm sm:p-4">
                <div class="relative">
                    <svg class="absolute right-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="7" stroke-width="1.8" />
                        <path d="m16 16 4 4" stroke-width="1.8" stroke-linecap="round" />
                    </svg>

                    <input id="menu-search" type="search" autocomplete="off" placeholder="ابحث عن وجبتك المفضلة..."
                        class="w-full rounded-xl border border-transparent bg-[#f7f8f5] py-4 pl-4 pr-12 text-sm font-medium outline-none transition placeholder:text-gray-400 focus:border-[#174c3c]/30 focus:bg-white focus:ring-4 focus:ring-[#174c3c]/5">
                </div>
            </div>

            {{-- Categories --}}
            <section class="mb-10">
                <div class="mb-4 flex items-end justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold tracking-wide text-[#59816d]">اكتشف الأصناف</p>
                        <h2 class="mt-1 text-xl font-black text-gray-900 sm:text-2xl">ماذا تشتهي اليوم؟</h2>
                    </div>
                    <span class="text-xs text-gray-500">{{ $categories->count() }} تصنيف</span>
                </div>

                <div class="flex gap-3 overflow-x-auto pb-3">
                    <button type="button" data-category="all"
                        class="category-filter shrink-0 rounded-xl bg-[#174c3c] px-5 py-3 text-sm font-bold text-white shadow-sm transition">
                        كل الأصناف
                    </button>

                    @foreach ($categories as $category)
                        <button type="button" data-category="{{ $category->id }}"
                            class="category-filter shrink-0 rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-bold text-gray-600 transition hover:border-[#174c3c] hover:text-[#174c3c]">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </section>

            {{-- Menu heading --}}
            <section id="menu-items" class="scroll-mt-24">
                <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold text-[#59816d]">محضّرة بكل عناية</p>
                        <h2 class="mt-1 text-2xl font-black text-gray-900 sm:text-3xl">قائمة الطعام</h2>
                        <p class="mt-2 text-sm text-gray-500">اختر أطباقك المفضلة وأضفها إلى طلبك.</p>
                    </div>

                    <span id="food-count" class="rounded-xl bg-[#e8efe8] px-4 py-2.5 text-sm font-extrabold text-[#174c3c]">
                        {{ $categories->sum(fn($category) => $category->foods->count()) }} وجبة
                    </span>
                </div>

                <div id="food-grid" class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @php
                        $foodItems = $categories->flatMap(function ($category) {
                            return $category->foods->map(function ($food) use ($category) {
                                $food->menu_category_id = $category->id;
                                $food->menu_category_name = $category->name;
                                return $food;
                            });
                        });
                    @endphp

                    @forelse ($foodItems as $food)
                        <article
                            class="food-card group overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl"
                            data-category="{{ $food->menu_category_id }}" data-name="{{ mb_strtolower($food->name) }}">

                            <div class="relative aspect-[4/3] overflow-hidden bg-[#edf0eb]">
                                @if ($food->image)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($food->image) }}"
                                        alt="{{ $food->name }}" loading="lazy"
                                        class="h-full w-full object-cover transition duration-700 group-hover:scale-110">
                                @else
                                    <div class="flex h-full items-center justify-center text-[#9bad9f]">
                                        <svg class="h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <rect x="3" y="3" width="18" height="18" rx="3"
                                                stroke-width="1.4" />
                                            <circle cx="8.5" cy="8.5" r="1.5" stroke-width="1.4" />
                                            <path d="m21 15-5-5L5 21" stroke-width="1.4" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                @endif

                                <span
                                    class="absolute right-3 top-3 max-w-[80%] truncate rounded-lg bg-white/95 px-3 py-1.5 text-xs font-extrabold text-[#174c3c] shadow-sm backdrop-blur">
                                    {{ $food->menu_category_name }}
                                </span>
                            </div>

                            <div class="p-4 sm:p-5">
                                <h3 class="truncate text-lg font-extrabold text-gray-900">
                                    {{ $food->name }}
                                </h3>

                                <p class="mt-2 min-h-[3rem] text-sm leading-6 text-gray-500">
                                    {{ $food->description ?: 'طبق شهي محضّر بعناية.' }}
                                </p>

                                <div class="mt-5 flex items-center justify-between gap-3 border-t border-gray-100 pt-4">
                                    <div class="min-w-0">
                                        <p class="text-xs text-gray-400">السعر</p>
                                        <p class="mt-1 text-lg font-black text-[#174c3c]">
                                            {{ number_format((float) $food->price, 2) }}
                                            <span class="text-xs font-bold">ل.س</span>
                                        </p>
                                    </div>

                                    <button type="button"
                                        class="add-to-order flex shrink-0 items-center gap-2 rounded-xl bg-[#174c3c] px-3 py-3 text-xs font-bold text-white transition hover:bg-[#10382c] active:scale-95 sm:px-4 sm:text-sm"
                                        data-id="{{ $food->id }}" data-name="{{ $food->name }}"
                                        data-price="{{ $food->price }}">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 5v14M5 12h14" stroke-width="2" stroke-linecap="round" />
                                        </svg>
                                        أضف للطلب
                                    </button>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div
                            class="col-span-full rounded-3xl border border-dashed border-gray-300 bg-white px-5 py-16 text-center">
                            <div
                                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-[#e8efe8] text-[#174c3c]">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M4 7h16M4 12h16M4 17h16" stroke-width="1.7" stroke-linecap="round" />
                                </svg>
                            </div>
                            <h3 class="font-extrabold text-gray-800">قائمة الطعام فارغة حالياً</h3>
                            <p class="mt-2 text-sm text-gray-500">ستظهر الوجبات هنا عند إضافتها وتفعيلها.</p>
                        </div>
                    @endforelse
                </div>

                <div id="no-results"
                    class="mt-5 hidden rounded-2xl border border-dashed border-gray-300 bg-white px-5 py-12 text-center">
                    <h3 class="font-extrabold text-gray-800">لم نعثر على نتائج</h3>
                    <p class="mt-2 text-sm text-gray-500">جرّب البحث باسم آخر أو اختر تصنيفاً مختلفاً.</p>
                </div>
            </section>
        </main>

        {{-- Order bar --}}
        <div id="order-bar"
            class="fixed inset-x-0 bottom-0 z-40 hidden border-t border-gray-200 bg-white/95 p-3 shadow-[0_-8px_30px_rgba(0,0,0,0.08)] backdrop-blur-md sm:p-4">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-xs text-gray-500">طلبك الحالي</p>
                    <p class="mt-1 font-black text-gray-900">
                        <span id="order-count">0</span>
                        <span class="text-sm font-medium">وجبة</span>
                    </p>
                </div>

                <div class="flex shrink-0 items-center gap-3 sm:gap-5">
                    <div class="text-left">
                        <p class="text-xs text-gray-500">الإجمالي</p>
                        <p class="mt-1 whitespace-nowrap font-black text-[#174c3c]">
                            <span id="order-total">0.00</span> ل.س
                        </p>
                    </div>

                    <button id="submit-order" type="button"
                        class="rounded-xl bg-[#174c3c] px-4 py-3 text-xs font-extrabold text-white transition hover:bg-[#10382c] sm:px-6 sm:text-sm">
                        متابعة الطلب
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('menu-search');
            const categoryButtons = document.querySelectorAll('.category-filter');
            const cards = [...document.querySelectorAll('.food-card')];
            const noResults = document.getElementById('no-results');
            const foodCount = document.getElementById('food-count');
            const orderBar = document.getElementById('order-bar');

            let selectedCategory = 'all';
            const order = new Map();

            const arabicNormalize = value => value
                .toLocaleLowerCase('ar')
                .replace(/[أإآ]/g, 'ا')
                .replace(/ى/g, 'ي')
                .replace(/ة/g, 'ه')
                .trim();

            function filterFoods() {
                const query = arabicNormalize(searchInput.value);
                let visibleCount = 0;

                cards.forEach(card => {
                    const matchesCategory =
                        selectedCategory === 'all' ||
                        card.dataset.category === selectedCategory;

                    const matchesSearch = arabicNormalize(card.dataset.name).includes(query);
                    const visible = matchesCategory && matchesSearch;

                    card.classList.toggle('hidden', !visible);
                    if (visible) visibleCount++;
                });

                foodCount.textContent = `${visibleCount} وجبة`;
                noResults.classList.toggle('hidden', visibleCount !== 0);
            }

            categoryButtons.forEach(button => {
                button.addEventListener('click', () => {
                    selectedCategory = button.dataset.category;

                    categoryButtons.forEach(item => {
                        item.className =
                            'category-filter shrink-0 rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-bold text-gray-600 transition hover:border-[#174c3c] hover:text-[#174c3c]';
                    });

                    button.className =
                        'category-filter shrink-0 rounded-xl bg-[#174c3c] px-5 py-3 text-sm font-bold text-white shadow-sm transition';

                    filterFoods();
                });
            });

            searchInput.addEventListener('input', filterFoods);

            function updateOrderSummary() {
                let count = 0;
                let total = 0;

                order.forEach(item => {
                    count += item.quantity;
                    total += item.price * item.quantity;
                });

                document.getElementById('order-count').textContent = count;
                document.getElementById('order-total').textContent =
                    total.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                orderBar.classList.toggle('hidden', count === 0);
            }

            document.querySelectorAll('.add-to-order').forEach(button => {
                button.addEventListener('click', () => {
                    const {
                        id,
                        name
                    } = button.dataset;
                    const price = Number(button.dataset.price);

                    if (order.has(id)) {
                        order.get(id).quantity++;
                    } else {
                        order.set(id, {
                            id,
                            name,
                            price,
                            quantity: 1
                        });
                    }

                    updateOrderSummary();

                    const originalHtml = button.innerHTML;
                    button.innerHTML = 'تمت الإضافة ✓';
                    button.classList.add('bg-emerald-700');

                    setTimeout(() => {
                        button.innerHTML = originalHtml;
                        button.classList.remove('bg-emerald-700');
                    }, 900);
                });
            });

            document.getElementById('submit-order').addEventListener('click', () => {
                const items = [...order.values()];

                if (!items.length) return;

                window.dispatchEvent(new CustomEvent('menu-order-selected', {
                    detail: {
                        items
                    }
                }));

                console.log('Selected order:', items);
            });
        });
    </script>
@endsection
