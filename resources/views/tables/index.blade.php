<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طاولات المطعم</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, sans-serif;
            background: #f3f6f4;
            color: #24332d;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0;
            color: #174c3c;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 22px;
            border-radius: 14px;
            border: 1px solid #e5ebe7;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        }

        .stat-card .label {
            color: #777;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .stat-card .number {
            font-size: 30px;
            font-weight: bold;
        }

        .total .number {
            color: #174c3c;
        }

        .available .number {
            color: #18753b;
        }

        .busy .number {
            color: #dc2626;
        }

        .tables-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
        }

        .table-card {
            background: white;
            border-radius: 16px;
            padding: 22px;
            border: 1px solid #e5ebe7;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border-top: 5px solid #16a34a;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .table-card.busy {
            border-top-color: #dc2626;
        }

        .table-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 22px rgba(0, 0, 0, 0.09);
        }

        .table-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #dcfce7;
            color: #15803d;
            border-radius: 14px;
            margin-bottom: 18px;
        }

        .table-icon svg {
            width: 38px;
            height: 38px;
        }

        .table-card.busy .table-icon {
            background: #fee2e2;
            color: #dc2626;
        }

        .table-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .table-number {
            color: #777;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .table-code {
            background: #f5f7f6;
            padding: 11px;
            border-radius: 8px;
            text-align: center;
            direction: ltr;
            font-family: monospace;
            font-size: 17px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        .status {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .status.available {
            background: #dcfce7;
            color: #15803d;
        }

        .status.busy {
            background: #fee2e2;
            color: #b91c1c;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 11px 8px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-show {
            background: #174c3c;
            color: white;
        }

        .btn-show-red {
            background: #b91c1c;
            ;
            color: white;
        }

        .btn-show-red:hover {
            background: #791313;
            ;
            color: white;
        }


        .btn-print:hover {
            background: #dce5e0;
        }

        .btn-print {
            background: #fddede;
            color: #b91c1c;
        }

        .btn-print:hover {
            background: #ffefef;
        }

        .btn svg {
            width: 17px;
            height: 17px;
        }

        .empty {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 16px;
            color: #777;
        }

        @media (max-width: 600px) {
            body {
                padding: 18px;
            }

            .stats {
                gap: 8px;
            }

            .stat-card {
                padding: 14px 10px;
            }

            .stat-card .label {
                font-size: 12px;
            }

            .stat-card .number {
                font-size: 24px;
            }

            .tables-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 12px;
            }

            .table-card {
                padding: 15px;
            }

            .table-name {
                font-size: 17px;
            }

            .actions {
                flex-direction: column;
            }
        }

        @media (max-width: 360px) {
            .tables-grid {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: row;
            }
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .header,
            .stats,
            .actions {
                display: none !important;
            }

            .tables-grid {
                display: block;
            }

            .table-card {
                display: none;
            }

            .table-card.printing {
                display: block;
                width: 100%;
                border: none;
                box-shadow: none;
                text-align: center;
            }

            .table-card.printing .table-icon,
            .table-card.printing .table-number,
            .table-card.printing .status {
                display: none;
            }

            .table-card.printing .table-code {
                background: white;
            }
        }



        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .search-form input {
            flex: 1;
            min-width: 200px;
            padding: 13px 15px;
            border: 1px solid #d5dfd9;
            border-radius: 9px;
            font-size: 15px;
            outline-color: #174c3c;
            direction: rtl;
        }

        .search-form button,
        .search-form a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 20px;
            border: none;
            border-radius: 9px;
            background: #174c3c;
            color: white;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .search-form a {
            background: #e5ebe7;
            color: #174c3c;
        }

        @media (max-width: 600px) {
            .search-form input {
                flex-basis: 100%;
            }
        }

        .table-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .table-icon {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .table-icon svg {
            width: 38px;
            height: 38px;
        }

        .btn-edit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 14px;
            border: 1px solid #d5e5dc;
            border-radius: 8px;
            background: #edf7f1;
            color: #176344;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
        }

        .btn-edit svg {
            width: 16px;
            height: 16px;
        }

        .btn-edit:hover {
            background: #176344;
            color: #fff;
            border-color: #176344;
            transform: translateY(-1px);
        }

        .btn-edit-red {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 14px;
            border: 1px solid #d5e5dc;
            border-radius: 8px;
            background: #f7d1d1;
            color: #b91c1c;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
        }

        .btn-edit-red svg {
            width: 16px;
            height: 16px;
        }

        .btn-edit-red:hover {
            background: #b91c1c;
            color: #fff;
            border-color: #b91c1c;
            transform: translateY(-1px);
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="header">
            <h1>طاولات المطعم</h1>
            <form method="GET" action="{{ route('tables.index') }}" class="search-form">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="ابحث برقم الطاولة أو كود QR..." autocomplete="off">

                <button type="submit">بحث</button>

                @if (request()->filled('search'))
                    <a href="{{ route('tables.index') }}">إلغاء البحث</a>
                @endif
            </form>
        </div>

        @php
            $totalTables = $tables->count();
            $availableTables = $tables->where('status', 1)->count();
            $busyTables = $tables->where('status', 0)->count();
        @endphp

        <div class="stats">
            <div class="stat-card total">
                <div class="label">إجمالي الطاولات</div>
                <div class="number">{{ $totalTables }}</div>
            </div>

            <div class="stat-card available">
                <div class="label">الطاولات المتاحة</div>
                <div class="number">{{ $availableTables }}</div>
            </div>

            <div class="stat-card busy">
                <div class="label">الطاولات المشغولة</div>
                <div class="number">{{ $busyTables }}</div>
            </div>
        </div>

        @if ($tables->isNotEmpty())
            <div class="tables-grid">
                @foreach ($tables as $table)
                    <div class="table-card {{ $table->status ? '' : 'busy' }}" id="table-card-{{ $table->id }}">
                        <div class="table-card-header">
                            <div class="table-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round" aria-label="أيقونة طاولة">
                                    <path d="M3 8h18v3H3z" />
                                    <path d="M5 11v9M19 11v9" />
                                    <path d="M8 8V5h8v3" />
                                </svg>
                            </div>

                            <a href="" class="btn {{ $table->status ? 'btn-edit' : 'btn-edit-red' }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 20h9" />
                                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L8 18l-4 1 1-4Z" />
                                </svg>
                                تعديل
                            </a>
                        </div>

                        <div class="table-name">
                            {{ $table->name }}
                        </div>

                        <div class="table-number">
                            رقم الطاولة: {{ $table->number }}
                        </div>

                        <div class="table-code">
                            {{ $table->code }}
                        </div>

                        <span class="status {{ $table->status ? 'available' : 'busy' }}">
                            {{ $table->status ? 'متاحة' : 'مشغولة' }}
                        </span>

                        <div class="actions">
                            <a href="{{ route('tables.qr', $table->id) }}"
                                class="btn {{ $table->status ? 'btn-show' : 'btn-show-red' }}"> <svg
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="7" height="7" rx="1" />
                                    <rect x="14" y="3" width="7" height="7" rx="1" />
                                    <rect x="3" y="14" width="7" height="7" rx="1" />
                                    <path d="M14 14h3v3h-3zM20 14v3M14 20h3M20 20v1" />
                                </svg>
                                عرض QR
                            </a>

                            @if (!$table->status)
                                <a href="" class="btn btn-print">

                                    عرض الفاتورة الحالية
                                </a>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <div class="empty">
                <h2>لا توجد طاولات</h2>
                <p>لم تتم إضافة أي طاولات حتى الآن.</p>
            </div>
        @endif

    </div>
</body>

</html>
