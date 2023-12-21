@extends('layouts.front_layout')
@section('title', 'Petunjuk Rute | GIS Kos Haversine')

@section('header')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">
                    <b>Petunjuk Rute: </b> {{ $boarding_data->name }} &nbsp;
                    {{-- <span><a id="link-gmaps" href="" target="_blank" data-toggle="tooltip" data-placement="top" title="Buka rute pada Google Maps"><i class="fas fa-external-link-alt"></i></a></span> --}}
                </h4>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('front.home')}}">Home</a></li>
                    <li class="breadcrumb-item active">Petunjuk Rute</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
@endsection

@section('content')
    <div class="row">
        <div id="map-col" class="col-12">
            <div class="map_container">
                <div class="map_area">
                    <div id="map" class="w-100 h-100"></div>
                </div>
            </div>
        </div>
        </div>
    </div>
    <!-- /.row -->
@endsection

@section('style_extra')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin=""/>
    <link rel="stylesheet" href="https://leaflet.github.io/Leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://leaflet.github.io/Leaflet.markercluster/dist/MarkerCluster.Default.css" />

    <!-- Routing Machine -->
    <link rel="stylesheet" href="{{asset('plugins')}}/routing-machine/leaflet-routing-machine.css" />
    <!-- Legend -->
    <link rel="stylesheet" href="{{asset('plugins')}}/leaflet-legend/leaflet.legend.css">

    <style>
        .map_container{
            height: calc(100vh - 105.547px - 90px);
            width: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .map_area{
            width: 100%;
            height: 50px;
            flex: 1;
        }
    </style>
@endsection

@section('script_extra')
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
            integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
            crossorigin="">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/leaflet.markercluster.js"
            integrity="sha512-OFs3W4DIZ5ZkrDhBFtsCP6JXtMEDGmhl0QPlmWYBJay40TT1n3gt2Xuw8Pf/iezgW9CdabjkNChRqozl/YADmg=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer">
        </script>
        <script src="{{asset('dist')}}/js/leaflet.ajax.js"></script>
        <!-- Routing Machine -->
        <script src="{{asset('plugins')}}/routing-machine/leaflet-routing-machine.js"></script>
        <!-- Legend -->
        <script src="{{asset('plugins')}}/leaflet-legend/leaflet.legend.js"></script>

        <script>
            const latitude = -7.946607322128791;
            const longitude = 112.61687003668041;

            let latLocalStorage = localStorage.getItem('latitude');
            let lngLocalStorage = localStorage.getItem('longitude');

            // Set default latitude and longitude
            if (latLocalStorage == null && lngLocalStorage == null) {
                localStorage.setItem('latitude', latitude);
                localStorage.setItem('longitude', longitude);
            }

            const centerLatLong = L.latLng(parseFloat(localStorage.getItem('latitude')), parseFloat(localStorage.getItem('longitude')));

            $("#link-gmaps")
                .attr(
                    "href",
                    "https://www.google.com/maps/dir/?api=1&origin="+ localStorage.getItem('latitude') +","+ localStorage.getItem('longitude') +
                    "&destination={{$boarding_data->latitude}},{{$boarding_data->longitude}}"
                );

            const tiles = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                maxZoom: 17,
                id: 'mapbox/navigation-night-v1',
                tileSize: 512,
                zoomOffset: -1,
                accessToken: 'pk.eyJ1IjoieXJlaGFuMzIiLCJhIjoiY2t2eDhpaDV3MHM1dDJ1cGhqZjRpOWV0aiJ9.qX5pEapVlA3fFYfnW69YEw'
            });

            const map = L.map('map', {
                center: centerLatLong,
                zoom: 16,
                layers: [tiles]
            });

            // Marker
            let currentLocationMarker = L.icon({
                iconUrl: '{{asset('dist')}}/img/markers/home_marker_dark.png',
                iconSize: [40, 40]
            });
            let destinationLocationMarker = L.icon({
                iconUrl: '{{asset('dist')}}/img/markers/blue_marker_dark.png',
                iconSize: [40, 40]
            });

            const legend = L.control.Legend({
                position: "bottomleft",
                collapsed: false,
                symbolWidth: 24,
                column: 2,
                legends: [{
                    label: "Lokasi Anda",
                    type: "image",
                    url: '{{asset('dist')}}/img/markers/home_marker_dark.png',
                } , {
                    label: "Kos Tujuan",
                    type: "image",
                    url: '{{asset('dist')}}/img/markers/blue_marker_dark.png',
                }]
            }).addTo(map);

            L.Routing.control({
                waypoints: [
                    L.latLng(localStorage.getItem('latitude'), localStorage.getItem('longitude')),
                    L.latLng(parseFloat("{{ $boarding_data->latitude }}"), parseFloat("{{ $boarding_data->longitude }}"))
                ],
                createMarker: function(i, wp, nWps) {
                    if (i === 0) {
                        const currentMarker = L.marker(wp.latLng, {
                            icon: currentLocationMarker,
                            draggable: true
                        });

                        currentMarker.on('dragend', function(event) {
                            localStorage.setItem('latitude', event.target._latlng.lat);
                            localStorage.setItem('longitude', event.target._latlng.lng);
                        });

                        return currentMarker;
                    } else if (i === nWps-1) {
                        return L.marker(wp.latLng, {icon: destinationLocationMarker });
                    }
                },
                fitSelectedRoutes: true,
                draggableWaypoints: true
            }).addTo(map);
        </script>
@endsection