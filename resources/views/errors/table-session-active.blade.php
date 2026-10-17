<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الطاولة قيد الاستخدام</title>
    @include('components.theme-mode')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="flex min-h-screen items-center justify-center bg-gradient-to-br from-gray-50 via-white to-amber-50/50 px-4 py-8">

    <main class="w-full max-w-lg overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-xl">

        <div class="px-6 pb-8 pt-10 text-center sm:px-10 sm:pb-10 sm:pt-12">

            <div
                class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-amber-50 text-amber-500 ring-8 ring-amber-50/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.7">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v4m0 4h.01M4.93 19h14.14a2 2 0 0 0 1.73-3L13.73 4a2 2 0 0 0-3.46 0L3.2 16a2 2 0 0 0 1.73 3Z" />
                </svg>
            </div>

            <p class="mb-3 text-sm font-extrabold tracking-wide text-amber-600">
                الطاولة قيد الاستخدام
            </p>

            <h1 class="mb-4 text-2xl font-extrabold leading-relaxed text-gray-900 sm:text-3xl">
                توجد جلسة نشطة لهذه الطاولة
            </h1>

            <p class="mx-auto mb-8 max-w-sm text-sm leading-8 text-gray-500 sm:text-base">
                الطاولة مستخدمة حالياً، ولا يمكن إنشاء جلسة جديدة قبل انتهاء الجلسة الحالية.
                يمكنك إعادة التحقق من حالة الطاولة أو الدخول باستخدام رمز خاص من شريكك.
            </p>

            <div class="flex flex-col gap-3">

                <button type="button" onclick="window.location.reload()"
                    class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-amber-500 px-5 py-3 font-bold text-white shadow-sm transition duration-200 hover:bg-amber-600 active:scale-[0.98]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 4v5h5M5.5 15a7 7 0 0 0 12 2L20 14M20 9a7 7 0 0 0-12-2L4 10" />
                    </svg>
                    التحقق مرة أخرى
                </button>

                <div class="mt-4 text-center text-sm leading-7 text-gray-600">
                    للانضمام إلى فاتورة أصدقائك، يرجى مسح رمز QR الموجود على الفاتورة باستخدام كاميرا هاتفك.
                </div>

            </div>

            <div class="mt-8 border-t border-gray-100 pt-5">
                <p class="text-xs leading-6 text-gray-400">
                    إذا كنت بحاجة إلى مساعدة، يرجى التواصل مع موظفي المطعم.
                </p>
            </div>

        </div>
    </main>

</body>

</html>
