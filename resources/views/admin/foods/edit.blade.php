 @extends('admin.layouts.main-layout')
@section('title','إدارة قائمة الطعام')

 @section('content')
     <div class="mx-auto w-full max-w-6xl space-y-6 p-3 sm:space-y-8 sm:p-5 lg:p-8" dir="rtl">

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
                         <span class="text-white">تعديل الوجبة</span>
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
                             <h1 class="text-xl font-extrabold leading-relaxed sm:text-3xl">تعديل الوجبة</h1>
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


         {{-- Validation Errors --}}
         @if ($errors->any())
             <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-red-700 shadow-sm">
                 <div class="flex items-start gap-3">
                     <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M12 9v4m0 4h.01M10.3 3.86 1.82 18a2 2 0 001.7 3h16.96a2 2 0 001.7-3L13.7 3.86a2 2 0 00-3.4 0z" />
                     </svg>
                     <div>
                         <p class="font-extrabold">يرجى مراجعة البيانات التالية</p>
                         <ul class="mt-2 list-inside list-disc space-y-1 text-sm">
                             @foreach ($errors->all() as $error)
                                 <li>{{ $error }}</li>
                             @endforeach
                         </ul>
                     </div>
                 </div>
             </div>
         @endif

         {{-- Main Form --}}
         <form action="{{ route('admin.category-food.food.update', [$category, $food]) }}" method="POST"
             enctype="multipart/form-data" id="food-edit-form" class="space-y-6">
             @csrf
             @method('PUT')

             <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-5">

                 {{-- Image Panel --}}
                 <div class="space-y-5 lg:col-span-2">
                     <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm">
                         <div class="flex items-center justify-between gap-3 border-b border-gray-100 px-5 py-4">
                             <div>
                                 <h2 class="font-extrabold text-gray-800">صورة الوجبة</h2>
                                 <p class="mt-1 text-xs text-gray-500">معاينة مباشرة للصورة</p>
                             </div>
                             <span
                                 class="flex h-10 w-10 items-center justify-center rounded-xl bg-[var(--restaurant-color-hex-174c3c)]/5 text-[var(--restaurant-color-hex-174c3c)]">
                                 <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                         d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M4 4h16v16H4z" />
                                 </svg>
                             </span>
                         </div>

                         <div class="p-4 sm:p-5">
                             <div id="image-drop-zone"
                                 class="group relative mx-auto aspect-square w-full max-w-md overflow-hidden rounded-2xl border-2 border-dashed border-gray-200 bg-gradient-to-br from-gray-50 to-gray-100 transition duration-300 hover:border-[var(--restaurant-color-hex-174c3c)]/40">

                                 <img id="food-image-preview"
                                     src="{{ $food->image ? asset('storage/' . $food->image) : '' }}"
                                     alt="{{ $food->name }}"
                                     class="{{ $food->image ? '' : 'hidden' }} absolute inset-0 h-full w-full object-contain p-2 transition duration-500 group-hover:scale-[1.02]">

                                 <div id="food-image-placeholder"
                                     class="{{ $food->image ? 'hidden' : '' }} absolute inset-0 flex flex-col items-center justify-center gap-3 p-5 text-center">
                                     <div
                                         class="flex h-20 w-20 items-center justify-center rounded-3xl bg-white text-[var(--restaurant-color-hex-174c3c)]/50 shadow-sm">
                                         <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                 d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M4 4h16v16H4z" />
                                         </svg>
                                     </div>
                                     <p class="font-bold text-gray-600">أضف صورة شهية لوجبتك</p>
                                     <p class="text-xs leading-5 text-gray-400">ستظهر معاينة الصورة هنا</p>
                                 </div>

                                 <div
                                     class="pointer-events-none absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/45 to-transparent p-4 pt-12">
                                     <span
                                         class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-black/25 px-3 py-1.5 text-xs font-bold text-white backdrop-blur-md">
                                         <span class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span>
                                         معاينة الوجبة
                                     </span>
                                 </div>

                                 <label for="image"
                                     class="absolute left-3 top-3 inline-flex cursor-pointer items-center gap-2 rounded-xl border border-white/70 bg-white/90 px-3 py-2 text-xs font-extrabold text-[var(--restaurant-color-hex-174c3c)] shadow-lg backdrop-blur transition hover:bg-white">
                                     <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                             d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 4v12m0-12L7 9m5-5l5 5" />
                                     </svg>
                                     تغيير الصورة
                                 </label>
                             </div>

                             <input type="file" name="image" id="image" accept="image/*" class="hidden">

                             <div class="mt-4 rounded-xl bg-gray-50 p-3">
                                 <p id="image-file-name" class="break-all text-center text-xs leading-5 text-gray-500">
                                     {{ $food->image ? 'الصورة الحالية محفوظة، اختر صورة جديدة لاستبدالها' : 'لم يتم اختيار صورة بعد' }}
                                 </p>
                             </div>

                             @error('image')
                                 <p class="mt-2 text-xs font-semibold text-red-500">{{ $message }}</p>
                             @enderror
                         </div>
                     </div>

                     {{-- Status Card --}}
                     <div class="rounded-3xl border border-gray-100 bg-white p-5 shadow-sm">
                         <div class="mb-4 flex items-center gap-3">
                             <span
                                 class="flex h-10 w-10 items-center justify-center rounded-xl {{ $food->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-300 text-red-500' }}">
                                 <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                     xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     fill="currentColor" viewBox="0 0 24 24">
                                     <path
                                         d="M4 5v2H3a1 1 0 0 0 0 2h1v10a1 1 0 1 0 2 0V9h2v5.586c0 1.782 2.154 2.674 3.414 1.414l.586-.586.586.586a2 2 0 0 0 2.828 0l.586-.586.586.586c.411.411.918.593 1.414.59V19a1 1 0 1 0 2 0V9h1a1 1 0 1 0 0-2h-1V6a2 2 0 0 0-2-2h-8a2 2 0 0 0-2 2v1H6V5a1 1 0 0 0-2 0Z" />
                                 </svg>

                             </span>
                             <div>
                                 <h3 class="font-extrabold text-gray-800">توفر الوجبة</h3>
                                 <p class="mt-1 text-xs text-gray-500">تحكم في ظهورها للعملاء</p>
                             </div>
                         </div>

                         <label for="is_active"
                             class="flex cursor-pointer items-center justify-between gap-3 rounded-2xl border border-gray-100 bg-gray-50 p-4 transition hover:border-[var(--restaurant-color-hex-174c3c)]/20 hover:bg-[var(--restaurant-color-hex-174c3c)]/5">
                             <div class="min-w-0">
                                 <span id="status-title" class="block text-sm font-extrabold text-gray-800">
                                     {{ old('is_active', $food->is_active) ? 'الوجبة متاحة' : 'الوجبة غير متاحة' }}
                                 </span>
                                 <span id="status-description" class="mt-1 block text-xs leading-5 text-gray-500">
                                     {{ old('is_active', $food->is_active) ? 'يمكن للعملاء طلب هذه الوجبة' : 'لن تظهر الوجبة كمتاحة للطلب' }}
                                 </span>
                             </div>

                             <span
                                 class="relative h-7 w-12 shrink-0 rounded-full transition-colors duration-300 {{ old('is_active', $food->is_active) ? 'bg-emerald-500' : 'bg-gray-300' }}"
                                 id="status-track">
                                 <input type="checkbox" name="is_active" value="1" id="is_active"
                                     {{ old('is_active', $food->is_active) ? 'checked' : '' }} class="peer sr-only">
                                 <span id="status-knob"
                                     class="absolute top-1 h-5 w-5 rounded-full bg-white shadow-md transition-all duration-300 {{ old('is_active', $food->is_active) ? 'right-1' : 'right-6' }}"></span>
                             </span>
                         </label>
                     </div>
                 </div>

                 {{-- Details Panel --}}
                 <div class="space-y-5 lg:col-span-3">
                     <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm">
                         <div class="flex items-center gap-3 border-b border-gray-100 px-5 py-5 sm:px-7">
                             <span
                                 class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[var(--restaurant-color-hex-174c3c)]/5 text-[var(--restaurant-color-hex-174c3c)]">
                                 <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                         d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                                 </svg>
                             </span>
                             <div>
                                 <h2 class="font-extrabold text-gray-800">تفاصيل الوجبة</h2>
                                 <p class="mt-1 text-xs text-gray-500">المعلومات الأساسية والتصنيف والسعر</p>
                             </div>
                         </div>

                         <div class="space-y-5 p-5 sm:p-7">

                             {{-- Name --}}
                             <div>
                                 <label for="name" class="mb-2 block text-sm font-bold text-gray-700">
                                     اسم الوجبة <span class="text-red-500">*</span>
                                 </label>

                                 <div class="relative">
                                     <span
                                         class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                                         <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                                 d="M4 6h16M4 12h16M4 18h16" />
                                         </svg>

                                     </span>

                                     <input type="text" name="name" id="name"
                                         value="{{ old('name', $food->name) }}" required
                                         placeholder="مثال: برغر لحم مشوي"
                                         class="w-full rounded-xl border border-gray-200 bg-gray-50/70 py-3.5 pr-12 pl-4 text-sm outline-none transition placeholder:text-gray-400 focus:border-[var(--restaurant-color-hex-174c3c)] focus:bg-white focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10">
                                 </div>

                                 @error('name')
                                     <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>
                                 @enderror
                             </div>

                             {{-- Category --}}
                             <div>
                                 <label for="category_food_id" class="mb-2 block text-sm font-bold text-gray-700">
                                     تصنيف الطعام <span class="text-red-500">*</span>
                                 </label>
                                 <div class="relative">
                                     <span
                                         class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                                         <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                                 d="M4 6h16M4 12h16M4 18h16" />
                                         </svg>
                                     </span>
                                     <select name="category_food_id" id="category_food_id" required
                                         class="w-full appearance-none rounded-xl border border-gray-200 bg-gray-50/70 py-3.5 pr-12 pl-10 text-sm outline-none transition focus:border-[var(--restaurant-color-hex-174c3c)] focus:bg-white focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10">
                                         @foreach ($categories as $cat)
                                             <option value="{{ $cat->id }}"
                                                 {{ old('category_food_id', $food->category_food_id) == $cat->id ? 'selected' : '' }}>
                                                 {{ $cat->name }}
                                             </option>
                                         @endforeach
                                     </select>
                                     <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                             d="M19 9l-7 7-7-7" />
                                     </svg>
                                 </div>
                                 @error('category_food_id')
                                     <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>
                                 @enderror
                             </div>

                             {{-- Price --}}
                             <div>
                                 <label for="price" class="mb-2 block text-sm font-bold text-gray-700">
                                     السعر <span class="text-red-500">*</span>
                                 </label>

                                 <div class="relative">
                                     <span
                                         class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                                         ل.س
                                     </span>

                                     <input type="number" step="1" min="0" name="price" id="price"
                                         value="{{ old('price', number_format($food->price, 0)) }}" required
                                         placeholder="000"
                                         class="mr-1 w-full rounded-xl border border-gray-200 bg-gray-50/70 py-3.5 pr-12 pl-20 text-sm outline-none transition focus:border-[var(--restaurant-color-hex-174c3c)] focus:bg-white focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10">

                                     <span
                                         class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 rounded-lg bg-[var(--restaurant-color-hex-174c3c)]/5 px-2 py-1 text-xs font-extrabold text-[var(--restaurant-color-hex-174c3c)]">
                                         ل.س
                                     </span>
                                 </div>

                                 @error('price')
                                     <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>
                                 @enderror
                             </div>

                             {{-- Description --}}
                             <div>
                                 <div class="mb-2 flex items-center justify-between gap-3">
                                     <label for="description" class="text-sm font-bold text-gray-700">وصف الوجبة</label>
                                     <span class="text-xs text-gray-400">اختياري</span>
                                 </div>
                                 <textarea name="description" id="description" rows="5" maxlength="1000"
                                     placeholder="اكتب وصفًا شهيًا يوضح مكونات الوجبة وما يميزها..."
                                     class="w-full resize-y rounded-xl border border-gray-200 bg-gray-50/70 px-4 py-3.5 text-sm leading-7 outline-none transition placeholder:text-gray-400 focus:border-[var(--restaurant-color-hex-174c3c)] focus:bg-white focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10">{{ old('description', $food->description) }}</textarea>
                                 <div class="mt-2 flex items-center justify-between gap-3">
                                     @error('description')
                                         <p class="text-xs font-semibold text-red-500">{{ $message }}</p>
                                     @else
                                         <p class="text-xs text-gray-400">استخدم وصفًا واضحًا ومختصرًا.</p>
                                     @enderror
                                     <span id="description-count"
                                         class="shrink-0 text-xs font-semibold tabular-nums text-gray-400">0 / 1000</span>
                                 </div>
                             </div>
                         </div>
                     </div>

                     {{-- Helpful Note --}}
                     <div class="flex items-start gap-3 rounded-2xl border border-emerald-100 bg-emerald-50/70 p-4">
                         <span
                             class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-emerald-700 shadow-sm">
                             <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                     d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                             </svg>
                         </span>
                         <div>
                             <p class="text-sm font-extrabold text-emerald-900">نصيحة لتحسين عرض الوجبة</p>
                             <p class="mt-1 text-xs leading-6 text-emerald-800/80">
                                 استخدم صورة واضحة، واسمًا مميزًا، ووصفًا يوضح المكونات لتسهيل اختيار الوجبة على العملاء.
                             </p>
                         </div>
                     </div>
                 </div>
             </div>

             {{-- Sticky Actions --}}
             <div
                 class="sticky bottom-3 z-20 rounded-2xl border border-gray-200/80 bg-white/95 p-3 shadow-xl shadow-gray-900/5 backdrop-blur-xl sm:p-4">
                 <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
                     <p class="hidden text-xs text-gray-400 sm:block">
                         تأكد من البيانات قبل حفظ التعديلات.
                     </p>

                     <div class="flex w-full flex-col-reverse gap-3 sm:w-auto sm:flex-row">
                         <a href="{{ route('admin.category-food.food.index', $category) }}"
                             class="inline-flex w-full items-center justify-center rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-bold text-gray-600 transition hover:border-gray-300 hover:bg-gray-50 sm:w-auto">
                             إلغاء
                         </a>

                         <button type="submit" id="save-food-button"
                             class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-l from-[var(--restaurant-color-hex-174c3c)] to-[var(--restaurant-color-hex-23674f)] px-7 py-3 text-sm font-extrabold text-white shadow-lg shadow-[var(--restaurant-color-hex-174c3c)]/20 transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-[var(--restaurant-color-hex-174c3c)]/25 focus:outline-none focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/20 active:translate-y-0 sm:w-auto">
                             <svg id="save-food-icon" class="h-5 w-5" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                     d="M5 13l4 4L19 7" />
                             </svg>
                             <span id="save-food-text">حفظ التعديلات</span>
                         </button>
                     </div>
                 </div>
             </div>
         </form>
     </div>

     {{-- Interactive Preview --}}
     <script>
         document.addEventListener('DOMContentLoaded', function() {
             const input = document.getElementById('image');
             const preview = document.getElementById('food-image-preview');
             const placeholder = document.getElementById('food-image-placeholder');
             const fileName = document.getElementById('image-file-name');
             const dropZone = document.getElementById('image-drop-zone');
             const statusInput = document.getElementById('is_active');
             const statusTrack = document.getElementById('status-track');
             const statusKnob = document.getElementById('status-knob');
             const statusTitle = document.getElementById('status-title');
             const statusDescription = document.getElementById('status-description');
             const description = document.getElementById('description');
             const descriptionCount = document.getElementById('description-count');
             const form = document.getElementById('food-edit-form');
             const saveButton = document.getElementById('save-food-button');
             const saveText = document.getElementById('save-food-text');

             let previewUrl = null;

             function updateDescriptionCount() {
                 const length = description.value.length;
                 descriptionCount.textContent = `${length} / 1000`;
                 descriptionCount.classList.toggle('text-amber-600', length > 900);
                 descriptionCount.classList.toggle('text-gray-400', length <= 900);
             }

             function updateStatus() {
                 const active = statusInput.checked;

                 statusTitle.textContent = active ? 'الوجبة متاحة' : 'الوجبة غير متاحة';
                 statusDescription.textContent = active ?
                     'يمكن للعملاء طلب هذه الوجبة' :
                     'لن تظهر الوجبة كمتاحة للطلب';

                 statusTrack.classList.toggle('bg-emerald-500', active);
                 statusTrack.classList.toggle('bg-gray-300', !active);
                 statusKnob.classList.toggle('right-1', active);
                 statusKnob.classList.toggle('right-6', !active);
             }

             function showImage(file) {
                 if (!file || !file.type.startsWith('image/')) {
                     fileName.textContent = 'يرجى اختيار ملف صورة صالح';
                     input.value = '';
                     return;
                 }

                 if (previewUrl) {
                     URL.revokeObjectURL(previewUrl);
                 }

                 previewUrl = URL.createObjectURL(file);
                 preview.src = previewUrl;
                 preview.classList.remove('hidden');
                 placeholder.classList.add('hidden');
                 fileName.textContent = `الصورة الجديدة: ${file.name}`;
                 dropZone.classList.add('border-[var(--restaurant-color-hex-174c3c)]/40');
             }

             input.addEventListener('change', function() {
                 const file = this.files && this.files[0];
                 if (file) showImage(file);
             });

             preview.addEventListener('error', function() {
                 if (previewUrl) {
                     preview.classList.add('hidden');
                     placeholder.classList.remove('hidden');
                     fileName.textContent = 'تعذر عرض الصورة، يرجى اختيار صورة أخرى';
                 }
             });

             dropZone.addEventListener('dragover', function(event) {
                 event.preventDefault();
                 dropZone.classList.add('border-[var(--restaurant-color-hex-174c3c)]', 'bg-emerald-50');
             });

             dropZone.addEventListener('dragleave', function() {
                 dropZone.classList.remove('border-[var(--restaurant-color-hex-174c3c)]', 'bg-emerald-50');
             });

             dropZone.addEventListener('drop', function(event) {
                 event.preventDefault();
                 dropZone.classList.remove('border-[var(--restaurant-color-hex-174c3c)]', 'bg-emerald-50');

                 const file = event.dataTransfer.files && event.dataTransfer.files[0];
                 if (!file) return;

                 const transfer = new DataTransfer();
                 transfer.items.add(file);
                 input.files = transfer.files;
                 showImage(file);
             });

             statusInput.addEventListener('change', updateStatus);
             description.addEventListener('input', updateDescriptionCount);

             form.addEventListener('submit', function() {
                 saveButton.disabled = true;
                 saveButton.classList.add('cursor-wait', 'opacity-80');
                 saveText.textContent = 'جارٍ حفظ التعديلات...';
             });

             updateStatus();
             updateDescriptionCount();
         });
     </script>
 @endsection
