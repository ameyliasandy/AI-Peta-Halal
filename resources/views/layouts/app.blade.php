<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
    :root{
        --g:#16a34a;
        --gd:#15803d;
        --gl:#dcfce7;

        --s1:#f3f4f6;
        --s2:#e5e7eb;
        --s4:#9ca3af;
        --s6:#4b5563;
        --s7:#374151;
        --s9:#111827;
    }


    /* BUTTON GLOBAL */
    .btn{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:7px;

        padding:9px 16px;

        border-radius:10px;

        font-size:13px;
        font-weight:700;

        text-decoration:none;

        cursor:pointer;

        transition:.2s ease;
    }


    /* HIJAU */
    .btn-primary{

        background:#16a34a;
        color:white;

        border:1px solid #16a34a;

    }


    .btn-primary:hover{

        background:#15803d;
        color:white;

    }



    /* OUTLINE */
    .btn-outline{

        background:white;

        color:#16a34a;

        border:1px solid #16a34a;

    }


    .btn-outline:hover{

        background:#dcfce7;

    }

    </style>


    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen overflow-x-hidden">

    @yield('content')

</body>
</html>