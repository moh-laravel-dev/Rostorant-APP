        {{-- Hero --}}
        <section class="menu-hero relative isolate overflow-hidden text-white">
            <div class="menu-dot-pattern pointer-events-none absolute inset-0 -z-10 opacity-30"></div>
            <div
                class="pointer-events-none absolute -left-24 -top-28 -z-10 h-80 w-80 rounded-full border-[42px] border-white/[0.035]">
            </div>
            <div
                class="pointer-events-none absolute -bottom-40 right-[25%] -z-10 h-96 w-96 rounded-full bg-emerald-200/[0.06] blur-3xl">
            </div>

            <div
                class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-12 sm:px-8 sm:py-16 lg:grid-cols-[1.1fr_.9fr] lg:gap-14 lg:py-20">
                <div class="relative z-10">
                    <div
                        class="mb-6 inline-flex items-center gap-2.5 rounded-full border border-white/15 bg-white/[0.07] px-4 py-2.5 text-xs font-bold text-emerald-100">
                        <span class="relative flex h-2.5 w-2.5">
                            <span
                                class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-300 opacity-50"></span>
                            <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-300"></span>
                        </span>
                        تجربة طعام تستحق التجربة
                    </div>
                    @if ($tableName || $tableNumber)
                        <div
                            class="relative mb-6 overflow-hidden rounded-2xl border border-white/10 bg-gradient-to-l from-[var(--restaurant-color-hex-174c3c)] via-[var(--restaurant-color-hex-123e32)] to-[var(--restaurant-color-hex-0d3027)] p-4 shadow-xl sm:p-5">

                            {{-- Decorative Background --}}
                            <div
                                class="pointer-events-none absolute -left-10 -top-12 h-32 w-32 rounded-full bg-[var(--restaurant-color-hex-e8c98c)]/10 blur-2xl">
                            </div>
                            <div
                                class="pointer-events-none absolute -bottom-12 right-1/3 h-28 w-28 rounded-full bg-emerald-300/10 blur-2xl">
                            </div>

                            {{-- Header --}}
                            <div class="relative mb-4 flex items-center gap-3">
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-[var(--restaurant-color-hex-e8c98c)]/30 bg-[var(--restaurant-color-hex-e8c98c)]/10 text-[var(--restaurant-color-hex-e8c98c)]">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 10h18M5 10V6h14v4M5 10v9m14-9v9M3 19h18" />
                                    </svg>
                                </div>

                                <div>
                                    <h3 class="text-sm font-extrabold tracking-wide text-white sm:text-base">
                                        معلومات الطاولة
                                    </h3>
                                    <p class="mt-1 text-xs text-white/55">
                                        تفاصيل الطاولة المرتبطة بالطلب
                                    </p>
                                </div>

                                <div
                                    class="mr-auto hidden items-center gap-1.5 rounded-full border border-emerald-300/20 bg-emerald-300/10 px-3 py-1.5 sm:flex">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span>
                                    <span class="text-[11px] font-bold text-emerald-200">معلومات الطلب</span>
                                </div>
                            </div>

                            {{-- Table Information --}}
                            <div class="relative grid grid-cols-1 gap-3 sm:grid-cols-2">

                                {{-- Table Name --}}
                                @if ($tableName)
                                    <div
                                        class="group flex min-w-0 items-center gap-3 rounded-xl border border-white/10 bg-white/[0.06] p-3.5 transition-all duration-300 hover:border-[var(--restaurant-color-hex-e8c98c)]/40 hover:bg-white/[0.09] sm:p-4">

                                        <div
                                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border border-[var(--restaurant-color-hex-e8c98c)]/25 bg-gradient-to-br from-[var(--restaurant-color-hex-e8c98c)]/20 to-[var(--restaurant-color-hex-e8c98c)]/5 text-[var(--restaurant-color-hex-e8c98c)] transition-transform duration-300 group-hover:scale-105">
                                            {{-- Table Icon --}}
                                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <rect x="3" y="7" width="18" height="5" rx="1.5" />
                                                <path d="M5 12v7m14-7v7M8 7V5h8v2M3 19h18" />
                                            </svg>
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <p class="mb-1 text-xs font-medium text-white/55">
                                                اسم الطاولة
                                            </p>
                                            <p class="truncate text-base font-extrabold text-white sm:text-lg">
                                                {{ $tableName }}
                                            </p>
                                        </div>

                                        <div
                                            class="shrink-0 rounded-lg border border-[var(--restaurant-color-hex-e8c98c)]/20 bg-[var(--restaurant-color-hex-e8c98c)]/10 px-2.5 py-1.5">
                                            <span
                                                class="text-[10px] font-extrabold tracking-widest text-[var(--restaurant-color-hex-e8c98c)]">
                                                TABLE
                                            </span>
                                        </div>
                                    </div>
                                @endif

                                {{-- Table Number --}}
                                @if ($tableNumber !== null)
                                    <div
                                        class="group flex min-w-0 items-center gap-3 rounded-xl border border-[var(--restaurant-color-hex-e8c98c)]/20 bg-gradient-to-l from-[var(--restaurant-color-hex-e8c98c)]/[0.10] to-white/[0.03] p-3.5 transition-all duration-300 hover:border-[var(--restaurant-color-hex-e8c98c)]/45 hover:bg-[var(--restaurant-color-hex-e8c98c)]/[0.12] sm:p-4">

                                        <div
                                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border border-[var(--restaurant-color-hex-e8c98c)]/30 bg-[var(--restaurant-color-hex-e8c98c)]/10 text-[var(--restaurant-color-hex-e8c98c)] transition-transform duration-300 group-hover:scale-105">
                                            {{-- Number / Hash Icon --}}
                                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M10 3L8 21M16 3l-2 18M4 9h16M3 15h16" />
                                            </svg>
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <p class="mb-1 text-xs font-medium text-white/55">
                                                رقم الطاولة
                                            </p>
                                            <p
                                                class="text-base font-black tabular-nums tracking-wider text-[var(--restaurant-color-hex-e8c98c)] sm:text-lg">
                                                {{ $tableNumber }}
                                            </p>
                                        </div>
                                        <div class="shrink-0 text-white/20">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M9 18l6-6-6-6" />
                                            </svg>
                                        </div>

                                    </div>
                                @endif

                            </div>

                            {{-- QR Code --}}
                            @if ($billCode)
                                <div
                                    class="bill-qr-panel mt-3 overflow-hidden rounded-2xl border border-[var(--restaurant-color-hex-e8c98c)]/25 bg-[var(--restaurant-color-hex-0c241d)] p-4 shadow-xl sm:p-5">

                                    {{-- Header --}}
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border border-[var(--restaurant-color-hex-e8c98c)]/30 bg-[var(--restaurant-color-hex-e8c98c)]/10 text-[var(--restaurant-color-hex-e8c98c)]">
                                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <rect x="3" y="3" width="7" height="7" rx="1" />
                                                <rect x="14" y="3" width="7" height="7" rx="1" />
                                                <rect x="3" y="14" width="7" height="7" rx="1" />
                                                <path d="M14 14h3v3h-3zM20 14v2m-3 3h3v2m-6-2v2" />
                                            </svg>
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-bold text-white/85">
                                                امسح رمز QR للانضمام
                                            </p>

                                            <p class="mt-1 text-xs leading-6 text-white/40">
                                                شارك هذا الرمز مع أصدقائك للانضمام إلى الفاتورة نفسها.
                                            </p>
                                        </div>
                                    </div>

                                    {{-- QR Code --}}
                                    <div
                                        class="bill-qr-frame mt-5 flex flex-col items-center rounded-2xl border border-[var(--restaurant-color-hex-e8c98c)]/20 bg-white p-4 sm:p-5">

                                        <div id="bill-qr-code"
                                            class="flex min-h-[220px] min-w-[220px] items-center justify-center"
                                            data-url="{{ route('menu.login', ['code' => $billCode]) }}">
                                        </div>
                                    </div>

                                    {{-- Bill Join Code --}}
                                    <div class="mt-5">
                                        <p class="text-center text-xs font-semibold text-white/50">
                                            رمز الانضمام لهذه الفاتورة
                                        </p>

                                        <div class="mx-auto mt-3 grid w-full max-w-sm grid-cols-6 gap-2 sm:gap-3"
                                            dir="ltr">

                                            @foreach (str_split($billCode) as $character)
                                                @if ($loop->index === 6)
                                                    <div class="col-span-6 flex items-center gap-3 py-1">
                                                        <div
                                                            class="h-px flex-1 bg-[var(--restaurant-color-hex-e8c98c)]/30">
                                                        </div>
                                                    </div>
                                                @endif
                                                <div
                                                    class="flex aspect-square min-w-0 flex-1 items-center justify-center rounded-xl border border-[var(--restaurant-color-hex-e8c98c)]/20 bg-white p-0.5 font-mono text-xl font-bold uppercase text-[var(--restaurant-color-hex-174c3c)] sm:text-2xl">
                                                    {{ $character }}
                                                </div>
                                            @endforeach
                                        </div>

                                        {{-- Copy Bill Code --}}
                                        <button type="button" id="copy-bill-code"
                                            class="mx-auto mt-4 flex w-full max-w-sm items-center justify-center gap-2 rounded-xl border border-[var(--restaurant-color-hex-e8c98c)]/30 bg-[var(--restaurant-color-hex-e8c98c)]/10 px-4 py-3 text-sm font-bold text-[var(--restaurant-color-hex-e8c98c)] transition hover:bg-[var(--restaurant-color-hex-e8c98c)]/20 active:scale-[0.98]">

                                            <svg id="copy-bill-code-icon" class="h-5 w-5" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="1.8"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="8" y="8" width="12" height="12" rx="2" />
                                                <path d="M16 8V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h3" />
                                            </svg>

                                            <span id="copy-bill-code-label">نسخ رمز الفاتورة</span>
                                        </button>

                                        <p id="copy-bill-code-feedback"
                                            class="mt-3 hidden text-center text-xs font-semibold text-[var(--restaurant-color-hex-e8c98c)]"
                                            role="status" aria-live="polite">
                                        </p>

                                        <span id="bill-join-code" class="sr-only">{{ $billCode }}</span>
                                    </div>
                                </div>

                                {{-- QR Code Library --}}
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const container = document.getElementById('bill-qr-code');

                                        if (container && typeof QRCode !== 'undefined') {
                                            new QRCode(container, {
                                                text: container.dataset.url,
                                                width: 220,
                                                height: 220,
                                                colorDark: '#0c241d',
                                                colorLight: '#ffffff',
                                                correctLevel: QRCode.CorrectLevel.H
                                            });
                                        }

                                        const button = document.getElementById('copy-bill-code');
                                        const codeElement = document.getElementById('bill-join-code');
                                        const label = document.getElementById('copy-bill-code-label');
                                        const icon = document.getElementById('copy-bill-code-icon');
                                        const feedback = document.getElementById('copy-bill-code-feedback');

                                        if (!button || !codeElement || !label || !icon || !feedback) {
                                            return;
                                        }

                                        button.addEventListener('click', async function() {
                                            const code = codeElement.textContent.trim();

                                            try {
                                                if (navigator.clipboard && window.isSecureContext) {
                                                    await navigator.clipboard.writeText(code);
                                                } else {
                                                    const textarea = document.createElement('textarea');

                                                    textarea.value = code;
                                                    textarea.setAttribute('readonly', '');
                                                    textarea.style.position = 'fixed';
                                                    textarea.style.left = '-9999px';

                                                    document.body.appendChild(textarea);
                                                    textarea.select();

                                                    const copied = document.execCommand('copy');
                                                    textarea.remove();

                                                    if (!copied) {
                                                        throw new Error('Copy failed');
                                                    }
                                                }

                                                label.textContent = 'تم النسخ';
                                                feedback.textContent = 'تم نسخ رمز الفاتورة بنجاح';
                                                feedback.classList.remove('hidden');

                                                icon.innerHTML = '<path d="m5 12 4 4L19 6" />';

                                                setTimeout(function() {
                                                    label.textContent = 'نسخ رمز الفاتورة';

                                                    feedback.classList.add('hidden');

                                                    icon.innerHTML = `
                            <rect x="8" y="8" width="12" height="12" rx="2" />
                            <path d="M16 8V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h3" />
                        `;
                                                }, 2000);

                                            } catch (error) {
                                                feedback.textContent = 'تعذر النسخ، يرجى نسخ الرمز يدويًا';
                                                feedback.classList.remove('hidden');
                                            }
                                        });
                                    });
                                </script>
                            @endif

                        </div>
                    @endif
                    <h1 class="max-w-2xl text-4xl font-black leading-[1.35] tracking-tight sm:text-5xl lg:text-6xl">
                        لحظتك المفضلة
                        <span class="block text-[var(--restaurant-color-hex-e8c98c)]">تبدأ من هنا.</span>
                    </h1>

                    <p class="mt-5 max-w-xl text-sm leading-8 text-white/70 sm:text-base">
                        من أول اختيار إلى آخر لقمة، اكتشف أطباقنا واختر ما يناسب ذوقك بكل سهولة.
                    </p>

                    <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                        <a href="#menu-items"
                            class="menu-btn bg-[var(--restaurant-color-hex-e8c98c)] text-[var(--restaurant-color-hex-26372b)] shadow-lg shadow-black/10 hover:-translate-y-0.5 hover:bg-[var(--restaurant-color-hex-f3dba9)]">
                            اكتشف قائمة الطعام
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 12H5m7 7-7-7 7-7" />
                            </svg>
                        </a>

                        {{-- <a href="#categories"
                            class="menu-btn border border-white/15 bg-white/[0.06] text-white hover:bg-white/10">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            تصفح التصنيفات
                        </a> --}}
                    </div>

                    <div class="mt-9 flex flex-wrap items-center gap-x-6 gap-y-3 border-t border-white/10 pt-6">
                        <div class="flex items-center gap-2.5">
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/[0.08] text-[var(--restaurant-color-hex-e8c98c)]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 3v18M3 12h18" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-white">خيارات متنوعة</p>
                                <p class="mt-1 text-[10px] text-white/45">لجميع الأذواق</p>
                            </div>
                        </div>

                        <div class="h-8 w-px bg-white/10"></div>

                        <div class="flex items-center gap-2.5">
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/[0.08] text-[var(--restaurant-color-hex-e8c98c)]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="9" />
                                    <path d="m9 12 2 2 4-4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-white">طلب سهل</p>
                                <p class="mt-1 text-[10px] text-white/45">بخطوات بسيطة</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Decorative Hero Card --}}
                <div class="relative mx-auto hidden w-full max-w-md lg:block">
                    <div class="absolute inset-6 rounded-[2.5rem] bg-emerald-200/10 blur-2xl"></div>

                    <div class="menu-glass relative rounded-[2rem] p-4 shadow-2xl">
                        <div
                            class="overflow-hidden rounded-[1.5rem] bg-[var(--restaurant-color-hex-f8f6ef)] p-5 text-[var(--restaurant-color-hex-174c3c)]">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs font-bold text-[var(--restaurant-color-hex-7e8d80)]">من قائمتنا
                                        إلى مائدتك</p>
                                    <h2 class="mt-1 text-2xl font-black">اختر ما تحب</h2>
                                </div>

                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[var(--restaurant-color-hex-174c3c)] text-[var(--restaurant-color-hex-e8c98c)]">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 3v6a3 3 0 0 0 6 0V3M6 3v18M15 3v18m0-18c3 2 4 5 4 8h-4" />
                                    </svg>
                                </div>
                            </div>

                            <div class="my-5 space-y-3">
                                <div
                                    class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-3 shadow-sm">
                                    <div
                                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-[var(--restaurant-color-hex-e7eee5)] text-[var(--restaurant-color-hex-174c3c)]">
                                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M4 11h16l-2 9H6l-2-9Zm4 0a4 4 0 0 1 8 0M3 7h18" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-extrabold">أطباق لكل مزاج</p>
                                        <p class="mt-1 text-xs text-gray-500">اكتشف النكهات المفضلة لديك</p>
                                    </div>
                                    <svg class="mr-auto h-4 w-4 shrink-0 text-[var(--restaurant-color-hex-9aab9d)]"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="m9 18 6-6-6-6" />
                                    </svg>
                                </div>

                                <div
                                    class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-3 shadow-sm">
                                    <div
                                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-[var(--restaurant-color-hex-f5ead4)] text-[var(--restaurant-color-hex-95743a)]">
                                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M12 3v18M3 12h18" />
                                            <circle cx="12" cy="12" r="8" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-extrabold">طلبك على ذوقك</p>
                                        <p class="mt-1 text-xs text-gray-500">أضف اختياراتك بكل سهولة</p>
                                    </div>
                                    <svg class="mr-auto h-4 w-4 shrink-0 text-[var(--restaurant-color-hex-9aab9d)]"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="m9 18 6-6-6-6" />
                                    </svg>
                                </div>
                            </div>

                            <div
                                class="flex items-center gap-3 rounded-xl bg-[var(--restaurant-color-hex-e7eee5)] p-3">
                                <span
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-[var(--restaurant-color-hex-174c3c)]">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m5 12 4 4L19 6" />
                                    </svg>
                                </span>
                                <p class="text-xs font-extrabold">كل ما تحتاجه، في مكان واحد.</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="mt-5 hidden rounded-2xl border border-white/15 bg-[var(--restaurant-color-hex-e8c98c)] px-4 py-3 text-[var(--restaurant-color-hex-26372b)] shadow-xl xl:block">
                        <p class="text-[10px] font-bold">تجربة مميزة</p>
                        <p class="mt-1 text-sm font-black">اختيارك يصنع الفرق</p>
                    </div>
                </div>
            </div>
        </section>
