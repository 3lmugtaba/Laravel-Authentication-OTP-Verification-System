@extends('layouts.app')

@section('contentTitle', 'Dashboard')

@section('content')
<style>
    /* تنسيقات الحاوية الرئيسية للداشبورد */
    .dashboard-container {
    min-height: 75vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* كرت الداشبورد الرئيسي */
    .dashboard-card {
    background-color: #ffffff;
    width: 100%;
    max-width: 500px;
    padding: 40px 30px;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05), 0 2px 6px rgba(0, 0, 0, 0.04);
    border: 1px solid #eaecf0;
    text-align: center;
    }

    /* الترحيب والعنوان */
    .dashboard-card h1 {
    margin: 0 0 10px 0;
    color: #101828;
    font-size: 26px;
    font-weight: 700;
    }

    /* بطاقة احصائية مبسطة لعدد المنتجات */
    .stats-box {
    background-color: #f8f9fc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    margin: 25px 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    }

    .stats-box p {
    margin: 0 0 5px 0;
    color: #475569;
    font-size: 14px;
    font-weight: 600;
    }

    .stats-number {
    font-size: 32px;
    font-weight: 700;
    color: #4f46e5;
    margin: 0;
    }

    /* زر تسجيل الخروج */
    .logout-form {
    margin-top: 20px;
    }

    .btn-logout {
    width: 100%;
    padding: 11px 16px;
    background-color: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s, color 0.2s;
    }

    .btn-logout:hover {
    background-color: #fee2e2;
    color: #7f1d1d;
    }

    .btn-logout:active {
    transform: scale(0.99);
    }
</style>

<div class="dashboard-container">
    <div class="dashboard-card">

        <h1>أهلاً بيك في الداشبورد</h1>
        <p style="color: #667085; font-size: 14px; margin-top: 0;">
            نظام إدارة المخزون والمبيعات (Elzaki Store)
        </p>

        <!-- بطاقة عدد المنتجات (يمكنك وضع متغير الـ Controller هنا لاحقاً مثل {{ $productsCount ?? 0 }}) -->
        <div class="stats-box">
            <p>
                عدد المنتجات
            </p>
            <p class="stats-number">
                {{ $productsCount ?? '0' }}
            </p>
        </div>

        <!-- نموذج تسجيل الخروج -->
        <form class="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>

    </div>
</div>
@endsection