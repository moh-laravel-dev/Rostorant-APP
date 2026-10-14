@extends('admin.layouts.main-layout')

@section('title', 'إضافة طاولة جديدة')
@section('page-title', 'إضافة طاولة جديدة')

@section('content')
    <div class="add-table-page mx-auto w-full max-w-5xl space-y-6 px-3 py-4 sm:px-6 sm:py-6" dir="rtl">

        <style>
            .add-table-page {
                --table-green: #174c3c;
                --table-dark: #10382d;
                --table-mint: #e8f3ed;
                --table-gold: #d8ad60;
            }

            .add-table-page .table-panel {
                background: #fff;
                border: 1px solid #e8eee9;
                border-radius: 24px;
                box-shadow: 0 12px 40px rgba(16, 56, 45, .06);
            }

            .add-table-page .table-field {
                width: 100%;
                min-height: 52px;
                border: 1px solid #e0e7e2;
                border-radius: 14px;
                padding: 12px 15px;
                background: #fbfcfb;
                color: #19392f;
                font-size: 14px;
                outline: none;
                transition: border-color .2s, box-shadow .2s, background .2s;
            }

            .add-table-page .table-field::placeholder {
                color: #a0aaa4;
            }

            .add-table-page .table-field:focus {
                background: #fff;
                border-color: var(--table-green);
                box-shadow: 0 0 0 4px rgba(23, 76, 60, .08);
            }

            .add-table-page .field-label {
                display: block;
                margin-bottom: 9px;
                color: #244c3d;
                font-size: 13px;
                font-weight: 800;
            }

            .add-table-page .table-btn {
                display: inline-flex;
                min-height: 48px;
                align-items: center;
                justify-content: center;
                gap: 9px;
                border-radius: 14px;
                padding: 12px 22px;
                font-size: 14px;
                font-weight: 800;
                transition: all .2s ease;
                cursor: pointer;
            }

            .add-table-page .table-btn-primary {
                color: #fff;
                background: var(--table-green);
                box-shadow: 0 7px 18px rgba(23, 76, 60, .16);
            }

            .add-table-page .table-btn-primary:hover {
                background: var(--table-dark);
                transform: translateY(-1px);
                box-shadow: 0 10px 22px rgba(23, 76, 60, .2);
            }

            .add-table-page .table-btn-secondary {
                color: #40534a;
                background: #f2f5f2;
                border: 1px solid #e5ebe6;
            }

            .add-table-page .table-btn-secondary:hover {
                background: #e8eee9;
            }

            .add-table-page .table-status-toggle {
                position: relative;
                display: inline-block;
                width: 52px;
                height: 29px;
                flex-shrink: 0;
            }

            .add-table-page .table-status-toggle input {
                position: absolute;
                width: 1px;
                height: 1px;
                opacity: 0;
            }

            .add-table-page .table-status-slider {
                position: absolute;
                inset: 0;
                border-radius: 999px;
                background: #d6ddd8;
                transition: .25s;
                cursor: pointer;
            }

            .add-table-page .table-status-slider::before {
                position: absolute;
                content: "";
                width: 21px;
                height: 21px;
                top: 4px;
                right: 4px;
                border-radius: 50%;
                background: #fff;
                box-shadow: 0 2px 5px #00000018;
                transition: .25s;
            }

            .add-table-page .table-status-toggle input:checked+.table-status-slider {
                background: var(--table-green);
            }

            .add-table-page .table-status-toggle input:checked+.table-status-slider::before {
                transform: translateX(-23px);
            }

            .add-table-page .table-status-toggle input:focus-visible+.table-status-slider {
                outline: 3px solid rgba(23, 76, 60, .2);
                outline-offset: 3px;
            }

            .add-table-page .table-number-card {
                position: relative;
                overflow: hidden;
                border: 1px solid #dcebe1;
                border-radius: 18px;
                background: linear-gradient(135deg, #f0f8f2, #e5f1e8);
            }

            .add-table-page .table-number-card::after {
                position: absolute;
                content: "";
                width: 115px;
                height: 115px;
                left: -32px;
                bottom: -65px;
                border: 18px solid rgba(23, 76, 60, .055);
                border-radius: 50%;
                pointer-events: none;
            }

            .add-table-page .table-alert {
                border-radius: 14px;
                padding: 14px 16px;
                font-size: 13px;
                line-height: 1.8;
            }

            @media (max-width: 640px) {
                .add-table-page .table-panel {
                    border-radius: 19px;
                }

                .add-table-page .table-btn {
                    width: 100%;
                }
            }

            @media (prefers-reduced-motion: reduce) {

                .add-table-page *,
                .add-table-page *::before,
                .add-table-page *::after {
                    transition: none !important;
                }
            }
        </style>

        {{-- Header --}}
        <div class="relative overflow-hidden rounded-3xl bg-[#10382d] p-5 text-white shadow-lg sm:p-8">
            <div
                class="pointer-events-none absolute -left-10 -top-16 h-48 w-48 rounded-full border-[28px] border-white/[0.04]">
            </div>
            <div class="pointer-events-none absolute -bottom-20 right-1/3 h-52 w-52 rounded-full bg-white/[0.035]"></div>

            <div class="relative flex flex-col justify-between gap-5 sm:flex-row sm:items-center">
                <div class="flex items-start gap-4">
                    <div
                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border border-white/10 bg-white/10 sm:h-16 sm:w-16">
                        <svg class="h-8 w-8 text-[#d8ad60]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 10h18M5 10V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v4" />
                            <path d="M5 10v10M19 10v10M3 15h18M8 20v-5M16 20v-5" />
                            <path d="M12 1v5M9.5 3.5h5" opacity=".8" />
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <div class="mb-2 flex flex-wrap items-center gap-2">
                            <span
                                class="rounded-full border border-[#d8ad60]/30 bg-[#d8ad60]/10 px-3 py-1 text-[11px] font-bold text-[#f0d5a1]">
                                إدارة الطاولات
                            </span>
                            <span class="text-xs text-white/45">/ إضافة جديدة</span>
                        </div>

                        <h1 class="text-2xl font-black tracking-tight sm:text-3xl">
                            أضف طاولتك الجديدة
                        </h1>
                        <p class="mt-2 max-w-lg text-sm leading-7 text-white/65">
                            أنشئ طاولة جديدة بسهولة، وحدد اسمها ورقمها وحالتها لتكون جاهزة ضمن نظام المطعم.
                        </p>
                    </div>
                </div>

                <a href="{{ route('tables.index') }}"
                    class="inline-flex min-h-11 shrink-0 items-center justify-center gap-2 self-start rounded-xl border border-white/15 bg-white/[0.07] px-4 py-2.5 text-sm font-bold text-white transition hover:bg-white/[0.13] sm:self-center">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="m15 18-6-6 6-6" />
                    </svg>
                    جميع الطاولات
                </a>
            </div>
        </div>

        {{-- Alerts --}}
        @if ($errors->any())
            <div class="table-alert flex items-start gap-3 border border-red-200 bg-red-50 text-red-800" role="alert">
                <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-red-100">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 8v4m0 4h.01" />
                    </svg>
                </div>
                <div>
                    <p class="font-extrabold">تعذرت إضافة الطاولة</p>
                    <p class="mt-1">{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

        @if (session('status'))
            <div class="table-alert flex items-start gap-3 border border-emerald-200 bg-emerald-50 text-emerald-800"
                role="status">
                <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="m5 12 4 4L19 6" />
                    </svg>
                </div>
                <div>
                    <p class="font-extrabold">تم تنفيذ العملية</p>
                    <p class="mt-1">{{ session('status') }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-[minmax(0,1fr)_280px]">

            {{-- Form --}}
            <section class="table-panel min-w-0 p-5 sm:p-8">
                <div class="mb-7 flex items-center gap-3 border-b border-gray-100 pb-5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#e8f3ed] text-[#174c3c]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3v18M3 12h18" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-[#174c3c]">بيانات الطاولة</h2>
                        <p class="mt-1 text-xs text-gray-500">أدخل المعلومات الأساسية للطاولة الجديدة.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('tables.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="name" class="field-label">
                            اسم الطاولة <span class="text-red-500">*</span>
                        </label>

                        <div class="relative">
                            <div class="pointer-events-none absolute right-4 top-1/2 z-10 -translate-y-1/2 text-gray-400">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 10h18M5 10V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v4M5 10v10M19 10v10M3 15h18" />
                                </svg>
                            </div>

                            <input id="name" type="text" name="name" value="{{ old('name') }}"
                                class="table-field !pr-12 !pl-4" placeholder="مثال: الطاولة الرئيسية" required
                                maxlength="255" autocomplete="off" aria-describedby="name-hint">
                        </div>

                        <p id="name-hint" class="mt-2 text-xs leading-6 text-gray-400">
                            اختر اسماً واضحاً ليسهل على الموظفين التعرف على الطاولة.
                        </p>

                        @error('name')
                            <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="field-label">رقم الطاولة</label>

                        <div class="table-number-card flex items-center justify-between gap-4 p-4 sm:p-5">
                            <div class="flex min-w-0 items-center gap-3">
                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-[#174c3c] shadow-sm">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="4" y="4" width="16" height="16" rx="3" />
                                        <path d="M8 9h8M8 13h5" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-[#547263]">الرقم التالي المتاح</p>
                                    <p class="mt-1 text-sm font-bold text-[#174c3c]">سيتم تخصيصه تلقائياً</p>
                                </div>
                            </div>

                            <div
                                class="relative z-10 flex h-14 min-w-14 shrink-0 items-center justify-center rounded-2xl border border-[#d7e7dc] bg-white px-4 text-2xl font-black tabular-nums text-[#174c3c] shadow-sm">
                                {{ $nextNumber }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="field-label">حالة الطاولة عند الإنشاء</label>

                        <div
                            class="flex flex-col justify-between gap-4 rounded-2xl border border-gray-100 bg-[#fafcfb] p-4 sm:flex-row sm:items-center sm:p-5">
                            <div class="flex items-center gap-3">
                                <div id="status-icon"
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 transition-colors">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m5 12 4 4L19 6" />
                                    </svg>
                                </div>
                                <div>
                                    <p id="status-label" class="text-sm font-extrabold text-[#174c3c]">
                                        {{ old('status', '1') == '1' ? 'متاحة لاستقبال الزبائن' : 'غير متاحة حالياً' }}
                                    </p>
                                    <p id="status-description" class="mt-1 text-xs leading-5 text-gray-500">
                                        {{ old('status', '1') == '1' ? 'يمكن استخدامها لاستقبال الطلبات.' : 'لن تكون متاحة لاستقبال الطلبات.' }}
                                    </p>
                                </div>
                            </div>

                            <label class="flex shrink-0 items-center gap-3 self-start sm:self-center">
                                <span class="text-xs font-bold text-gray-500">غير متاحة</span>
                                <span class="table-status-toggle">
                                    <input type="hidden" name="status" value="0">
                                    <input type="checkbox" id="status" name="status" value="1"
                                        @checked(old('status', '1') == '1') aria-label="تحديد حالة الطاولة">
                                    <span class="table-status-slider"></span>
                                </span>
                                <span class="text-xs font-bold text-[#174c3c]">متاحة</span>
                            </label>
                        </div>

                        @error('status')
                            <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="border-t border-gray-100 pt-6">
                        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                            <a href="{{ route('tables.index') }}" class="table-btn table-btn-secondary">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m15 18-6-6 6-6" />
                                </svg>
                                إلغاء والعودة
                            </a>

                            <button type="submit" class="table-btn table-btn-primary">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 5v14M5 12h14" />
                                </svg>
                                إضافة الطاولة
                            </button>
                        </div>
                    </div>
                </form>
            </section>

            {{-- Side information --}}
            <aside class="space-y-4">
                <div class="table-panel overflow-hidden">
                    <div class="bg-[#174c3c] p-5 text-white">
                        <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 text-[#e7c98e]">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9" />
                                <path d="M12 11v5m0-8h.01" />
                            </svg>
                        </div>
                        <h3 class="text-base font-extrabold">قبل إضافة الطاولة</h3>
                        <p class="mt-2 text-sm leading-7 text-white/65">
                            تأكد من إدخال اسم يسهل تمييزه، ثم اختر الحالة المناسبة لبدء استخدام الطاولة.
                        </p>
                    </div>

                    <div class="space-y-4 p-5">
                        <div class="flex items-start gap-3">
                            <div
                                class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#e8f3ed] text-[#174c3c]">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m5 12 4 4L19 6" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-extrabold text-gray-800">رقم تلقائي</p>
                                <p class="mt-1 text-xs leading-6 text-gray-500">يظهر رقم الطاولة المخصص لها قبل الحفظ.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div
                                class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-700">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="5" width="18" height="14" rx="2" />
                                    <path d="M3 10h18" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-extrabold text-gray-800">حالة مرنة</p>
                                <p class="mt-1 text-xs leading-6 text-gray-500">يمكنك تحديد ما إذا كانت الطاولة متاحة عند
                                    إنشائها.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div
                                class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-700">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 3v18M3 12h18" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-extrabold text-gray-800">سهولة الإدارة</p>
                                <p class="mt-1 text-xs leading-6 text-gray-500">ستظهر الطاولة ضمن قائمة الطاولات بعد
                                    إضافتها بنجاح.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-[#e8e1d2] bg-[#faf7f0] p-5">
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-[#a47b37]" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 9v4m0 4h.01" />
                            <path
                                d="M10.3 3.9 1.8 18.5A1.7 1.7 0 0 0 3.3 21h17.4a1.7 1.7 0 0 0 1.5-2.5L13.7 3.9a2 2 0 0 0-3.4 0Z" />
                        </svg>
                        <div>
                            <p class="text-sm font-extrabold text-[#765b2e]">ملاحظة مهمة</p>
                            <p class="mt-1 text-xs leading-6 text-[#8d7956]">
                                تأكد من أن اسم الطاولة يعكس موقعها أو رقمها الفعلي داخل المطعم.
                            </p>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSwitch = document.getElementById('status');
            const statusLabel = document.getElementById('status-label');
            const statusDescription = document.getElementById('status-description');
            const statusIcon = document.getElementById('status-icon');

            function updateStatusUI() {
                if (!statusSwitch || !statusLabel || !statusDescription || !statusIcon) {
                    return;
                }

                if (statusSwitch.checked) {
                    statusLabel.textContent = 'متاحة لاستقبال الزبائن';
                    statusDescription.textContent = 'يمكن استخدامها لاستقبال الطلبات.';
                    statusLabel.className = 'text-sm font-extrabold text-[#174c3c]';
                    statusIcon.className =
                        'flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 transition-colors';
                    statusIcon.innerHTML =
                        '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 4 4L19 6"/></svg>';
                } else {
                    statusLabel.textContent = 'غير متاحة حالياً';
                    statusDescription.textContent = 'لن تكون متاحة لاستقبال الطلبات.';
                    statusLabel.className = 'text-sm font-extrabold text-amber-700';
                    statusIcon.className =
                        'flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700 transition-colors';
                    statusIcon.innerHTML =
                        '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v4m0 4h.01"/></svg>';
                }
            }

            if (statusSwitch) {
                statusSwitch.addEventListener('change', updateStatusUI);
                updateStatusUI();
            }
        });
    </script>
@endsection
