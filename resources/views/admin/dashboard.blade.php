@extends('admin.layouts.main-layout')
@section('content')
    {{-- Dashboard Content --}}
    <section class="mb-8">
        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-800">نظرة عامة</h3>
            <p class="mt-1 text-sm text-gray-500">ملخص سريع لأداء المطعم</p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            <div class="rounded-2xl bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-md sm:p-5">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm text-gray-500">إجمالي طلبات اليوم</p>
                        <h4 class="mt-2 text-2xl font-bold text-gray-800 sm:text-3xl">{{ number_format($totalOrders) }}</h4>
                    </div>
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600 sm:h-12 sm:w-12">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4h18v16H3zM7 8h10M7 12h10M7 16h6" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-xs text-gray-500">الطلبات المسجلة اليوم</p>
            </div>

            <div class="rounded-2xl bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-md sm:p-5">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm text-gray-500">الطلبات النشطة اليوم</p>
                        <h4 class="mt-2 text-2xl font-bold text-gray-800 sm:text-3xl">{{ number_format($activeOrders) }}
                        </h4>
                    </div>
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600 sm:h-12 sm:w-12">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9" stroke-width="2" />
                            <path stroke-linecap="round" stroke-width="2" d="M12 7v5l3 2" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-xs text-gray-500">طلبات اليوم ضمن الفواتير المفتوحة أو بانتظار الدفع</p>
            </div>

            <div class="rounded-2xl bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-md sm:p-5">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm text-gray-500">مبيعات اليوم</p>
                        <h4 class="mt-2 text-2xl font-bold text-gray-800 sm:text-3xl">
                            {{ number_format((float) $totalSales, 0) }}</h4>
                    </div>
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-green-100 text-green-600 sm:h-12 sm:w-12">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 40" width="100" height="40">
                            <text x="50" y="27" font-family="Arial, sans-serif" font-size="28" font-weight="bold"
                                text-anchor="middle" fill="currentColor" direction="rtl">ل.س</text>
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-xs text-gray-500">من الفواتير المكتملة اليوم</p>
            </div>

            <div class="rounded-2xl bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-md sm:p-5">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm text-gray-500">الطاولات المشغولة</p>
                        <h4 class="mt-2 text-2xl font-bold text-gray-800 sm:text-3xl">{{ $occupiedTables }} /
                            {{ $totalTables }}</h4>
                    </div>
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-purple-100 text-purple-600 sm:h-12 sm:w-12">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 10h16M6 10v9m12-9v9M3 7h18v3H3z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-xs text-gray-500">{{ $totalTables - $occupiedTables }} طاولات متاحة</p>
            </div>

        </div>
    </section>

    {{-- Recent Orders --}}
    <section class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-gray-100 p-4 sm:flex-row sm:items-center sm:justify-between sm:p-5">
            <div>
                <h3 class="text-lg font-bold text-gray-800">طلبات اليوم</h3>
                <p class="mt-1 text-sm text-gray-500">الطلبات المسجلة اليوم، مرتبة من الأحدث</p>
            </div>

            <a href="{{ route('admin.bills.index') }}"
                class="inline-flex items-center justify-center rounded-xl bg-teal-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-teal-600">
                عرض جميع الفواتير
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[600px] text-right text-sm">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="whitespace-nowrap px-5 py-4 font-medium">رقم الفاتورة</th>
                        <th class="whitespace-nowrap px-5 py-4 font-medium">الطاولة / الوجبة</th>
                        <th class="whitespace-nowrap px-5 py-4 font-medium">التاريخ</th>
                        <th class="whitespace-nowrap px-5 py-4 font-medium">المبلغ</th>
                        <th class="whitespace-nowrap px-5 py-4 font-medium">الحالة</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse ($recentOrders as $order)
                        @php
                            $orderStatusLabels = [
                                '0' => 'قيد التأكيد',
                                '1' => 'في المطبخ',
                                '2' => 'على الطاولة',
                                '3' => 'جاهز',
                            ];
                            $orderStatusColors = [
                                '0' => 'bg-blue-100 text-blue-700',
                                '1' => 'bg-amber-100 text-amber-700',
                                '2' => 'bg-green-100 text-green-700',
                                '3' => 'bg-emerald-100 text-emerald-700',
                            ];
                        @endphp
                        <tr class="bg-[var(--theme-table)] transition hover:bg-[var(--theme-table-hover)]">
                            <td class="whitespace-nowrap px-5 py-4 font-semibold text-gray-800">
                                <a href="{{ $order->bill ? route('admin.bills.show', $order->bill) : route('admin.bills.index') }}"
                                    class="text-[var(--restaurant-color-hex-174c3c)] hover:underline">
                                    {{ $order->bill?->number ? '#' . $order->bill->number : 'طلب #' . $order->id }}
                                </a>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <span>{{ $order->bill?->table?->number ? 'طاولة ' . $order->bill->table->number : '—' }}</span>
                                <span
                                    class="mt-1 block text-xs text-gray-500">{{ $order->food?->name ?? 'وجبة غير متاحة' }}</span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-gray-500">
                                @if ($order->created_at)
                                    <time
                                        datetime="{{ $order->created_at->toIso8601String() }}">{{ $order->created_at->format('Y/m/d H:i') }}</time>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 font-semibold">
                                {{ number_format((float) ($order->total_price ?? 0), 0) }} ل.س
                            </td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <span
                                    class="rounded-full px-3 py-1 text-xs font-medium {{ $orderStatusColors[(string) $order->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $orderStatusLabels[(string) $order->status] ?? 'غير معروفة' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-sm text-gray-500">
                                لا توجد طلبات مسجلة اليوم.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-[var(--theme-border)] bg-[var(--theme-surface)] px-4 py-4 sm:px-5">
            @if ($recentOrders->total() > 0)
                @php
                    $recentOrders->withQueryString();
                    $firstVisiblePage = max(1, $recentOrders->currentPage() - 1);
                    $lastVisiblePage = min($recentOrders->lastPage(), $recentOrders->currentPage() + 1);
                    if ($firstVisiblePage === 3) {
                        $firstVisiblePage = 2;
                    }
                    if ($lastVisiblePage === $recentOrders->lastPage() - 2) {
                        $lastVisiblePage++;
                    }
                    $paginationPages = [];

                    if ($firstVisiblePage > 1) {
                        $paginationPages[] = 1;
                        if ($firstVisiblePage > 2) {
                            $paginationPages[] = 'ellipsis';
                        }
                    }

                    for ($page = $firstVisiblePage; $page <= $lastVisiblePage; $page++) {
                        $paginationPages[] = $page;
                    }

                    if ($lastVisiblePage < $recentOrders->lastPage()) {
                        if ($lastVisiblePage < $recentOrders->lastPage() - 1) {
                            $paginationPages[] = 'ellipsis';
                        }
                        $paginationPages[] = $recentOrders->lastPage();
                    }
                @endphp

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div
                        class="flex min-w-0 items-center gap-3 rounded-xl border border-[var(--theme-border)] bg-[var(--theme-surface-raised)] px-3.5 py-2.5">
                        <span
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[var(--brand-primary)] text-sm font-bold text-white"
                            aria-hidden="true">ي</span>
                        <div class="min-w-0">
                            <p class="text-[11px] font-bold tracking-wide text-[var(--theme-text-muted)]">سجل اليوم</p>
                            <p class="mt-0.5 flex flex-wrap items-baseline gap-x-1.5 text-sm text-[var(--theme-text)]">
                                <span class="text-[var(--theme-text-muted)]">عرض</span>
                                <span dir="ltr" class="tabular-nums font-extrabold text-[var(--theme-text)]">
                                    {{ $recentOrders->firstItem() }}–{{ $recentOrders->lastItem() }}
                                </span>
                                <span class="text-[var(--theme-text-muted)]">من</span>
                                <span dir="ltr" class="tabular-nums font-bold">{{ number_format($recentOrders->total()) }}</span>
                                <span>طلب اليوم</span>
                            </p>
                        </div>
                    </div>

                    @if ($recentOrders->hasPages())
                        <nav aria-label="التنقل بين صفحات طلبات اليوم" class="w-full sm:w-auto">
                            <ul class="flex flex-wrap items-center justify-start gap-1.5 sm:justify-end" role="list">
                                <li>
                                    @if ($recentOrders->previousPageUrl())
                                        <a href="{{ $recentOrders->previousPageUrl() }}" rel="prev"
                                            aria-label="الصفحة السابقة"
                                            class="inline-flex min-h-10 min-w-10 items-center justify-center gap-1.5 rounded-lg border border-[var(--theme-border)] bg-[var(--theme-surface)] px-2.5 text-sm font-semibold text-[var(--theme-text)] transition hover:border-[var(--brand-primary)] hover:bg-[var(--theme-surface-raised)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--brand-accent)]">
                                            <svg aria-hidden="true" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="m9 18 6-6-6-6" />
                                            </svg>
                                            <span class="hidden sm:inline">السابق</span>
                                        </a>
                                    @else
                                        <button type="button" disabled aria-disabled="true"
                                            class="inline-flex min-h-10 min-w-10 cursor-not-allowed items-center justify-center gap-1.5 rounded-lg border border-[var(--theme-border)] bg-[var(--theme-surface-raised)] px-2.5 text-sm font-semibold text-[var(--theme-text-muted)]">
                                            <svg aria-hidden="true" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="m9 18 6-6-6-6" />
                                            </svg>
                                            <span aria-hidden="true" class="hidden sm:inline">السابق</span>
                                            <span class="sr-only">الصفحة السابقة، غير متاحة</span>
                                        </button>
                                    @endif
                                </li>

                                @foreach ($paginationPages as $page)
                                    @if ($page === 'ellipsis')
                                        <li aria-hidden="true"
                                            class="inline-flex min-h-10 min-w-7 items-center justify-center text-sm font-semibold text-[var(--theme-text-muted)]">
                                            …
                                        </li>
                                    @else
                                        <li>
                                            @if ($page == $recentOrders->currentPage())
                                                <span aria-current="page" aria-label="الصفحة {{ $page }}، الحالية"
                                                    class="inline-flex min-h-10 min-w-10 items-center justify-center rounded-lg border border-[var(--brand-primary)] bg-[var(--brand-primary)] px-3 text-sm font-extrabold text-white shadow-sm">
                                                    {{ $page }}
                                                </span>
                                            @else
                                                <a href="{{ $recentOrders->url($page) }}" aria-label="الصفحة {{ $page }}"
                                                    class="inline-flex min-h-10 min-w-10 items-center justify-center rounded-lg border border-[var(--theme-border)] bg-[var(--theme-surface)] px-3 text-sm font-semibold tabular-nums text-[var(--theme-text)] transition hover:border-[var(--brand-primary)] hover:bg-[var(--theme-surface-raised)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--brand-accent)]">
                                                    {{ $page }}
                                                </a>
                                            @endif
                                        </li>
                                    @endif
                                @endforeach

                                <li>
                                    @if ($recentOrders->nextPageUrl())
                                        <a href="{{ $recentOrders->nextPageUrl() }}" rel="next"
                                            aria-label="الصفحة التالية"
                                            class="inline-flex min-h-10 min-w-10 items-center justify-center gap-1.5 rounded-lg border border-[var(--theme-border)] bg-[var(--theme-surface)] px-2.5 text-sm font-semibold text-[var(--theme-text)] transition hover:border-[var(--brand-primary)] hover:bg-[var(--theme-surface-raised)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--brand-accent)]">
                                            <span class="hidden sm:inline">التالي</span>
                                            <svg aria-hidden="true" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="m15 18-6-6 6-6" />
                                            </svg>
                                        </a>
                                    @else
                                        <button type="button" disabled aria-disabled="true"
                                            class="inline-flex min-h-10 min-w-10 cursor-not-allowed items-center justify-center gap-1.5 rounded-lg border border-[var(--theme-border)] bg-[var(--theme-surface-raised)] px-2.5 text-sm font-semibold text-[var(--theme-text-muted)]">
                                            <span aria-hidden="true" class="hidden sm:inline">التالي</span>
                                            <svg aria-hidden="true" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="m15 18-6-6 6-6" />
                                            </svg>
                                            <span class="sr-only">الصفحة التالية، غير متاحة</span>
                                        </button>
                                    @endif
                                </li>
                            </ul>
                        </nav>
                    @endif
                </div>
            @endif
        </div>
    </section>
@endsection
