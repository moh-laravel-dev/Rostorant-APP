@extends('admin.layouts.main-layout')

@section('content')
    <div class="mx-auto max-w-3xl space-y-6 p-4 sm:p-6" dir="rtl">

        <div>
            <h1 class="text-2xl font-bold text-[#174c3c]">تفاصيل التصنيف</h1>
        </div>

        <div class="space-y-5 rounded-2xl border border-gray-200 bg-white p-5 sm:p-7">
            <div>
                @if ($category->photo)
                    <img src="{{ asset('storage/' . $category->photo) }}" alt="{{ $category->name }}"
                        class="h-30 w-30 rounded-xl object-cover">
                @else
                    <div class="flex h-30 w-30 items-center justify-center rounded-xl bg-gray-100 text-gray-400">
                        <icon name="image" size="xl" />
                    </div>
                @endif
            </div>
            <div>
                <p class="text-sm text-gray-500">اسم التصنيف</p>
                <p class="mt-1 font-bold text-gray-800">{{ $category->name }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">الوصف</p>
                <p class="mt-1 whitespace-pre-line text-gray-800">
                    {{ $category->description ?: 'لا يوجد وصف' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">الحالة</p>

                @if ($category->is_active)
                    <span class="mt-2 inline-block rounded-full bg-green-50 px-3 py-1 text-xs font-bold text-green-700">
                        فعال
                    </span>
                @else
                    <span class="mt-2 inline-block rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-700">
                        غير فعال
                    </span>
                @endif
            </div>

            <div class="flex flex-wrap gap-3 pt-2">
                <a href="{{ route('admin.category-food.edit', $category) }}"
                    class="rounded-xl bg-[#174c3c] px-6 py-3 font-bold text-white hover:bg-[#10382c]">
                    تعديل
                </a>

                <a href="{{ route('admin.category-food.index') }}"
                    class="rounded-xl bg-gray-100 px-6 py-3 font-bold text-gray-700 hover:bg-gray-200">
                    رجوع
                </a>
            </div>
        </div>
    </div>
@endsection
