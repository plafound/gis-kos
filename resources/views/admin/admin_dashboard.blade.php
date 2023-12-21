@extends('layouts.admin_layout')
@section('title', 'Dashboard')

@section('header')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{route('front.home')}}">Dashboard</a></li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
@endsection

@section('content')

    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-6 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3 id="summary-room-total">0</h3>

                    <p>Total Kamar</p>
                </div>
                <div class="icon">
                    <i class="ion ion-android-star"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-6 col-12">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3 id="summary-room-filled">0</h3>
        
                    <p>Total Kamar Terisi</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <!-- /.row -->

@endsection

@section('style_extra')

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

@endsection

@section('script_extra')

    <!-- ChartJS -->
    <script src="{{asset('plugins')}}/chart.js/Chart.min.js"></script>

    <script>
        $(function() {
            // Summary
            $.ajax({
                url: "{{ route('ajax.summary.admin_dashboard') }}",
                type: "POST",
                dataType: "json",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function (res) {
                    if (!res.error) {
                        console.log(res.data);
                        $('#summary-admin-total').html(res.data.admin_total);
                        $('#summary-boarding-total').html(res.data.boarding_total);
                        $('#summary-room-total').html(res.data.room_total);
                        $('#summary-room-filled').html(res.data.room_filled);
                    }
                },
                error: function (res) {
                    console.log('Error:', res);
                }
            });
        })
    </script>

@endsection