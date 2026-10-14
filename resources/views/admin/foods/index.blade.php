 @extends('admin.layouts.main-layout')

 @section('content')
     <div class="space-y-6 sm:space-y-8" dir="rtl">

         {{-- Header --}}
         <div
             class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#174c3c] via-[#1c604b] to-[#10382c] p-5 text-white shadow-lg sm:p-7">
             <div class="pointer-events-none absolute -left-12 -top-16 h-48 w-48 rounded-full bg-white/5"></div>
             <div class="pointer-events-none absolute -bottom-20 left-1/3 h-56 w-56 rounded-full bg-white/5"></div>

             <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                 <div class="min-w-0">
                     <div class="mb-4 flex flex-wrap items-center gap-2 text-xs font-medium text-white/70 sm:text-sm">
                         <a href="{{ route('admin.category-food.index') }}"
                             class="transition hover:text-white hover:underline">
                             تصنيفات الطعام
                         </a>
                         <span class="text-white/40">/</span>
                         <span class="max-w-[180px] truncate text-white">{{ $category->name }}</span>
                     </div>

                     <div class="flex items-start gap-3 sm:gap-4">
                         <div
                             class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-white/15 bg-white/10 sm:h-14 sm:w-14">
                             <svg class="h-7 w-7 sm:h-8 sm:w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                     d="M3 3v7a4 4 0 004 4h1V3M7 3v18M17 3v7a4 4 0 004 4h-4V3zm0 11v7" />
                             </svg>
                         </div>

                         <div class="min-w-0">
                             <h1 class="text-xl font-extrabold leading-relaxed sm:text-3xl">
                                 وجبات قسم {{ $category->name }}
                             </h1>
                             <p class="mt-1 text-sm leading-6 text-white/70 sm:text-base">
                                 إدارة الوجبات والأسعار والصور وحالة التوفر.
                             </p>
                         </div>
                     </div>

                     <div
                         class="mt-5 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm">
                         <svg class="h-4 w-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                 d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a3 3 0 016 0M9 5h6m-7 7h8m-8 4h8" />
                         </svg>
                         <span class="text-white/80">إجمالي الوجبات</span>
                         <strong>{{ $foods->total() }}</strong>
                     </div>
                 </div>

                 <div class="flex flex-col gap-3 sm:flex-row lg:shrink-0">
                     <a href="{{ route('admin.category-food.index') }}"
                         class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm font-bold text-white transition hover:bg-white/20">
                         <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                 d="M19 12H5m7 7l-7-7 7-7" />
                         </svg>
                         رجوع للتصنيفات
                     </a>

                     <a href="{{ route('admin.category-food.food.create', $category) }}"
                         class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-extrabold text-[#174c3c] shadow-sm transition hover:-translate-y-0.5 hover:bg-emerald-50 hover:shadow-md">
                         <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 5v14m-7-7h14" />
                         </svg>
                         إضافة وجبة جديدة
                     </a>
                 </div>
             </div>
         </div>
         {{-- Filters --}}
         <div class="rounded-3xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6">
             <form action="{{ route('admin.category-food.food.index', $category) }}" method="GET"
                 class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

                 {{-- Search --}}
                 <div class="sm:col-span-2">
                     <label for="search" class="mb-2 block text-sm font-bold text-gray-700">
                         البحث عن وجبة
                     </label>

                     <div class="relative">
                         <svg class="absolute right-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none"
                             stroke="currentColor" viewBox="0 0 24 24">
                             <circle cx="11" cy="11" r="7" stroke-width="1.8" />
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m16 16 4 4" />
                         </svg>

                         <input type="search" id="search" name="search" value="{{ request('search') }}"
                             placeholder="ابحث باسم الوجبة أو الوصف..."
                             class="min-h-12 w-full rounded-xl border border-gray-200 bg-gray-50 py-3 pr-10 pl-4 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-[#174c3c] focus:bg-white focus:ring-4 focus:ring-[#174c3c]/10">
                     </div>
                 </div>

                 {{-- Availability --}}
                 <div>
                     <label for="status" class="mb-2 block text-sm font-bold text-gray-700">
                         حالة التوفر
                     </label>

                     <select id="status" name="status"
                         class="min-h-12 w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-[#174c3c] focus:bg-white focus:ring-4 focus:ring-[#174c3c]/10">
                         <option value="">جميع الوجبات</option>
                         <option value="active" @selected(request('status') === 'active')>
                             الوجبات المتاحة
                         </option>
                         <option value="inactive" @selected(request('status') === 'inactive')>
                             الوجبات غير المتاحة
                         </option>
                     </select>
                 </div>

                 {{-- Sort --}}
                 <div>
                     <label for="sort" class="mb-2 block text-sm font-bold text-gray-700">
                         ترتيب الوجبات
                     </label>

                     <select id="sort" name="sort"
                         class="min-h-12 w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-[#174c3c] focus:bg-white focus:ring-4 focus:ring-[#174c3c]/10">
                         <option value="latest" @selected(request('sort', 'latest') === 'latest')>
                             الأحدث أولاً
                         </option>
                         <option value="oldest" @selected(request('sort') === 'oldest')>
                             الأقدم أولاً
                         </option>
                         <option value="price_asc" @selected(request('sort') === 'price_asc')>
                             السعر من الأقل للأعلى
                         </option>
                         <option value="price_desc" @selected(request('sort') === 'price_desc')>
                             السعر من الأعلى للأقل
                         </option>
                         <option value="name_asc" @selected(request('sort') === 'name_asc')>
                             الاسم أبجدياً
                         </option>
                     </select>
                 </div>

                 {{-- Actions --}}
                 <div class="flex flex-col gap-2 sm:col-span-2 xl:col-span-4 sm:flex-row sm:justify-end">
                     <a href="{{ route('admin.category-food.food.index', $category) }}"
                         class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-gray-200 px-5 py-3 text-sm font-bold text-gray-600 transition hover:border-gray-300 hover:bg-gray-50">
                         <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                 d="M4 4v5h5M5 9a8 8 0 1 1-1 5" />
                         </svg>
                         إعادة ضبط
                     </a>

                     <button type="submit"
                         class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-[#174c3c] px-6 py-3 text-sm font-extrabold text-white shadow-sm transition hover:bg-[#10382c] focus:outline-none focus:ring-4 focus:ring-[#174c3c]/20">
                         <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                 d="M3 4h18l-7 8v6l-4 2v-8L3 4z" />
                         </svg>
                         تطبيق الفلاتر
                     </button>
                 </div>
             </form>

             @if (request()->filled('search') || request()->filled('status') || request('sort', 'latest') !== 'latest')
                 <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-gray-100 pt-4">
                     <span class="text-xs font-bold text-gray-500">الفلاتر النشطة:</span>

                     @if (request()->filled('search'))
                         <span
                             class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700">
                             البحث: {{ request('search') }}
                         </span>
                     @endif

                     @if (request('status') === 'active')
                         <span class="rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700">
                             الوجبات المتاحة
                         </span>
                     @elseif (request('status') === 'inactive')
                         <span class="rounded-full bg-red-50 px-3 py-1.5 text-xs font-bold text-red-700">
                             الوجبات غير المتاحة
                         </span>
                     @endif

                     @if (request('sort') === 'oldest')
                         <span class="rounded-full bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700">الأقدم
                             أولاً</span>
                     @elseif (request('sort') === 'price_asc')
                         <span class="rounded-full bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700">السعر الأقل
                             أولاً</span>
                     @elseif (request('sort') === 'price_desc')
                         <span class="rounded-full bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700">السعر الأعلى
                             أولاً</span>
                     @elseif (request('sort') === 'name_asc')
                         <span class="rounded-full bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700">الاسم
                             أبجدياً</span>
                     @endif
                 </div>
             @endif
         </div>
         {{-- Success Alert --}}
         @if (session('success'))
             <div
                 class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold leading-6 text-emerald-800 shadow-sm">
                 <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100">
                     <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                     </svg>
                 </div>
                 <div class="pt-1">{{ session('success') }}</div>
             </div>
         @endif

         {{-- Food Cards: square 1:1 cards --}}
         <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 sm:gap-6 xl:grid-cols-3 2xl:grid-cols-4">
             @forelse ($foods as $food)
                 <article
                     class="group relative aspect-square min-w-0 overflow-hidden rounded-3xl border border-gray-200/80 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-200 hover:shadow-xl">
                     <div class="bg-white">
                         {{-- Full card background image --}}
                         @if ($food->image)
                             <img src="{{ asset('storage/' . $food->image) }}" alt="{{ $food->name }}" loading="lazy"
                                 class="absolute inset-0 h-full w-full object-contain object-center bg-gray-50 transition duration-500 group-hover:scale-[1.02]">
                         @else
                             <div
                                 class="absolute inset-0 flex flex-col items-center justify-center gap-3 bg-gradient-to-br from-gray-50 to-gray-100 text-gray-400">
                                 <div class="flex h-20 w-20 items-center justify-center rounded-3xl bg-white shadow-sm">
                                     <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                             d="M4 4h16a1 1 0 011 1v14a1 1 0 01-1 1H4a1 1 0 01-1-1V5a1 1 0 011-1zm0 13l5-5 4 4 3-3 5 5M8 9h.01" />
                                     </svg>
                                 </div>
                                 <span class="text-sm font-medium">لا توجد صورة للوجبة</span>
                             </div>
                         @endif
                     </div>
                     {{-- Readable overlay --}}
                     <div
                         class="pointer-events-none absolute inset-0 bg-gradient-to-b from-black/35 via-transparent to-black/85">
                     </div>

                     {{-- Availability --}}
                     <div class="absolute left-3 top-3 z-10 sm:left-4 sm:top-4">
                         <form action="{{ route('admin.category-food.food.toggle-status', [$category, $food]) }}"
                             method="POST">
                             @csrf
                             @method('PATCH')

                             <button type="submit" role="switch" aria-label="تغيير حالة {{ $food->name }}"
                                 aria-checked="{{ $food->is_active ? 'true' : 'false' }}"
                                 title="{{ $food->is_active ? 'تعطيل الوجبة' : 'تفعيل الوجبة' }}"
                                 class="inline-flex items-center gap-2 rounded-full border border-white/60 px-3 py-2 text-xs font-extrabold text-white shadow-lg backdrop-blur-md transition hover:scale-105 {{ $food->is_active ? 'bg-emerald-600/95' : 'bg-red-600/95' }}">
                                 <span class="h-2 w-2 rounded-full bg-white"></span>
                                 {{ $food->is_active ? 'متاحة' : 'غير متاحة' }}
                             </button>
                         </form>
                     </div>

                     {{-- Price --}}
                     <div
                         class="absolute right-3 top-3 z-10 max-w-[55%] rounded-2xl border border-white/60 bg-white/95 px-3 py-2 shadow-lg backdrop-blur-md sm:right-4 sm:top-4 sm:px-4">
                         <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-500 sm:text-xs">
                             <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                     d="M20.59 13.41L11 3.83H4v7l9.59 9.58a2 2 0 002.82 0l4.18-4.18a2 2 0 000-2.82zM7.5 7.5h.01" />
                             </svg>
                             سعر الوجبة
                         </div>
                         <div class="mt-0.5 truncate text-base font-extrabold tabular-nums text-[#174c3c] sm:text-lg">
                             {{ number_format($food->price, 0) }}
                             <span class="text-xs font-bold">ل.س</span>
                         </div>
                     </div>

                     {{-- Card information and actions --}}
                     <div class="absolute inset-x-0 bottom-0 z-10 p-3 sm:p-4">
                         <div class="mb-2 text-xs font-medium text-white/80">ضمن تصنيف {{ $category->name }}</div>

                         <h2
                             class="line-clamp-2 break-words text-base font-extrabold leading-6 text-white drop-shadow sm:text-lg">
                             {{ $food->name }}
                         </h2>

                         @if ($food->description)
                             <p class="mt-1 line-clamp-2 break-words text-xs leading-5 text-white/90 sm:text-sm">
                                 {{ $food->description }}
                             </p>
                         @else
                             <p class="mt-1 text-xs leading-5 text-white/75">لم تتم إضافة وصف لهذه الوجبة.</p>
                         @endif

                         <div class="mt-3 flex items-center gap-2 border-t border-white/20 pt-3">
                             {{-- View --}}
                             <a href="{{ route('admin.category-food.food.show', [$category, $food]) }}"
                                 class="inline-flex min-h-10 min-w-0 flex-1 items-center justify-center gap-1.5 rounded-xl bg-white/95 px-2 py-2 text-xs font-extrabold text-gray-800 transition hover:bg-white focus:outline-none focus-visible:ring-2 focus-visible:ring-white sm:text-sm"
                                 title="عرض تفاصيل الوجبة">
                                 <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                         d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z" />
                                     <circle cx="12" cy="12" r="3" stroke-width="1.8" />
                                 </svg>
                                 <span>عرض</span>
                             </a>

                             {{-- Edit --}}
                             <a href="{{ route('admin.category-food.food.edit', [$category, $food]) }}"
                                 class="inline-flex min-h-10 min-w-0 flex-1 items-center justify-center gap-1.5 rounded-xl bg-blue-100/95 px-2 py-2 text-xs font-extrabold text-blue-800 transition hover:bg-blue-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-200 sm:text-sm"
                                 title="تعديل الوجبة">
                                 <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                         d="M12 20h9M16.5 3.5a2.12 2.12 0 013 3L8 18l-4 1 1-4 11.5-11.5z" />
                                 </svg>
                                 <span>تعديل</span>
                             </a>

                             {{-- Delete --}}
                             <form class="shrink-0"
                                 action="{{ route('admin.category-food.food.destroy', [$category, $food]) }}"
                                 method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الوجبة؟')">
                                 @csrf
                                 @method('DELETE')

                                 <button type="submit"
                                     class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-red-100/95 text-red-700 transition hover:bg-red-100 hover:text-red-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-200"
                                     title="حذف الوجبة" aria-label="حذف {{ $food->name }}">
                                     <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                             d="M4 7h16M10 11v6m4-6v6M5 7l1 14h12l1-14M9 7V4h6v3" />
                                     </svg>
                                 </button>
                             </form>
                         </div>
                     </div>
                 </article>
             @empty
                 <div
                     class="col-span-full overflow-hidden rounded-3xl border border-dashed border-gray-300 bg-white px-5 py-14 text-center sm:py-20">
                     <div
                         class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-emerald-50 text-[#174c3c]">
                         <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                 d="M3 3v7a4 4 0 004 4h1V3M7 3v18M17 3v7a4 4 0 004 4h-4V3zm0 11v7" />
                         </svg>
                     </div>

                     <h3 class="mt-5 text-xl font-extrabold text-gray-800">لا توجد وجبات حتى الآن</h3>
                     <p class="mx-auto mt-2 max-w-md text-sm leading-7 text-gray-500">
                         ابدأ بإضافة أول وجبة لهذا التصنيف، وستظهر هنا مع صورتها وسعرها وتفاصيلها.
                     </p>

                     <a href="{{ route('admin.category-food.food.create', $category) }}"
                         class="mt-6 inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-[#174c3c] px-5 py-3 text-sm font-extrabold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#10382c] hover:shadow-md">
                         <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                 d="M12 5v14m-7-7h14" />
                         </svg>
                         إضافة أول وجبة
                     </a>
                 </div>
             @endforelse
         </div>

         {{-- Pagination --}}
         @if ($foods->hasPages())
             <div class="overflow-x-auto rounded-2xl border border-gray-200 bg-white px-3 py-4 shadow-sm sm:px-5">
                 {{ $foods->links() }}
             </div>
         @endif

     </div>
 @endsection
