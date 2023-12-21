@extends('layouts.front_layout')
@section('title', 'GIS Kos Haversine')

@section('navbar-left-item')
<li class="nav-item d-none d-sm-inline-block ml-2">
    <a href="{{ route('front.home') }}" class="btn btn-outline-primary"><i class="fas fa-search"></i> &nbsp; Cari Kos Terdekat</a>
</li>
@endsection

@section('header')
<div class="content-header">
    <div class="container-fluid">
        <div class="row text-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4>RUNGKAT</h4>
                        <h2 class="text-bold">RUMAH KOS DENGAN JARAK DEKAT</h2>
                    </div>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            @foreach ($top_boarding as $item)
                            <div class="col-sm-4 col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="text-bold">{{ $item->name }}</h4>
                                        <button type="button" class="btn btn-light btn-sm mb-3 active" role="button" aria-pressed="true">
                                            @if ($item->type == 0)
                                            KOS PUTRA &nbsp; <i class="fas fa-male"></i>
                                            @elseif ($item->type == 1)
                                            KOS PUTRI &nbsp; <i class="fas fa-female"></i>
                                            @elseif ($item->type == 2)
                                            KOS CAMPUR &nbsp; <i class="fas fa-venus-mars"></i>
                                            @endif
                                        </button>
                                        <br>Alamat</b> : {{ $item->address }}, Kec. {{ $item->district->name }}, Kota Malang, Jawa Timur, {{ $item->postal_code }}
                                        <br><a href="{{ route('front.detail', $item->id) }}" type='button' class='btn btn-sm btn-primary btn-block text-light mt-3'>Info Selengkapnya</a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-7 col-12">
                                <p class="mr-md-3">
                                    <b>Hello Malang Peps!</b> <br>
                                    Kalian lagi cari hunian sementara/rumah kos yang dekat dengan kampus? <br>
                                    Atau sedang bimbang dengan fasilitas rumah kos yang kurang? <br>
                                    Kalian bisa coba website ini untuk mencari Rumah Kos yang cocok dengan keinginan, lokasi terdekat dengan kampus, serta fasilitas yang dapat disesuaikan dengan kebutuhan. Let's Try!
                                </p>
                            </div>
                            <div class="col-md-5 col-12">
                                <img src="{{ asset('dist') }}/img/balai_kota_malang.jpg" class="img-fluid" alt="Kota Malang">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
@endsection

@section('style_extra')
@endsection

@section('script_extra')
@endsection