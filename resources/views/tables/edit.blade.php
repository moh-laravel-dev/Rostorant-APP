<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل الطاولة</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: #faf7f0;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            background: #fff;
            outline: none;
            transition: .2s;
        }

        .form-input:focus {
            border-color: #174c3c;
            box-shadow: 0 0 0 3px rgba(23, 76, 60, .1);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 11px 20px;
            border-radius: 10px;
            font-weight: 700;
            text-decoration: none;
            transition: .2s;
            cursor: pointer;
        }

        .btn-save {
            background: #174c3c;
            color: white;
            border: none;
        }

        .btn-save:hover {
            background: #10382d;
        }

        .btn-cancel {
            background: #f3f4f6;
            color: #374151;
        }

        .btn-cancel:hover {
            background: #e5e7eb;
        }

        .status-alert {
            margin: 15px 0;
            padding: 12px;
            border: 1px solid #f59e0b;
            border-radius: 10px;
            background: #fffbeb;
            color: #92400e;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>

<body class="min-h-screen px-4 py-10">

    <main class="mx-auto w-full max-w-xl">

        <div class="mb-6">
            <h1 class="text-2xl font-extrabold text-[#174c3c]">
                تعديل الطاولة
            </h1>
            @if ((int) $table->status === 0)
                <div class="status-alert">
                    ⚠️ تنبيه: هذه الطاولة مشغولة حاليًا
                </div>
            @endif
            @error('error')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-2 text-sm text-gray-500">
                تعديل بيانات {{ $table->name }}
            </p>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-lg sm:p-8">

            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('status'))
                <div class="mb-5 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('tables.update', $table->code) }}">
                @csrf
                @method('PUT')

                <div class="mb-5">
                    <label for="name" class="mb-2 block text-sm font-bold text-[#174c3c]">
                        اسم الطاولة
                    </label>

                    <input id="name" type="text" name="name" value="{{ old('name', $table->name) }}"
                        class="form-input" required maxlength="255">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="number" class="mb-2 block text-sm font-bold text-[#174c3c]">
                        رقم الطاولة
                    </label>

                    <input id="number" type="number" value="{{ old('number', $table->number) }}" readonly
                        dir="ltr" class="form-input" required min="1">
                    @error('number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">

                    <div class="mb-6">
                        <label class="mb-3 block text-sm font-bold text-[#174c3c]">
                            حالة الطاولة
                        </label>

                        <label class="relative inline-flex cursor-pointer items-center gap-3">
                            <input type="hidden" name="status" value="0">

                            <input type="checkbox" id="status" name="status" value="1" class="peer sr-only"
                                @checked((bool) old('status', $table->status))>

                            <div
                                class="relative h-7 w-12 rounded-full bg-gray-300 transition-colors duration-300
                    after:absolute after:right-1 after:top-1 after:h-5 after:w-5
                    after:rounded-full after:bg-white after:shadow after:transition-transform after:duration-300
                    peer-checked:bg-[#174c3c] peer-checked:after:-translate-x-5
                    peer-focus-visible:ring-4 peer-focus-visible:ring-[#174c3c]/20">
                            </div>

                            <span id="status-label" class="text-sm font-semibold text-gray-600">
                                {{ (bool) old('status', $table->status) ? 'متاحة' : 'مشغولة' }}
                            </span>
                        </label>
                    </div>


                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="mb-2 block text-sm font-bold text-[#174c3c]">
                        كود الطاولة
                    </label>

                    <input type="text" value="{{ $table->code }}" class="form-input bg-gray-50 text-gray-500"
                        readonly dir="ltr">

                    <p class="mt-1 text-xs text-gray-400">
                        كود الطاولة يُنشأ تلقائيًا ولا يمكن تعديله.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3 justify-center">
                    <button type="submit" class="btn btn-save">
                        حفظ التعديلات
                    </button>

                    <a href="{{ route('tables.index') }}" class="btn btn-cancel">
                        إلغاء
                    </a>
                    <button type="button" onclick="deleteTable()"
                        class="btn border-0 bg-red-600 text-white hover:bg-red-700">
                        حذف الطاولة
                    </button>

                </div>
            </form>
            <form action="{{ route('tables.destroy', $table->code) }}" method="POST" id="delete-table-form"
                class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </main>

</body>
<script>
    const statusSwitch = document.getElementById('status');
    const statusLabel = document.getElementById('status-label');

    statusSwitch.addEventListener('change', function() {
        statusLabel.textContent = this.checked ? 'متاحة' : 'مشغولة';
    });
    const deleteTableForm = document.getElementById('delete-table-form');

    function deleteTable() {
        if (confirm('هل أنت متأكد أنك تريد حذف هذه الطاولة؟')) {
            deleteTableForm.submit();
        }
    }
</script>

</html>
