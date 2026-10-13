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
                        <p class="text-sm text-gray-500">إجمالي الطلبات</p>
                        <h4 class="mt-2 text-2xl font-bold text-gray-800 sm:text-3xl">248</h4>
                    </div>
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600 sm:h-12 sm:w-12">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4h18v16H3zM7 8h10M7 12h10M7 16h6" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-xs text-green-600">+12% مقارنة بالشهر الماضي</p>
            </div>

            <div class="rounded-2xl bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-md sm:p-5">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm text-gray-500">الطلبات النشطة</p>
                        <h4 class="mt-2 text-2xl font-bold text-gray-800 sm:text-3xl">18</h4>
                    </div>
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600 sm:h-12 sm:w-12">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9" stroke-width="2" />
                            <path stroke-linecap="round" stroke-width="2" d="M12 7v5l3 2" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-xs text-gray-500">تحتاج إلى المتابعة</p>
            </div>

            <div class="rounded-2xl bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-md sm:p-5">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm text-gray-500">إجمالي المبيعات</p>
                        <h4 class="mt-2 text-2xl font-bold text-gray-800 sm:text-3xl">12,450</h4>
                    </div>
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-green-100 text-green-600 sm:h-12 sm:w-12">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 2v20m5-15H9a3 3 0 000 6h6a3 3 0 010 6H6" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-xs text-green-600">+8% مقارنة بالشهر الماضي</p>
            </div>

            <div class="rounded-2xl bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-md sm:p-5">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm text-gray-500">الطاولات المشغولة</p>
                        <h4 class="mt-2 text-2xl font-bold text-gray-800 sm:text-3xl">7 / 12</h4>
                    </div>
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-purple-100 text-purple-600 sm:h-12 sm:w-12">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 10h16M6 10v9m12-9v9M3 7h18v3H3z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-xs text-gray-500">5 طاولات متاحة</p>
            </div>

        </div>
    </section>

    {{-- Recent Orders --}}
    <section class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-gray-100 p-4 sm:flex-row sm:items-center sm:justify-between sm:p-5">
            <div>
                <h3 class="text-lg font-bold text-gray-800">أحدث الطلبات</h3>
                <p class="mt-1 text-sm text-gray-500">متابعة آخر الطلبات المسجلة</p>
            </div>

            <a href="#"
                class="inline-flex items-center justify-center rounded-xl bg-teal-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-teal-600">
                عرض جميع الطلبات
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[600px] text-right text-sm">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="whitespace-nowrap px-5 py-4 font-medium">رقم الطلب</th>
                        <th class="whitespace-nowrap px-5 py-4 font-medium">العميل</th>
                        <th class="whitespace-nowrap px-5 py-4 font-medium">التاريخ</th>
                        <th class="whitespace-nowrap px-5 py-4 font-medium">المبلغ</th>
                        <th class="whitespace-nowrap px-5 py-4 font-medium">الحالة</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    <tr class="transition hover:bg-gray-50">
                        <td class="whitespace-nowrap px-5 py-4 font-semibold text-gray-800">#ORD-1024</td>
                        <td class="whitespace-nowrap px-5 py-4">أحمد محمد</td>
                        <td class="whitespace-nowrap px-5 py-4 text-gray-500">12 أكتوبر 2026</td>
                        <td class="whitespace-nowrap px-5 py-4 font-semibold">250 ل.س</td>
                        <td class="whitespace-nowrap px-5 py-4">
                            <span
                                class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">مكتمل</span>
                        </td>
                    </tr>

                    <tr class="transition hover:bg-gray-50">
                        <td class="whitespace-nowrap px-5 py-4 font-semibold text-gray-800">#ORD-1023</td>
                        <td class="whitespace-nowrap px-5 py-4">سارة خالد</td>
                        <td class="whitespace-nowrap px-5 py-4 text-gray-500">12 أكتوبر 2026</td>
                        <td class="whitespace-nowrap px-5 py-4 font-semibold">180 ل.س</td>
                        <td class="whitespace-nowrap px-5 py-4">
                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">قيد
                                التحضير</span>
                        </td>
                    </tr>

                    <tr class="transition hover:bg-gray-50">
                        <td class="whitespace-nowrap px-5 py-4 font-semibold text-gray-800">#ORD-1022</td>
                        <td class="whitespace-nowrap px-5 py-4">محمد علي</td>
                        <td class="whitespace-nowrap px-5 py-4 text-gray-500">11 أكتوبر 2026</td>
                        <td class="whitespace-nowrap px-5 py-4 font-semibold">320 ل.س</td>
                        <td class="whitespace-nowrap px-5 py-4">
                            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">جديد</span>
                        </td>
                    </tr>

                    <tr class="transition hover:bg-gray-50">
                        <td class="whitespace-nowrap px-5 py-4 font-semibold text-gray-800">#ORD-1021</td>
                        <td class="whitespace-nowrap px-5 py-4">ليلى حسن</td>
                        <td class="whitespace-nowrap px-5 py-4 text-gray-500">11 أكتوبر 2026</td>
                        <td class="whitespace-nowrap px-5 py-4 font-semibold">150 ل.س</td>
                        <td class="whitespace-nowrap px-5 py-4">
                            <span
                                class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">مكتمل</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
@endsection
