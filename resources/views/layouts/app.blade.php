<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Payment') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="payment service" name="description" />
    <meta content="payment" name="payment-hub" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.jpeg') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/selectize/css/selectize.bootstrap3.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/icons.min.css') }}">
</head>

<!-- body start -->
<body  data-layout-mode="detached" data-theme="light" data-topbar-color="dark" data-menu-position="fixed" data-leftbar-color="light" data-leftbar-size='default' data-sidebar-user='true'>


<!-- Begin page -->
<div id="wrapper">

    @include('layouts.navigation')


    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-fluid">
                {{ $slot }}

            </div> <!-- container -->

        </div> <!-- content -->



    </div>



</div>


<div class="rightbar-overlay"></div>

<script src="{{ asset('assets/js/vendor.min.js') }}"></script>
<script src="{{ asset('assets/js/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/js/selectize.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.bootstrap.wizard.min.js') }}"></script>
<script src="{{ asset('assets/js/form-wizard.init.js') }}"></script>
<script src="{{ asset('assets/js/app.min.js') }}"></script>



</body>
</html>
