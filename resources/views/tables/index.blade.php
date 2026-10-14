@extends('admin.layouts.main-layout')

@section('title', 'الطاولات')
@section('page-title', 'إدارة الطاولات')

@section('content')
    <style>
        .tables-page {
            --primary: #174c3c;
            --primary-dark: #10382c;
            --primary-light: #e8f4ed;
            --border: #e3ebe6;
            --muted: #718078;
            color: #20372c;
        }

        .tables-page * {
            box-sizing: border-box;
        }

        .tables-page .page-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 28px;
        }

        .tables-page .heading-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .tables-page .heading-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 58px;
            height: 58px;
            flex-shrink: 0;
            color: white;
            border-radius: 19px;
            background: linear-gradient(145deg, #26745a, #10382c);
            box-shadow: 0 8px 20px rgb(23 76 60 / 18%);
        }

        .tables-page .heading-icon svg {
            width: 29px;
            height: 29px;
        }

        .tables-page .heading-title {
            margin: 0;
            color: var(--primary-dark);
            font-size: clamp(21px, 2.5vw, 29px);
            font-weight: 800;
            letter-spacing: -.6px;
        }

        .tables-page .heading-description {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
        }

        .tables-page .btn-add {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 46px;
            padding: 12px 19px;
            border: 1px solid transparent;
            border-radius: 14px;
            background: linear-gradient(135deg, #26745a, #174c3c);
            color: white;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 6px 16px rgb(23 76 60 / 17%);
            transition: .2s ease;
        }

        .tables-page .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgb(23 76 60 / 23%);
        }

        .tables-page .btn-add svg {
            width: 19px;
            height: 19px;
        }

        .tables-page .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 25px;
        }

        .tables-page .stat-card {
            position: relative;
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 0;
            overflow: hidden;
            padding: 22px;
            border: 1px solid var(--border);
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 5px 20px rgb(23 76 60 / 4%);
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .tables-page .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgb(23 76 60 / 8%);
        }

        .tables-page .stat-card::after {
            position: absolute;
            bottom: -28px;
            left: -22px;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: currentColor;
            content: "";
            opacity: .045;
        }

        .tables-page .stat-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 53px;
            height: 53px;
            flex-shrink: 0;
            border-radius: 17px;
        }

        .tables-page .stat-icon svg {
            width: 25px;
            height: 25px;
        }

        .tables-page .stat-total {
            color: #174c3c;
        }

        .tables-page .stat-total .stat-icon {
            background: #e7f3ec;
        }

        .tables-page .stat-available {
            color: #16804a;
        }

        .tables-page .stat-available .stat-icon {
            background: #e2f7e9;
        }

        .tables-page .stat-busy {
            color: #c43b3b;
        }

        .tables-page .stat-busy .stat-icon {
            background: #fff0ef;
        }

        .tables-page .stat-label {
            margin-bottom: 7px;
            color: #748178;
            font-size: 13px;
            font-weight: 600;
        }

        .tables-page .stat-number {
            color: #173d2f;
            font-size: clamp(25px, 3vw, 32px);
            font-weight: 850;
            line-height: 1.2;
            font-variant-numeric: tabular-nums;
        }

        .tables-page .search-panel {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 22px;
            padding: 17px;
            border: 1px solid var(--border);
            border-radius: 19px;
            background: #fff;
            box-shadow: 0 4px 16px rgb(23 76 60 / 3%);
        }

        .tables-page .search-title {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--primary-dark);
            font-size: 15px;
            font-weight: 800;
        }

        .tables-page .search-title svg {
            width: 20px;
            height: 20px;
            color: #26745a;
        }

        .tables-page .search-form {
            display: flex;
            flex: 1;
            justify-content: flex-end;
            gap: 9px;
            min-width: 0;
        }

        .tables-page .search-input-wrap {
            position: relative;
            flex: 1;
            max-width: 410px;
            min-width: 0;
        }

        .tables-page .search-input-wrap svg {
            position: absolute;
            top: 50%;
            right: 14px;
            width: 19px;
            height: 19px;
            color: #8a9a90;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .tables-page .search-input {
            width: 100%;
            min-height: 45px;
            padding: 11px 43px 11px 13px;
            border: 1px solid #dce7df;
            border-radius: 12px;
            outline: none;
            background: #fbfdfb;
            color: #20372c;
            font-size: 13px;
            transition: .2s ease;
        }

        .tables-page .search-input:focus {
            border-color: #43866a;
            background: #fff;
            box-shadow: 0 0 0 4px rgb(38 116 90 / 9%);
        }

        .tables-page .btn-search,
        .tables-page .btn-reset {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            min-height: 45px;
            padding: 11px 16px;
            border: 0;
            border-radius: 12px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            transition: .2s ease;
        }

        .tables-page .btn-search {
            background: var(--primary);
            color: #fff;
        }

        .tables-page .btn-search:hover {
            background: var(--primary-dark);
        }

        .tables-page .btn-reset {
            background: #edf3ef;
            color: #496454;
        }

        .tables-page .btn-reset:hover {
            background: #e1ebe4;
        }

        .tables-page .section-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .tables-page .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
            color: var(--primary-dark);
            font-size: 18px;
            font-weight: 850;
        }

        .tables-page .section-title::before {
            width: 5px;
            height: 23px;
            border-radius: 5px;
            background: #2b8964;
            content: "";
        }

        .tables-page .results-count {
            padding: 7px 12px;
            border: 1px solid #e1ebe4;
            border-radius: 10px;
            background: #f5faf6;
            color: #41634e;
            font-size: 12px;
            font-weight: 700;
        }

        .tables-page .tables-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .tables-page .table-card {
            position: relative;
            min-width: 0;
            overflow: hidden;
            padding: 20px;
            border: 1px solid #e4ece6;
            border-radius: 21px;
            background: #fff;
            box-shadow: 0 5px 18px rgb(23 76 60 / 4%);
            transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
        }

        .tables-page .table-card::before {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            height: 4px;
            background: linear-gradient(90deg, #57ad7a, #1a704d);
            content: "";
        }

        .tables-page .table-card.busy::before {
            background: linear-gradient(90deg, #f29b91, #c52e35);
        }

        .tables-page .table-card:hover {
            transform: translateY(-4px);
            border-color: #cbded1;
            box-shadow: 0 13px 28px rgb(23 76 60 / 9%);
        }

        .tables-page .table-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 20px;
        }

        .tables-page .table-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 57px;
            height: 57px;
            flex-shrink: 0;
            border: 1px solid #d9eee0;
            border-radius: 18px;
            background: linear-gradient(145deg, #f0faf3, #e0f1e6);
            color: #23784d;
        }

        .tables-page .table-icon svg {
            width: 31px;
            height: 31px;
        }

        .tables-page .table-card.busy .table-icon {
            border-color: #f4d7d4;
            background: linear-gradient(145deg, #fff5f4, #fee7e5);
            color: #c33838;
        }

        .tables-page .table-name {
            overflow-wrap: anywhere;
            margin-bottom: 8px;
            color: #173d2f;
            font-size: 19px;
            font-weight: 850;
            line-height: 1.5;
        }

        .tables-page .table-number {
            display: flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 17px;
            color: #7a8980;
            font-size: 13px;
        }

        .tables-page .table-number svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        .tables-page .code-label {
            margin-bottom: 8px;
            color: #7b8980;
            font-size: 11px;
            font-weight: 700;
        }

        .tables-page .table-code {
            overflow-wrap: anywhere;
            margin-bottom: 15px;
            padding: 13px 10px;
            border: 1px dashed #d8e5dc;
            border-radius: 12px;
            background: #f7faf8;
            color: #244b38;
            text-align: center;
            direction: ltr;
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .tables-page .card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }

        .tables-page .status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 11px;
            border: 1px solid #d6eddf;
            border-radius: 10px;
            background: #edfaf1;
            color: #197444;
            font-size: 12px;
            font-weight: 800;
        }

        .tables-page .status::before {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: currentColor;
            content: "";
        }

        .tables-page .status.busy {
            border-color: #f5d7d4;
            background: #fff0ef;
            color: #bd3030;
        }

        .tables-page .btn-edit,
        .tables-page .btn-edit-red {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 9px 12px;
            border: 1px solid #d5e5dc;
            border-radius: 11px;
            background: #edf7f1;
            color: #176344;
            font-size: 12px;
            font-weight: 750;
            text-decoration: none;
            white-space: nowrap;
            transition: .2s ease;
        }

        .tables-page .btn-edit:hover {
            border-color: #176344;
            background: #176344;
            color: #fff;
        }

        .tables-page .btn-edit-red {
            border-color: #f1d3d0;
            background: #fff1f0;
            color: #b91c1c;
        }

        .tables-page .btn-edit-red:hover {
            border-color: #b91c1c;
            background: #b91c1c;
            color: #fff;
        }

        .tables-page .btn-edit svg,
        .tables-page .btn-edit-red svg {
            width: 15px;
            height: 15px;
        }

        .tables-page .actions {
            display: grid;
            grid-template-columns: 1fr;
            gap: 9px;
        }

        .tables-page .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            min-height: 43px;
            padding: 11px 12px;
            border: 1px solid transparent;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 750;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: .2s ease;
        }

        .tables-page .btn svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .tables-page .btn-show {
            background: var(--primary);
            color: white;
        }

        .tables-page .btn-show:hover {
            background: #10382c;
            box-shadow: 0 5px 12px rgb(23 76 60 / 15%);
        }

        .tables-page .btn-show-red {
            background: #b92f35;
            color: white;
        }

        .tables-page .btn-show-red:hover {
            background: #922329;
        }

        .tables-page .btn-print {
            border-color: #f1d6d3;
            background: #fff3f1;
            color: #ad3434;
        }

        .tables-page .btn-print:hover {
            border-color: #eabbb6;
            background: #ffe7e4;
        }

        .tables-page .empty {
            padding: 55px 20px;
            border: 1px dashed #cddfd2;
            border-radius: 22px;
            background: linear-gradient(145deg, #fff, #f7fbf8);
            text-align: center;
        }

        .tables-page .empty-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 75px;
            height: 75px;
            margin: 0 auto 18px;
            border-radius: 24px;
            background: #e7f4eb;
            color: #26745a;
        }

        .tables-page .empty-icon svg {
            width: 38px;
            height: 38px;
        }

        .tables-page .empty h2 {
            margin: 0 0 9px;
            color: #174c3c;
            font-size: 20px;
            font-weight: 850;
        }

        .tables-page .empty p {
            margin: 0 0 20px;
            color: #75847a;
            font-size: 14px;
            line-height: 1.8;
        }

        @media (min-width: 1500px) {
            .tables-page .tables-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        @media (max-width: 1100px) {
            .tables-page .tables-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .tables-page .stat-card {
                padding: 18px;
            }
        }

        @media (max-width: 640px) {
            .tables-page .page-heading {
                align-items: stretch;
                gap: 15px;
                margin-bottom: 20px;
            }

            .tables-page .heading-content {
                gap: 12px;
            }

            .tables-page .heading-icon {
                width: 48px;
                height: 48px;
                border-radius: 15px;
            }

            .tables-page .heading-icon svg {
                width: 25px;
                height: 25px;
            }

            .tables-page .heading-description {
                font-size: 12px;
            }

            .tables-page .btn-add {
                width: 100%;
            }

            .tables-page .stats {
                gap: 9px;
                margin-bottom: 18px;
            }

            .tables-page .stat-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
                padding: 13px 11px;
                border-radius: 15px;
            }

            .tables-page .stat-icon {
                width: 38px;
                height: 38px;
                border-radius: 12px;
            }

            .tables-page .stat-icon svg {
                width: 21px;
                height: 21px;
            }

            .tables-page .stat-label {
                min-height: 32px;
                margin-bottom: 4px;
                font-size: 11px;
                line-height: 1.6;
            }

            .tables-page .stat-number {
                font-size: 25px;
            }

            .tables-page .search-panel {
                align-items: stretch;
                padding: 14px;
                border-radius: 16px;
            }

            .tables-page .search-form {
                display: grid;
                grid-template-columns: minmax(0, 1fr) auto;
                width: 100%;
                gap: 8px;
            }

            .tables-page .search-input-wrap {
                min-width: 0;
            }

            .tables-page .search-input {
                font-size: 12px;
            }

            .tables-page .btn-search {
                padding-inline: 13px;
            }

            .tables-page .btn-reset {
                grid-column: 1 / -1;
                width: 100%;
            }

            .tables-page .section-title {
                font-size: 16px;
            }

            .tables-page .tables-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 11px;
            }

            .tables-page .table-card {
                padding: 13px;
                border-radius: 16px;
            }

            .tables-page .table-card-header {
                align-items: flex-start;
                gap: 6px;
                margin-bottom: 15px;
            }

            .tables-page .table-icon {
                width: 43px;
                height: 43px;
                border-radius: 13px;
            }

            .tables-page .table-icon svg {
                width: 25px;
                height: 25px;
            }

            .tables-page .btn-edit,
            .tables-page .btn-edit-red {
                gap: 4px;
                padding: 7px 8px;
                font-size: 11px;
            }

            .tables-page .table-name {
                font-size: 15px;
            }

            .tables-page .table-number {
                align-items: flex-start;
                font-size: 11px;
                line-height: 1.7;
            }

            .tables-page .table-code {
                padding: 11px 5px;
                font-size: 12px;
                letter-spacing: .3px;
            }

            .tables-page .card-footer {
                align-items: flex-start;
                flex-direction: column;
                margin-bottom: 13px;
            }

            .tables-page .status {
                padding: 7px 9px;
                font-size: 10px;
            }

            .tables-page .actions {
                gap: 7px;
            }

            .tables-page .btn {
                gap: 5px;
                min-height: 39px;
                padding: 9px 6px;
                font-size: 11px;
            }

            .tables-page .btn svg {
                width: 16px;
                height: 16px;
            }
        }

        @media (max-width: 360px) {
            .tables-page .tables-grid {
                grid-template-columns: 1fr;
            }

            .tables-page .table-card {
                padding: 17px;
            }

            .tables-page .table-card-header {
                align-items: center;
            }

            .tables-page .card-footer {
                align-items: center;
                flex-direction: row;
            }

            .tables-page .table-name {
                font-size: 17px;
            }

            .tables-page .table-code {
                font-size: 15px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .tables-page *,
            .tables-page *::before,
            .tables-page *::after {
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
            }
        }

        @media print {
            body {
                background: #fff !important;
            }

            .tables-page .page-heading,
            .tables-page .stats,
            .tables-page .search-panel,
            .tables-page .section-heading,
            .tables-page .btn-add,
            .tables-page .actions,
            .tables-page .btn-edit,
            .tables-page .btn-edit-red,
            .tables-page .card-footer,
            .tables-page .table-icon,
            .tables-page .table-number,
            .tables-page .code-label {
                display: none !important;
            }

            .tables-page .tables-grid {
                display: block;
            }

            .tables-page .table-card {
                display: none;
                border: 0;
                box-shadow: none;
                text-align: center;
            }

            .tables-page .table-card.printing {
                display: block !important;
                width: 100%;
            }

            .tables-page .table-code {
                border: 0;
                background: #fff;
                color: #000;
                font-size: 22px;
            }
        }
    </style>

    <div class="tables-page" dir="rtl">
        <div class="page-heading">
            <div class="heading-content">
                <div class="heading-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 8h18v3H3z"/>
                        <path d="M5 11v9M19 11v9"/>
                        <path d="M8 8V5h8v3"/>
                    </svg>
                </div>

                <div>
                    <h1 class="heading-title">طاولات المطعم</h1>
                    <p class="heading-description">
                        إدارة الطاولات وحالاتها وأكواد QR بسهولة من مكان واحد.
                    </p>
                </div>
            </div>

            <a href="{{ route('tables.create') }}" class="btn-add">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                إضافة طاولة جديدة
            </a>
        </div>

        @php
            $totalTables = $tables->count();
            $availableTables = $tables->where('status', 1)->count();
            $busyTables = $tables->where('status', 0)->count();
        @endphp

        <div class="stats">
            <div class="stat-card stat-total">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="16" rx="3"/>
                        <path d="M3 10h18M9 4v6M15 4v6"/>
                    </svg>
                </div>
                <div>
                    <div class="stat-label">إجمالي الطاولات</div>
                    <div class="stat-number">{{ $totalTables }}</div>
                </div>
            </div>

            <div class="stat-card stat-available">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="m8 12 2.5 2.5L16 9"/>
                    </svg>
                </div>
                <div>
                    <div class="stat-label">الطاولات المتاحة</div>
                    <div class="stat-number">{{ $availableTables }}</div>
                </div>
            </div>

            <div class="stat-card stat-busy">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 7v5l3 2"/>
                    </svg>
                </div>
                <div>
                    <div class="stat-label">الطاولات المشغولة</div>
                    <div class="stat-number">{{ $busyTables }}</div>
                </div>
            </div>
        </div>

        <div class="search-panel">
            <div class="search-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="7"/>
                    <path d="m16 16 4 4"/>
                </svg>
                البحث عن طاولة
            </div>

            <form method="GET" action="{{ route('tables.index') }}" class="search-form">
                <div class="search-input-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7"/>
                        <path d="m16 16 4 4"/>
                    </svg>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="ابحث برقم الطاولة أو كود QR..."
                        autocomplete="off"
                        class="search-input"
                    >
                </div>

                <button type="submit" class="btn-search">
                    بحث
                </button>

                @if (request()->filled('search'))
                    <a href="{{ route('tables.index') }}" class="btn-reset">
                        إلغاء البحث
                    </a>
                @endif
            </form>
        </div>

        <div class="section-heading">
            <h2 class="section-title">جميع الطاولات</h2>
            <span class="results-count">
                {{ $totalTables }} طاولة
            </span>
        </div>

        @if ($tables->isNotEmpty())
            <div class="tables-grid">
                @foreach ($tables as $table)
                    <div
                        class="table-card {{ $table->status ? '' : 'busy' }}"
                        id="table-card-{{ $table->id }}"
                    >
                        <div class="table-card-header">
                            <div class="table-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                                    aria-label="أيقونة طاولة">
                                    <path d="M3 8h18v3H3z"/>
                                    <path d="M5 11v9M19 11v9"/>
                                    <path d="M8 8V5h8v3"/>
                                </svg>
                            </div>

                            <a href="{{ route('tables.edit', $table->code) }}"
                                class="{{ $table->status ? 'btn-edit' : 'btn-edit-red' }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 20h9"/>
                                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L8 18l-4 1 1-4Z"/>
                                </svg>
                                تعديل
                            </a>
                        </div>

                        <div class="table-name">
                            {{ $table->name }}
                        </div>

                        <div class="table-number">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="4" y="3" width="16" height="18" rx="2"/>
                                <path d="M9 8h6M9 12h6M9 16h3"/>
                            </svg>
                            <span>رقم الطاولة: {{ $table->number }}</span>
                        </div>

                        <div class="code-label">كود الطاولة</div>

                        <div class="table-code">
                            {{ $table->code }}
                        </div>

                        <div class="card-footer">
                            <span class="status {{ $table->status ? 'available' : 'busy' }}">
                                {{ $table->status ? 'متاحة للزبائن' : 'مشغولة حالياً' }}
                            </span>
                        </div>

                        <div class="actions">
                            <a
                                href="{{ route('tables.qr', $table->id) }}"
                                class="btn {{ $table->status ? 'btn-show' : 'btn-show-red' }}"
                            >
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                                    <path d="M14 14h3v3h-3zM20 14v3M14 20h3M20 20v1"/>
                                </svg>
                                عرض رمز QR
                            </a>

                            @if (!$table->status)
                                <a href="" class="btn btn-print">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 4h16v16H4z"/>
                                        <path d="M8 8h8M8 12h8M8 16h5"/>
                                    </svg>
                                    عرض الفاتورة الحالية
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty">
                <div class="empty-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 8h18v3H3z"/>
                        <path d="M5 11v9M19 11v9"/>
                        <path d="M8 8V5h8v3"/>
                        <path d="M12 13v3M12 18h.01"/>
                    </svg>
                </div>

                <h2>
                    {{ request()->filled('search') ? 'لم يتم العثور على نتائج' : 'لا توجد طاولات حتى الآن' }}
                </h2>

                <p>
                    {{ request()->filled('search')
                        ? 'جرّب البحث باستخدام رقم طاولة أو كود QR مختلف.'
                        : 'ابدأ بإضافة أول طاولة إلى مطعمك، وستظهر هنا لإدارتها بسهولة.' }}
                </p>

                @if (!request()->filled('search'))
                    <a href="{{ route('tables.create') }}" class="btn-add">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round">
                            <path d="M12 5v14M5 12h14"/>
                        </svg>
                        إضافة أول طاولة
                    </a>
                @else
                    <a href="{{ route('tables.index') }}" class="btn-add">
                        عرض جميع الطاولات
                    </a>
                @endif
            </div>
        @endif
    </div>
@endsection
