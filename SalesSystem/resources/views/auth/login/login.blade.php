@extends('layouts.app')

@section('contentTitle', 'Login')

@section('content')
<style>
    /* تنسيقات الحاوية الرئيسية */
    .auth-container {
    min-height: 75vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* كرت النموذج */
    .auth-card {
    background-color: #ffffff;
    width: 100%;
    max-width: 420px;
    padding: 35px 30px;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05), 0 2px 6px rgba(0, 0, 0, 0.04);
    border: 1px solid #eaecf0;
    }

    /* الهيدر والعنوان */
    .auth-header {
    text-align: center;
    margin-bottom: 24px;
    }

    .auth-header h2 {
    margin: 0 0 8px 0;
    color: #101828;
    font-size: 22px;
    font-weight: 700;
    }

    .auth-header p {
    margin: 0;
    color: #667085;
    font-size: 14px;
    }

    /* التنبيهات والأخطاء */
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
    margin-top: 5px;
    }

    .btn-submit:hover {
    background-color: #4338ca;
    }

    .btn-submit:active {
    transform: scale(0.99);
    }

    /* الروابط السفلية */
    .auth-links {
    margin-top: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 14px;
    }

    .auth-links a {
    color: #4f46e5;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
    }

    .auth-links a:hover {
    color: #4338ca;
    text-decoration: underline;
    }
</style>

<div class="auth-container">
    <div class="auth-card">

        <div class="auth-header">
            <h2>Welcome back</h2>
            <p>
                Please enter your details to sign in.
            </p>
        </div>

        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif

        @if ($errors->any())
        <ul class="alert-error-list">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        @endif

        <form action="{{ route('login.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="name@example.com">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            </div>

            <button type="submit" class="btn-submit">Login</button>
        </form>

        <div class="auth-links">
            <a href="{{ route('formReset') }}">Forgot password?</a>
            <a href="{{ route('register') }}">Create account</a>
        </div>

    </div>
</div>
@endsection