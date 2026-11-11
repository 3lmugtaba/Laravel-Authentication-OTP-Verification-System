@extends('layouts.app')

@section('contentTitle', 'Reset password')

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

    /* التنبيهات */
    .alert {
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 14px;
    margin-bottom: 20px;
    }

    .alert-success {
    background-color: #ecfdf5;
    color: #065f46;
    border: 1px solid #a7f3d0;
    }

    .alert-error {
    background-color: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
    }

    /* حقول الإدخال */
    .form-group {
    margin-bottom: 20px;
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

    .error-text {
    margin: 6px 0 0 0;
    color: #d92d20;
    font-size: 13px;
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
            <h2>Reset your password</h2>
            <p>
                Enter your phone number to receive an OTP code.
            </p>
        </div>

        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif

        @if (session('err'))
        <div class="alert alert-error">
            {{ session('err') }}
        </div>
        @endif

        <form action="{{ route('reset') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="number">Phone number</label>
                <input
                type="text"
                id="number"
                name="number"
                value="{{ old('number') }}"
                placeholder="e.g. 0912345678"
                required
                >
                @error('number')
                <p class="error-text">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Send OTP</button>
        </form>

    </div>
</div>
@endsection