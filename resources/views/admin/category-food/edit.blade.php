@extends('admin.layouts.main-layout')

@section('content')
    <div class="mx-auto max-w-3xl space-y-6 p-4 sm:p-6" dir="rtl">

        <div>
            <h1 class="text-2xl font-bold text-[#174c3c]">تعديل التصنيف</h1>
            <p class="mt-1 text-sm text-gray-500">تعديل معلومات {{ $category->name }}</p>
        </div>

        @if ($errors->any())
            <div class="rounded-xl bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.category-food.update', $category) }}" method="POST"
            class="space-y-5 rounded-2xl border border-gray-200 bg-white p-5 sm:p-7" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="mb-2 block text-sm font-bold text-gray-700">
                    اسم التصنيف
                </label>

                <input id="name" name="name" type="text" required value="{{ old('name', $category->name) }}"
                    class="w-full rounded-xl border border-gray-200 px-4 py-3 outline-none transition focus:border-[#174c3c] focus:ring-2 focus:ring-[#174c3c]/10">
            </div>
            <div>
                <label for="photo" class="mb-2 block text-sm font-bold text-gray-700">
                    صورة التصنيف
                </label>

                @if ($category->photo)
                    <img src="{{ asset('storage/' . $category->photo) }}" alt="{{ $category->name }}"
                        class="mb-3 h-32 w-32 rounded-xl object-cover">

                    <p class="mb-2 text-xs text-gray-500">اختر صورة جديدة لاستبدال الحالية.</p>
                @endif

                <input type="file" id="photo" name="photo" accept="image/*"
                    class="w-full rounded-xl border border-gray-200 px-4 py-3">

                @error('photo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="description" class="mb-2 block text-sm font-bold text-gray-700">
                    الوصف
                </label>

                <textarea id="description" name="description" rows="4"
                    class="w-full rounded-xl border border-gray-200 px-4 py-3 outline-none transition focus:border-[#174c3c] focus:ring-2 focus:ring-[#174c3c]/10">{{ old('description', $category->description) }}</textarea>
            </div>

            <label class="flex cursor-pointer items-center gap-3">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active ? '1' : '0') == '1')
                    class="h-5 w-5 rounded border-gray-300 accent-[#174c3c]">

                <span class="text-sm font-bold text-gray-700">التصنيف فعال</span>
            </label>

            <div class="flex flex-wrap gap-3 pt-2">
                <button type="submit"
                    class="rounded-xl bg-[#174c3c] px-6 py-3 font-bold text-white transition hover:bg-[#10382c]">
                    حفظ التعديلات
                </button>

                <a href="{{ route('admin.category-food.index') }}"
                    class="rounded-xl bg-gray-100 px-6 py-3 font-bold text-gray-700 hover:bg-gray-200">
                    إلغاء
                </a>
            </div>
        </form>
    </div>
@endsection
