<!DOCTYPE html>
<!--
    Created with ❤ by Yusuf Rehan
    Email: mail@yrehan.my.id

    Copyright {{ date('Y') }} All Rights Reserved.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('plugins')}}/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{asset('plugins')}}/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist')}}/css/adminlte.min.css">

    <style>
        body {
            position: relative;
            background-image: url("{{asset('dist')}}/img/auth-bg.jpg");
            background-size: cover;
            background-position: center center;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #000000;
            opacity: 0.5;
        }
    </style>
</head>

<body class="hold-transition @yield('body-class')">
    @yield('content')

    <!-- jQuery -->
    <script src="{{asset('plugins')}}/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('plugins')}}/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('dist')}}/js/adminlte.min.js"></script>

    @yield('script_extra')
</body>

</html>