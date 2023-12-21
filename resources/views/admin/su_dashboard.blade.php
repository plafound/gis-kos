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
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3 id="summary-admin-total">0</h3>

                    <p>Total Pemilik</p>
                </div>
                <div class="icon">
                    <i class="ion ion-android-people"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3 id="summary-boarding-total">0</h3>

                    <p>Total Kos</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
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
        <div class="col-lg-3 col-6">
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

    <div class="row">
        <div class="col-md-6">
            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Jumlah Kos Setiap Kecamatan</h3>
                </div>
                <div class="card-body">
                    <div style="height: 350px; width: 100%;"><canvas id="boardingHouseCount"></canvas></div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>

        <div class="col-md-6">
            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Jumlah Kamar Terisi Terbanyak</h3>
                </div>
                <div class="card-body">
                    <div style="height: 350px; width: 100%;"><canvas id="roomFilled"></canvas></div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>

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

            // Chart Boarding House Count
            $.ajax({
                url: "{{ route('ajax.chart.boarding_house_total') }}",
                type: "POST",
                dataType: "json",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function (res) {
                    if (!res.error) {
                        let data = res.data;

                        let labels = data.map(item => item.name);
                        let values = data.map(item => item.total);

                        let max_value = Math.max(...values);

                        let boardingHouseData = {
                            labels : labels,
                            datasets: [
                                {
                                    label : 'Total Kos',
                                    backgroundColor : 'rgba(60,141,188,0.9)',
                                    borderColor : 'rgba(60,141,188,0.8)',
                                    pointRadius : false,
                                    pointColor : '#3b8bba',
                                    pointStrokeColor : 'rgba(60,141,188,1)',
                                    pointHighlightFill : '#fff',
                                    pointHighlightStroke: 'rgba(60,141,188,1)',
                                    data : values
                                },
                            ]
                        }

                        let boardingHouseCanvas = $('#boardingHouseCount').get(0).getContext('2d')
                        let barChartData = $.extend(true, {}, boardingHouseData)
                        
                        let dataset_1 = boardingHouseData.datasets[0]
                        barChartData.datasets[0] = dataset_1

                        let barChartOptions = {
                            responsive              : true,
                            maintainAspectRatio     : false,
                            datasetFill             : false,
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        min: 0,
                                        max: max_value + 1,
                                        stepSize: 1
                                    }
                                }]
                            }
                        }

                        new Chart(boardingHouseCanvas, {
                            type: 'bar',
                            data: barChartData,
                            options: barChartOptions
                        })
                    }
                },
                error: function (res) {
                    console.log('Error:', res);
                }
            });

            // Chart Room Filled
            $.ajax({
                url: "{{ route('ajax.chart.room_filled') }}",
                type: "POST",
                dataType: "json",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function (res) {
                    if (!res.error) {
                        let data = res.data;

                        let labels = data.map(item => item.name);
                        let values = data.map(item => item.filled_capacity);

                        let donutChartCanvas = $('#roomFilled').get(0).getContext('2d')
                        let donutData = {
                            labels: labels,
                            datasets: [
                                {
                                    data: values,
                                    backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc'],
                                }
                            ]
                        }
                        let donutOptions = {
                            maintainAspectRatio : false,
                            responsive : true,
                        }
                        
                        new Chart(donutChartCanvas, {
                            type: 'doughnut',
                            data: donutData,
                            options: donutOptions
                        })
                    }
                },
                error: function (res) {
                    console.log('Error:', res);
                }
            });
        })
    </script>

@endsection