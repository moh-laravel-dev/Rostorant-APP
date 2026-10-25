@extends('admin.layouts.main-layout')

@section('title', 'تعديل حساب الكابتن')

@section('content')
    <main class="mx-auto w-full max-w-4xl space-y-6 p-4 sm:p-6 lg:p-8" dir="rtl">
        <header class="rounded-3xl bg-gradient-to-br from-[var(--restaurant-color-hex-174c3c)] to-[var(--restaurant-color-hex-10382c)] p-6 text-white shadow-lg">
            <h1 class="text-2xl font-black">تعديل حساب الكابتن</h1>
            <p class="mt-2 text-sm text-white/80">حدّث بيانات {{ $captain->name }} وأسند إليه الطاولات التي يتابعها.</p>
        </header>
        @if ($errors->any())
            <div role="alert" class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('admin.captains.update', $captain) }}" method="POST"
            class="space-y-6 rounded-3xl border border-gray-200 bg-white p-5 shadow-sm sm:p-7">
            @csrf
            @method('PUT')
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="captain-name" class="mb-2 block text-sm font-bold text-gray-700">الاسم</label>
                    <input id="captain-name" name="name" type="text" required maxlength="255"
                        value="{{ old('name', $captain->name) }}"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm">
                </div>
                <div>
                    <label for="captain-phone" class="mb-2 block text-sm font-bold text-gray-700">رقم الهاتف الدولي</label>
                    <input id="captain-phone" name="phone" type="tel" required pattern="\+[1-9][0-9]{7,14}"
                        value="{{ old('phone', $captain->phone) }}" dir="ltr"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-left text-sm">
                </div>
                <div>
                    <label for="captain-password" class="mb-2 block text-sm font-bold text-gray-700">كلمة مرور جديدة (اختياري)</label>
                    <input id="captain-password" name="password" type="password" minlength="8" autocomplete="new-password"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm">
                </div>
                <div>
                    <label for="captain-password-confirmation" class="mb-2 block text-sm font-bold text-gray-700">تأكيد كلمة المرور</label>
                    <input id="captain-password-confirmation" name="password_confirmation" type="password" minlength="8" autocomplete="new-password"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm">
                </div>
            </div>
            <fieldset class="space-y-3 border-t border-gray-100 pt-5">
                <legend class="mb-1 text-lg font-extrabold text-gray-800">الطاولات المسندة</legend>
                <p class="text-sm text-gray-500">اختيار طاولة مسندة لكابتن آخر ينقل إسنادها إلى هذا الكابتن.</p>
                @if ($tables->isEmpty())
                    <p class="rounded-xl bg-gray-50 p-4 text-sm text-gray-500">لا توجد طاولات في النظام.</p>
                @else
                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach ($tables as $table)
                            <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 p-4 hover:border-emerald-700">
                                <input type="checkbox" name="tables[]" value="{{ $table->id }}"
                                    @checked(in_array($table->id, old('tables', $assignedTableIds)))
                                    class="h-4 w-4 accent-emerald-800">
                                <span class="min-w-0 flex-1">
                                    <span class="block font-bold text-gray-800">طاولة {{ $table->number }} — {{ $table->name }}</span>
                                    <span class="mt-1 block text-xs text-gray-500">
                                        {{ $table->captain_id && $table->captain_id !== $captain->id ? 'مسندة لكابتن آخر' : 'غير مسندة أو مسندة لهذا الحساب' }}
                                        · {{ $table->status ? 'متاحة' : 'مشغولة' }}
                                    </span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                @endif
            </fieldset>
            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-between">
                <a href="{{ route('admin.captains.index') }}" class="rounded-xl border border-gray-200 px-5 py-3 text-center text-sm font-bold text-gray-700">إلغاء</a>
                <button type="submit" class="rounded-xl bg-[var(--restaurant-color-hex-174c3c)] px-6 py-3 text-sm font-bold text-white">حفظ التعديلات</button>
            </div>
        </form>
    </main>
@endsection
