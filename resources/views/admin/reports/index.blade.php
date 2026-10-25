@extends('admin.layouts.main-layout')

@section('title', 'التقارير')

@section('content')
    @php
        $circleRadius = 94;
        $circleCircumference = 2 * M_PI * $circleRadius;
        $circleOffset = 0;
        $circleColors = [
            '#e63946',
            '#f3722c',
            '#f9c74f',
            '#90be6d',
            '#43aa8b',
            '#4d908e',
            '#277da1',
            '#4361ee',
            '#3a0ca3',
            '#7209b7',
            '#b5179e',
            '#f72585',
        ];
        $lineChartWidth = 900;
        $lineChartHeight = 320;
        $lineChartLeft = 54;
        $lineChartRight = 24;
        $lineChartTop = 24;
        $lineChartBottom = 54;
        $lineChartPlotWidth = $lineChartWidth - $lineChartLeft - $lineChartRight;
        $lineChartPlotHeight = $lineChartHeight - $lineChartTop - $lineChartBottom;
        $dailySalesMax = max(array_values($dailySales) ?: [0]);
        $dailySalesScale = $dailySalesMax > 0 ? $dailySalesMax : 1;
        $dailySalesPoints = [];

        foreach ($dailySales as $day => $amount) {
            $x = $lineChartLeft + (($day - 1) * $lineChartPlotWidth) / max(count($dailySales) - 1, 1);
            $y = $lineChartTop + $lineChartPlotHeight - ($amount / $dailySalesScale) * $lineChartPlotHeight;
            $dailySalesPoints[] = [
                'day' => $day,
                'amount' => $amount,
                'x' => round($x, 2),
                'y' => round($y, 2),
            ];
        }

        $dailySalesPolyline = collect($dailySalesPoints)
            ->map(fn ($point) => $point['x'] . ',' . $point['y'])
            ->implode(' ');
        $dayLabelStep = max(1, (int) ceil(count($dailySales) / 7));
    @endphp

    <style>
        .report-donut-segment {
            transform-box: view-box;
            transform-origin: 120px 120px;
            transition: transform 180ms ease, stroke-width 180ms ease;
            cursor: pointer;
        }

        .report-donut-label {
            transform-box: view-box;
            transform-origin: 120px 120px;
            transition: transform 180ms ease;
        }

        .report-donut-segment.is-active,
        .report-donut-segment:hover,
        .report-donut-segment:focus-visible {
            transform: translate(var(--explode-x), var(--explode-y)) scale(1.08);
            stroke-width: 34;
            outline: none;
        }

        .report-donut-label.is-active {
            transform: translate(var(--explode-x), var(--explode-y)) scale(1.08);
        }

        @media (prefers-reduced-motion: reduce) {
            .report-donut-segment,
            .report-donut-label {
                transition: none;
            }
        }
    </style>

    <main class="mx-auto w-full max-w-7xl space-y-6 p-4 sm:p-6 lg:p-8" dir="rtl">
        <header class="relative overflow-hidden rounded-3xl px-6 py-7 text-white shadow-xl sm:px-8 sm:py-9"
            style="background: radial-gradient(circle at 12% 10%, color-mix(in srgb, var(--brand-accent) 20%, transparent), transparent 30%), linear-gradient(120deg, var(--brand-dark), var(--brand-primary) 60%, var(--brand-medium));">
            <div class="relative">
                <p class="text-xs font-extrabold tracking-wide text-emerald-100">قراءة واضحة لأداء المطعم</p>
                <h1 class="mt-2 text-3xl font-black sm:text-4xl">التقارير</h1>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-emerald-50/85">
                    تابع إجمالي قيمة الفواتير خلال أشهر السنة، وقارن حركة المبيعات يوماً بيوم.
                </p>
            </div>
        </header>

        <form method="GET" action="{{ route('admin.reports.index') }}"
            class="grid gap-4 rounded-2xl border border-[var(--theme-border)] bg-[var(--theme-surface)] p-4 shadow-sm sm:grid-cols-3 sm:p-5">
            <label class="block">
                <span class="mb-1.5 block text-sm font-bold text-[var(--theme-text)]">سنة الرسم الدائري</span>
                <input type="number" name="circle_year" min="1900" max="2100" step="1"
                    value="{{ $circleYear }}"
                    class="min-h-11 w-full rounded-xl border border-[var(--theme-border)] bg-[var(--theme-surface-raised)] px-3 text-sm text-[var(--theme-text)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-[var(--brand-accent)]">
            </label>
            <label class="block">
                <span class="mb-1.5 block text-sm font-bold text-[var(--theme-text)]">سنة الرسم اليومي</span>
                <input type="number" name="line_year" min="1900" max="2100" step="1"
                    value="{{ $lineYear }}"
                    class="min-h-11 w-full rounded-xl border border-[var(--theme-border)] bg-[var(--theme-surface-raised)] px-3 text-sm text-[var(--theme-text)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-[var(--brand-accent)]">
            </label>
            <label class="block">
                <span class="mb-1.5 block text-sm font-bold text-[var(--theme-text)]">شهر الرسم اليومي</span>
                <select name="line_month"
                    class="min-h-11 w-full rounded-xl border border-[var(--theme-border)] bg-[var(--theme-surface-raised)] px-3 text-sm text-[var(--theme-text)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-[var(--brand-accent)]">
                    @foreach ($months as $monthIndex => $monthName)
                        <option value="{{ $monthIndex + 1 }}" @selected($lineMonth === $monthIndex + 1)>
                            {{ $monthName }}
                        </option>
                    @endforeach
                </select>
            </label>
            <div class="flex flex-wrap items-center gap-3 sm:col-span-3">
                <button type="submit"
                    class="inline-flex min-h-11 items-center justify-center rounded-xl bg-[var(--brand-primary)] px-6 text-sm font-extrabold text-white shadow-sm transition hover:-translate-y-px hover:bg-[var(--brand-dark)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--brand-accent)]">
                    تحديث التقارير
                </button>
                @if ($errors->any())
                    <p role="alert" class="text-sm font-semibold text-red-700 dark:text-red-300">
                        يرجى اختيار سنة بين 1900 و2100 وشهر صالح.
                    </p>
                @endif
            </div>
        </form>

        <section class="grid gap-6">
            <article class="overflow-hidden rounded-3xl border border-[var(--theme-border)] bg-[var(--theme-surface)] shadow-sm">
                <div class="border-b border-[var(--theme-border)] px-5 py-5 sm:px-6">
                    <p class="text-xs font-extrabold text-[var(--brand-primary)]">توزيع سنوي</p>
                    <h2 class="mt-1 text-xl font-black text-[var(--theme-text)]">قيمة الفواتير حسب الشهر</h2>
                    <p class="mt-1 text-sm text-[var(--theme-text-muted)]">السنة {{ $circleYear }} · جميع حالات الفواتير</p>
                </div>

                <div class="grid items-center gap-5 p-5 sm:p-6 md:grid-cols-[minmax(220px,1fr)_minmax(0,1.3fr)]"
                    x-data="{
                        amounts: @js(array_values($yearlySales)),
                        hiddenMonths: [],
                        activeMonth: null,
                        get visibleTotal() {
                            return this.amounts.reduce((total, amount, index) =>
                                total + (this.hiddenMonths.includes(index) ? 0 : Number(amount)), 0);
                        },
                        percentage(index) {
                            return this.visibleTotal > 0
                                ? Number(this.amounts[index]) / this.visibleTotal * 100
                                : 0;
                        },
                        percentageLabel(index) {
                            return Math.round(this.percentage(index)) + '%';
                        },
                        segmentLength(index) {
                            return this.visibleTotal > 0
                                ? Number(this.amounts[index]) / this.visibleTotal * {{ $circleCircumference }}
                                : 0;
                        },
                        dashArray(index) {
                            const length = Math.max(0, this.segmentLength(index) - 2.5);
                            return length + ' ' + ({{ $circleCircumference }} - length);
                        },
                        dashOffset(index) {
                            let offset = 0;
                            for (let month = 0; month < index; month++) {
                                if (!this.hiddenMonths.includes(month)) {
                                    offset += this.segmentLength(month);
                                }
                            }
                            return -offset;
                        },
                        segmentStyle(index) {
                            let offset = 0;
                            for (let month = 0; month < index; month++) {
                                if (!this.hiddenMonths.includes(month)) {
                                    offset += this.segmentLength(month);
                                }
                            }
                            const angle = -Math.PI / 2 + (offset + this.segmentLength(index) / 2)
                                / {{ $circleCircumference }} * 2 * Math.PI;
                            return '--explode-x:' + (-Math.sin(angle) * 7) + 'px;--explode-y:'
                                + (Math.cos(angle) * 7) + 'px;';
                        },
                        labelPosition(index) {
                            let offset = 0;
                            for (let month = 0; month < index; month++) {
                                if (!this.hiddenMonths.includes(month)) {
                                    offset += this.segmentLength(month);
                                }
                            }
                            const angle = -Math.PI / 2 + (offset + this.segmentLength(index) / 2)
                                / {{ $circleCircumference }} * 2 * Math.PI;
                            return {
                                x: 120 + Math.cos(angle) * {{ $circleRadius }},
                                y: 120 + Math.sin(angle) * {{ $circleRadius }}
                            };
                        },
                        toggleMonth(index) {
                            this.hiddenMonths = this.hiddenMonths.includes(index)
                                ? this.hiddenMonths.filter(month => month !== index)
                                : [...this.hiddenMonths, index];
                        }
                    }">
                    <div class="relative mx-auto w-full max-w-[280px]">
                        <svg viewBox="0 0 240 240" role="img"
                            aria-label="توزيع إجمالي قيمة الفواتير على أشهر سنة {{ $circleYear }}"
                            class="mx-auto w-full overflow-visible">
                            <circle cx="120" cy="120" r="{{ $circleRadius }}"
                                fill="none" stroke="var(--theme-surface-raised)" stroke-width="30" />
                            @if ($yearlySalesTotal > 0)
                                <g transform="rotate(-90 120 120)">
                                    @foreach ($yearlySales as $monthNumber => $amount)
                                        @php
                                            $segmentLength = ($amount / $yearlySalesTotal) * $circleCircumference;
                                            $visibleSegmentLength = max(0, $segmentLength - 2.5);
                                        @endphp
                                        @if ($amount > 0)
                                            <circle cx="120" cy="120" r="{{ $circleRadius }}" fill="none"
                                                stroke="{{ $circleColors[$monthNumber - 1] }}" stroke-width="30"
                                                stroke-linecap="butt"
                                                stroke-dasharray="{{ $visibleSegmentLength }} {{ $circleCircumference - $visibleSegmentLength }}"
                                                stroke-dashoffset="{{ -$circleOffset }}"
                                                class="report-donut-segment"
                                                x-bind:stroke-dasharray="dashArray({{ $monthNumber - 1 }})"
                                                x-bind:stroke-dashoffset="dashOffset({{ $monthNumber - 1 }})"
                                                x-bind:style="segmentStyle({{ $monthNumber - 1 }})"
                                                x-bind:class="activeMonth === {{ $monthNumber - 1 }} ? 'is-active' : ''"
                                                x-show="!hiddenMonths.includes({{ $monthNumber - 1 }})"
                                                @mouseenter="activeMonth = {{ $monthNumber - 1 }}"
                                                @mouseleave="activeMonth = null"
                                                @focus="activeMonth = {{ $monthNumber - 1 }}"
                                                @blur="activeMonth = null"
                                                tabindex="0" role="button"
                                                aria-label="{{ $months[$monthNumber - 1] }}: {{ number_format($amount, 0) }} ل.س"
                                                @keydown.enter.prevent="toggleMonth({{ $monthNumber - 1 }})"
                                                @keydown.space.prevent="toggleMonth({{ $monthNumber - 1 }})"
                                                @click="toggleMonth({{ $monthNumber - 1 }})">
                                                <title>{{ $months[$monthNumber - 1] }}: {{ number_format($amount, 0) }} ل.س</title>
                                            </circle>
                                        @endif
                                        @php $circleOffset += $segmentLength; @endphp
                                    @endforeach
                                </g>
                                @foreach ($yearlySales as $monthNumber => $amount)
                                    @if ($amount > 0)
                                        <text text-anchor="middle" dominant-baseline="central"
                                            class="report-donut-label pointer-events-none select-none fill-white text-[10px] font-black"
                                            style="paint-order: stroke; stroke: rgb(0 0 0 / 35%); stroke-width: 2px;"
                                            x-bind:x="labelPosition({{ $monthNumber - 1 }}).x"
                                            x-bind:y="labelPosition({{ $monthNumber - 1 }}).y"
                                            x-bind:style="segmentStyle({{ $monthNumber - 1 }})"
                                            x-bind:class="{
                                                'is-active': activeMonth === {{ $monthNumber - 1 }},
                                                'hidden': percentage({{ $monthNumber - 1 }}) < 5
                                            }"
                                            x-show="!hiddenMonths.includes({{ $monthNumber - 1 }})"
                                            x-text="percentageLabel({{ $monthNumber - 1 }})"
                                            aria-hidden="true"></text>
                                    @endif
                                @endforeach
                            @else
                                <circle cx="120" cy="120" r="{{ $circleRadius }}" fill="none"
                                    stroke="var(--theme-border)" stroke-width="30" stroke-dasharray="5 5" />
                            @endif
                        </svg>
                        <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center text-center">
                            <span class="text-xs font-bold text-[var(--theme-text-muted)]">إجمالي السنة</span>
                            <span class="mt-1 text-2xl font-black tabular-nums text-[var(--theme-text)]"
                                x-text="Math.round(visibleTotal).toLocaleString()">
                                {{ number_format($yearlySalesTotal, 0) }}
                            </span>
                            <span class="text-xs font-bold text-[var(--theme-text-muted)]">ل.س</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($yearlySales as $monthNumber => $amount)
                            <button type="button"
                                class="flex min-w-0 items-center gap-2 rounded-xl bg-[var(--theme-surface-raised)] px-2.5 py-2 text-right transition hover:-translate-y-px hover:shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--brand-accent)]"
                                @click="toggleMonth({{ $monthNumber - 1 }})"
                                :aria-pressed="!hiddenMonths.includes({{ $monthNumber - 1 }})"
                                :class="hiddenMonths.includes({{ $monthNumber - 1 }}) ? 'opacity-45' : ''">
                                <span aria-hidden="true"
                                    class="flex h-4 w-4 shrink-0 items-center justify-center rounded border transition"
                                    :class="hiddenMonths.includes({{ $monthNumber - 1 }}) ? 'border-[var(--theme-border)] bg-transparent' : 'border-[var(--brand-primary)] bg-[var(--brand-primary)] text-white'">
                                    <svg x-show="!hiddenMonths.includes({{ $monthNumber - 1 }})"
                                        class="h-3 w-3" viewBox="0 0 16 16" fill="none">
                                        <path d="m3 8 3 3 7-7" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <span class="h-3 w-3 shrink-0 rounded-full"
                                    style="background: {{ $circleColors[$monthNumber - 1] }}"></span>
                                <span class="min-w-0 flex-1 truncate text-xs font-semibold text-[var(--theme-text-muted)]">
                                    {{ $months[$monthNumber - 1] }}
                                </span>
                                <span class="shrink-0 text-xs font-extrabold tabular-nums text-[var(--theme-text)]"
                                    title="{{ number_format($amount, 2) }} ل.س">
                                    {{ number_format($amount, 0) }}
                                </span>
                                <span class="w-9 shrink-0 text-left text-[11px] font-bold tabular-nums text-[var(--theme-text-muted)]"
                                    x-text="percentageLabel({{ $monthNumber - 1 }})"></span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </article>

            <article class="overflow-hidden rounded-3xl border border-[var(--theme-border)] bg-[var(--theme-surface)] shadow-sm">
                <div class="flex flex-wrap items-end justify-between gap-3 border-b border-[var(--theme-border)] px-5 py-5 sm:px-6">
                    <div>
                        <p class="text-xs font-extrabold text-[var(--brand-primary)]">تفصيل يومي</p>
                        <h2 class="mt-1 text-xl font-black text-[var(--theme-text)]">قيمة الفواتير لكل يوم</h2>
                        <p class="mt-1 text-sm text-[var(--theme-text-muted)]">
                            {{ $months[$lineMonth - 1] }} {{ $lineYear }}
                        </p>
                    </div>
                    <div class="rounded-xl bg-[var(--theme-surface-raised)] px-3 py-2 text-left">
                        <p class="text-[11px] font-bold text-[var(--theme-text-muted)]">إجمالي الشهر</p>
                        <p class="text-lg font-black tabular-nums text-[var(--theme-text)]">
                            {{ number_format($monthlySalesTotal, 0) }} <span class="text-xs">ل.س</span>
                        </p>
                    </div>
                </div>

                <div class="w-full p-4 sm:p-6">
                    <div class="w-full">
                        <svg viewBox="0 0 {{ $lineChartWidth }} {{ $lineChartHeight }}" role="img"
                            aria-label="قيمة الفواتير لكل يوم في {{ $months[$lineMonth - 1] }} {{ $lineYear }}"
                            class="block h-auto w-full">
                            @foreach ([0, 0.5, 1] as $gridRatio)
                                @php
                                    $gridY = $lineChartTop + $lineChartPlotHeight * $gridRatio;
                                    $gridValue = $dailySalesScale * (1 - $gridRatio);
                                @endphp
                                <line x1="{{ $lineChartLeft }}" y1="{{ $gridY }}"
                                    x2="{{ $lineChartWidth - $lineChartRight }}" y2="{{ $gridY }}"
                                    stroke="var(--theme-border)" stroke-dasharray="4 5" />
                                <text x="{{ $lineChartLeft - 8 }}" y="{{ $gridY + 4 }}"
                                    text-anchor="end" fill="var(--theme-text-muted)" font-size="11">
                                    {{ number_format($gridValue, 0) }}
                                </text>
                            @endforeach

                            <polyline points="{{ $dailySalesPolyline }}" fill="none"
                                stroke="var(--brand-primary)" stroke-width="4" stroke-linecap="round"
                                stroke-linejoin="round" />

                            @foreach ($dailySalesPoints as $point)
                                <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="3.5"
                                    fill="var(--theme-surface)" stroke="var(--brand-primary)" stroke-width="2">
                                    <title>اليوم {{ $point['day'] }}: {{ number_format($point['amount'], 0) }} ل.س</title>
                                </circle>
                                @if ($point['day'] === 1 || $point['day'] % $dayLabelStep === 0 || $point['day'] === count($dailySales))
                                    <text x="{{ $point['x'] }}" y="{{ $lineChartHeight - 16 }}"
                                        text-anchor="middle" fill="var(--theme-text-muted)" font-size="11">
                                        {{ $point['day'] }}
                                    </text>
                                @endif
                            @endforeach
                        </svg>
                    </div>
                    @if ($monthlySalesTotal <= 0)
                        <p class="mt-3 text-center text-sm text-[var(--theme-text-muted)]">
                            لا توجد فواتير مسجلة في هذا الشهر.
                        </p>
                    @endif
                    <p class="mt-2 text-center text-xs font-semibold text-[var(--theme-text-muted)]">
                        اليوم من الشهر · قيمة الفواتير بالليرة السورية
                    </p>
                </div>
            </article>
        </section>
    </main>
@endsection