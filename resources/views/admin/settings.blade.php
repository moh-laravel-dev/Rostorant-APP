```blade
@extends('admin.layouts.main-layout')

@section('content')
    <div class="mx-auto max-w-7xl space-y-6" dir="rtl">

        <div>
            <h1 class="text-2xl font-extrabold text-[#174c3c]">
                إعدادات خدمات الطاولات
            </h1>
            <p class="mt-2 text-sm text-gray-500">
                إدارة الخدمات والأسعار والعملات من مكان واحد.
            </p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-800">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- إضافة خدمة --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
            <h2 class="mb-5 text-lg font-bold text-[#174c3c]">
                إضافة خدمة جديدة
            </h2>

            <form action="{{ route('admin.settings.store') }}" method="POST"
                class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @csrf

                <div>
                    <label for="new-name" class="mb-2 block text-sm font-bold text-gray-700">
                        اسم الخدمة
                    </label>
                    <input id="new-name" name="name" type="text" value="{{ old('name') }}"
                        placeholder="مثال: خدمة الإنترنت" maxlength="255" required
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 outline-none transition focus:border-[#174c3c] focus:ring-4 focus:ring-[#174c3c]/10">
                </div>

                <div>
                    <label for="new-value" class="mb-2 block text-sm font-bold text-gray-700">
                        السعر
                    </label>
                    <input id="new-value" name="value" type="number" value="{{ old('value', 0) }}" min="0"
                        step="0.01" required
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 outline-none transition focus:border-[#174c3c] focus:ring-4 focus:ring-[#174c3c]/10">
                </div>

                <div>
                    <label for="new-currency" class="mb-2 block text-sm font-bold text-gray-700">
                        العملة
                    </label>
                    <input id="new-currency" name="currency" type="text" value="{{ old('currency', 'SYP') }}"
                        maxlength="10" required
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 uppercase outline-none transition focus:border-[#174c3c] focus:ring-4 focus:ring-[#174c3c]/10">
                </div>

                <div class="flex items-end">
                    <button type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#174c3c] px-5 py-3 font-bold text-white transition hover:bg-[#10382d]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                        إضافة الخدمة
                    </button>
                </div>
            </form>
        </div>

        {{-- قائمة الخدمات --}}
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-100 p-5 sm:p-6">
                <h2 class="text-lg font-bold text-[#174c3c]">الخدمات الحالية</h2>
                <span class="rounded-full bg-gray-100 px-3 py-1 text-sm font-bold text-gray-600">
                    {{ count($services) }} خدمة
                </span>
            </div>

            @forelse ($services as $service)
                <div class="border-b border-gray-100 p-5 last:border-b-0 sm:p-6">
                    <div class="flex items-center gap-2">
                        <span
                            class="h-2.5 w-2.5 rounded-full
                            {{ $service['active'] ?? true ? 'bg-green-500' : 'bg-red-400' }}">
                        </span>

                        <span
                            class="text-sm font-bold
                            {{ $service['active'] ?? true ? 'text-green-700' : 'text-red-500' }}">
                            {{ $service['active'] ?? true ? 'الخدمة مفعّلة' : 'الخدمة معطّلة' }}
                        </span>
                        {{-- toggle --}}
                        <form action="{{ route('admin.settings.toggle-status', $service['id']) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <button type="submit" role="switch"
                                aria-checked="{{ $service['active'] ?? true ? 'true' : 'false' }}"
                                aria-label="{{ $service['active'] ?? true ? 'تعطيل الخدمة' : 'تفعيل الخدمة' }}"
                                class="relative inline-flex h-7 w-12 items-center rounded-full transition-colors duration-300
            {{ $service['active'] ?? true ? 'bg-green-600' : 'bg-red-600' }}">

                                <span
                                    class="inline-block h-5 w-5 transform rounded-full bg-white shadow-sm transition-transform duration-300
                {{ $service['active'] ?? true ? '-translate-x-6' : '-translate-x-1' }}">
                                </span>
                            </button>
                        </form>
                    </div>
                    <br>

                    <div>
                        {{-- Edit --}}
                        <form action="{{ route('admin.settings.update', $service['id']) }}" method="POST"
                            class="grid grid-cols-1 items-end gap-4 sm:grid-cols-2 lg:grid-cols-12">
                            @csrf
                            @method('PUT')

                            <div class="lg:col-span-5">
                                <label for="name-{{ $service['id'] }}" class="mb-2 block text-sm font-bold text-gray-700">
                                    اسم الخدمة
                                </label>
                                <input id="name-{{ $service['id'] }}" name="name" type="text"
                                    value="{{ $service['name'] ?? '' }}" maxlength="255" required
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 outline-none transition focus:border-[#174c3c] focus:ring-4 focus:ring-[#174c3c]/10">
                            </div>

                            <div class="lg:col-span-3">
                                <label for="value-{{ $service['id'] }}" class="mb-2 block text-sm font-bold text-gray-700">
                                    السعر
                                </label>
                                <input id="value-{{ $service['id'] }}" name="value" type="number"
                                    value="{{ $service['value'] ?? 0 }}" min="0" step="0.01" required
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 outline-none transition focus:border-[#174c3c] focus:ring-4 focus:ring-[#174c3c]/10">
                            </div>

                            <div class="lg:col-span-2">
                                <label for="currency-{{ $service['id'] }}"
                                    class="mb-2 block text-sm font-bold text-gray-700">
                                    العملة
                                </label>
                                <input id="currency-{{ $service['id'] }}" name="currency" type="text"
                                    value="{{ $service['currency'] ?? 'SYP' }}" maxlength="10" required
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 uppercase outline-none transition focus:border-[#174c3c] focus:ring-4 focus:ring-[#174c3c]/10">
                            </div>

                            <div class="flex flex-wrap gap-2 lg:col-span-2">
                                <button type="submit"
                                    class="rounded-xl bg-[#174c3c] px-4 py-3 text-sm font-bold text-white transition hover:bg-[#10382d]">
                                    حفظ
                                </button>
                        </form>

                        {{-- delete --}}
                        <form action="{{ route('admin.settings.destroy', $service['id']) }}" method="POST"
                            onsubmit="return confirm('هل أنت متأكد من حذف هذه الخدمة؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="rounded-xl bg-red-400 px-4 py-3 text-sm font-bold text-white transition hover:bg-red-100">
                                حذف
                            </button>
                        </form>
                        {{-- <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                            <span class="text-xs text-gray-400">
                                {{ $service['currency'] ?? 'SYP' }}
                            </span>
                        </div> --}}
                    </div>
                </div>
        </div>
    @empty
        <div class="p-10 text-center">
            <p class="font-bold text-gray-600">لا توجد خدمات حاليًا.</p>
            <p class="mt-2 text-sm text-gray-400">أضف أول خدمة من النموذج أعلاه.</p>
        </div>
        @endforelse
    </div>
    </div>
@endsection
```
