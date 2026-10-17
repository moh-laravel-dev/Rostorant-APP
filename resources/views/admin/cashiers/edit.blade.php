@extends('admin.layouts.main-layout')

@section('title', 'تعديل حساب الكاشير')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6 p-4 sm:p-6 lg:p-8" dir="rtl">
        <header class="relative isolate overflow-hidden rounded-3xl bg-gradient-to-br from-[var(--restaurant-color-hex-174c3c)] via-[var(--restaurant-color-hex-1c604b)] to-[var(--restaurant-color-hex-10382c)] p-6 text-white shadow-xl sm:p-8">
            <div class="pointer-events-none absolute -left-12 -top-16 h-48 w-48 rounded-full border-[28px] border-white/5"></div>
            <div class="relative flex flex-col justify-between gap-5 sm:flex-row sm:items-center">
                <div>
                    <p class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-xs font-bold text-emerald-100">
                        <span class="h-1.5 w-1.5 rounded-full bg-amber-300"></span>
                        إدارة فريق الكاشير
                    </p>
                    <h1 class="text-2xl font-black sm:text-3xl">تعديل حساب الكاشير</h1>
                    <p class="mt-2 text-sm leading-7 text-white/75">حدّث بيانات الدخول لـ {{ $cashier->name }}، واترك كلمة المرور فارغة إن لم ترغب بتغييرها.</p>
                </div>
                <div class="flex h-16 w-16 shrink-0 items-center justify-center self-start rounded-2xl border border-white/15 bg-white/10 text-amber-200 sm:self-center" aria-hidden="true">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 2.651 2.651M4 20l4.2-.84L19.7 7.66a1.875 1.875 0 0 0-2.65-2.65L5.55 16.51 4 20Z" />
                    </svg>
                </div>
            </div>
        </header>

        @if ($errors->any())
            <div role="alert" class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-semibold leading-6 text-red-700">
                <p>يرجى مراجعة البيانات:</p>
                <ul class="mt-1 list-inside list-disc font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="overflow-hidden rounded-3xl border border-gray-200/80 bg-white shadow-sm">
            <div class="border-r-4 border-[var(--restaurant-color-hex-174c3c)] border-b border-b-gray-100 bg-emerald-50/40 px-5 py-5 sm:px-7">
                <p class="text-[11px] font-extrabold tracking-wide text-emerald-700">بيانات الحساب</p>
                <h2 class="mt-1 text-lg font-extrabold text-gray-800">{{ $cashier->name }}</h2>
                <p class="mt-1 text-sm text-gray-500">
                    حالة الحساب:
                    <span class="font-bold {{ $cashier->is_active ? 'text-green-700' : 'text-red-700' }}">
                        {{ $cashier->is_active ? 'مفعّل' : 'موقوف' }}
                    </span>
                    — يمكن تغييرها من قائمة الحسابات.
                </p>
            </div>

            <form action="{{ route('admin.cashiers.update', $cashier) }}" method="POST" class="space-y-6 p-5 sm:p-7">
                @csrf
                @method('PUT')

                <div>
                    <label for="cashier-name" class="mb-2 block text-sm font-bold text-gray-700">اسم الكاشير</label>
                    <input id="cashier-name" name="name" type="text" required maxlength="255"
                        value="{{ old('name', $cashier->name) }}" autocomplete="name"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-[var(--restaurant-color-hex-174c3c)] focus:bg-white focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10">
                    @error('name')
                        <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="cashier-phone" class="mb-2 block text-sm font-bold text-gray-700">رقم الهاتف مع رمز الدولة</label>
                    <input id="cashier-phone" name="phone" type="tel" required inputmode="tel"
                        pattern="\+[1-9][0-9]{7,14}" maxlength="16"
                        value="{{ old('phone', $cashier->phone) }}" autocomplete="tel" dir="ltr"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3 text-left text-sm text-gray-800 outline-none transition focus:border-[var(--restaurant-color-hex-174c3c)] focus:bg-white focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10">
                    <p class="mt-2 text-xs leading-5 text-gray-500">اكتب الرقم الدولي كاملًا، مثل +963912345678.</p>
                    @error('phone')
                        <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rounded-2xl border border-gray-200 bg-gray-50/70 p-4 sm:p-5">
                    <div class="mb-4">
                        <h3 class="text-sm font-extrabold text-gray-800">تغيير كلمة المرور</h3>
                        <p class="mt-1 text-xs leading-5 text-gray-500">اختياري — اترك الحقلين فارغين للإبقاء على كلمة المرور الحالية.</p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="cashier-password" class="mb-2 block text-sm font-bold text-gray-700">كلمة المرور الجديدة</label>
                            <input id="cashier-password" name="password" type="password" minlength="8"
                                autocomplete="new-password"
                                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-[var(--restaurant-color-hex-174c3c)] focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10">
                            @error('password')
                                <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="cashier-password-confirmation" class="mb-2 block text-sm font-bold text-gray-700">تأكيد كلمة المرور</label>
                            <input id="cashier-password-confirmation" name="password_confirmation" type="password"
                                minlength="8" autocomplete="new-password"
                                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-[var(--restaurant-color-hex-174c3c)] focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10">
                        </div>
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-between">
                    <a href="{{ route('admin.cashiers.index') }}"
                        class="inline-flex min-h-11 items-center justify-center rounded-xl border border-gray-200 px-5 py-3 text-sm font-bold text-gray-700 transition hover:bg-gray-50">
                        إلغاء والعودة
                    </a>
                    <button type="submit"
                        class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-[var(--restaurant-color-hex-174c3c)] px-6 py-3 text-sm font-bold text-white shadow-md shadow-emerald-900/10 transition hover:-translate-y-0.5 hover:bg-[var(--restaurant-color-hex-10382c)]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4 4L19 6" />
                        </svg>
                        حفظ التعديلات
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection
