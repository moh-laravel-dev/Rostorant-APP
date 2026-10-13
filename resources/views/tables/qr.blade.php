<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR الطاولة {{ $table->number }}</title>

    <style>
        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, sans-serif;
            background: #f3f6f4;
            text-align: center;
            color: #174c3c;
        }

        .qr-card {
            max-width: 420px;
            margin: 30px auto;
            padding: 30px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 5px 20px #00000010;
        }

        .qr-image {
            width: 300px;
            max-width: 100%;
            height: auto;
        }

        .code {
            font-size: 22px;
            font-weight: bold;
            direction: ltr;
            margin: 15px 0;
        }

        .btn {
            display: inline-block;
            padding: 12px 22px;
            margin: 8px;
            border: 0;
            border-radius: 8px;
            background: #174c3c;
            color: white;
            text-decoration: none;
            cursor: pointer;
        }

        .red-btn {
            display: inline-block;
            padding: 12px 22px;
            margin: 8px;
            border: 0;
            border-radius: 8px;
            background: #4c1717;
            color: white;
            text-decoration: none;
            cursor: pointer;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .qr-card {
                box-shadow: none;
                margin: 0 auto;
            }

            .btn {
                display: none;
            }
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

        @media print {
            .status-alert {
                display: none !important;
            }
        }
    </style>
</head>s

<body>
    <div class="qr-card">
        @if ((int) $table->status === 0)
            <div class="status-alert">
                ⚠️ تنبيه: هذه الطاولة مشغولة حاليًا
            </div>
        @endif
        <h1>{{ $table->name }}</h1>
        <p>رقم الطاولة: {{ $table->number }}</p>

        <img class="qr-image" src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code">

        <div class="code">{{ $table->code }}</div>

        <button class="btn" onclick="window.print()">طباعة QR</button>

        <a class="btn" href="{{ route('tables.index') }}">العودة للطاولات</a>
    </div>

    @if (request()->boolean('print'))
        <script>
            window.addEventListener('load', () => window.print());
        </script>
    @endif
</body>

</html>
