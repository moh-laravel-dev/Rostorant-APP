<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">

    <title>يرجى الانتظار</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: Tahoma, Arial, sans-serif;
        }

        .timer-ring {
            stroke-dasharray: 283;
            stroke-dashoffset: 0;
            transform: rotate(-90deg);
            transform-origin: center;
            transition: stroke-dashoffset 0.9s linear;
        }
    </style>
</head>

<body class="flex min-h-screen items-center justify-center bg-gray-50 p-5">

    <main class="w-full max-w-md rounded-3xl border border-gray-200 bg-white p-8 text-center shadow-lg">

        <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-amber-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-amber-600" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 8v4m0 4h.01M5.5 19h13a2 2 0 001.73-3L13.73 4a2 2 0 00-3.46 0L3.77 16a2 2 0 001.73 3z" />
            </svg>
        </div>

        <h1 class="mb-3 text-2xl font-bold text-gray-800">
            تم تجاوز الحد المسموح
        </h1>

        <p class="mb-8 leading-7 text-gray-500">
            لقد أرسلت عددًا كبيرًا من الطلبات خلال فترة قصيرة.
            يرجى الانتظار حتى انتهاء المؤقت، وستعود الصفحة تلقائيًا.
        </p>

        <div class="relative mx-auto mb-6 h-28 w-28">
            <svg class="h-full w-full" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="45" stroke="#e5e7eb" stroke-width="6" fill="none" />

                <circle id="timer-ring" cx="50" cy="50" r="45" stroke="#d97706" stroke-width="6"
                    fill="none" stroke-linecap="round" class="timer-ring" />
            </svg>

            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span id="timer" class="text-3xl font-bold text-gray-800">--</span>
                <span class="mt-1 text-xs text-gray-500">ثانية</span>
            </div>
        </div>

        <p id="status-message" aria-live="polite" class="text-sm font-medium text-gray-500">
            جارٍ انتظار انتهاء مدة الحظر...
        </p>

        <noscript>
            <p class="mt-4 text-sm text-red-600">
                يرجى تفعيل JavaScript للعودة تلقائيًا.
            </p>
        </noscript>

    </main>

    <script>
        (() => {
            let remaining = Math.max(
                0,
                Math.ceil(Number(@json($retryAfter)) || 0)
            );

            const initialSeconds = Math.max(remaining, 1);
            const timerElement = document.getElementById('timer');
            const ringElement = document.getElementById('timer-ring');
            const statusElement = document.getElementById('status-message');

            const circumference = 2 * Math.PI * 45;

            ringElement.style.strokeDasharray = circumference;
            ringElement.style.strokeDashoffset = 0;

            let deadline = Date.now() + remaining * 1000;
            let redirected = false;

            function updateTimer() {
                remaining = Math.max(
                    0,
                    Math.ceil((deadline - Date.now()) / 1000)
                );

                timerElement.textContent = remaining;

                const progress = Math.min(
                    1,
                    Math.max(0, 1 - remaining / initialSeconds)
                );

                ringElement.style.strokeDashoffset =
                    circumference * progress;

                if (remaining <= 0) {
                    if (redirected) return;

                    redirected = true;
                    statusElement.textContent = 'انتهى وقت الانتظار، جارٍ العودة...';

                    // Wait until the server-side rate limit has expired.
                    window.location.reload();
                    return;
                }

                window.setTimeout(updateTimer, 200);
            }

            updateTimer();
        })();
    </script>

</body>

</html>
