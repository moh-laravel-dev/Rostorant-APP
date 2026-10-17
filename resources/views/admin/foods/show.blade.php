 @extends('admin.layouts.main-layout')
 @section('title', 'إدارة قائمة الطعام')

 @section('content')
     <div class="mx-auto w-full max-w-5xl space-y-6 sm:space-y-8" dir="rtl">

         {{-- Header --}}
         <div
             class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[var(--restaurant-color-hex-174c3c)] via-[var(--restaurant-color-hex-1c604b)] to-[var(--restaurant-color-hex-10382c)] p-5 text-white shadow-lg sm:p-8">
             <div class="pointer-events-none absolute -left-12 -top-16 h-48 w-48 rounded-full bg-white/5"></div>
             <div class="pointer-events-none absolute -bottom-20 left-1/3 h-56 w-56 rounded-full bg-white/5"></div>

             <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                 <div class="min-w-0">
                     <div class="mb-4 flex flex-wrap items-center gap-2 text-xs font-medium text-white/70 sm:text-sm">
                         <a href="{{ route('admin.category-food.index') }}"
                             class="transition hover:text-white hover:underline">
                             تصنيفات الطعام
                         </a>
                         <svg class="h-3.5 w-3.5 rotate-180" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" aria-hidden="true">
                             <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6" />
                         </svg>
                         <a href="{{ route('admin.category-food.food.index', $category) }}"
                             class="max-w-[140px] truncate transition hover:text-white hover:underline">
                             {{ $category->name }}
                         </a>
                         <svg class="h-3.5 w-3.5 rotate-180" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" aria-hidden="true">
                             <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6" />
                         </svg>
                         <span class="text-white">تفاصيل الوجبة</span>
                     </div>

                     <div class="flex items-start gap-3 sm:gap-4">
                         <div
                             class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-white/15 bg-white/10 sm:h-14 sm:w-14">
                             <svg class="h-7 w-7 sm:h-8 sm:w-8" fill="none" stroke="currentColor" stroke-width="1.7"
                                 viewBox="0 0 24 24" aria-hidden="true">
                                 <path stroke-linecap="round" stroke-linejoin="round"
                                     d="M6 3v6m4-6v6M6 6h4m-2 3v12M16 3v18m0-18c2 2 3 5 3 8h-3" />
                             </svg>
                         </div>

                         <div class="min-w-0">
                             <h1 class="text-xl font-extrabold leading-relaxed sm:text-3xl">تفاصيل الوجبة</h1>
                             <p class="mt-1 text-sm leading-6 text-white/70 sm:text-base">
                                 جميع معلومات الوجبة في مكان واحد، من السعر والوصف إلى حالة التوفر.
                             </p>
                         </div>
                     </div>

                     <div
                         class="mt-5 inline-flex max-w-full items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm">
                         <svg class="h-4 w-4 shrink-0 text-emerald-200" fill="none" stroke="currentColor"
                             stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16v16H4zM8 8h8M8 12h8M8 16h5" />
                         </svg>
                         <span class="text-white/70">التصنيف:</span>
                         <span class="truncate font-bold text-white">{{ $category->name }}</span>
                     </div>
                 </div>

                 <div class="flex w-full flex-col gap-2 sm:w-auto sm:shrink-0">
                     <a href="{{ route('admin.category-food.food.edit', [$category, $food]) }}"
                         class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-white px-4 py-3 text-sm font-extrabold text-[var(--restaurant-color-hex-174c3c)] shadow-sm transition hover:-translate-y-0.5 hover:bg-emerald-50">
                         <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"
                             aria-hidden="true">
                             <path stroke-linecap="round" stroke-linejoin="round"
                                 d="m15.5 5.5 3 3M4 20l4.5-1 11-11a2.12 2.12 0 0 0-3-3l-11 11L4 20z" />
                         </svg>
                         تعديل الوجبة
                     </a>

                     <a href="{{ route('admin.category-food.food.index', $category) }}"
                         class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm font-bold text-white transition hover:bg-white/20">
                         <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"
                             aria-hidden="true">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7-7-7 7-7" />
                         </svg>
                         رجوع للقائمة
                     </a>
                 </div>
             </div>
         </div>

         {{-- Main Details Card --}}
         <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">

             {{-- Image Area --}}
             <div class="relative overflow-hidden bg-gray-50">
                 <div class="relative aspect-square w-full sm:aspect-[16/9] lg:aspect-[2/1]">
                     @if ($food->image)
                         <img src="{{ asset('storage/' . $food->image) }}" alt="{{ $food->name }}"
                             class="absolute inset-0 h-full w-full object-contain object-center p-3 sm:p-5">
                     @else
                         <div class="absolute inset-0 flex flex-col items-center justify-center gap-3 text-gray-400">
                             <div
                                 class="flex h-20 w-20 items-center justify-center rounded-3xl border border-gray-200 bg-white shadow-sm">
                                 <svg class="h-10 w-10" fill="none" stroke="currentColor" stroke-width="1.5"
                                     viewBox="0 0 24 24" aria-hidden="true">
                                     <rect x="3" y="3" width="18" height="18" rx="3" />
                                     <circle cx="8.5" cy="8.5" r="1.5" />
                                     <path stroke-linecap="round" stroke-linejoin="round" d="m21 15-5-5L5 21" />
                                 </svg>
                             </div>
                             <span class="text-sm font-semibold">لا توجد صورة لهذه الوجبة</span>
                         </div>
                     @endif

                     {{-- Image Overlay --}}
                     <div
                         class="pointer-events-none absolute inset-x-0 bottom-0 h-28 bg-gradient-to-t from-black/30 to-transparent">
                     </div>

                     {{-- Price --}}
                     <div
                         class="absolute bottom-4 right-4 flex items-center gap-3 rounded-2xl border border-white/70 bg-white/95 px-4 py-3 shadow-lg backdrop-blur sm:bottom-6 sm:right-6 sm:px-5">
                         <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[var(--restaurant-color-hex-174c3c)]/10 text-[var(--restaurant-color-hex-174c3c)]">
                             <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                                 viewBox="0 0 24 24" aria-hidden="true">
                                 <rect x="3" y="5" width="18" height="14" rx="2" />
                                 <path stroke-linecap="round" stroke-linejoin="round"
                                     d="M7 9h.01M17 15h.01M12 9.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z" />
                             </svg>
                         </div>
                         <div>
                             <p class="text-xs font-medium text-gray-500">سعر الوجبة</p>
                             <p class="mt-0.5 text-lg font-extrabold text-[var(--restaurant-color-hex-174c3c)] sm:text-xl">
                                 {{ number_format($food->price, 2) }}
                                 <span class="text-sm font-bold">ل.س</span>
                             </p>
                         </div>
                     </div>

                     {{-- Status --}}
                     <div class="absolute left-4 top-4 sm:left-6 sm:top-6">
                         <span
                             class="inline-flex items-center gap-2 rounded-full border border-white/70 px-3.5 py-2 text-xs font-extrabold shadow-sm backdrop-blur {{ $food->is_active ? 'bg-emerald-50/95 text-emerald-700' : 'bg-red-50/95 text-red-700' }}">
                             <span
                                 class="h-2 w-2 rounded-full {{ $food->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                             {{ $food->is_active ? 'متاحة للطلب' : 'غير متاحة للطلب' }}
                         </span>
                     </div>
                 </div>
             </div>

             {{-- Food Information --}}
             <div class="space-y-6 p-5 sm:p-8">

                 {{-- Name and Category --}}
                 <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                     <div class="min-w-0">
                         <div class="mb-3 flex flex-wrap items-center gap-2">
                             <span
                                 class="inline-flex items-center gap-1.5 rounded-lg bg-[var(--restaurant-color-hex-174c3c)]/10 px-3 py-1.5 text-xs font-bold text-[var(--restaurant-color-hex-174c3c)]">
                                 <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.8"
                                     viewBox="0 0 24 24" aria-hidden="true">
                                     <path stroke-linecap="round" stroke-linejoin="round"
                                         d="M4 4h16v16H4zM4 9h16M9 4v5" />
                                 </svg>
                                 {{ $food->categoryFood->name ?? ($category->name ?? 'غير مصنف') }}
                             </span>

                             <span
                                 class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-bold {{ $food->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                 <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2"
                                     viewBox="0 0 24 24" aria-hidden="true">
                                     @if ($food->is_active)
                                         <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" />
                                     @else
                                         <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18" />
                                     @endif
                                 </svg>
                                 {{ $food->is_active ? 'نشطة' : 'معطلة' }}
                             </span>
                         </div>

                         <h2 class="break-words text-2xl font-extrabold leading-relaxed text-gray-900 sm:text-3xl">
                             {{ $food->name }}
                         </h2>

                         <p class="mt-2 text-sm leading-6 text-gray-500">
                             تفاصيل الوجبة ومعلوماتها المسجلة في النظام.
                         </p>
                     </div>

                     <div class="flex shrink-0 items-center gap-2 rounded-2xl border border-gray-100 bg-gray-50 px-4 py-3">
                         <div
                             class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-[var(--restaurant-color-hex-174c3c)] shadow-sm">
                             <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                                 viewBox="0 0 24 24" aria-hidden="true">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2" />
                                 <circle cx="12" cy="12" r="9" />
                             </svg>
                         </div>
                         <div>
                             <p class="text-xs text-gray-500">رقم الوجبة</p>
                             <p class="mt-1 text-sm font-extrabold text-gray-800">#{{ $food->id }}</p>
                         </div>
                     </div>
                 </div>

                 {{-- Description --}}
                 <div class="rounded-2xl border border-gray-100 bg-gray-50/70 p-4 sm:p-5">
                     <div class="mb-3 flex items-center gap-2.5">
                         <div
                             class="flex h-9 w-9 items-center justify-center rounded-xl bg-white text-[var(--restaurant-color-hex-174c3c)] shadow-sm">
                             <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                                 viewBox="0 0 24 24" aria-hidden="true">
                                 <path stroke-linecap="round" stroke-linejoin="round"
                                     d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                             </svg>
                         </div>
                         <h3 class="text-sm font-extrabold text-gray-800">وصف الوجبة</h3>
                     </div>

                     <p class="whitespace-pre-line break-words text-sm leading-8 text-gray-600">
                         {{ $food->description ?: 'لا يوجد وصف متاح لهذه الوجبة.' }}
                     </p>
                 </div>

                 {{-- Metadata --}}
                 <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                     <div class="flex items-start gap-3 rounded-2xl border border-gray-100 p-4">
                         <div
                             class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                             <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                                 viewBox="0 0 24 24" aria-hidden="true">
                                 <rect x="3" y="4" width="18" height="17" rx="2" />
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M16 2v4M8 2v4M3 9h18" />
                             </svg>
                         </div>
                         <div class="min-w-0">
                             <p class="text-xs font-medium text-gray-500">تاريخ الإضافة</p>
                             <p class="mt-1 break-words text-sm font-bold text-gray-800">
                                 {{ $food->created_at?->format('Y-m-d H:i') ?? 'غير متوفر' }}
                             </p>
                         </div>
                     </div>

                     <div class="flex items-start gap-3 rounded-2xl border border-gray-100 p-4">
                         <div
                             class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                             <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                                 viewBox="0 0 24 24" aria-hidden="true">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M20 7v5h-5M4 17v-5h5" />
                                 <path stroke-linecap="round" stroke-linejoin="round"
                                     d="M5.6 9A7 7 0 0117 5l3 2M4 17l3 2a7 7 0 0011.4-4" />
                             </svg>
                         </div>
                         <div class="min-w-0">
                             <p class="text-xs font-medium text-gray-500">آخر تحديث</p>
                             <p class="mt-1 break-words text-sm font-bold text-gray-800">
                                 {{ $food->updated_at?->format('Y-m-d H:i') ?? 'غير متوفر' }}
                             </p>
                         </div>
                     </div>
                 </div>
             </div>

             {{-- Footer Actions --}}
             <div
                 class="flex flex-col gap-3 border-t border-gray-100 bg-gray-50/70 p-5 sm:flex-row sm:items-center sm:justify-between sm:px-8">
                 <p class="flex items-center gap-2 text-xs leading-5 text-gray-500">
                     <svg class="h-4 w-4 shrink-0 text-[var(--restaurant-color-hex-174c3c)]" fill="none" stroke="currentColor"
                         stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                         <path stroke-linecap="round" stroke-linejoin="round"
                             d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4z" />
                         <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4" />
                     </svg>
                     تفاصيل الوجبة محفوظة ضمن بيانات النظام.
                 </p>

                 <div class="flex flex-col gap-2 sm:flex-row">
                     <a href="{{ route('admin.category-food.food.index', $category) }}"
                         class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-bold text-gray-700 transition hover:bg-gray-100">
                         <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8"
                             viewBox="0 0 24 24" aria-hidden="true">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7-7-7 7-7" />
                         </svg>
                         العودة إلى القائمة
                     </a>

                     <a href="{{ route('admin.category-food.food.edit', [$category, $food]) }}"
                         class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-[var(--restaurant-color-hex-174c3c)] px-5 py-3 text-sm font-extrabold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[var(--restaurant-color-hex-10382c)] hover:shadow-md">
                         <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8"
                             viewBox="0 0 24 24" aria-hidden="true">
                             <path stroke-linecap="round" stroke-linejoin="round"
                                 d="m15.5 5.5 3 3M4 20l4.5-1 11-11a2.12 2.12 0 0 0-3-3l-11 11L4 20z" />
                         </svg>
                         تعديل بيانات الوجبة
                     </a>
                 </div>
             </div>
         </div>
     </div>
 @endsection
