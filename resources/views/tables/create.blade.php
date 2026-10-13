<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة طاولة</title>

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
            gap: 8px;
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
    </style>
</head>

<body class="min-h-screen px-4 py-10">

    <main class="mx-auto w-full max-w-xl">

        <div class="mb-6">
            <h1 class="text-2xl font-extrabold text-[#174c3c]">
                إضافة طاولة جديدة
            </h1>

            <p class="mt-2 text-sm text-gray-500">
                أدخل بيانات الطاولة لإضافتها إلى نظام المطعم.
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

            <form method="POST" action="{{ route('tables.store') }}">
                @csrf

                <div class="mb-5">
                    <label for="name" class="mb-2 block text-sm font-bold text-[#174c3c]">
                        اسم الطاولة
                    </label>

                    <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-input"
                        placeholder="مثال: طاولة رقم 156" required maxlength="255">

                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="number" class="mb-2 block text-sm font-bold text-[#174c3c]">
                       رقم الطاولة {{ $nextNumber }}
                    </label>
            </div>
            <div class="mb-6">
                <label class="mb-3 block text-sm font-bold text-[#174c3c]">
                    حالة الطاولة
                </label>

                <label class="relative inline-flex cursor-pointer items-center gap-3">
                    <input type="hidden" name="status" value="0">

                    <input type="checkbox" id="status" name="status" value="1" class="peer sr-only"
                        @checked(old('status', '1') == '1')>

                    <div
                        class="relative h-7 w-12 rounded-full bg-gray-300 transition-colors duration-300
                            after:absolute after:right-1 after:top-1 after:h-5 after:w-5
                            after:rounded-full after:bg-white after:shadow after:transition-transform after:duration-300
                            peer-checked:bg-[#174c3c] peer-checked:after:-translate-x-5
                            peer-focus-visible:ring-4 peer-focus-visible:ring-[#174c3c]/20">
                    </div>

                    <span id="status-label" class="text-sm font-semibold text-gray-600">
                        {{ old('status', '1') == '1' ? 'متاحة' : 'مشغولة' }}
                    </span>
                </label>

                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-wrap justify-center gap-3">
                <button type="submit" class="btn btn-save">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    إضافة الطاولة
                </button>

                <a href="{{ route('tables.index') }}" class="btn btn-cancel">
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</main>

<script>
    const statusSwitch = document.getElementById('status');
    const statusLabel = document.getElementById('status-label');

    if (statusSwitch && statusLabel) {
        statusSwitch.addEventListener('change', function() {
            statusLabel.textContent = this.checked ? 'متاحة' : 'مشغولة';
        });
    }
</script>

</body>

</html>
