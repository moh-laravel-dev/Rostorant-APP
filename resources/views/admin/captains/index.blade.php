@extends('admin.layouts.main-layout')

@section('title', 'إدارة الكباتن')

@section('content')
    <main class="mx-auto w-full max-w-6xl space-y-6 p-4 sm:p-6 lg:p-8" dir="rtl">
        <header class="rounded-3xl bg-gradient-to-br from-[var(--restaurant-color-hex-174c3c)] to-[var(--restaurant-color-hex-10382c)] p-6 text-white shadow-lg">
            <h1 class="text-2xl font-black sm:text-3xl">إدارة حسابات الكباتن</h1>
            <p class="mt-2 text-sm leading-7 text-white/80">أنشئ حسابات الكباتن، ثم عيّن لكل كابتن الطاولات التي يتابعها.</p>
        </header>

        @if (session('status'))
            <div role="status" class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-800">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->any())
            <div role="alert" class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 bg-emerald-50/50 px-5 py-4">
                <h2 class="font-extrabold text-gray-800">إنشاء حساب كابتن</h2>
            </div>
            <form action="{{ route('admin.captains.store') }}" method="POST" class="grid gap-4 p-5 sm:grid-cols-2">
                @csrf
                <div>
                    <label for="captain-name" class="mb-2 block text-sm font-bold text-gray-700">الاسم</label>
                    <input id="captain-name" name="name" type="text" required maxlength="255" value="{{ old('name') }}"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm outline-none focus:border-emerald-700">
                </div>
                <div>
                    <label for="captain-phone" class="mb-2 block text-sm font-bold text-gray-700">رقم الهاتف</label>
                    <div class="flex gap-2" dir="ltr">
                        <input name="country_code" type="text" required pattern="\+[1-9][0-9]{0,3}" maxlength="5"
                            value="{{ old('country_code', '+963') }}" aria-label="رمز الدولة"
                            class="w-24 rounded-xl border border-gray-200 bg-gray-50 px-3 py-3 text-sm">
                        <input id="captain-phone" name="phone" type="tel" required inputmode="numeric" pattern="[0-9]+"
                            maxlength="20" value="{{ old('phone') }}" aria-label="رقم الهاتف"
                            class="min-w-0 flex-1 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm">
                    </div>
                </div>
                <div>
                    <label for="captain-password" class="mb-2 block text-sm font-bold text-gray-700">كلمة المرور</label>
                    <input id="captain-password" name="password" type="password" required minlength="8" autocomplete="new-password"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm outline-none focus:border-emerald-700">
                </div>
                <div>
                    <label for="captain-password-confirmation" class="mb-2 block text-sm font-bold text-gray-700">تأكيد كلمة المرور</label>
                    <input id="captain-password-confirmation" name="password_confirmation" type="password" required minlength="8" autocomplete="new-password"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm outline-none focus:border-emerald-700">
                </div>
                <div class="sm:col-span-2">
                    <button type="submit" class="rounded-xl bg-[var(--restaurant-color-hex-174c3c)] px-6 py-3 text-sm font-bold text-white hover:opacity-90">
                        إنشاء الحساب
                    </button>
                </div>
            </form>
        </section>

        <section class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-5 py-4">
                <h2 class="font-extrabold text-gray-800">حسابات الكباتن</h2>
            </div>
            @forelse ($captains as $captain)
                <article class="flex flex-col gap-4 border-b border-gray-100 p-5 last:border-0 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="font-bold text-gray-800">{{ $captain->name }}</h3>
                        <p class="mt-1 text-sm text-gray-600" dir="ltr">{{ $captain->phone }}</p>
                        <p class="mt-1 text-xs text-gray-500">الطاولات المسندة: {{ $captain->tables_count }}</p>
                        <span class="mt-2 inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $captain->is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                            {{ $captain->is_active ? 'مفعّل' : 'موقوف' }}
                        </span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.captains.edit', $captain) }}"
                            class="rounded-lg border border-emerald-800/20 bg-emerald-50 px-4 py-2.5 text-sm font-bold text-emerald-900">
                            تعديل وإسناد الطاولات
                        </a>
                        <form action="{{ route('admin.captains.toggle-status', $captain) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-bold text-gray-700">
                                {{ $captain->is_active ? 'إيقاف' : 'تفعيل' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.captains.destroy', $captain) }}" method="POST"
                            onsubmit="return confirm('هل تريد حذف حساب {{ $captain->name }} وإلغاء إسناد طاولاته؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg border border-red-200 px-4 py-2.5 text-sm font-bold text-red-700">
                                حذف
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <p class="p-8 text-center text-sm text-gray-500">لا توجد حسابات كباتن حتى الآن.</p>
            @endforelse
        </section>
    </main>
@endsection
