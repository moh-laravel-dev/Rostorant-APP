@extends('admin.layouts.main-layout')
@section('title','إدارة قائمة الطعام')

@section('content')
    <div class="mx-auto w-full max-w-5xl space-y-6 sm:space-y-8" dir="rtl">

        {{-- Header --}}
        <div
            class="food-create-header relative overflow-hidden rounded-3xl bg-gradient-to-br from-[var(--restaurant-color-hex-174c3c)] via-[var(--restaurant-color-hex-1c604b)] to-[var(--restaurant-color-hex-10382c)] p-5 text-white shadow-lg sm:p-8">
            <div class="pointer-events-none absolute -left-12 -top-16 h-48 w-48 rounded-full bg-white/5"></div>
            <div class="pointer-events-none absolute -bottom-20 left-1/3 h-56 w-56 rounded-full bg-white/5"></div>

            <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <div class="mb-4 flex flex-wrap items-center gap-2 text-xs font-medium text-white/70 sm:text-sm">
                        <a href="{{ route('admin.category-food.index') }}"
                            class="transition hover:text-white hover:underline">
                            تصنيفات الطعام
                        </a>
                        <span class="text-white/40">/</span>
                        <a href="{{ route('admin.category-food.food.index', $category) }}"
                            class="max-w-[140px] truncate transition hover:text-white hover:underline">
                            {{ $category->name }}
                        </a>
                        <span class="text-white/40">/</span>
                        <span class="text-white">إضافة وجبة</span>
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
                                إضافة وجبة جديدة
                            </h1>
                            <p class="mt-1 text-sm leading-6 text-white/70 sm:text-base">
                                أضف تفاصيل الوجبة وصورتها وسعرها لتكون جاهزة للعرض ضمن القائمة.
                            </p>
                        </div>
                    </div>

                    <div
                        class="mt-5 inline-flex max-w-full items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm">
                        <svg class="h-4 w-4 shrink-0 text-emerald-200" fill="none" stroke="currentColor"
                            stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 4h16a1 1 0 011 1v14a1 1 0 01-1 1H4a1 1 0 01-1-1V5a1 1 0 011-1z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9h18M8 4v5m8-5v5" />
                        </svg>
                        <span class="text-white/70">التصنيف:</span>
                        <span class="truncate font-bold text-white">{{ $category->name }}</span>
                    </div>
                </div>

                <a href="{{ route('admin.category-food.food.index', $category) }}"
                    class="inline-flex min-h-11 shrink-0 items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm font-bold text-white transition hover:bg-white/20">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7l-7-7 7-7" />
                    </svg>
                    رجوع للقائمة
                </a>
            </div>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 shadow-sm">
                <div class="flex items-center gap-2 font-extrabold">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10.3 3.86L1.82 18a2 2 0 002.5 3h15.36a2 2 0 002.5-3L13.7 3.86a2 2 0 00-3.4 0z" />
                    </svg>
                    يرجى مراجعة الحقول التالية:
                </div>
                <ul class="mt-2 list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('admin.category-food.food.store', $category) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf

            {{-- Food Information --}}
            <section class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-gray-100 bg-gray-50/80 px-5 py-4 sm:px-7">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[var(--restaurant-color-hex-174c3c)]/10 text-[var(--restaurant-color-hex-174c3c)]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 5.5A1.5 1.5 0 015.5 4h13A1.5 1.5 0 0120 5.5v13a1.5 1.5 0 01-1.5 1.5h-13A1.5 1.5 0 014 18.5v-13z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 8h8M8 12h8M8 16h5" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-gray-800">معلومات الوجبة</h2>
                        <p class="mt-1 text-xs text-gray-500">الاسم والسعر والتفاصيل الأساسية</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 sm:gap-6 sm:p-7">

                    {{-- Name --}}
                    <div>
                        <label for="name" class="mb-2 block text-sm font-bold text-gray-700">
                            اسم الوجبة <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                                    viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 6.5A1.5 1.5 0 015.5 5h13A1.5 1.5 0 0120 6.5v11a1.5 1.5 0 01-1.5 1.5h-13A1.5 1.5 0 014 17.5v-11z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 9h8M8 13h5" />
                                </svg>
                            </div>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                autocomplete="off"
                                class="w-full rounded-xl border bg-white py-3.5 pr-12 pl-4 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-[var(--restaurant-color-hex-174c3c)] focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10 {{ $errors->has('name') ? 'border-red-400' : 'border-gray-200' }}"
                                placeholder="مثال: برغر كلاسيكي">
                        </div>
                        @error('name')
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Price --}}
                    <div>
                        <label for="price" class="mb-2 block text-sm font-bold text-gray-700">
                            السعر (ل.س) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                                    viewBox="0 0 24 24" aria-hidden="true">
                                    <rect x="3" y="5" width="18" height="14" rx="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7 9h.01M17 15h.01M12 9.5a2.5 2.5 0 100 5 2.5 2.5 0 000-5z" />
                                </svg>
                            </div>
                            <input type="number" id="price" name="price" value="{{ old('price') }}" required
                                min="0" step="1" inputmode="decimal"
                                class="w-full rounded-xl border bg-white py-3.5 pr-12 pl-14 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-[var(--restaurant-color-hex-174c3c)] focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10 {{ $errors->has('price') ? 'border-red-400' : 'border-gray-200' }}"
                                placeholder="0.00">
                            <span
                                class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-xs font-bold text-gray-400">ل.س</span>
                        </div>
                        @error('price')
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="sm:col-span-2">
                        <label for="description" class="mb-2 block text-sm font-bold text-gray-700">
                            وصف الوجبة
                            <span class="mr-1 text-xs font-normal text-gray-400">(اختياري)</span>
                        </label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full resize-y rounded-xl border bg-white px-4 py-3.5 text-sm leading-7 text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-[var(--restaurant-color-hex-174c3c)] focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10 {{ $errors->has('description') ? 'border-red-400' : 'border-gray-200' }}"
                            placeholder="اكتب وصفاً شهياً للوجبة، واذكر المكونات أو الإضافات المتوفرة...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            {{-- Image Upload --}}
            <section class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm"
                x-data="{
                    preview: null,
                    fileName: '',
                    chooseFile(event) {
                        const file = event.target.files[0];

                        if (!file) return;

                        if (this.preview) {
                            URL.revokeObjectURL(this.preview);
                        }

                        this.preview = URL.createObjectURL(file);
                        this.fileName = file.name;
                    },
                    removeFile() {
                        if (this.preview) {
                            URL.revokeObjectURL(this.preview);
                        }

                        this.preview = null;
                        this.fileName = '';
                        this.$refs.imageInput.value = '';
                    }
                }">
                <div class="flex items-center gap-3 border-b border-gray-100 bg-gray-50/80 px-5 py-4 sm:px-7">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[var(--restaurant-color-hex-174c3c)]/10 text-[var(--restaurant-color-hex-174c3c)]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <rect x="3" y="3" width="18" height="18" rx="3" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 15l-5-5L5 21" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-gray-800">صورة الوجبة</h2>
                        <p class="mt-1 text-xs text-gray-500">اختر صورة واضحة تظهر كاملة دون قص</p>
                    </div>
                </div>

                <div class="p-4 sm:p-7">
                    <label for="image"
                        class="group relative flex cursor-pointer flex-col items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-gray-300 bg-gray-50/70 p-3 transition hover:border-[var(--restaurant-color-hex-174c3c)]/60 hover:bg-emerald-50/30 sm:p-5">
                        {{-- Image Preview --}}
                        <template x-if="preview">
                            <div class="w-full max-w-md">
                                <div
                                    class="relative aspect-square w-full overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                                    <img :src="preview" alt="معاينة صورة الوجبة"
                                        class="absolute inset-0 h-full w-full object-contain object-center">

                                    <div
                                        class="absolute inset-x-2 bottom-2 flex items-center justify-between gap-2 rounded-xl bg-black/65 px-3 py-2 text-white backdrop-blur-sm">
                                        <span class="min-w-0 truncate text-xs font-semibold" x-text="fileName"></span>
                                        <span class="shrink-0 text-xs">معاينة الصورة</span>
                                    </div>
                                </div>

                                <p class="mt-3 text-center text-xs leading-5 text-gray-500">
                                    ستظهر الصورة كاملة دون اقتطاع أجزائها.
                                </p>
                            </div>
                        </template>

                        {{-- Empty State --}}
                        <template x-if="!preview">
                            <div class="flex w-full flex-col items-center py-8 text-center sm:py-12">
                                <div
                                    class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-[var(--restaurant-color-hex-174c3c)] shadow-sm transition group-hover:-translate-y-1 group-hover:shadow-md">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.7"
                                        viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 16V4m0 0L7 9m5-5l5 5" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M5 14v5a1 1 0 001 1h12a1 1 0 001-1v-5" />
                                    </svg>
                                </div>

                                <p class="mt-4 text-sm font-extrabold text-gray-700">
                                    اضغط لاختيار صورة الوجبة
                                </p>

                                <p class="mt-2 text-xs leading-6 text-gray-400">
                                    ستظهر الصورة بنسبة 1:1 مع الحفاظ على أبعادها الأصلية
                                </p>

                                <span
                                    class="mt-4 inline-flex items-center gap-2 rounded-xl bg-[var(--restaurant-color-hex-174c3c)] px-4 py-2.5 text-xs font-bold text-white transition group-hover:bg-[var(--restaurant-color-hex-10382c)]">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8"
                                        viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
                                    </svg>
                                    اختيار صورة
                                </span>
                            </div>
                        </template>

                        <input x-ref="imageInput" type="file" id="image" name="image" accept="image/*"
                            class="sr-only" @change="chooseFile($event)">
                    </label>

                    {{-- Remove Selected Image --}}
                    <div x-show="preview" x-cloak class="mt-4 flex justify-center">
                        <button type="button" @click="removeFile()"
                            class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-xs font-bold text-red-600 transition hover:bg-red-100">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8"
                                viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 7h16M10 11v6m4-6v6M6 7l1 14h10l1-14M9 7V4h6v3" />
                            </svg>
                            إزالة الصورة المختارة
                        </button>
                    </div>

                    @error('image')
                        <p class="mt-3 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </section>

            {{-- Availability --}}
            <section class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-gray-100 bg-gray-50/80 px-5 py-4 sm:px-7">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[var(--restaurant-color-hex-174c3c)]/10 text-[var(--restaurant-color-hex-174c3c)]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.5 12l2.3 2.3 4.7-4.7" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-gray-800">حالة الوجبة</h2>
                        <p class="mt-1 text-xs text-gray-500">تحكم في إمكانية ظهور الوجبة للطلب</p>
                    </div>
                </div>

                <div class="p-5 sm:p-7">
                    <label for="is_active"
                        class="flex cursor-pointer items-center justify-between gap-4 rounded-2xl border border-gray-200 p-4 transition hover:border-emerald-200 hover:bg-emerald-50/30 sm:p-5"
                        x-data="{ active: {{ old('is_active', true) ? 'true' : 'false' }} }">
                        <div class="flex min-w-0 items-center gap-3">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl transition"
                                :class="active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500'">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                                    viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12.5l4.5 4L19 7" />
                                </svg>
                            </div>

                            <div class="min-w-0">
                                <span class="block text-sm font-extrabold text-gray-800">متاحة للطلب</span>
                                <span class="mt-1 block text-xs leading-5 text-gray-500"
                                    x-text="active ? 'ستكون الوجبة متاحة للعملاء.' : 'لن تكون الوجبة متاحة للعملاء.'"></span>
                            </div>
                        </div>

                        <span class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition-colors"
                            :class="active ? 'bg-emerald-600' : 'bg-gray-300'">
                            <span class="inline-block h-4 w-4 rounded-full bg-white shadow-sm transition-transform"
                                :class="active ? '-translate-x-6' : '-translate-x-1'"></span>
                        </span>

                        <input type="checkbox" name="is_active" value="1" id="is_active"
                            @change="active = $event.target.checked" {{ old('is_active', true) ? 'checked' : '' }}
                            class="sr-only">
                    </label>
                </div>
            </section>

            {{-- Form Actions --}}
            <div
                class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-5 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('admin.category-food.food.index', $category) }}"
                    class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-bold text-gray-600 transition hover:bg-gray-50 hover:text-gray-800">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10 6H5a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 3h7v7m0-7L10 14" />
                    </svg>
                    إلغاء والعودة
                </a>

                <button type="submit"
                    class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-[var(--restaurant-color-hex-174c3c)] px-7 py-3 text-sm font-extrabold text-white shadow-md shadow-[var(--restaurant-color-hex-174c3c)]/15 transition hover:-translate-y-0.5 hover:bg-[var(--restaurant-color-hex-10382c)] hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/20">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5 3h12l4 4v13a1 1 0 01-1 1H4a1 1 0 01-1-1V4a1 1 0 011-1h1z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 3v6h8V3M8 21v-7h8v7" />
                    </svg>
                    حفظ الوجبة
                </button>
            </div>
        </form>
    </div>
@endsection
