@extends('layouts.app')

@section('contentTitle', 'Set new password')

@section('content')
<style>
    /* تنسيقات الحاوية الرئيسية */
    .reset-container {
    min-height: 75vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* كرت النموذج */
    .reset-card {
    background-color: #ffffff;
    width: 100%;
    max-width: 420px;
    padding: 35px 30px;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05), 0 2px 6px rgba(0, 0, 0, 0.04);
    border: 1px solid #eaecf0;
    }

    /* الهيدر والعنوان */
    .reset-header {
    text-align: center;
    margin-bottom: 24px;
    }

    .reset-header h2 {
    margin: 0 0 8px 0;
    color: #101828;
    font-size: 22px;
    font-weight: 700;
    }

    .reset-header p {
    margin: 0;
    color: #667085;
    font-size: 14px;
    line-height: 1.5;
    }

    /* قائمة الأخطاء */
    .alert-error-list {
    background-color: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
    padding: 12px 16px 12px 32px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
    }

    .alert-error-list li {
    margin-bottom: 4px;
    }

    .alert-error-list li:last-child {
    margin-bottom: 0;
    }

    /* حقول الإدخال */
    .form-group {
    margin-bottom: 18px;
    }

    .form-group label {
    display: block;
    margin-bottom: 6px;
    font-size: 14px;
    font-weight: 600;
    color: #344054;
    }

    .form-group input {
    width: 100%;
    padding: 10px 14px;
    font-size: 15px;
    border: 1px solid #d0d5dd;
    border-radius: 8px;
    outline: none;
    box-sizing: border-box;
    transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-group input:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    /* زر الإرسال */
    .btn-submit {
    width: 100%;
    padding: 11px 16px;
    background-color: #4f46e5;
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s;
    margin-top: 6px;
    }

    .btn-submit:hover {
    background-color: #4338ca;
    }

    .btn-submit:active {
    transform: scale(0.99);
    }
</style>

<div class="reset-container">
    <div class="reset-card">

        <div class="reset-header">
            <h2>Set a new password</h2>
            <p>
                Please enter your new password below.
            </p>
        </div>

        {{-- عرض قائمة الأخطاء إن وجدت --}}
        @if ($errors->any())
        <ul class="alert-error-list">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        @endif

        <form action="{{ route('checkPassword') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="newPassword">New password</label>
                <input
                id="newPassword"
                type="password"
                name="newPassword"
                minlength="8"
                maxlength="64"
                required
                autocomplete="new-password"
                placeholder="••••••••"
                >
            </div>

            <div class="form-group">
                <label for="newPassword_confirmation">Confirm new password</label>
                <input
                id="newPassword_confirmation"
                type="password"
                name="newPassword_confirmation"
                minlength="8"
                maxlength="64"
                required
                autocomplete="new-password"
                placeholder="••••••••"
                >
            </div>

            <button type="submit" class="btn-submit">Change password</button>
        </form>

    </div>
</div>
@endsection