@extends('admin.layouts.main-layout')

@section('content')
    <div class="space-y-6 p-4 sm:p-6" dir="rtl">

        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#174c3c]">تصنيفات الطعام</h1>
                <p class="mt-1 text-sm text-gray-500">إدارة تصنيفات قائمة الطعام</p>
            </div>

            <a href="{{ route('admin.category-food.create') }}"
                class="rounded-xl bg-[#174c3c] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#10382c]">
                + إضافة تصنيف
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-xl bg-green-50 p-4 text-sm font-bold text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-xl bg-red-50 p-4 text-sm font-bold text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-5 py-4">#</th>
                            <th class="px-5 py-4">الصورة</th>
                            <th class="px-5 py-4">اسم التصنيف</th>
                            <th class="px-5 py-4">الوصف</th>
                            <th class="px-5 py-4">الحالة</th>
                            <th class="px-5 py-4">الإجراءات</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($categories as $category)
                            <tr class="transition hover:bg-gray-50"></tr>
                            <td class="px-3 py-3 sm:px-5 sm:py-4">
                                <div class="flex items-center justify-center sm:justify-start">
                                    @if ($category->photo)
                                        <img src="{{ asset('storage/' . $category->photo) }}" alt="{{ $category->name }}"
                                            loading="lazy"
                                            class="h-14 w-14 shrink-0 rounded-xl border border-gray-200 object-cover shadow-sm transition duration-300 hover:scale-105 sm:h-16 sm:w-16 lg:h-20 lg:w-20">
                                    @else
                                        <div
                                            class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl border border-gray-200 bg-gray-50 text-gray-400 sm:h-16 sm:w-16 lg:h-20 lg:w-20">
                                            <icon name="image" size="xl" />
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <td class="px-5 py-4">{{ $categories->firstItem() + $loop->index }}</td>

                            <td class="px-5 py-4 font-bold text-gray-800">
                                {{ $category->name }}
                            </td>

                            <td class="px-5 py-4 text-gray-500">
                                {{ $category->description ?: '—' }}
                            </td>

                            <td class="px-5 py-4">
                                <form action="{{ route('admin.category-food.toggle-status', $category) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit" role="switch"
                                        aria-checked="{{ $category->is_active ? 'true' : 'false' }}"
                                        aria-label="{{ $category->is_active ? 'تعطيل التصنيف' : 'تفعيل التصنيف' }}"
                                        class="relative inline-flex h-7 w-12 items-center rounded-full transition-colors duration-300
                {{ $category->is_active ? 'bg-green-600' : 'bg-red-600' }}">

                                        <span
                                            class="inline-block h-5 w-5 rounded-full bg-white shadow-sm transition-transform duration-300
                    {{ $category->is_active ? '-translate-x-6' : '-translate-x-1' }}">
                                        </span>
                                    </button>
                                </form>
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex flex-wrap items-center gap-2">
                                    <a href="{{ route('admin.category-food.show', $category) }}"
                                        class="rounded-lg bg-gray-100 px-3 py-2 font-bold text-gray-700 hover:bg-gray-200">
                                        عرض
                                    </a>

                                    <a href="{{ route('admin.category-food.edit', $category) }}"
                                        class="rounded-lg bg-blue-50 px-3 py-2 font-bold text-blue-700 hover:bg-blue-100">
                                        تعديل
                                    </a>

                                    <form action="{{ route('admin.category-food.destroy', $category) }}" method="POST"
                                        onsubmit="return confirm('هل أنت متأكد من حذف هذا التصنيف؟')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="rounded-lg bg-red-50 px-3 py-2 font-bold text-red-700 hover:bg-red-100">
                                            حذف
                                        </button>
                                    </form>
                                    <a href="#">
                                        <div
                                            class="rounded-lg bg-red-50 px-3 py-2 font-bold text-amber-400 hover:bg-amber-100">
                                            عرض الاصناف
                                        </div>
                                    </a>
                                </div>
                            </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center text-gray-500">
                                    لا توجد تصنيفات حتى الآن.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-100 bg-white px-4 py-5 sm:px-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    {{-- Results Info --}}
                    <div class="text-center text-sm text-gray-500 sm:text-right">
                        @if ($categories->total() > 0)
                            عرض
                            <span class="font-bold text-[#174c3c]">{{ $categories->firstItem() }}</span>
                            -
                            <span class="font-bold text-[#174c3c]">{{ $categories->lastItem() }}</span>
                            من
                            <span class="font-bold text-[#174c3c]">{{ $categories->total() }}</span>
                            تصنيف
                        @else
                            لا توجد نتائج
                        @endif
                    </div>

                    {{-- Pagination --}}
                    @if ($categories->hasPages())
                        <nav aria-label="التنقل بين الصفحات" class="flex items-center justify-center gap-1.5"
                            dir="rtl">

                            {{-- Previous --}}
                            @if ($categories->onFirstPage())
                                <span aria-disabled="true"
                                    class="flex h-10 w-10 cursor-not-allowed items-center justify-center rounded-xl border border-gray-200 bg-gray-50 text-gray-300">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" aria-hidden="true">
                                        <path d="m15 18-6-6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $categories->previousPageUrl() }}" rel="prev" aria-label="الصفحة السابقة"
                                    class="flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-600 transition hover:border-[#174c3c] hover:bg-[#174c3c] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#174c3c]/30">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" aria-hidden="true">
                                        <path d="m15 18-6-6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </a>
                            @endif

                            {{-- Page Numbers --}}
                            @foreach ($categories->getUrlRange(max(1, $categories->currentPage() - 2), min($categories->lastPage(), $categories->currentPage() + 2)) as $page => $url)
                                @if ($page == $categories->currentPage())
                                    <span aria-current="page"
                                        class="flex h-10 min-w-10 items-center justify-center rounded-xl border border-[#174c3c] bg-[#174c3c] px-3 text-sm font-bold text-white shadow-sm">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" aria-label="الصفحة {{ $page }}"
                                        class="flex h-10 min-w-10 items-center justify-center rounded-xl border border-gray-200 bg-white px-3 text-sm font-semibold text-gray-600 transition hover:border-[#174c3c] hover:bg-[#174c3c]/5 hover:text-[#174c3c] focus:outline-none focus:ring-2 focus:ring-[#174c3c]/30">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            {{-- Next --}}
                            @if ($categories->hasMorePages())
                                <a href="{{ $categories->nextPageUrl() }}" rel="next" aria-label="الصفحة التالية"
                                    class="flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-600 transition hover:border-[#174c3c] hover:bg-[#174c3c] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#174c3c]/30">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" aria-hidden="true">
                                        <path d="m9 18 6-6-6-6" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </a>
                            @else
                                <span aria-disabled="true"
                                    class="flex h-10 w-10 cursor-not-allowed items-center justify-center rounded-xl border border-gray-200 bg-gray-50 text-gray-300">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" aria-hidden="true">
                                        <path d="m9 18 6-6-6-6" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            @endif

                        </nav>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
