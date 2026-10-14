<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - الوصول مرفوض</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex min-h-screen items-center justify-center bg-gray-50 px-4 py-10">
    <main class="w-full max-w-lg rounded-3xl border border-gray-100 bg-white p-8 text-center shadow-xl sm:p-12">

        <div class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-red-50 text-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="1.7">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v3.75m0 3h.008M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" />
            </svg>
        </div>

        <p class="mb-2 text-sm font-bold uppercase tracking-widest text-red-500">
            خطأ 403
        </p>

        <h1 class="mb-4 text-3xl font-extrabold text-gray-900 sm:text-4xl">
            عذراً، الوصول مرفوض
        </h1>

        <p class="mb-8 leading-8 text-gray-500">
            لا يمكنك الوصول إلى هذه الصفحة دون جلسة طاولة صالحة.
            يرجى مسح رمز QR الخاص بالطاولة مرة أخرى للمتابعة إلى قائمة الطعام.
        </p>

        <div class="flex flex-col justify-center gap-3 sm:flex-row">
            <button type="button" onclick="window.history.back()"
                class="rounded-xl border border-gray-200 px-6 py-3 font-bold text-gray-700 transition hover:bg-gray-50">
                العودة إلى الصفحة السابقة
            </button>
        </div>

        <p class="mt-8 text-xs text-gray-400">
            إذا استمرت المشكلة، يرجى التواصل مع موظفي المطعم.
        </p>
    </main>
</body>

</html>
