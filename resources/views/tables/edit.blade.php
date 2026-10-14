@extends('admin.layouts.main-layout')

@section('title', 'تعديل الطاولة')
@section('page-title', 'تعديل الطاولة')

@section('content')
    <style>
        .edit-table-page {
            --primary: #174c3c;
            --primary-dark: #10382c;
            --primary-light: #e8f4ed;
            --border: #e2ebe5;
            --muted: #77867d;
            color: #20372c;
        }

        .edit-table-page * {
            box-sizing: border-box;
        }

        .edit-table-page .page-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .edit-table-page .header-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 58px;
            height: 58px;
            flex-shrink: 0;
            border-radius: 19px;
            background: linear-gradient(145deg, #26745a, #10382c);
            color: #fff;
            box-shadow: 0 8px 20px rgb(23 76 60 / 16%);
        }

        .edit-table-page .header-icon svg {
            width: 28px;
            height: 28px;
        }

        .edit-table-page .page-title {
            margin: 0;
            color: var(--primary-dark);
            font-size: clamp(22px, 3vw, 29px);
            font-weight: 850;
            letter-spacing: -.5px;
        }

        .edit-table-page .page-description {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
        }

        .edit-table-page .status-alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 22px;
            padding: 15px 17px;
            border: 1px solid #f3d6a2;
            border-radius: 15px;
            background: linear-gradient(120deg, #fffaf0, #fff5dc);
            color: #895714;
            font-size: 13px;
            line-height: 1.8;
        }

        .edit-table-page .status-alert svg {
            width: 22px;
            height: 22px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .edit-table-page .status-alert strong {
            display: block;
            margin-bottom: 2px;
            font-size: 14px;
        }

        .edit-table-page .form-card {
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 23px;
            background: #fff;
            box-shadow: 0 8px 30px rgb(23 76 60 / 5%);
        }

        .edit-table-page .card-header {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 22px 25px;
            border-bottom: 1px solid #eaf0ec;
            background: linear-gradient(120deg, #fbfdfb, #f1f8f3);
        }

        .edit-table-page .card-header-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 43px;
            height: 43px;
            flex-shrink: 0;
            border: 1px solid #d9eae0;
            border-radius: 14px;
            background: #e6f4eb;
            color: #23734d;
        }

        .edit-table-page .card-header-icon svg {
            width: 22px;
            height: 22px;
        }

        .edit-table-page .card-heading {
            margin: 0;
            color: #174c3c;
            font-size: 16px;
            font-weight: 850;
        }

        .edit-table-page .card-subheading {
            margin: 5px 0 0;
            color: #7b8980;
            font-size: 12px;
            line-height: 1.7;
        }

        .edit-table-page .form-body {
            padding: 26px;
        }

        .edit-table-page .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px 18px;
        }

        .edit-table-page .field {
            min-width: 0;
        }

        .edit-table-page .field-full {
            grid-column: 1 / -1;
        }

        .edit-table-page .field-label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 9px;
            color: #244d39;
            font-size: 13px;
            font-weight: 800;
        }

        .edit-table-page .field-label svg {
            width: 17px;
            height: 17px;
            flex-shrink: 0;
            color: #448364;
        }

        .edit-table-page .required-mark {
            color: #dc4545;
        }

        .edit-table-page .form-input {
            display: block;
            width: 100%;
            min-height: 48px;
            padding: 12px 14px;
            border: 1px solid #dce6df;
            border-radius: 13px;
            outline: none;
            background: #fbfdfb;
            color: #233d2e;
            font-size: 14px;
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        }

        .edit-table-page .form-input:hover {
            border-color: #c4d8ca;
        }

        .edit-table-page .form-input:focus {
            border-color: #398260;
            background: #fff;
            box-shadow: 0 0 0 4px rgb(23 76 60 / 9%);
        }

        .edit-table-page .form-input[readonly] {
            border-color: #e5ebe6;
            background: #f4f7f5;
            color: #66786c;
            cursor: default;
        }

        .edit-table-page .ltr-input {
            direction: ltr;
            text-align: right;
        }

        .edit-table-page .field-help {
            margin: 8px 0 0;
            color: #859189;
            font-size: 11px;
            line-height: 1.8;
        }

        .edit-table-page .field-error {
            margin: 7px 0 0;
            color: #c52e35;
            font-size: 12px;
            font-weight: 600;
        }

        .edit-table-page .status-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            min-height: 84px;
            padding: 17px 18px;
            border: 1px solid #dce9df;
            border-radius: 16px;
            background: #f8fbf8;
            transition: background .2s ease, border-color .2s ease;
        }

        .edit-table-page .status-box.is-available {
            border-color: #cde8d5;
            background: #f0faf3;
        }

        .edit-table-page .status-box.is-busy {
            border-color: #f0d7d5;
            background: #fff6f5;
        }

        .edit-table-page .status-info {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .edit-table-page .status-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 43px;
            height: 43px;
            flex-shrink: 0;
            border-radius: 14px;
            background: #e0f3e6;
            color: #18834a;
        }

        .edit-table-page .status-box.is-busy .status-icon {
            background: #ffe7e5;
            color: #c53535;
        }

        .edit-table-page .status-icon svg {
            width: 22px;
            height: 22px;
        }

        .edit-table-page .status-title {
            margin: 0 0 4px;
            color: #294837;
            font-size: 13px;
            font-weight: 850;
        }

        .edit-table-page .status-description {
            margin: 0;
            color: #7b8980;
            font-size: 11px;
            line-height: 1.7;
        }

        .edit-table-page .status-control {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .edit-table-page .status-label {
            min-width: 57px;
            color: #18834a;
            font-size: 12px;
            font-weight: 850;
            text-align: center;
        }

        .edit-table-page .status-box.is-busy .status-label {
            color: #c53535;
        }

        .edit-table-page .switch {
            position: relative;
            display: inline-flex;
            width: 52px;
            height: 29px;
            flex-shrink: 0;
            cursor: pointer;
        }

        .edit-table-page .switch input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
        }

        .edit-table-page .switch-track {
            position: absolute;
            inset: 0;
            border-radius: 30px;
            background: #cbd5ce;
            transition: background .25s ease;
        }

        .edit-table-page .switch-track::after {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 21px;
            height: 21px;
            border-radius: 50%;
            background: white;
            box-shadow: 0 1px 4px rgb(0 0 0 / 18%);
            content: "";
            transition: transform .25s ease;
        }

        .edit-table-page .switch input:checked + .switch-track {
            background: #218653;
        }

        .edit-table-page .switch input:checked + .switch-track::after {
            transform: translateX(-23px);
        }

        .edit-table-page .switch input:focus-visible + .switch-track {
            outline: 3px solid rgb(23 76 60 / 25%);
            outline-offset: 3px;
        }

        .edit-table-page .code-panel {
            position: relative;
            padding: 18px;
            border: 1px dashed #cbded0;
            border-radius: 16px;
            background: linear-gradient(130deg, #f7fbf8, #edf6f0);
        }

        .edit-table-page .code-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
        }

        .edit-table-page .code-heading {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #315b43;
            font-size: 13px;
            font-weight: 850;
        }

        .edit-table-page .code-heading svg {
            width: 18px;
            height: 18px;
        }

        .edit-table-page .auto-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 9px;
            border: 1px solid #d5e9db;
            border-radius: 8px;
            background: #e7f5eb;
            color: #267448;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .edit-table-page .code-input {
            min-height: 49px;
            border-color: #dce9df;
            background: #fff;
            text-align: center;
            direction: ltr;
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .edit-table-page .form-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 28px;
            padding-top: 22px;
            border-top: 1px solid #edf1ee;
        }

        .edit-table-page .action-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .edit-table-page .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 46px;
            padding: 12px 18px;
            border: 1px solid transparent;
            border-radius: 12px;
            cursor: pointer;
            font-family: inherit;
            font-size: 13px;
            font-weight: 800;
            text-decoration: none;
            transition: .2s ease;
        }

        .edit-table-page .btn svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .edit-table-page .btn-save {
            background: linear-gradient(135deg, #26745a, #174c3c);
            color: #fff;
            box-shadow: 0 5px 14px rgb(23 76 60 / 16%);
        }

        .edit-table-page .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgb(23 76 60 / 23%);
        }

        .edit-table-page .btn-cancel {
            border-color: #dfe8e2;
            background: #f5f8f6;
            color: #53665a;
        }

        .edit-table-page .btn-cancel:hover {
            border-color: #cbdad0;
            background: #eaf1ec;
        }

        .edit-table-page .btn-delete {
            border-color: #f0d6d4;
            background: #fff3f2;
            color: #b52c32;
        }

        .edit-table-page .btn-delete:hover {
            transform: translateY(-1px);
            border-color: #b52c32;
            background: #b52c32;
            color: #fff;
        }

        .edit-table-page .flash-message {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 20px;
            padding: 14px 16px;
            border: 1px solid;
            border-radius: 13px;
            font-size: 13px;
            line-height: 1.8;
        }

        .edit-table-page .flash-message svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .edit-table-page .flash-error {
            border-color: #f3cece;
            background: #fff3f3;
            color: #a82828;
        }

        .edit-table-page .flash-success {
            border-color: #cce8d4;
            background: #effaf2;
            color: #176b3d;
        }

        @media (max-width: 640px) {
            .edit-table-page .page-header {
                align-items: flex-start;
                gap: 12px;
                margin-bottom: 20px;
            }

            .edit-table-page .header-icon {
                width: 48px;
                height: 48px;
                border-radius: 15px;
            }

            .edit-table-page .header-icon svg {
                width: 24px;
                height: 24px;
            }

            .edit-table-page .page-description {
                font-size: 12px;
            }

            .edit-table-page .form-card {
                border-radius: 18px;
            }

            .edit-table-page .card-header {
                padding: 17px;
            }

            .edit-table-page .form-body {
                padding: 18px 15px;
            }

            .edit-table-page .form-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 19px;
            }

            .edit-table-page .field-full {
                grid-column: auto;
            }

            .edit-table-page .status-box {
                align-items: flex-start;
                padding: 14px 12px;
            }

            .edit-table-page .status-info {
                gap: 9px;
            }

            .edit-table-page .status-icon {
                width: 36px;
                height: 36px;
                border-radius: 11px;
            }

            .edit-table-page .status-description {
                max-width: 180px;
            }

            .edit-table-page .status-control {
                gap: 6px;
            }

            .edit-table-page .status-label {
                min-width: 45px;
                font-size: 11px;
            }

            .edit-table-page .switch {
                width: 46px;
                height: 27px;
            }

            .edit-table-page .switch-track::after {
                top: 3px;
                right: 3px;
                width: 21px;
                height: 21px;
            }

            .edit-table-page .switch input:checked + .switch-track::after {
                transform: translateX(-19px);
            }

            .edit-table-page .code-panel {
                padding: 13px;
            }

            .edit-table-page .form-actions {
                align-items: stretch;
                flex-direction: column;
                margin-top: 22px;
            }

            .edit-table-page .action-group {
                display: grid;
                grid-template-columns: 1fr 1fr;
                width: 100%;
            }

            .edit-table-page .btn-save {
                width: 100%;
            }

            .edit-table-page .btn-delete {
                grid-column: 1 / -1;
            }

            .edit-table-page .btn {
                padding: 11px 12px;
                font-size: 12px;
            }
        }

        @media (max-width: 360px) {
            .edit-table-page .status-box {
                align-items: flex-start;
                flex-direction: column;
                gap: 13px;
            }

            .edit-table-page .status-control {
                width: 100%;
                justify-content: flex-end;
            }

            .edit-table-page .action-group {
                grid-template-columns: 1fr;
            }

            .edit-table-page .btn-delete {
                grid-column: auto;
            }

            .edit-table-page .code-top {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>

    <div class="edit-table-page" dir="rtl">
        <div class="page-header">
            <div class="header-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 20h9"/>
                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L8 18l-4 1 1-4Z"/>
                </svg>
            </div>

            <div>
                <h1 class="page-title">تعديل الطاولة</h1>
                <p class="page-description">
                    تحديث بيانات الطاولة وإدارة حالتها بكل سهولة.
                </p>
            </div>
        </div>

        @if ((int) $table->status === 0)
            <div class="status-alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m10.3 3.9-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.7-3.1l-8-14a2 2 0 0 0-3.4 0Z"/>
                    <path d="M12 9v4M12 17h.01"/>
                </svg>
                <div>
                    <strong>الطاولة مشغولة حاليًا</strong>
                    انتبه عند تغيير حالة الطاولة أو حذفها؛ فقد تكون مرتبطة بطلب أو فاتورة نشطة.
                </div>
            </div>
        @endif

        @error('error')
            <div class="flash-message flash-error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="m9 9 6 6m0-6-6 6"/>
                </svg>
                <span>{{ $message }}</span>
            </div>
        @enderror

        <div class="form-card">
            <div class="card-header">
                <div class="card-header-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 5h16M4 12h16M4 19h16"/>
                        <circle cx="8" cy="5" r="1.5" fill="currentColor"/>
                        <circle cx="15" cy="12" r="1.5" fill="currentColor"/>
                        <circle cx="10" cy="19" r="1.5" fill="currentColor"/>
                    </svg>
                </div>
                <div>
                    <h2 class="card-heading">بيانات الطاولة</h2>
                    <p class="card-subheading">
                        تعديل معلومات: {{ $table->name }}
                    </p>
                </div>
            </div>

            <div class="form-body">
                @if ($errors->any() && !$errors->has('error'))
                    <div class="flash-message flash-error">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="m9 9 6 6m0-6-6 6"/>
                        </svg>
                        <div>
                            <strong>يرجى مراجعة البيانات التالية:</strong>
                            {{ $errors->first() }}
                        </div>
                    </div>
                @endif

                @if (session('status'))
                    <div class="flash-message flash-success">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="m8 12 3 3 5-6"/>
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('tables.update', $table->code) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-grid">
                        <div class="field">
                            <label for="name" class="field-label">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 8h18v3H3z"/>
                                    <path d="M5 11v9M19 11v9"/>
                                    <path d="M8 8V5h8v3"/>
                                </svg>
                                اسم الطاولة
                                <span class="required-mark">*</span>
                            </label>

                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name', $table->name) }}"
                                class="form-input"
                                placeholder="مثال: طاولة العائلة"
                                required
                                maxlength="255"
                                autocomplete="off"
                            >

                            @error('name')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="number" class="field-label">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="4" y="3" width="16" height="18" rx="2"/>
                                    <path d="M9 8h6M9 12h6M9 16h3"/>
                                </svg>
                                رقم الطاولة
                            </label>

                            <input
                                id="number"
                                type="number"
                                value="{{ old('number', $table->number) }}"
                                readonly
                                dir="ltr"
                                class="form-input"
                            >

                            <p class="field-help">
                                رقم ثابت لتحديد الطاولة داخل المطعم.
                            </p>

                            @error('number')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field field-full">
                            <label class="field-label" for="status">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="9"/>
                                    <path d="M12 7v5l3 2"/>
                                </svg>
                                حالة الطاولة
                            </label>

                            <div id="status-box" class="status-box">
                                <div class="status-info">
                                    <div class="status-icon">
                                        <svg id="status-icon-svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m5 12 4 4L19 6"/>
                                        </svg>
                                    </div>

                                    <div>
                                        <p class="status-title" id="status-title">
                                            {{ (bool) old('status', $table->status) ? 'الطاولة متاحة' : 'الطاولة مشغولة' }}
                                        </p>
                                        <p class="status-description" id="status-description">
                                            {{ (bool) old('status', $table->status)
                                                ? 'يمكن للزبائن الجلوس وبدء طلب جديد.'
                                                : 'الطاولة غير متاحة حاليًا للطلبات الجديدة.' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="status-control">
                                    <span id="status-label" class="status-label">
                                        {{ (bool) old('status', $table->status) ? 'متاحة' : 'مشغولة' }}
                                    </span>

                                    <label class="switch" for="status">
                                        <input
                                            type="checkbox"
                                            id="status"
                                            name="status"
                                            value="1"
                                            @checked((bool) old('status', $table->status))
                                            aria-label="تغيير حالة الطاولة"
                                        >
                                        <span class="switch-track"></span>
                                    </label>
                                </div>
                            </div>

                            @error('status')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field field-full">
                            <div class="code-panel">
                                <div class="code-top">
                                    <div class="code-heading">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="7" height="7" rx="1"/>
                                            <rect x="14" y="3" width="7" height="7" rx="1"/>
                                            <rect x="3" y="14" width="7" height="7" rx="1"/>
                                            <path d="M14 14h3v3h-3zM20 14v3M14 20h3M20 20v1"/>
                                        </svg>
                                        كود الطاولة
                                    </div>

                                    <span class="auto-badge">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            width="13" height="13">
                                            <path d="M12 15v2m-6 4h12a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2Z"/>
                                            <path d="M8 10V6a4 4 0 1 1 8 0v4"/>
                                        </svg>
                                        محمي تلقائيًا
                                    </span>
                                </div>

                                <input
                                    type="text"
                                    value="{{ $table->code }}"
                                    class="form-input code-input"
                                    readonly
                                    dir="ltr"
                                    aria-label="كود الطاولة"
                                >

                                <p class="field-help">
                                    يُستخدم هذا الكود للتعرّف على الطاولة وربطها برمز QR، ولا يمكن تعديله.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-save">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/>
                                <path d="M17 21v-8H7v8M7 3v5h8"/>
                            </svg>
                            حفظ التعديلات
                        </button>

                        <div class="action-group">
                            <a href="{{ route('tables.index') }}" class="btn btn-cancel">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m15 18-6-6 6-6"/>
                                </svg>
                                رجوع
                            </a>

                            <button type="button" id="delete-table-button" class="btn btn-delete">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18M8 6V4h8v2m-9 0 1 14h8l1-14M10 10v6m4-6v6"/>
                                </svg>
                                حذف الطاولة
                            </button>
                        </div>
                    </div>
                </form>

                <form
                    action="{{ route('tables.destroy', $table->code) }}"
                    method="POST"
                    id="delete-table-form"
                    class="hidden"
                >
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const statusSwitch = document.getElementById('status');
            const statusBox = document.getElementById('status-box');
            const statusLabel = document.getElementById('status-label');
            const statusTitle = document.getElementById('status-title');
            const statusDescription = document.getElementById('status-description');
            const statusIcon = document.getElementById('status-icon-svg');
            const deleteButton = document.getElementById('delete-table-button');
            const deleteForm = document.getElementById('delete-table-form');

            function updateStatusUI() {
                const isAvailable = statusSwitch.checked;

                statusLabel.textContent = isAvailable ? 'متاحة' : 'مشغولة';
                statusTitle.textContent = isAvailable ? 'الطاولة متاحة' : 'الطاولة مشغولة';
                statusDescription.textContent = isAvailable
                    ? 'يمكن للزبائن الجلوس وبدء طلب جديد.'
                    : 'الطاولة غير متاحة حاليًا للطلبات الجديدة.';

                statusBox.classList.toggle('is-available', isAvailable);
                statusBox.classList.toggle('is-busy', !isAvailable);

                statusIcon.innerHTML = isAvailable
                    ? '<path d="m5 12 4 4L19 6"/>'
                    : '<path d="M12 8v4m0 4h.01"/><circle cx="12" cy="12" r="9"/>';
            }

            statusSwitch.addEventListener('change', updateStatusUI);
            updateStatusUI();

            deleteButton.addEventListener('click', function () {
                if (confirm('هل أنت متأكد أنك تريد حذف هذه الطاولة؟ لا يمكن التراجع عن هذا الإجراء.')) {
                    deleteForm.submit();
                }
            });
        });
    </script>
@endsection

