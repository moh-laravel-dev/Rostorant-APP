@extends('admin.layouts.main-layout')

@section('content')
    <div class="mx-auto w-full max-w-5xl space-y-6 p-3 sm:p-5 lg:p-7" dir="rtl">

        {{-- Header --}}
        <div class="relative overflow-hidden rounded-3xl bg-[#174c3c] p-6 text-white shadow-lg sm:p-8">
            <div class="pointer-events-none absolute -left-12 -top-16 h-48 w-48 rounded-full bg-white/5"></div>
            <div class="pointer-events-none absolute -bottom-20 right-1/3 h-56 w-56 rounded-full bg-white/5"></div>

            <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div
                        class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-semibold text-white/90">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        إدارة تصنيفات الطعام
                    </div>

                    <h1 class="text-2xl font-extrabold sm:text-3xl">تعديل التصنيف</h1>

                    <p class="mt-2 text-sm leading-7 text-white/75">
                        حدّث معلومات وصورة تصنيف
                        <span class="font-bold text-white">{{ $category->name }}</span>
                        بسهولة.
                    </p>
                </div>

                <a href="{{ route('admin.category-food.index') }}"
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm font-bold text-white transition hover:bg-white/20">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                    العودة للتصنيفات
                </a>
            </div>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="flex gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-700 sm:p-5">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-100">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    </svg>
                </div>

                <div class="min-w-0">
                    <h2 class="font-extrabold">يرجى مراجعة البيانات التالية</h2>
                    <ul class="mt-2 list-inside list-disc space-y-1 text-sm leading-6">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Edit Form --}}
        <form action="{{ route('admin.category-food.update', $category) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-5">

                {{-- Image Section --}}
                <div class="space-y-5 lg:col-span-2">
                    <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">

                        <div class="border-b border-gray-100 px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#174c3c]/10 text-[#174c3c]">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M4 4h16v16H4zM8 14l3-3 5 5m-5-2l2-2 3 3M9 8h.01" />
                                    </svg>
                                </div>

                                <div>
                                    <h2 class="font-extrabold text-gray-800">صورة التصنيف</h2>
                                    <p class="mt-1 text-xs text-gray-400">معاينة وتغيير الصورة</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4 p-4 sm:p-5">

                            {{-- Image Preview --}}
                            <div
                                class="relative aspect-square w-full overflow-hidden rounded-2xl border border-gray-100 bg-gray-50">
                                <img id="category-photo-preview"
                                    src="{{ $category->photo ? asset('storage/' . $category->photo) : '' }}"
                                    alt="{{ $category->name }}"
                                    class="{{ $category->photo ? '' : 'hidden' }} absolute inset-0 h-full w-full object-cover">

                                <div id="category-photo-placeholder"
                                    class="{{ $category->photo ? 'hidden' : '' }} absolute inset-0 flex flex-col items-center justify-center gap-3 text-gray-400">
                                    <svg class="h-14 w-14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 4h16v16H4zM8 14l3-3 5 5m-5-2l2-2 3 3M9 8h.01" />
                                    </svg>
                                    <span class="text-sm">لا توجد صورة</span>
                                </div>

                                <div
                                    class="pointer-events-none absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/65 to-transparent p-4 pt-12">
                                    <p class="truncate text-base font-extrabold text-white" id="category-photo-title">
                                        {{ $category->name }}
                                    </p>
                                    <p class="mt-1 text-xs text-white/80">صورة التصنيف</p>
                                </div>
                            </div>

                            {{-- Upload Button --}}
                            <label for="photo"
                                class="flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl border border-dashed border-[#174c3c]/30 bg-[#174c3c]/5 px-4 py-3.5 text-sm font-bold text-[#174c3c] transition hover:border-[#174c3c] hover:bg-[#174c3c]/10">
                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 4v12m0-12L7 9m5-5l5 5" />
                                </svg>
                                <span id="photo-button-text">
                                    {{ $category->photo ? 'تغيير الصورة' : 'اختيار صورة' }}
                                </span>
                            </label>

                            <input type="file" id="photo" name="photo" accept="image/*" class="hidden">

                            <p id="photo-file-name" class="break-all text-center text-xs leading-5 text-gray-500">
                                اختر صورة جديدة إذا كنت ترغب في استبدال الصورة الحالية.
                            </p>

                            @error('photo')
                                <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                            @enderror

                            <div class="flex items-start gap-2 rounded-xl bg-gray-50 p-3 text-xs leading-6 text-gray-500">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-[#174c3c]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 110-20 10 10 0 010 20z" />
                                </svg>
                                <span>ستظهر معاينة الصورة الجديدة مباشرةً. يتم حفظها عند الضغط على زر حفظ التعديلات.</span>
                            </div>
                        </div>
                    </div>

                    {{-- Current Status Card --}}
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold text-gray-500">الحالة الحالية للتصنيف</p>

                        <div class="mt-3 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <span id="status-indicator"
                                    class="h-3 w-3 rounded-full {{ $category->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>

                                <span id="status-text"
                                    class="text-sm font-extrabold {{ $category->is_active ? 'text-green-700' : 'text-red-700' }}">
                                    {{ $category->is_active ? 'تصنيف فعال' : 'تصنيف غير فعال' }}
                                </span>
                            </div>

                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Fields Section --}}
                <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm lg:col-span-3">

                    <div class="border-b border-gray-100 px-5 py-4 sm:px-6">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#174c3c]/10 text-[#174c3c]">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                                </svg>
                            </div>

                            <div>
                                <h2 class="font-extrabold text-gray-800">بيانات التصنيف</h2>
                                <p class="mt-1 text-xs text-gray-400">حدّث الحقول المطلوبة أدناه</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6 p-5 sm:p-6">

                        {{-- Name --}}
                        <div>
                            <label for="name" class="mb-2 block text-sm font-bold text-gray-700">
                                اسم التصنيف
                                <span class="text-red-500">*</span>
                            </label>

                            <div class="relative">
                                {{-- <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M4 6h16M4 12h16M4 18h10" />
                                    </svg>
                                </div> --}}

                                <input id="name" name="name" type="text" required maxlength="255"
                                    value="{{ old('name', $category->name) }}" placeholder="مثال: الوجبات الرئيسية"
                                    class="w-full rounded-xl border border-gray-200 bg-white py-3.5 pe-12 ps-4 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-[#174c3c] focus:ring-4 focus:ring-[#174c3c]/10">
                            </div>

                            @error('name')
                                <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <div class="mb-2 flex items-center justify-between gap-3">
                                <label for="description" class="block text-sm font-bold text-gray-700">
                                    وصف التصنيف
                                </label>
                                <span class="text-xs text-gray-400">اختياري</span>
                            </div>

                            <textarea id="description" name="description" rows="5"
                                placeholder="اكتب وصفاً مختصراً يوضح محتويات هذا التصنيف..."
                                class="w-full resize-y rounded-xl border border-gray-200 bg-white px-4 py-3.5 text-sm leading-7 text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-[#174c3c] focus:ring-4 focus:ring-[#174c3c]/10">{{ old('description', $category->description) }}</textarea>

                            @error('description')
                                <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Active Toggle --}}
                        <div class="rounded-2xl border border-gray-200 bg-gray-50/80 p-4 sm:p-5">
                            <div class="flex items-center justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="text-sm font-extrabold text-gray-800">تفعيل التصنيف</p>
                                    <p id="active-description" class="mt-1 text-xs leading-6 text-gray-500">
                                        {{ old('is_active', $category->is_active ? '1' : '0') == '1'
                                            ? 'التصنيف فعال ويمكن عرضه وفق إعدادات قائمة الطعام.'
                                            : 'التصنيف غير فعال حالياً.' }}
                                    </p>
                                </div>

                                <label for="is_active" class="relative inline-flex shrink-0 cursor-pointer items-center">
                                    <input type="checkbox" name="is_active" value="1" id="is_active"
                                        @checked(old('is_active', $category->is_active ? '1' : '0') == '1') class="peer sr-only">

                                    <span
                                        class="h-7 w-12 rounded-full bg-gray-300 transition-colors duration-200 peer-checked:bg-[#174c3c] peer-focus-visible:ring-4 peer-focus-visible:ring-[#174c3c]/20"></span>

                                    <span
                                        class="absolute right-1 h-5 w-5 rounded-full bg-white shadow-sm transition-transform duration-200 peer-checked:-translate-x-5"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div
                        class="flex flex-col-reverse gap-3 border-t border-gray-100 bg-gray-50/70 p-5 sm:flex-row sm:justify-end sm:px-6">
                        <a href="{{ route('admin.category-food.index') }}"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-5 py-3.5 text-sm font-bold text-gray-600 transition hover:bg-gray-100 sm:w-auto">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            إلغاء
                        </a>

                        <button type="submit"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#174c3c] px-6 py-3.5 text-sm font-extrabold text-white shadow-sm transition hover:bg-[#10382c] focus:outline-none focus:ring-4 focus:ring-[#174c3c]/20 sm:w-auto">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            حفظ التعديلات
                        </button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Footer Note --}}
        <div class="flex items-start gap-3 rounded-2xl border border-[#174c3c]/10 bg-[#174c3c]/5 p-4">
            <svg class="mt-0.5 h-5 w-5 shrink-0 text-[#174c3c]" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-xs leading-6 text-gray-600">
                تأكد من مراجعة المعلومات قبل الحفظ. لن يتم استبدال صورة التصنيف المخزنة إلا عند اختيار صورة جديدة وحفظ
                التعديلات.
            </p>
        </div>
    </div>

    {{-- Live Preview & Status --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const photoInput = document.getElementById('photo');
            const photoPreview = document.getElementById('category-photo-preview');
            const photoPlaceholder = document.getElementById('category-photo-placeholder');
            const photoFileName = document.getElementById('photo-file-name');
            const photoButtonText = document.getElementById('photo-button-text');
            const nameInput = document.getElementById('name');
            const photoTitle = document.getElementById('category-photo-title');

            const activeInput = document.getElementById('is_active');
            const statusIndicator = document.getElementById('status-indicator');
            const statusText = document.getElementById('status-text');
            const activeDescription = document.getElementById('active-description');

            let currentPreviewUrl = null;

            photoInput.addEventListener('change', function() {
                const file = this.files && this.files[0];

                if (!file) {
                    return;
                }

                if (!file.type.startsWith('image/')) {
                    this.value = '';
                    photoFileName.textContent = 'يرجى اختيار ملف صورة صالح.';
                    return;
                }

                if (currentPreviewUrl) {
                    URL.revokeObjectURL(currentPreviewUrl);
                }

                currentPreviewUrl = URL.createObjectURL(file);

                photoPreview.onload = function() {
                    if (currentPreviewUrl) {
                        URL.revokeObjectURL(currentPreviewUrl);
                        currentPreviewUrl = null;
                    }
                };

                photoPreview.onerror = function() {
                    photoPreview.classList.add('hidden');
                    photoPlaceholder.classList.remove('hidden');
                    photoFileName.textContent = 'تعذر عرض الصورة، يرجى اختيار صورة أخرى.';
                };

                photoPreview.src = currentPreviewUrl;
                photoPreview.classList.remove('hidden');
                photoPlaceholder.classList.add('hidden');

                photoFileName.textContent = file.name;
                photoButtonText.textContent = 'اختيار صورة أخرى';
            });

            nameInput.addEventListener('input', function() {
                photoTitle.textContent = this.value.trim() || 'اسم التصنيف';
            });

            function updateStatus() {
                if (activeInput.checked) {
                    statusIndicator.className = 'h-3 w-3 rounded-full bg-green-500';
                    statusText.className = 'text-sm font-extrabold text-green-700';
                    statusText.textContent = 'تصنيف فعال';
                    activeDescription.textContent = 'التصنيف فعال ويمكن عرضه وفق إعدادات قائمة الطعام.';
                } else {
                    statusIndicator.className = 'h-3 w-3 rounded-full bg-red-500';
                    statusText.className = 'text-sm font-extrabold text-red-700';
                    statusText.textContent = 'تصنيف غير فعال';
                    activeDescription.textContent = 'التصنيف غير فعال حالياً.';
                }
            }

            activeInput.addEventListener('change', updateStatus);
            updateStatus();
        });
    </script>
@endsection
