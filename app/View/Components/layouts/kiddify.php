<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiddify - @yield('title')</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

    <style>
        body {
            background-color: #f5f5f5;
        }
        .kiddify-navbar {
            background-color: #5B0B60;
            padding: 10px 30px;
        }
        .kiddify-navbar .nav-link,
        .kiddify-navbar .welcome-text {
            color: white !important;
            font-weight: 500;
        }
        .yellow-btn {
            background-color: #FFB932;
            border: none;
            color: #000;
            padding: 5px 15px;
            border-radius: 5px;
        }
        .yellow-btn:hover {
            filter: brightness(90%);
        }
        .footer {
            background-color: #5B0B60;
            padding: 10px;
            color: white;
            text-align: center;
            margin-top: 50px;
        }
        .content-box {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
        }
    </style>

</head>

<body>

<!-- ================= NAVBAR ================= -->
<nav class="navbar kiddify-navbar navbar-expand-lg">
    <div class="container-fluid">

        <span class="navbar-brand text-white fw-bold">
            <img src="/images/kiddifylogo.png" width="40">
            Kiddify
        </span>

        <span class="welcome-text me-auto ms-3">
            Welcome, {{ Auth::user()->name ?? 'User' }} <br>
            <small>{{ date('D, d M Y') }}</small>
        </span>

        <ul class="navbar-nav ms-auto">

            <li class="nav-item"><a href="#" class="nav-link">Parents</a></li>

            <li class="nav-item">
                <a href="{{ route('courses.index') }}" class="nav-link yellow-btn text-dark me-2">Course</a>
            </li>

            <li class="nav-item"><a href="#" class="nav-link">Result</a></li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
                    <img src="/images/profile.jpg" width="32" class="rounded-circle">
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">My Profile</a></li>
                    <li><a class="dropdown-item" href="#">Logout</a></li>
                </ul>
            </li>

        </ul>
    </div>
</nav>

<!-- ================= PAGE CONTENT ================= -->
<div class="container mt-4 mb-5">
    @include('components.alerts')  
    @yield('content')
</div>

<!-- ================= FOOTER ================= -->
<div class="footer">
    Kiddify © Learn Smart, Play Hard
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>

</body>
</html>
