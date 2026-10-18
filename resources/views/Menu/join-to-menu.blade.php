@extends('admin.layouts.app')

@section('title', 'الدخول إلى قائمة الطعام')
@section('description', 'أدخل كود الطاولة لفتح قائمة الطعام.')

@section('content')
    <style>
        .join-menu-page {
            background:
                radial-gradient(circle at 12% 18%, rgb(215 168 91 / 12%), transparent 28rem),
                radial-gradient(circle at 90% 85%, rgb(23 76 60 / 8%), transparent 30rem),
                var(--theme-page);
            color: var(--theme-text);
        }

        .join-menu-card {
            background-color: var(--theme-surface);
            border-color: var(--theme-border);
            box-shadow: 0 24px 70px rgb(10 35 25 / 14%);
            color: var(--theme-text);
        }

        .menu-entry-emblem {
            background:
                radial-gradient(circle at 78% 22%, rgb(215 168 91 / 18%), transparent 34%),
                var(--theme-surface-raised);
            border-color: var(--theme-border);
            color: var(--restaurant-color-hex-174c3c);
            --entry-emblem-accent: #80550d;
        }

        .join-menu-eyebrow {
            color: #80550d;
        }

        .join-menu-title {
            color: var(--restaurant-color-hex-174c3c);
        }

        .join-menu-description,
        .join-menu-link,
        .table-code-help {
            color: var(--theme-text-muted);
        }

        .table-code-label {
            color: var(--theme-text);
        }

        .table-code-box {
            border-color: var(--theme-border);
            background-color: var(--theme-surface-raised);
            color: var(--theme-text);
        }

        .table-code-box-hash {
            border-color: var(--theme-border);
            background-color: var(--theme-surface-raised);
            color: var(--theme-text);
        }

        .table-code-box::placeholder {
            color: var(--theme-text-muted);
        }

        .table-code-box {
            direction: ltr;
            caret-color: var(--theme-text);
            font-variant-numeric: lining-nums;
            transition: border-color 160ms ease, box-shadow 160ms ease, background-color 160ms ease;
        }

        .table-code-box-hash {
            direction: ltr;
            caret-color: var(--theme-text);
            font-variant-numeric: lining-nums;
            transition: border-color 160ms ease, box-shadow 160ms ease, background-color 160ms ease;
        }

        .table-code-box:focus {
            border-color: var(--restaurant-color-hex-174c3c);
            background-color: var(--theme-surface);
            box-shadow: 0 0 0 4px rgb(23 76 60 / 18%);
            outline: none;
        }

        .table-code-box:not(:placeholder-shown) {
            background-color: var(--theme-surface);
        }

        .table-code-box[aria-invalid="true"] {
            border-color: #c2413a;
        }

        .table-code-error {
            border: 1px solid rgb(194 65 58 / 32%);
            background-color: rgb(194 65 58 / 8%);
            color: #a52f29;
        }

        .join-menu-button {
            background-color: var(--restaurant-color-hex-174c3c);
            color: #fff;
        }

        .join-menu-button:hover {
            background-color: var(--restaurant-color-hex-10382c);
        }

        .join-menu-button:focus-visible,
        .join-menu-link:focus-visible {
            outline: 3px solid #d7a85b;
            outline-offset: 3px;
        }

        .join-menu-link:hover {
            color: var(--restaurant-color-hex-174c3c);
        }

        html[data-theme="dark"] .join-menu-page {
            background:
                radial-gradient(circle at 12% 18%, rgb(215 168 91 / 8%), transparent 28rem),
                radial-gradient(circle at 90% 85%, rgb(94 171 126 / 7%), transparent 30rem),
                var(--theme-page);
        }

        html[data-theme="dark"] .join-menu-card {
            box-shadow: 0 24px 70px rgb(0 0 0 / 28%);
        }

        html[data-theme="dark"] .menu-entry-emblem {
            color: #b7e2c4;
            --entry-emblem-accent: #f0d6a4;
        }

        html[data-theme="dark"] .join-menu-eyebrow {
            color: #f0d6a4;
        }

        html[data-theme="dark"] .join-menu-title {
            color: var(--theme-text);
        }

        html[data-theme="dark"] .table-code-box:focus {
            border-color: #87c99a;
            box-shadow: 0 0 0 4px rgb(135 201 154 / 20%);
        }

        html[data-theme="dark"] .table-code-box[aria-invalid="true"] {
            border-color: #ffaaa4;
        }

        html[data-theme="dark"] .table-code-error {
            border-color: rgb(255 170 164 / 28%);
            background-color: rgb(255 170 164 / 8%);
            color: #ffaaa4;
        }

        html[data-theme="dark"] .join-menu-button {
            background-color: #27734f;
        }

        html[data-theme="dark"] .join-menu-button:hover {
            background-color: #31845c;
        }

        html[data-theme="dark"] .join-menu-link:hover {
            color: #b7e2c4;
        }

        @media (prefers-reduced-motion: reduce) {
            .table-code-box {
                transition: none;
            }
        }
    </style>

    @php
        $previousCode = old('code', []);
        if (is_string($previousCode)) {
            $previousCode = str_split(preg_replace('/^#/', '', $previousCode));
        }
        $previousCode = is_array($previousCode) ? array_values($previousCode) : [];
    @endphp

    <section class="join-menu-page flex min-h-[75vh] items-center justify-center px-4 py-12 sm:px-6 sm:py-16">
        <div class="join-menu-card w-full max-w-lg rounded-3xl border p-6 text-center sm:p-10">
            <div
                class="menu-entry-emblem relative mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl border shadow-sm">
                <svg class="h-9 w-9" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.6"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="16" cy="16" r="11.5" />
                    <circle cx="16" cy="16" r="7.2" />
                    <path d="M4.5 6v7M2.5 6v4a2 2 0 0 0 4 0V6M4.5 13v13" />
                    <path d="M25 6v20M25 6c-2.5 2.3-3.2 5-3.2 8h3.2" />
                    <path d="M13.5 16.2c1.3-1.9 3.7-2.2 5-.2" stroke="var(--entry-emblem-accent)" />
                </svg>
                <span
                    class="absolute -right-1 -top-1 h-3 w-3 rounded-full border-2 border-[var(--theme-surface)] bg-[#80550d]"
                    aria-hidden="true"></span>
            </div>

            <p class="join-menu-eyebrow mb-2 text-sm font-bold">أهلاً وسهلاً بكم</p>
            <h1 class="join-menu-title mb-3 text-2xl font-black sm:text-3xl">
                افتح قائمة الطعام
            </h1>
            <p class="join-menu-description mx-auto mb-8 max-w-sm text-sm leading-7">
                أدخل الرمز المكوّن من ست خانات والموجود على طاولتك، أو امسح رمز QR للبدء بطلبك.
            </p>

            <dev class="text-right">
                <fieldset>
                    <legend class="table-code-label mb-3 block text-sm font-bold">
                        كود الطاولة
                    </legend>

                    <div class="mx-auto grid w-full max-w-sm grid-cols-7 gap-2 sm:gap-3" dir="ltr">
                        <div
                            class="table-code-box-hash aspect-square min-w-0 rounded-xl border p-1 text-center font-mono text-xl font-bold uppercase placeholder:text-[var(--theme-text-muted)] sm:text-2xl">
                            #</div>
                        @foreach (range(0, 5) as $index)
                            <input type="text" name="code[]" value="{{ $previousCode[$index] ?? '' }}" maxlength="1"
                                pattern="[A-Za-z0-9]" inputmode="text" autocapitalize="characters"
                                autocomplete="{{ $index === 0 ? 'one-time-code' : 'off' }}" spellcheck="false" required
                                aria-label="الخانة {{ $index + 1 }} من 6 لكود الطاولة"
                                aria-describedby="table-code-help{{ $errors->has('code') ? ' table-code-error' : '' }}"
                                @if ($errors->has('code')) aria-invalid="true" @endif
                                @if ($index === 5) enterkeyhint="go" @endif
                                class="table-code-box aspect-square min-w-0 rounded-xl border p-1 text-center font-mono text-xl font-bold uppercase placeholder:text-[var(--theme-text-muted)] sm:text-2xl"
                                placeholder="•">
                        @endforeach
                    </div>
                </fieldset>

                <p id="table-code-help" class="table-code-help mt-3 text-center text-xs">
                    اكتب الخانات بالترتيب بدون علامة #.
                </p>

                @error('code')
                    <p id="table-code-error" class="table-code-error mt-3 rounded-lg px-3 py-2 text-sm font-semibold"
                        role="alert">
                        {{ $message }}
                    </p>
                @enderror

                <button type="submit"
                    class="join-menu-button mt-6 flex w-full items-center justify-center gap-2 rounded-xl px-5 py-4 font-bold transition focus-visible:outline-none">
                    <span>الذهاب إلى القائمة</span>

                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M19 12H5M12 19l-7-7 7-7" />
                    </svg>
                </button>
            </dev>

            <a href="{{ route('welcome') }}" class="join-menu-link mt-6 inline-flex text-sm font-semibold transition">
                العودة إلى الرئيسية
            </a>
        </div>
    </section>
    <script>
        (() => {
            const boxes = Array.from(document.querySelectorAll('.table-code-box'));
            const button = document.querySelector('.join-menu-button');

            if (!boxes.length || !button) return;

            const normalize = (value) => value.replace(/^#/, '').toUpperCase();

            const focusBox = (index) => {
                boxes[Math.max(0, Math.min(index, boxes.length - 1))]?.focus();
            };

            const getCode = () => boxes.map(box => box.value.trim()).join('');

            const goToMenu = () => {
                if (!boxes.every(box => box.value.trim() !== '')) {
                    focusBox(boxes.findIndex(box => !box.value.trim()));
                    return;
                }

                const code = getCode();

                if (!/^[A-Z0-9]{6}$/.test(code)) return;

                window.location.href = @json(route('menu.page_login', ['code' => '__CODE__', 'from' => 1]))
                    .replace('__CODE__', encodeURIComponent(code));
            };

            boxes.forEach((box, index) => {
                box.addEventListener('input', () => {
                    const value = normalize(box.value).replace(/[^A-Z0-9]/g, '');

                    if (value.length > 1) {
                        value.slice(0, boxes.length).split('').forEach((character, offset) => {
                            if (boxes[offset]) boxes[offset].value = character;
                        });

                        focusBox(Math.min(value.length, boxes.length) - 1);
                        return;
                    }

                    box.value = value;

                    if (value && index < boxes.length - 1) {
                        focusBox(index + 1);
                    }
                });

                box.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        goToMenu();
                    } else if (event.key === 'Backspace') {
                        event.preventDefault();

                        if (box.value) {
                            box.value = '';
                        } else if (index > 0) {
                            boxes[index - 1].value = '';
                            focusBox(index - 1);
                        }
                    } else if (event.key === 'Delete') {
                        event.preventDefault();
                        box.value = '';
                    } else if (event.key === 'ArrowLeft') {
                        event.preventDefault();
                        focusBox(index - 1);
                    } else if (event.key === 'ArrowRight') {
                        event.preventDefault();
                        focusBox(index + 1);
                    }
                });

                box.addEventListener('paste', (event) => {
                    const pastedCode = normalize(
                        event.clipboardData.getData('text').trim()
                    ).replace(/^#/, '');

                    if (!/^[A-Z0-9]{6}$/.test(pastedCode)) return;

                    event.preventDefault();

                    pastedCode.split('').forEach((character, offset) => {
                        boxes[offset].value = character;
                    });

                    focusBox(boxes.length - 1);
                });
            });

            button.addEventListener('click', (event) => {
                event.preventDefault();
                goToMenu();
            });
        })();
    </script>
@endsection
