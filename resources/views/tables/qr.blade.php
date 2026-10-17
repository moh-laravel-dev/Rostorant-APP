@extends('admin.layouts.main-layout')

@section('title', 'رمز QR للطاولة ' . $table->number)
@section('page-title', 'رمز QR للطاولة')

@section('content')
    <div class="table-qr-page mx-auto w-full max-w-6xl space-y-6 px-3 py-4 sm:px-6 sm:py-6" dir="rtl">

        <style>
            .table-qr-page {
                --qr-green: var(--restaurant-color-hex-174c3c);
                --qr-dark: var(--restaurant-color-hex-10382d);
                --qr-gold: var(--restaurant-color-hex-d8ad60);
            }

            .table-qr-page .qr-surface {
                border: 1px solid var(--restaurant-color-hex-e7eee8);
                border-radius: 24px;
                background: var(--restaurant-color-hex-fff);
                box-shadow: 0 12px 40px var(--restaurant-color-rgb-16-56-45-point-06);
            }

            .table-qr-page .qr-action {
                display: inline-flex;
                min-height: 46px;
                align-items: center;
                justify-content: center;
                gap: 9px;
                border-radius: 13px;
                padding: 11px 19px;
                font-size: 13px;
                font-weight: 800;
                transition: .2s ease;
                cursor: pointer;
                text-decoration: none;
            }

            .table-qr-page .qr-action-primary {
                color: var(--restaurant-color-hex-fff);
                background: var(--qr-green);
                box-shadow: 0 6px 16px var(--restaurant-color-rgb-23-76-60-point-15);
            }

            .table-qr-page .qr-action-primary:hover {
                background: var(--qr-dark);
                transform: translateY(-1px);
            }

            .table-qr-page .qr-action-light {
                border: 1px solid var(--restaurant-color-hex-e2e9e3);
                background: var(--restaurant-color-hex-fff);
                color: var(--restaurant-color-hex-345244);
            }

            .table-qr-page .qr-action-light:hover {
                background: var(--restaurant-color-hex-f2f7f3);
            }

            .table-qr-page .qr-poster {
                position: relative;
                overflow: hidden;
                border-radius: 25px;
                background: var(--restaurant-color-hex-fff);
                border: 1px solid var(--restaurant-color-hex-e1e8e2);
            }

            .table-qr-page .qr-poster-head {
                position: relative;
                overflow: hidden;
                background: linear-gradient(135deg, var(--restaurant-color-hex-10382d), var(--restaurant-color-hex-1d6049));
                color: var(--restaurant-color-hex-fff);
            }

            .table-qr-page .qr-poster-head::before {
                position: absolute;
                content: "";
                width: 230px;
                height: 230px;
                left: -75px;
                top: -145px;
                border: 35px solid var(--restaurant-color-rgb-255-255-255-point-055);
                border-radius: 50%;
            }

            .table-qr-page .qr-poster-head::after {
                position: absolute;
                content: "";
                width: 160px;
                height: 160px;
                right: -80px;
                bottom: -100px;
                border: 24px solid var(--restaurant-color-rgb-216-173-96-point-14);
                border-radius: 50%;
            }

            .table-qr-page .qr-code-frame {
                display: flex;
                width: min(100%, 310px);
                aspect-ratio: 1;
                align-items: center;
                justify-content: center;
                margin-inline: auto;
                padding: 15px;
                border: 1px solid var(--restaurant-color-hex-e7eee8);
                border-radius: 23px;
                background: var(--restaurant-color-hex-fff);
                box-shadow: 0 8px 28px var(--restaurant-color-rgb-16-56-45-point-07);
            }

            .table-qr-page .qr-code-image {
                display: block;
                width: 100%;
                height: 100%;
                object-fit: contain;
            }

            .table-qr-page .qr-dash {
                background-image: linear-gradient(to right, var(--restaurant-color-hex-dfe8e1) 50%, transparent 50%);
                background-size: 8px 1px;
                background-repeat: repeat-x;
            }

            .table-qr-page .qr-info-row {
                display: flex;
                align-items: flex-start;
                gap: 12px;
            }

            .table-qr-page .qr-info-icon {
                display: flex;
                width: 39px;
                height: 39px;
                flex-shrink: 0;
                align-items: center;
                justify-content: center;
                border-radius: 12px;
                background: var(--restaurant-color-hex-edf5ef);
                color: var(--restaurant-color-hex-174c3c);
            }

            @media (max-width: 640px) {
                .table-qr-page .qr-surface {
                    border-radius: 19px;
                }

                .table-qr-page .qr-action {
                    width: 100%;
                }

                .table-qr-page .qr-code-frame {
                    width: min(100%, 280px);
                    padding: 12px;
                }
            }

            @media print {
                @page {
                    size: A5 portrait;
                    margin: 8mm;
                }

                body {
                    background: var(--restaurant-color-hex-fff) !important;
                    -webkit-print-color-adjust: exact !important;
                    print-color-adjust: exact !important;
                }

                body * {
                    visibility: hidden !important;
                }

                #table-qr-print,
                #table-qr-print * {
                    visibility: visible !important;
                }

                #table-qr-print {
                    position: absolute !important;
                    inset: 0 auto auto 0 !important;
                    width: 100% !important;
                    max-width: none !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    border: 0 !important;
                    border-radius: 0 !important;
                    box-shadow: none !important;
                }

                #table-qr-print .qr-poster {
                    width: 100% !important;
                    max-width: 120mm !important;
                    margin: 0 auto !important;
                    border: 1px solid var(--restaurant-color-hex-dfe7e0) !important;
                    border-radius: 5mm !important;
                    box-shadow: none !important;
                    break-inside: avoid !important;
                }

                #table-qr-print .qr-code-frame {
                    width: 72mm !important;
                    padding: 3mm !important;
                    border-radius: 3mm !important;
                    box-shadow: none !important;
                }

                #table-qr-print .qr-poster-head {
                    padding: 7mm !important;
                }

                #table-qr-print .qr-print-hide,
                #table-qr-print .status-alert {
                    display: none !important;
                }

                #table-qr-print .qr-poster-content {
                    padding: 7mm !important;
                }

                #table-qr-print .qr-print-title {
                    font-size: 23pt !important;
                }

                #table-qr-print .qr-print-number {
                    font-size: 13pt !important;
                }

                #table-qr-print .qr-print-code {
                    font-size: 10pt !important;
                    overflow-wrap: anywhere !important;
                }

                #table-qr-print .qr-print-instruction {
                    font-size: 9pt !important;
                }

                #table-qr-print .qr-print-footer {
                    display: block !important;
                }
            }
        </style>

        {{-- Page Header --}}
        <div class="relative overflow-hidden rounded-3xl bg-[var(--restaurant-color-hex-10382d)] p-5 text-white shadow-lg sm:p-8">
            <div
                class="pointer-events-none absolute -left-10 -top-16 h-48 w-48 rounded-full border-[28px] border-white/[0.04]">
            </div>

            <div class="relative flex flex-col justify-between gap-5 sm:flex-row sm:items-center">
                <div class="flex items-start gap-4">
                    <div
                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border border-white/10 bg-white/10">
                        <svg class="h-8 w-8 text-[var(--restaurant-color-hex-d8ad60)]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7" rx="1" />
                            <rect x="14" y="3" width="7" height="7" rx="1" />
                            <rect x="3" y="14" width="7" height="7" rx="1" />
                            <path d="M14 14h3v3h-3zM20 14v2m-3 3h4v2h-4z" />
                        </svg>
                    </div>

                    <div>
                        <div class="mb-2 flex flex-wrap items-center gap-2">
                            <span
                                class="rounded-full border border-[var(--restaurant-color-hex-d8ad60)]/30 bg-[var(--restaurant-color-hex-d8ad60)]/10 px-3 py-1 text-[11px] font-bold text-[var(--restaurant-color-hex-f0d5a1)]">
                                إدارة الطاولات
                            </span>
                            <span class="text-xs text-white/45">/ رمز الاستجابة السريعة</span>
                        </div>

                        <h1 class="text-2xl font-black sm:text-3xl">
                            رمز QR للطاولة
                        </h1>
                        <p class="mt-2 text-sm leading-7 text-white/65">
                            اطبع الرمز وضعه على الطاولة ليتمكن الزبائن من الوصول إلى صفحة الطلب.
                        </p>
                    </div>
                </div>

                <a href="{{ route('tables.index') }}" class="qr-action qr-action-light self-start sm:self-center">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="m15 18-6-6 6-6" />
                    </svg>
                    العودة للطاولات
                </a>
            </div>
        </div>

        @if ((int) $table->status === 0)
            <div
                class="status-alert flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-amber-900">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 9v4m0 4h.01" />
                        <path
                            d="M10.3 3.9 1.8 18.5A1.7 1.7 0 0 0 3.3 21h17.4a1.7 1.7 0 0 0 1.5-2.5L13.7 3.9a2 2 0 0 0-3.4 0Z" />
                    </svg>
                </div>
                <div>
                    <p class="font-extrabold">الطاولة غير متاحة حالياً</p>
                    <p class="mt-1 text-sm leading-6">
                        حالة هذه الطاولة مشغولة أو غير متاحة. تأكد من حالتها قبل وضع رمز QR للاستخدام.
                    </p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-[minmax(0,1fr)_300px]">

            {{-- Printable QR Poster --}}
            <section id="table-qr-print" class="qr-surface min-w-0 p-3 sm:p-5">
                <div class="qr-poster">

                    {{-- Poster Heading --}}
                    <div class="qr-poster-head px-5 py-6 text-center sm:px-8 sm:py-8">
                        <div
                            class="relative z-10 mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl border border-white/15 bg-white/10">
                            <svg class="h-7 w-7 text-[var(--restaurant-color-hex-e9cc91)]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 10h18M5 10V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v4M5 10v10M19 10v10M3 15h18" />
                            </svg>
                        </div>

                        <p class="relative z-10 text-xs font-bold tracking-[.2em] text-[var(--restaurant-color-hex-e9cc91)]">
                            أهلاً وسهلاً بكم
                        </p>

                        <h2
                            class="qr-print-title relative z-10 mt-3 break-words text-3xl font-black tracking-tight sm:text-4xl">
                            {{ $table->name }}
                        </h2>

                        <div
                            class="relative z-10 mt-4 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2">
                            <span class="text-xs font-semibold text-white/65">رقم الطاولة</span>
                            <span class="qr-print-number text-lg font-black text-[var(--restaurant-color-hex-f0d5a1)]">{{ $table->number }}</span>
                        </div>
                    </div>

                    {{-- QR Code --}}
                    <div class="qr-poster-content px-4 py-6 text-center sm:px-8 sm:py-8">
                        <p class="qr-print-instruction mb-5 text-sm font-bold text-[var(--restaurant-color-hex-174c3c)]">
                            امسح الرمز بكاميرا هاتفك
                        </p>

                        <div class="qr-code-frame">
                            <img class="qr-code-image" src="data:image/svg+xml;base64,{{ $qrCode }}"
                                alt="رمز QR للطاولة {{ $table->number }}" decoding="sync">
                        </div>

                        <p class="qr-print-instruction mx-auto mt-5 max-w-sm text-sm leading-7 text-gray-500">
                            امسح رمز QR للبدء، ثم اتبع التعليمات الظاهرة على هاتفك.
                        </p>

                        <div class="qr-dash my-6 h-px w-full"></div>

                        <div class="mx-auto max-w-sm">
                            <p class="text-[10px] font-extrabold tracking-[.18em] text-gray-400">
                                TABLE ACCESS CODE
                            </p>

                            <div class="qr-print-code mt-2 break-all rounded-xl border border-dashed border-[var(--restaurant-color-hex-d9e4db)] bg-[var(--restaurant-color-hex-f7faf7)] px-3 py-3 font-mono text-base font-bold tracking-wider text-[var(--restaurant-color-hex-174c3c)] sm:text-lg"
                                dir="ltr">
                                {{ $table->code }}
                            </div>
                        </div>

                        <div class="qr-print-footer mt-6 hidden border-t border-gray-100 pt-4 text-center">
                            <p class="text-[9px] font-bold tracking-widest text-gray-400">
                                نرحب بكم ونتمنى لكم تجربة مميزة
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="qr-print-hide mt-5 flex flex-col gap-3 sm:flex-row sm:justify-center">
                    <button type="button" onclick="printTableQR()" class="qr-action qr-action-primary">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 6 2 18 2 18 9" />
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                            <rect x="6" y="14" width="12" height="8" />
                        </svg>
                        طباعة بطاقة الطاولة
                    </button>

                    <a href="{{ route('tables.index') }}" class="qr-action qr-action-light">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 12H5m7 7-7-7 7-7" />
                        </svg>
                        العودة للقائمة
                    </a>
                </div>
            </section>

            {{-- Instructions and Information --}}
            <aside class="space-y-5">
                <div class="qr-surface overflow-hidden">
                    <div class="border-b border-gray-100 px-5 py-5">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[var(--restaurant-color-hex-e8f3ed)] text-[var(--restaurant-color-hex-174c3c)]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="9" />
                                    <path d="M12 11v5m0-8h.01" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-[var(--restaurant-color-hex-174c3c)]">كيف تستخدم الرمز؟</h3>
                                <p class="mt-1 text-xs text-gray-400">ثلاث خطوات بسيطة</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-5 p-5">
                        <div class="qr-info-row">
                            <div class="qr-info-icon text-sm font-black">١</div>
                            <div>
                                <p class="text-sm font-extrabold text-gray-800">اطبع البطاقة</p>
                                <p class="mt-1 text-xs leading-6 text-gray-500">اطبع التصميم وضعه في مكان واضح على الطاولة.
                                </p>
                            </div>
                        </div>

                        <div class="qr-info-row">
                            <div class="qr-info-icon text-sm font-black">٢</div>
                            <div>
                                <p class="text-sm font-extrabold text-gray-800">امسح الرمز</p>
                                <p class="mt-1 text-xs leading-6 text-gray-500">يوجه الرمز الزبون إلى الصفحة المخصصة
                                    للطاولة.</p>
                            </div>
                        </div>

                        <div class="qr-info-row">
                            <div class="qr-info-icon text-sm font-black">٣</div>
                            <div>
                                <p class="text-sm font-extrabold text-gray-800">تابع التعليمات</p>
                                <p class="mt-1 text-xs leading-6 text-gray-500">يتابع الزبون خطوات النظام للوصول إلى الخدمة
                                    المتاحة.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="qr-surface p-5">
                    <h3 class="mb-4 flex items-center gap-2 text-sm font-extrabold text-[var(--restaurant-color-hex-174c3c)]">
                        <svg class="h-5 w-5 text-[var(--restaurant-color-hex-d0a34f)]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3 14.8 8.7 21 9.6l-4.5 4.4 1.1 6.2L12 17.3l-5.6 2.9 1.1-6.2L3 9.6l6.2-.9L12 3Z" />
                        </svg>
                        تفاصيل الطاولة
                    </h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-xs text-gray-500">اسم الطاولة</span>
                            <span class="max-w-[60%] break-words text-left text-sm font-bold text-gray-800">
                                {{ $table->name }}
                            </span>
                        </div>

                        <div class="h-px bg-gray-100"></div>

                        <div class="flex items-center justify-between gap-3">
                            <span class="text-xs text-gray-500">رقم الطاولة</span>
                            <span class="text-sm font-black tabular-nums text-[var(--restaurant-color-hex-174c3c)]">{{ $table->number }}</span>
                        </div>

                        <div class="h-px bg-gray-100"></div>

                        <div class="flex items-center justify-between gap-3">
                            <span class="text-xs text-gray-500">الحالة</span>
                            @if ((int) $table->status === 1)
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    متاحة
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1.5 text-xs font-bold text-amber-800">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                    مشغولة
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="qr-tip rounded-2xl border border-[var(--restaurant-color-hex-e8e1d2)] bg-[var(--restaurant-color-hex-faf7f0)] p-5">
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-[var(--restaurant-color-hex-a47b37)]" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 9v4m0 4h.01" />
                            <path
                                d="M10.3 3.9 1.8 18.5A1.7 1.7 0 0 0 3.3 21h17.4a1.7 1.7 0 0 0 1.5-2.5L13.7 3.9a2 2 0 0 0-3.4 0Z" />
                        </svg>
                        <div>
                            <p class="text-sm font-extrabold text-[var(--restaurant-color-hex-765b2e)]">حافظ على وضوح الرمز</p>
                            <p class="mt-1 text-xs leading-6 text-[var(--restaurant-color-hex-8d7956)]">
                                اطبع الرمز بجودة عالية، وتجنب تغطيته أو وضعه على سطح يعكس الضوء حتى يسهل مسحه بالهاتف.
                            </p>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <script>
        function printTableQR() {
            const qrImage = document.querySelector('#table-qr-print .qr-code-image');

            if (qrImage && !qrImage.complete) {
                qrImage.onload = () => window.print();
                qrImage.onerror = () => window.print();
                return;
            }

            window.print();
        }

        @if (request()->boolean('print'))
            window.addEventListener('load', function() {
                printTableQR();
            });
        @endif
    </script>
@endsection
