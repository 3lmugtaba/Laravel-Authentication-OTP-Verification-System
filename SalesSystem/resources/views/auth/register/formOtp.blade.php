@extends('layouts.app')

@section('contentTitle', 'Password reset verification')

@section('content')
<style>
    /* تنسيقات الحاوية الرئيسية */
    .otp-container {
    min-height: 75vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* كرت النموذج */
    .otp-card {
    background-color: #ffffff;
    width: 100%;
    max-width: 420px;
    padding: 35px 30px;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05), 0 2px 6px rgba(0, 0, 0, 0.04);
    border: 1px solid #eaecf0;
    }

    /* الهيدر والعنوان */
    .otp-header {
    text-align: center;
    margin-bottom: 24px;
    }

    .otp-header h2 {
    margin: 0 0 8px 0;
    color: #101828;
    font-size: 22px;
    font-weight: 700;
    }

    .otp-header p {
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
    padding: 12px 14px;
    font-size: 18px;
    letter-spacing: 4px;
    text-align: center;
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
    text-align: center;
    }

    /* الأزرار */
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

    /* نموذج إعادة الإرسال */
    .resend-form {
    margin-top: 16px;
    text-align: center;
    }

    .btn-resend {
    background: none;
    border: none;
    color: #4f46e5;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    padding: 0;
    transition: color 0.2s;
    }

    .btn-resend:hover {
    color: #4338ca;
    text-decoration: underline;
    }
</style>

<div class="otp-container">
    <div class="otp-card">

        <div class="otp-header">
            <h2>Enter the password reset OTP</h2>
            <p>
                Please enter the 6-digit verification code sent to your phone.
            </p>
        </div>

        {{-- حلقة عرض رسائل الحالة، الخطأ، أو خطأ الإرسال --}}
        @foreach (['status', 'error', 'otp_delivery_error'] as $key)
        @if (session($key))
        <div class="alert {{ $key === 'status' ? 'alert-status' : 'alert-error' }}">
            {{ session($key) }}
        </div>
        @endif
        @endforeach

        {{-- نموذج التحقق من الرمز --}}
        <form action="{{ route('setNewPassword') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="resetOTP">Six-digit OTP</label>
                <input
                id="resetOTP"
                type="text"
                name="resetOTP"
                inputmode="numeric"
                pattern="[0-9]{6}"
                maxlength="6"
                required
                autocomplete="one-time-code"
                placeholder="------"
                >
                @error('resetOTP')
                <p class="error-text">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Verify OTP</button>
        </form>

        {{-- نموذج إعادة إرسال الرمز --}}
        <form class="resend-form" action="{{ route('reset.otp.resend') }}" method="POST">
            @csrf
            <button type="submit" class="btn-resend">Didn't receive the code? Resend OTP</button>
        </form>

    </div>
</div>
@endsection