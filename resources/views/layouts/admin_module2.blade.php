@extends('layouts.navigation')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    /* Admin Module 2 Colors */
    :root {
        --admin-primary: #6f42c1;   /* purple */
        --admin-accent: #ffce00;    /* yellow */
        --admin-bg: #f4f0ff;
    }

    body {
        background: var(--admin-bg);
    }

    .module-header {
        background: var(--admin-primary);
        color: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        margin-bottom: 25px;
    }

    .tooltip-icon {
        color: var(--admin-primary);
        cursor: pointer;
    }

    .card-custom {
        border-radius: 14px;
        border: none;
        box-shadow: 0 4px 14px rgba(0,0,0,0.08);
    }

    .btn-admin {
        background: var(--admin-primary);
        color: white;
        border-radius: 8px;
    }

    .btn-admin:hover {
        background: #59359c;
    }

    footer {
        background: var(--admin-primary);
        color: white;
        padding: 20px;
        margin-top: 40px;
        text-align: center;
        border-radius: 12px 12px 0 0;
    }
</style>

<div class="container mt-4">
    @yield('module-content')
</div>

<footer>
    <strong>Kiddyfy System • Module 2 — Course Management</strong><br>
    Confidentiality • Availability • Learnability Optimized
</footer>

@endsection
