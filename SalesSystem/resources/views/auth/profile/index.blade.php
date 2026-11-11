@extends('layouts.app')

@section('contentTitle', 'My profile')

@section('content')
<style>
    /* تنسيقات الحاوية الرئيسية */
    .profile-container {
        min-height: 75vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* كرت الملف الشخصي */
    .profile-card {
        background-color: #ffffff;
        width: 100%;
        max-width: 480px;
        padding: 35px 30px;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05), 0 2px 6px rgba(0, 0, 0, 0.04);
        border: 1px solid #eaecf0;
    }

    /* الهيدر والعنوان */
    .profile-header {
        text-align: center;
        margin-bottom: 24px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eaecf0;
    }

    .profile-header h1 {
        margin: 0 0 6px 0;
        color: #101828;
        font-size: 24px;
        font-weight: 700;
    }

    .profile-header p {
        margin: 0;
        color: #667085;
        font-size: 14px;
    }

    /* تفاصيل البيانات */
    .profile-details {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .profile-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        background-color: #f9fafb;
        border-radius: 10px;
        border: 1px solid #f2f4f7;
    }

    .profile-label {
        font-size: 14px;
        font-weight: 600;
        color: #475467;
    }

    .profile-value {
        font-size: 14px;
        font-weight: 500;
        color: #1d2939;
    }

    /* شارات الحالة (Verified / Roles) */
    .badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-yes {
        background-color: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .badge-no {
        background-color: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .badge-role {
        background-color: #e0e7ff;
        color: #3730a3;
        border: 1px solid #c7d2fe;
    }
</style>

<div class="profile-container">
    <div class="profile-card">
        
        <div class="profile-header">
            <h1>Welcome, {{ $user->name }}</h1>
            <p>Here is your account information</p>
        </div>

        <div class="profile-details">
            <div class="profile-item">
                <span class="profile-label">Email</span>
                <span class="profile-value">{{ $user->email }}</span>
            </div>

            <div class="profile-item">
                <span class="profile-label">Phone</span>
                <span class="profile-value">{{ $user->phone }}</span>
            </div>

            <div class="profile-item">
                <span class="profile-label">Role</span>
                <span class="profile-value">
                    <span class="badge badge-role">{{ $user->role }}</span>
                </span>
            </div>

            <div class="profile-item">
                <span class="profile-label">Verified</span>
                <span class="profile-value">
                    @if($user->is_Verified)
                        <span class="badge badge-yes">Yes</span>
                    @else
                        <span class="badge badge-no">No</span>
                    @endif
                </span>
            </div>
        </div>

    </div>
</div>
@endsection
