@extends('admin.layouts.main-layout')

@section('title', 'إدارة الكاشير')

@section('content')
    <div class="mx-auto w-full min-w-0 max-w-6xl space-y-6 overflow-x-clip p-4 pb-32 sm:p-6 sm:pb-8 lg:p-8" dir="rtl">
        <header class="relative isolate overflow-hidden rounded-3xl bg-gradient-to-br from-[var(--restaurant-color-hex-174c3c)] via-[var(--restaurant-color-hex-1c604b)] to-[var(--restaurant-color-hex-10382c)] p-6 text-white shadow-xl sm:p-8">
            <div class="pointer-events-none absolute -left-12 -top-16 h-48 w-48 rounded-full border-[28px] border-white/5"></div>
            <div class="pointer-events-none absolute -bottom-20 right-1/3 h-56 w-56 rounded-full bg-emerald-300/10 blur-3xl"></div>

            <div class="relative flex flex-col justify-between gap-6 sm:flex-row sm:items-center">
                <div class="min-w-0">
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-xs font-bold text-emerald-100">
                        <span class="h-1.5 w-1.5 rounded-full bg-amber-300"></span>
                        إدارة النظام
                    </div>
                    <h1 class="text-2xl font-black leading-relaxed sm:text-3xl">حسابات الكاشير</h1>
                    <p class="mt-2 max-w-xl text-sm leading-7 text-white/75 sm:text-base">
                        أنشئ حسابات فريقك ونظّم صلاحية الدخول إلى نقطة البيع.
                    </p>
                </div>
                <div class="flex h-16 w-16 shrink-0 items-center justify-center self-start rounded-2xl border border-white/15 bg-white/10 text-amber-200 shadow-inner sm:h-20 sm:w-20 sm:self-center" aria-hidden="true">
                    <svg class="h-9 w-9 sm:h-10 sm:w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m6-10a4 4 0 100-8 4 4 0 000 8zm10 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                    </svg>
                </div>
            </div>
        </header>

        @if (session('status'))
            <div role="status" class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold leading-6 text-emerald-800 shadow-sm">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4 4L19 6" />
                    </svg>
                </span>
                <p class="pt-1">{{ session('status') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div role="alert" class="flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-semibold leading-6 text-red-700 shadow-sm">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-700" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.3 3.9 1.8 18.5A1.7 1.7 0 003.3 21h17.4a1.7 1.7 0 001.5-2.5L13.7 3.9a2 2 0 00-3.4 0z" />
                    </svg>
                </span>
                <div class="min-w-0">
                    <p>تعذّر حفظ الحساب:</p>
                    <ul class="mt-1 list-inside list-disc space-y-1 font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <section class="overflow-hidden rounded-3xl border border-gray-200/80 bg-white shadow-sm">
            <div class="flex items-center gap-4 border-r-4 border-b border-[var(--restaurant-color-hex-174c3c)] border-b-gray-100 bg-emerald-50/40 px-5 py-5 sm:px-7">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[var(--restaurant-color-hex-174c3c)] text-white shadow-lg shadow-emerald-900/10" aria-hidden="true">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 5v14M5 12h14" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="mb-0.5 text-[11px] font-extrabold tracking-wide text-emerald-700">01 <span aria-hidden="true">/</span> إعداد الدخول</p>
                    <h2 class="text-lg font-extrabold text-gray-800">إنشاء حساب كاشير</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-500">يتكوّن رقم الدخول من رمز الدولة ورقم الهاتف.</p>
                </div>
            </div>

            <form action="{{ route('admin.cashiers.store') }}" method="POST" class="grid gap-x-6 gap-y-5 p-5 2xl:grid-cols-2 2xl:p-7">
                @csrf
                <div class="mr-3 min-w-0">
                    <label for="cashier-name" class="mb-2 block text-sm font-bold text-gray-700">الاسم</label>
                    <input id="cashier-name" name="name" type="text" required maxlength="255"
                        value="{{ old('name') }}" autocomplete="name"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-[var(--restaurant-color-hex-174c3c)] focus:bg-white focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10">
                </div>
                <div class="min-w-0 rounded-2xl border border-amber-200/80 border-r-4 border-r-amber-400 bg-amber-50/30 p-4">
                    <p class="mb-3 text-xs font-extrabold text-amber-700">مُعرّف الحساب <span class="font-medium text-gray-500">· رمز الدولة ورقم الهاتف</span></p>
                    <div class="grid min-w-0 grid-cols-[minmax(0,1fr)_minmax(5.5rem,7rem)] gap-3">
                        <div class="min-w-0">
                            <label for="cashier-phone-number" class="mb-2 block text-sm font-bold text-gray-700">رقم الهاتف</label>
                            <input id="cashier-phone-number" name="phone" type="tel" required inputmode="numeric"
                                pattern="[0-9]+" maxlength="20" value="{{ old('phone') }}" autocomplete="tel-national"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3 text-left text-sm text-gray-800 outline-none transition focus:border-[var(--restaurant-color-hex-174c3c)] focus:bg-white focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10">
                        </div>
                        <div class="min-w-0">
                            <label for="cashier-country-code" class="mb-2 block text-sm font-bold text-gray-700">رمز الدولة</label>
                            <input id="cashier-country-code" name="country_code" type="text" required
                                pattern="\+[1-9][0-9]{0,3}" maxlength="5" value="{{ old('country_code', '+963') }}"
                                dir="ltr" placeholder="+963"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50/50 px-3 py-3 text-left text-sm text-gray-800 outline-none transition focus:border-[var(--restaurant-color-hex-174c3c)] focus:bg-white focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10">
                        </div>
                    </div>
                </div>
                <div class="min-w-0">
                    <label for="cashier-password" class="mb-2 block text-sm font-bold text-gray-700">كلمة المرور</label>
                    <input id="cashier-password" name="password" type="password" required minlength="8"
                        autocomplete="new-password"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-[var(--restaurant-color-hex-174c3c)] focus:bg-white focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10">
                </div>
                <div class="min-w-0">
                    <label for="cashier-password-confirmation" class="mb-2 block text-sm font-bold text-gray-700">تأكيد كلمة المرور</label>
                    <input id="cashier-password-confirmation" name="password_confirmation" type="password" required minlength="8"
                        autocomplete="new-password"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-[var(--restaurant-color-hex-174c3c)] focus:bg-white focus:ring-4 focus:ring-[var(--restaurant-color-hex-174c3c)]/10">
                </div>
                <div class="border-t border-gray-100 pt-5 2xl:col-span-2">
                    <button type="submit"
                        class="inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-xl bg-[var(--restaurant-color-hex-174c3c)] px-6 py-3 text-sm font-bold text-white shadow-md shadow-emerald-900/10 transition hover:-translate-y-0.5 hover:bg-[var(--restaurant-color-hex-10382c)] hover:shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--restaurant-color-hex-174c3c)] sm:w-auto">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 5v14M5 12h14" />
                        </svg>
                        إنشاء الحساب
                    </button>
                </div>
            </form>
        </section>

        <section class="overflow-hidden rounded-3xl border border-gray-200/80 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-5 py-5 sm:px-7">
                <div>
                    <p class="mb-0.5 text-[11px] font-extrabold tracking-wide text-emerald-700">02 <span aria-hidden="true">/</span> سجل الفريق</p>
                    <h2 class="text-lg font-extrabold text-gray-800">الحسابات الحالية</h2>
                    <p class="mt-1 text-sm text-gray-500">إدارة حالة دخول فريق الكاشير.</p>
                </div>
                <span aria-label="إجمالي عدد الحسابات: {{ $cashiers->count() }}" class="inline-flex items-center gap-2 rounded-xl border border-emerald-100 bg-emerald-50 px-2 py-1.5 text-xs font-bold text-emerald-800">
                    <span class="flex h-8 min-w-8 items-center justify-center rounded-lg bg-white px-2 text-sm font-black tabular-nums text-[var(--restaurant-color-hex-174c3c)]">{{ $cashiers->count() }}</span>
                    <span>حساب</span>
                </span>
            </div>

            @if ($cashiers->isEmpty())
                <div class="flex flex-col items-center px-6 py-10 text-center">
                    <span class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-gray-50 text-gray-400" aria-hidden="true">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m6-10a4 4 0 100-8 4 4 0 000 8zm10 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                        </svg>
                    </span>
                    <p class="text-sm font-bold text-gray-700">لا توجد حسابات كاشير بعد.</p>
                    <p class="mt-1 text-xs text-gray-500">أنشئ أول حساب من النموذج أعلاه.</p>
                </div>
            @else
                <div class="divide-y divide-gray-100 xl:hidden">
                    @foreach ($cashiers as $cashier)
                        <article class="flex flex-col gap-4 px-5 py-4 transition-colors hover:bg-gray-50/70">
                            <div class="flex min-w-0 items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h3 class="break-words font-bold text-gray-800">{{ $cashier->name }}</h3>
                                    <p class="mt-1 text-sm text-gray-600" dir="ltr">{{ $cashier->phone }}</p>
                                </div>
                                <span class="shrink-0 rounded-full px-3 py-1 text-xs font-bold {{ $cashier->is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                    {{ $cashier->is_active ? 'مفعّل' : 'موقوف' }}
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('admin.cashiers.edit', $cashier) }}"
                                    class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-[var(--restaurant-color-hex-174c3c)]/20 bg-emerald-50/70 px-3 py-2.5 text-xs font-bold text-[var(--restaurant-color-hex-174c3c)] transition hover:border-[var(--restaurant-color-hex-174c3c)] hover:bg-emerald-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--restaurant-color-hex-174c3c)]">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m16.862 4.487 2.651 2.651M4 20l4.6-.92L19.3 8.38a1.875 1.875 0 0 0-2.65-2.65L5.95 16.43 5 20Z" />
                                    </svg>
                                    تعديل
                                </a>
                                <form action="{{ route('admin.cashiers.toggle-status', $cashier) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="min-h-11 w-full rounded-xl border border-gray-200 px-3 py-2.5 text-xs font-bold text-gray-700 transition hover:border-[var(--restaurant-color-hex-174c3c)] hover:bg-emerald-50 hover:text-[var(--restaurant-color-hex-174c3c)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--restaurant-color-hex-174c3c)]">
                                        {{ $cashier->is_active ? 'إيقاف الحساب' : 'تفعيل الحساب' }}
                                    </button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="hidden overflow-x-auto xl:block">
                    <table class="w-full text-right text-sm">
                        <thead class="bg-gray-50 text-xs font-bold text-gray-500">
                            <tr>
                                <th scope="col" class="px-6 py-4">الاسم</th>
                                <th scope="col" class="px-6 py-4">رقم الهاتف</th>
                                <th scope="col" class="px-6 py-4">الحالة</th>
                                <th scope="col" class="px-6 py-4">إجراء</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($cashiers as $cashier)
                                <tr class="transition hover:bg-gray-50/70">
                                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $cashier->name }}</td>
                                    <td class="px-6 py-4 text-gray-600" dir="ltr">{{ $cashier->phone }}</td>
                                    <td class="px-6 py-4">
                                        <span class="rounded-full px-3 py-1 text-xs font-bold {{ $cashier->is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                            {{ $cashier->is_active ? 'مفعّل' : 'موقوف' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <a href="{{ route('admin.cashiers.edit', $cashier) }}"
                                                class="inline-flex min-h-9 items-center gap-1.5 rounded-lg border border-[var(--restaurant-color-hex-174c3c)]/20 bg-emerald-50/70 px-3 py-2 text-xs font-bold text-[var(--restaurant-color-hex-174c3c)] transition hover:border-[var(--restaurant-color-hex-174c3c)] hover:bg-emerald-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--restaurant-color-hex-174c3c)]">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m16.862 4.487 2.651 2.651M4 20l4.6-.92L19.3 8.38a1.875 1.875 0 0 0-2.65-2.65L5.95 16.43 5 20Z" />
                                                </svg>
                                                تعديل
                                            </a>
                                            <form action="{{ route('admin.cashiers.toggle-status', $cashier) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="min-h-9 rounded-lg border border-gray-200 px-3 py-2 text-xs font-bold text-gray-700 transition hover:border-[var(--restaurant-color-hex-174c3c)] hover:bg-emerald-50 hover:text-[var(--restaurant-color-hex-174c3c)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--restaurant-color-hex-174c3c)]">
                                                    {{ $cashier->is_active ? 'إيقاف الحساب' : 'تفعيل الحساب' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
@endsection
