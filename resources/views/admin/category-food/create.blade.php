@extends('admin.layouts.main-layout')
@section('title','إدارة قائمة الطعام')

@section('content')
    <div class="category-create-page mx-auto max-w-5xl space-y-6 p-4 sm:p-6 lg:p-8" dir="rtl">

        {{-- Header / Breadcrumb Links --}}
        <div
            class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[var(--restaurant-color-hex-174c3c)] via-[var(--restaurant-color-hex-1d604b)] to-[var(--restaurant-color-hex-10382c)] p-5 text-white shadow-xl sm:p-8">
            <div class="pointer-events-none absolute -left-12 -top-16 h-48 w-48 rounded-full border-[24px] border-white/5">
            </div>
            <div class="pointer-events-none absolute -bottom-20 right-1/3 h-56 w-56 rounded-full bg-white/5 blur-2xl"></div>

            <div class="relative">
                <nav aria-label="مسار التنقل"
                    class="mb-6 flex flex-wrap items-center gap-2 text-xs font-semibold text-emerald-100/80 sm:text-sm">
                    <a href="{{ route('admin.category-food.index') }}"
                        class="rounded-lg transition hover:bg-white/10 hover:text-white">
                        إدارة التصنيفات
                    </a>
                    <svg class="h-3.5 w-3.5 rotate-180 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6" />
                    </svg>
                    <span class="rounded-lg bg-white/10 px-2.5 py-1 text-white">إضافة تصنيف</span>
                </nav>

                <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border border-white/15 bg-white/10 shadow-inner sm:h-16 sm:w-16">
                            <svg class="h-8 w-8 sm:h-9 sm:w-9" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 5.5A1.5 1.5 0 0 1 5.5 4h13A1.5 1.5 0 0 1 20 5.5v13a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 18.5v-13Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 9h8M8 13h5M8 17h3" />
                            </svg>
                        </div>

                        <div>
                            <p class="mb-1 text-xs font-semibold tracking-widest text-emerald-100/80">إدارة قائمة الطعام</p>
                            <h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl">إضافة تصنيف جديد</h1>
                            <p class="mt-2 max-w-xl text-sm leading-6 text-emerald-50/80">
                                نظّم قائمة الطعام بتصنيفات واضحة وصور مميزة تسهّل على العملاء اختيار ما يحبّون.
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('admin.category-food.index') }}"
                        class="inline-flex w-fit shrink-0 items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm font-bold text-white transition hover:bg-white/20">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7-7-7 7-7" />
                        </svg>
                        جميع التصنيفات
                    </a>
                </div>
            </div>
        </div>

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="flex gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-700">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-100">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v4m0 4h.01M10.3 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.7 3.86a2 2 0 0 0-3.4 0Z" />
                    </svg>
                </div>
                <div>
                    <p class="font-bold">يرجى مراجعة البيانات التالية</p>
                    <ul class="mt-2 list-inside list-disc space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.category-food.store') }}" method="POST" enctype="multipart/form-data"
            class="category-create-form overflow-hidden rounded-3xl border border-gray-200/80 bg-white shadow-sm">
            @csrf

            <div class="border-b border-gray-100 bg-gray-50/70 px-5 py-5 sm:px-8">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[var(--restaurant-color-hex-174c3c)]/10 text-[var(--restaurant-color-hex-174c3c)]">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 6.5A2.5 2.5 0 0 1 6.5 4h11A2.5 2.5 0 0 1 20 6.5v11a2.5 2.5 0 0 1-2.5 2.5h-11A2.5 2.5 0 0 1 4 17.5v-11Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 9h8M8 13h5" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-gray-800">معلومات التصنيف</h2>
                        <p class="mt-1 text-xs text-gray-500">أدخل التفاصيل الأساسية للتصنيف الجديد.</p>
                    </div>
                </div>
            </div>

            <div class="space-y-7 p-5 sm:p-8">

                {{-- Name --}}
                <div>
                    <label for="name" class="mb-2.5 flex items-center gap-2 text-sm font-bold text-gray-700">
                        <svg class="h-5 w-5 text-[var(--restaurant-color-hex-174c3c)]" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 5.5A1.5 1.5 0 0 1 5.5 4h13A1.5 1.5 0 0 1 20 5.5v13a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 18.5v-13Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 9h8M8 13h5" />
                        </svg>
                        اسم التصنيف <span class="text-red-500">*</span>
                    </label>

                    <input id="name" name="name" type="text" required value="{{ old('name') }}"
                        placeholder="مثال: الوجبات الرئيسية، المشروبات، الحلويات"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3.5 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 hover:border-gray-300 focus:border-[var(--restaurant-color-hex-174c3c)] focus:bg-white focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10">

                    @error('name')
                        <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Image --}}
                <div>
                    <label for="photo" class="mb-2.5 flex items-center gap-2 text-sm font-bold text-gray-700">
                        <svg class="h-5 w-5 text-[var(--restaurant-color-hex-174c3c)]" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.8">
                            <rect x="3" y="3" width="18" height="18" rx="3" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 15-5-5L5 21" />
                        </svg>
                        صورة التصنيف
                        <span class="text-xs font-normal text-gray-400">(اختياري)</span>
                    </label>

                    <input type="file" id="photo" name="photo" accept="image/*" class="sr-only"
                        onchange="previewCategoryImage(event)">

                    <label for="photo" id="upload-area"
                        class="category-image-upload group relative block cursor-pointer overflow-hidden rounded-3xl border-2 border-dashed border-gray-200 bg-gradient-to-br from-gray-50 to-white p-4 transition hover:border-[var(--restaurant-color-hex-174c3c)]/50 hover:shadow-lg hover:shadow-[var(--restaurant-color-hex-174c3c)]/5 sm:p-6">

                        <div
                            class="grid grid-cols-1 items-center gap-5 sm:grid-cols-[minmax(0,1fr)_minmax(0,1fr)] sm:gap-7">
                            {{-- 1:1 image preview --}}
                            <div
                                class="category-image-frame relative mx-auto aspect-square w-full max-w-[320px] overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-inner sm:max-w-none">
                                <img id="image-preview" src="" alt="معاينة صورة التصنيف"
                                    class="absolute inset-0 hidden h-full w-full object-contain p-1">

                                <div id="image-placeholder"
                                    class="absolute inset-0 flex flex-col items-center justify-center overflow-hidden bg-gradient-to-br from-[var(--restaurant-color-hex-174c3c)]/5 via-white to-emerald-50 p-5 text-center">
                                    <div
                                        class="pointer-events-none absolute -left-8 -top-8 h-28 w-28 rounded-full bg-[var(--restaurant-color-hex-174c3c)]/5">
                                    </div>
                                    <div
                                        class="pointer-events-none absolute -bottom-10 -right-10 h-36 w-36 rounded-full bg-emerald-100/60">
                                    </div>

                                    <div
                                        class="relative mb-4 flex h-20 w-20 items-center justify-center rounded-3xl bg-white text-[var(--restaurant-color-hex-174c3c)] shadow-lg shadow-[var(--restaurant-color-hex-174c3c)]/10 ring-1 ring-[var(--restaurant-color-hex-174c3c)]/10 transition group-hover:-translate-y-1">
                                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.4">
                                            <rect x="3" y="3" width="18" height="18" rx="3" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 15-5-5L5 21" />
                                        </svg>
                                    </div>
                                    <span class="relative text-sm font-extrabold text-gray-700">معاينة صورة التصنيف</span>
                                    <span class="relative mt-2 text-xs leading-5 text-gray-500">ستظهر الصورة هنا بنسبة
                                        1:1</span>
                                </div>

                                <div id="image-overlay"
                                    class="pointer-events-none absolute inset-x-0 bottom-0 hidden bg-gradient-to-t from-black/60 to-transparent px-4 pb-4 pt-10">
                                    <p class="truncate text-sm font-bold text-white">صورة التصنيف</p>
                                    <p class="mt-1 text-xs text-white/80">معاينة مربعة • 1:1</p>
                                </div>

                                <div
                                    class="pointer-events-none absolute right-3 top-3 rounded-lg border border-white/80 bg-white/90 px-2.5 py-1 text-[10px] font-extrabold text-[var(--restaurant-color-hex-174c3c)] shadow-sm backdrop-blur">
                                    1:1
                                </div>
                            </div>

                            {{-- Upload instructions --}}
                            <div class="flex flex-col items-center text-center sm:items-start sm:text-right">
                                <div
                                    class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[var(--restaurant-color-hex-174c3c)]/10 text-[var(--restaurant-color-hex-174c3c)] transition group-hover:scale-105">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 16V4m0 0L7 9m5-5 5 5" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4 16.5v2A1.5 1.5 0 0 0 5.5 20h13a1.5 1.5 0 0 0 1.5-1.5v-2" />
                                    </svg>
                                </div>

                                <h3 id="upload-title" class="text-base font-extrabold text-gray-800 sm:text-lg">
                                    أضف صورة جذابة للتصنيف
                                </h3>
                                <p id="upload-hint" class="mt-2 max-w-sm text-sm leading-7 text-gray-500">
                                    اختر صورة واضحة لتمثيل التصنيف. ستظهر الصورة كاملة داخل إطار مربع على مختلف أحجام
                                    الشاشات.
                                </p>

                                <span
                                    class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--restaurant-color-hex-174c3c)] px-5 py-3 text-sm font-bold text-white shadow-md shadow-[var(--restaurant-color-hex-174c3c)]/15 transition group-hover:-translate-y-0.5 group-hover:bg-[var(--restaurant-color-hex-10382c)]">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
                                    </svg>
                                    اختيار صورة
                                </span>

                                <div class="mt-5 flex flex-wrap items-center justify-center gap-2 sm:justify-start">
                                    <span
                                        class="rounded-lg bg-gray-100 px-2.5 py-1.5 text-[11px] font-semibold text-gray-500">JPG</span>
                                    <span
                                        class="rounded-lg bg-gray-100 px-2.5 py-1.5 text-[11px] font-semibold text-gray-500">PNG</span>
                                    <span
                                        class="rounded-lg bg-gray-100 px-2.5 py-1.5 text-[11px] font-semibold text-gray-500">WEBP</span>
                                </div>
                            </div>
                        </div>
                    </label>

                    <div id="image-preview-container"
                        class="mt-3 hidden items-center gap-3 rounded-xl border border-gray-200 bg-white p-3">
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" />
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-gray-700">تم اختيار الصورة</p>
                            <p id="image-file-name" class="mt-1 truncate text-xs text-gray-500"></p>
                        </div>
                        <button type="button" onclick="clearCategoryImage(event)"
                            class="shrink-0 rounded-lg p-2 text-gray-400 transition hover:bg-red-50 hover:text-red-600"
                            aria-label="إزالة الصورة">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m6 6 12 12M18 6 6 18" />
                            </svg>
                        </button>
                    </div>

                    @error('photo')
                        <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="mb-2.5 flex items-center gap-2 text-sm font-bold text-gray-700">
                        <svg class="h-5 w-5 text-[var(--restaurant-color-hex-174c3c)]" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 6h12M8 10h12M8 14h8M8 18h5" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 6h.01M4 10h.01M4 14h.01M4 18h.01" />
                        </svg>
                        وصف التصنيف
                        <span class="text-xs font-normal text-gray-400">(اختياري)</span>
                    </label>

                    <textarea id="description" name="description" rows="4"
                        placeholder="أضف وصفاً مختصراً يساعد العملاء على معرفة محتويات هذا التصنيف..."
                        class="w-full resize-y rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3.5 text-sm leading-7 text-gray-800 outline-none transition placeholder:text-gray-400 hover:border-gray-300 focus:border-[var(--restaurant-color-hex-174c3c)] focus:bg-white focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10">{{ old('description') }}</textarea>

                    @error('description')
                        <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fixed status switch --}}
                <div class="category-status-card rounded-2xl border border-[var(--restaurant-color-hex-174c3c)]/15 bg-[var(--restaurant-color-hex-174c3c)]/[0.035] p-4 sm:p-5">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex min-w-0 items-center gap-3">
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[var(--restaurant-color-hex-174c3c)]/10 text-[var(--restaurant-color-hex-174c3c)]">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 3 5 6v5c0 4.4 3 7.5 7 10 4-2.5 7-5.6 7-10V6l-7-3Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4" />
                                </svg>
                            </div>
                            <div>
                                <label for="is_active"
                                    class="block cursor-pointer text-sm font-extrabold text-gray-800">تفعيل التصنيف</label>
                                <p id="status-description" class="mt-1 text-xs leading-5 text-gray-500">التصنيف فعال ويمكن
                                    عرضه حسب إعدادات القائمة.</p>
                            </div>
                        </div>

                        <label for="is_active" class="relative inline-flex shrink-0 cursor-pointer items-center">
                            <input id="is_active" type="checkbox" name="is_active" value="1"
                                @checked(old('is_active', '1') == '1') class="peer sr-only">

                            <span
                                class="h-7 w-12 rounded-full bg-gray-300 transition-colors duration-200 peer-checked:bg-[var(--restaurant-color-hex-174c3c)] peer-focus-visible:outline-none peer-focus-visible:ring-4 peer-focus-visible:ring-[var(--restaurant-color-hex-174c3c)]/20"></span>
                            <span
                                class="pointer-events-none absolute left-1 top-1 h-5 w-5 rounded-full bg-white shadow-md transition-transform duration-200 peer-checked:translate-x-5"></span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div
                class="category-create-actions flex flex-col-reverse gap-3 border-t border-gray-100 bg-gray-50/70 p-5 sm:flex-row sm:items-center sm:justify-between sm:px-8 sm:py-6">
                <a href="{{ route('admin.category-food.index') }}"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3.5 text-sm font-bold text-gray-600 transition hover:border-gray-300 hover:bg-gray-100 sm:w-auto">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6" />
                    </svg>
                    إلغاء
                </a>

                <button type="submit"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[var(--restaurant-color-hex-174c3c)] px-7 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-[var(--restaurant-color-hex-174c3c)]/15 transition hover:-translate-y-0.5 hover:bg-[var(--restaurant-color-hex-10382c)] hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/20 sm:w-auto">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    حفظ التصنيف
                </button>
            </div>
        </form>
    </div>

    <script>
        function previewCategoryImage(event) {
            const file = event.target.files[0];

            if (!file) return;

            if (!file.type.startsWith('image/')) {
                event.target.value = '';
                return;
            }

            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('image-placeholder');
            const overlay = document.getElementById('image-overlay');

            if (preview.dataset.objectUrl) {
                URL.revokeObjectURL(preview.dataset.objectUrl);
            }

            const objectUrl = URL.createObjectURL(file);
            preview.dataset.objectUrl = objectUrl;
            preview.src = objectUrl;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
            overlay.classList.remove('hidden');

            document.getElementById('image-preview-container').classList.remove('hidden');
            document.getElementById('image-preview-container').classList.add('flex');
            document.getElementById('image-file-name').textContent = file.name;
            document.getElementById('upload-title').textContent = 'تم اختيار الصورة بنجاح';
            document.getElementById('upload-hint').textContent =
                'الصورة كاملة داخل إطار مربع. يمكنك اختيار صورة أخرى في أي وقت.';
        }

        function clearCategoryImage(event) {
            event.preventDefault();
            event.stopPropagation();

            const input = document.getElementById('photo');
            const preview = document.getElementById('image-preview');

            if (preview.dataset.objectUrl) {
                URL.revokeObjectURL(preview.dataset.objectUrl);
                delete preview.dataset.objectUrl;
            }

            input.value = '';
            preview.removeAttribute('src');
            preview.classList.add('hidden');

            document.getElementById('image-placeholder').classList.remove('hidden');
            document.getElementById('image-overlay').classList.add('hidden');
            document.getElementById('image-preview-container').classList.add('hidden');
            document.getElementById('image-preview-container').classList.remove('flex');
            document.getElementById('upload-title').textContent = 'أضف صورة جذابة للتصنيف';
            document.getElementById('upload-hint').textContent =
                'اختر صورة واضحة لتمثيل التصنيف. ستظهر الصورة كاملة داخل إطار مربع على مختلف أحجام الشاشات.';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('is_active');
            const description = document.getElementById('status-description');

            function updateStatusDescription() {
                description.textContent = toggle.checked ?
                    'التصنيف فعال ويمكن عرضه حسب إعدادات القائمة.' :
                    'التصنيف غير فعال ولن يظهر للعملاء إذا كانت القائمة تخفي التصنيفات المعطلة.';
            }

            toggle.addEventListener('change', updateStatusDescription);
            updateStatusDescription();
        });
    </script>
@endsection
