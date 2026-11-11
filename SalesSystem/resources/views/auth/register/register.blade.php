@extends('layouts.app')

@section('contentTitle', 'Register')

@section('content')
<style>
    /* تنسيقات الحاوية الرئيسية */
    .register-container {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* كرت النموذج */
    .register-card {
    background-color: #ffffff;
    width: 100%;
    max-width: 460px;
    padding: 35px 30px;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05), 0 2px 6px rgba(0, 0, 0, 0.04);
    border: 1px solid #eaecf0;
    }

    /* الهيدر والعنوان */
    .register-header {
    text-align: center;
    margin-bottom: 24px;
    }

    .register-header h2 {
    margin: 0 0 6px 0;
    color: #101828;
    font-size: 22px;
    font-weight: 700;
    }

    .register-header p {
    margin: 0;
    color: #667085;
    font-size: 14px;
    }

    /* التنبيهات */
    .alert {
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 14px;
    margin-bottom: 20px;
    }

    .alert-status {
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
    margin-bottom: 16px;
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
    margin-top: 6px;
    }

    .btn-submit:hover {
    background-color: #4338ca;
    }

    .btn-submit:active {
    transform: scale(0.99);
    }

    /* الروابط السفلية */
    .register-footer-links {
    margin-top: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 14px;
    border-top: 1px solid #eaecf0;
    padding-top: 16px;
    }

    .register-footer-links a {
    color: #4f46e5;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
    }

    .register-footer-links a:hover {
    color: #4338ca;
    text-decoration: underline;
    }
</style>

<div class="register-container">
    <div class="register-card">

        <div class="register-header">
            <h2>Create an account</h2>
            <p>
                Please fill in the details to get started.
            </p>
        </div>

        {{-- رسائل الخطأ العامة --}}
        @if (session('err'))
        <div class="alert alert-error">
            {{ session('err') }}
        </div>
        @endif

        {{-- رسائل الحالة العامة --}}
        @if (session('status'))
        <div class="alert alert-status">
            {{ session('status') }}
        </div>
        @endif

        <form action="{{ route('register.store') }}" method="POST">
            @csrf

            <!-- Username -->
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g., Mohammed">
                @error('name')
                <p class="error-text">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="name@example.com">
                @error('email')
                <p class="error-text">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Phone -->
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="0912345678">
                @error('phone')
                <p class="error-text">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required minlength="8" maxlength="16" placeholder="••••••••">
                @error('password')
                <p class="error-text">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Telegram Chat ID -->
            <div class="form-group">
                <label>Telegram Chat ID</label>
                <input type="text" name="telegram_chat_id" value="{{ old('telegram_chat_id') }}" required placeholder="123456789">
                @error('telegram_chat_id')
                <p class="error-text">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Register</button>
        </form>

        <div class="register-footer-links">
            <a href="{{ route('formReset') }}">Forgot password?</a>
            <a href="{{ route('login') }}">Already have an account? Login</a>
        </div>

    </div>
</div>
@endsection