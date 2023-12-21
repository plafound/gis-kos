@extends('layouts.front_layout')
@section('title', 'GIS Kos Haversine')

@section('navbar-left-item')
<li class="nav-item d-none d-sm-inline-block ml-2">
    <button id="search-btn" class="btn btn-outline-primary"><i class="fas fa-search"></i> &nbsp; Cari</button>
</li>
<li class="nav-item d-none d-sm-inline-block ml-2">
    <button id="filter-btn" class="btn btn-outline-primary" data-toggle="modal" data-target="#filter-modal">
        <i class="fas fa-filter"></i> &nbsp; Filter
    </button>
</li>
<li class="nav-item d-none d-sm-inline-block ml-2">
    <button id="reset-filter-btn" class="btn btn-outline-danger d-none" onclick="resetMap()">
        <i class="fas fa-filter"></i> &nbsp; Reset
    </button>
</li>
@endsection

@section('header')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">RUMAH KOS DENGAN JARAK TERDEKAT </h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{route('front.home')}}">Home</a></li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
@endsection

@section('content')
    <div class="row">
        <div id="map-col" class="col-sm-12">
            <div class="map_container">
                <div class="map_area">
                    <div id="map" class="w-100 h-100"></div>
                </div>
            </div>
        </div>
        <div id="result-col" class="col-sm-3 d-none">
            <div class="card card-primary h-100 d-flex flex-column">
                <h5 class="card-header">Rumah Kos Terdekat</h5>
                <div class="card-body" id="haversine-card-body">
                    <div id="haversine-loading" class="text-center d-none">
                        <i class="fas fa-spinner fa-spin fa-3x"></i>
                    </div>
                    <div id="haversine-result"></div>
                </div>
              </div>
        </div>
    </div>
    <!-- /.row -->

    <div class="modal fade" id="filter-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Filter</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-sm-6">
                            <label for="kostType">Jenis Kos</label>
                            <select class="form-control" id="filter-type" name="type">
                                <option value="all">Semua</option>
                                <option value="0">Putra</option>
                                <option value="1">Putri</option>
                                <option value="2">Campur</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for="filter-district">Kecamatan</label>
                            <select class="form-control" id="filter-district" name="district">
                                <option value="all">Semua</option>
                                @foreach ($districts as $district)
                                    <option value="{{$district->id}}">{{$district->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <p class="h6 text-bold text-uppercase mt-4">Fasilitas Umum</p>
                    <div class="checkbox-wrapper">
                        @forelse ($general_facilities as $item)
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input filter-checkbox" type="checkbox" id="filter-{{ $item->id }}" value="{{ $item->id }}">
                                <label for="filter-{{ $item->id }}" class="custom-control-label font-weight-normal">{{ $item->name }}</label>
                            </div>
                        @empty
                            <li>Tidak ada filter untuk fasilitas umum</li>
                        @endforelse
                    </div>

                    <p class="h6 text-bold text-uppercase mt-4">Fasilitas Kamar</p>
                    <div class="checkbox-wrapper">
                        @forelse ($room_facilities as $item)
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input filter-checkbox" type="checkbox" id="filter-{{ $item->id }}" value="{{ $item->id }}">
                                <label for="filter-{{ $item->id }}" class="custom-control-label font-weight-normal">{{ $item->name }}</label>
                            </div>
                        @empty
                            <li>Tidak ada filter untuk fasilitas kamar</li>
                        @endforelse
                    </div>

                    <p class="h6 text-bold text-uppercase mt-4">Fasilitas Kamar Mandi</p>
                    <div class="checkbox-wrapper">
                        @forelse ($bathroom_facilities as $item)
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input filter-checkbox" type="checkbox" id="filter-{{ $item->id }}" value="{{ $item->id }}">
                                <label for="filter-{{ $item->id }}" class="custom-control-label font-weight-normal">{{ $item->name }}</label>
                            </div>
                        @empty
                            <li>Tidak ada filter untuk fasilitas kamar mandi</li>
                        @endforelse
                    </div>

                    <div class="form-row mt-3">
                        <div class="col-sm-6">
                            <label for="filter-min-price">Harga Minimal</label>
                            <input type="text" class="form-control numberInput" id="filter-min-price" placeholder="Masukkan harga minimal" value="0" autocomplete="off">
                        </div>
                        <div class="col-sm-6">
                            <label for="filter-min-price">Harga Maksimal</label>
                            <input type="text" class="form-control numberInput" id="filter-max-price" placeholder="Masukkan harga maksimal" value="0" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="apply-filter">Terapkan</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection

@section('style_extra')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin=""/>
    <link rel="stylesheet" href="https://leaflet.github.io/Leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://leaflet.github.io/Leaflet.markercluster/dist/MarkerCluster.Default.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        #map-col, #result-col {
            transition: all 0.5s ease-in-out;
        }

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

        .checkbox-wrapper {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(1, 1fr);
        }

        #haversine-card-body {
            height: calc(100vh - 500px);
            overflow-y: auto;
        }

        .marker-label {
            background-color: #ffffff;
            border: 1px solid #337ab7;
            border-radius: 5px;
            padding: 5px;
            font-size: 12px;
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

    <!-- Leaflet.SmoothMarkerBouncing -->
    <script type="text/javascript"
        src="https://cdn.jsdelivr.net/gh/hosuaby/Leaflet.SmoothMarkerBouncing@v3.0.2/dist/bundle.js"
        crossorigin="anonymous">
    </script>

    <script>
        const latitude = -7.946607322128791;
        const longitude = 112.61687003668041;

        let isSearchCardOpen = false;
        let facilityFilterApplied = [];

        let latLocalStorage = localStorage.getItem('latitude');
        let lngLocalStorage = localStorage.getItem('longitude');

        // Set default latitude and longitude
        if (latLocalStorage == null && lngLocalStorage == null) {
            localStorage.setItem('latitude', latitude);
            localStorage.setItem('longitude', longitude);
        }

        const map = L.map('map').setView([parseFloat(localStorage.getItem('latitude')), parseFloat(localStorage.getItem('longitude'))], 17);

        const tiles = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            maxZoom: 20,
            id: 'mapbox/streets-v12',
            tileSize: 512,
            zoomOffset: -1,
            accessToken: 'pk.eyJ1IjoieXJlaGFuMzIiLCJhIjoiY2t2eDhpaDV3MHM1dDJ1cGhqZjRpOWV0aiJ9.qX5pEapVlA3fFYfnW69YEw'
        }).addTo(map);

        // Data Boundary
        const myStyle = {
            "color": "#0665d0",
            "weight": 3,
            "opacity": 0.2,
            "fillOpacity": 0.1,
        };
        let geoJson = new L.GeoJSON.AJAX(["/geojson/kota_malang.geojson"], {style: myStyle}).addTo(map);

        // Custom Icon Marker
        var homeIcon = L.icon({
            iconUrl: '{{asset('dist')}}/img/markers/home_marker.png',
            iconSize: [40, 40]
        });

        var blueIcon = L.icon({
            iconUrl: '{{asset('dist')}}/img/markers/blue_marker.png',
            iconSize: [40, 40]
        });


        // Menambahkan point marker
        var markers = new L.markerClusterGroup();

        // Mendapatkan data dari API
        showMap();

        // CENTER TO VIEW ON POPUP
        map.on('popupopen', function(e) {
            var px = map.project(e.target._popup._latlng);
            px.y -= e.target._popup._container.clientHeight/2;
            map.panTo(map.unproject(px),{animate: true});
        });

        // search-btn click
        $('#search-btn').click(function(){
            if (!isSearchCardOpen) {
                // Set map-col to 9
                $('#map-col').removeClass('col-sm-12');
                $('#map-col').addClass('col-sm-9');

                // Show result-col
                setTimeout(function() {
                    $('#result-col').removeClass('d-none');
                    $('#result-col').addClass('animate__animated animate__fadeIn animate__faster');
                    isSearchCardOpen = true;

                    // Remove animation class
                    setTimeout(function() {
                        $('#result-col').removeClass('animate__animated animate__fadeIn animate__faster');
                    }, 500);
                }, 500);
            } else {
                // Hide result-col
                $('#result-col').addClass('animate__animated animate__fadeOut animate__faster');
                
                // Set map-col to 12
                setTimeout(function() {
                    $('#result-col').addClass('d-none');
                    $('#map-col').removeClass('col-sm-9');
                    $('#map-col').addClass('col-sm-12');
                    isSearchCardOpen = false;

                    // Remove animation class
                    $('#result-col').removeClass('animate__animated animate__fadeOut animate__faster');
                }, 500);
            }
        });

        // filter-checkbox click
        $(document).on('click', '.filter-checkbox', function () {
            let id = $(this).val();

            if ($(this).is(':checked')) {
                facilityFilterApplied.push(id);
            } else {
                facilityFilterApplied.splice($.inArray(id, facilityFilterApplied), 1);
            }
        });

        // apply_filter click
        $('#apply-filter').click(function () {
            let filterFacilityCount = facilityFilterApplied.length;
            let filterType = $('#filter-type').val();
            let filterDistrict = $('#filter-district').val();
            let filterMinPrice = $('#filter-min-price').val().replace(/\D/g, '');
            let filterMaxPrice = $('#filter-max-price').val().replace(/\D/g, '');

            if (filterFacilityCount > 0 || filterType != "all" || filterDistrict != "all" || filterMinPrice > 0 || filterMaxPrice > 0) {
                showMap(false, facilityFilterApplied, filterType, filterDistrict, filterMinPrice, filterMaxPrice);
            } else {
                showMap();
            }

            // Close modal
            $('#filter-modal').modal('hide');

            // show reset button
            $('#reset-filter-btn').removeClass('d-none');
        });

        function showMap(isGetAll = true, facilities = [], boardingType = "all", district = "all", minPrice = 0, maxPrice = 0) {
            removeMapLayer();
            getMyLocation();

            $.ajax({
                url: "{{ route('ajax.boarding_houses.get') }}",
                type: "POST",
                dataType: "json",
                data: {
                    _token: "{{ csrf_token() }}",
                    current_location: [
                        parseFloat(localStorage.getItem('latitude')),
                        parseFloat(localStorage.getItem('longitude'))
                    ],
                    get_all: isGetAll,
                    facilities: facilities,
                    type: boardingType,
                    district: district,
                    min_price: minPrice,
                    max_price: maxPrice
                },
                success:function(res){
                    console.log(res);
                    let resultHtml = '';

                    for (var i = 0; i < res['data']['boarding'].length; i++) {
                        let id = res['data']['boarding'][i]['id'];
                        let name = res['data']['boarding'][i]['name'];
                        let address = res['data']['boarding'][i]['address'];
                        let district = res['data']['boarding'][i]['district']['name'];
                        let postal_code = res['data']['boarding'][i]['postal_code'];
                        let latitude = res['data']['boarding'][i]['latitude'];
                        let longitude = res['data']['boarding'][i]['longitude'];
                        let phone_number = res['data']['boarding'][i]['phone_number'];
                        let type = res['data']['boarding'][i]['type'];
                        let detail_url ="{{ route('front.detail', ':id') }}";
                        detail_url = detail_url.replace(':id', id);

                        let type_str = "";
                        if (type == 0) {
                            type_str = `KOS PUTRA &nbsp; <i class="fas fa-male"></i>`;
                        } else if (type == 1) {
                            type_str = `KOS PUTRI &nbsp; <i class="fas fa-female"></i>`;
                        } else if (type == 2) {
                            type_str = `KOS CAMPUR &nbsp; <i class="fas fa-venus-mars"></i>`;
                        }

                        let type_badge = '<button type="button" class="btn btn-light btn-sm mb-3 active" role="button" aria-pressed="true">' + type_str + '</button>';
                        
                        let popUpData = "<h4>" + name + "</h4><hr>" +
                            type_badge +
                            "<br><b>Alamat</b> : "+ address + ", Kec. " + district + ", Kota Malang, Jawa Timur, " + postal_code +
                            "<br><a href='"+ detail_url +"' type='button' class='btn btn-sm btn-primary btn-block text-light mt-3'>More Info</a>";
                        
                        let marker = null;
                        marker = L.marker(new L.LatLng(latitude, longitude), {icon: blueIcon});

                        marker.bindTooltip(name, {
                            permanent: true,
                            direction: 'top',
                            className: 'marker-label',
                            offset: [0, -10],
                        }).openTooltip();

                        marker.bindPopup(popUpData);
                        markers.addLayer(marker);

                        // Assuming the data is stored in a variable called "haversineData"
                        let haversine_id = res['data']['haversine'][i]['id'];
                        let haversine_name = res['data']['haversine'][i]['name'];
                        let haversine_address = res['data']['haversine'][i]['address'];
                        let haversine_latitude = res['data']['haversine'][i]['latitude'];
                        let haversine_longitude = res['data']['haversine'][i]['longitude'];
                        let haversine_distance = res['data']['haversine'][i]['distance'];

                        // Adding each data to the resultHtml variable
                        resultHtml += `<div class="card">
                                            <div class="card-body">
                                                <h5 class="text-bold mb-0">` + haversine_name + `</h5>
                                                <p class="mb-2"><small><b> Jarak: </b> ` + haversine_distance.toFixed(2).toString().replace(".", ",") + ` KM</small></p>
                                                <p class="text-wrap text-secondary">
                                                    <small>` + haversine_address + `</small>
                                                </p>
                                                <button class="btn btn-sm btn-outline-primary btn-block popup-button" data-lat="` +haversine_latitude + `" data-long="` + haversine_longitude + `">
                                                    Lihat
                                                </button>
                                            </div>
                                        </div>`
                        // resultHtml += '<p>Name: ' + haversine_name + '</p><p>Address: ' + haversine_address + '</p><p>Distance: ' + haversine_distance + '</p><br><button class="popup-button" data-lat="'+ haversine_latitude +'" data-long="'+ haversine_longitude +'">OPEN</button><hr>';
                    }

                    // Adding the resultHtml to the div with id "haversine-result"
                    $('#haversine-result').html(resultHtml);
                },
            });
            map.addLayer(markers);
        }

        // open popup when .popup-button clicked
        $(document).on('click', '.popup-button', function () {
            let lat = $(this).data('lat');
            let long = $(this).data('long');

            // zoom the map first with the marker location and animate
            map.setView([lat, long], 19, {
                animate: true,
            });
            markers.eachLayer(function(layer) {
                if (layer.getLatLng().lat == lat && layer.getLatLng().lng == long) {
                    layer.bounce(2);
                }
            });
        });

        function resetMap() {
            // remove all markers
            removeMapLayer();
            getMyLocation();

            // empty facilityFilterApplied
            facilityFilterApplied = [];

            // reset filter form
            $('#filter-type').val('all').trigger('change');
            $('#filter-district').val('all').trigger('change');
            $('#filter-min-price').val('0');
            $('#filter-max-price').val('0');
            $('.filter-checkbox').prop('checked', false);

            // show all boarding houses
            showMap();

            // hide reset button
            $('#reset-filter-btn').addClass('d-none');
        }

        function getMyLocation() {
            const myLocation = L.marker(new L.LatLng(localStorage.getItem('latitude'), localStorage.getItem('longitude')), {
                icon: homeIcon,
                draggable: true,
            });
            let myLocationPopUp = "<h6 class='mb-1 text-bold'>Lokasi Saya</h6>";
            myLocation.bindPopup(myLocationPopUp);
            markers.addLayer(myLocation);

            myLocation.on('dragend', function(event) {
                localStorage.setItem('latitude', event.target._latlng.lat);
                localStorage.setItem('longitude', event.target._latlng.lng);
                
                resetMap();
            });
        }

        function removeMapLayer() {
            markers.clearLayers();
            map.removeLayer(markers);
        }

        $(document).ready(function() {
            // Initialize the map size
            updateMapSize();

            // Create a ResizeObserver instance
            const observer = new ResizeObserver(entries => {
                for (const entry of entries) {
                    // Call the updateMapSize function when the observed element's width changes
                    if (entry.target.id === 'map-col') {
                        updateMapSize();
                    }
                }
            });

            // Observe the #map-col element for changes to its width
            observer.observe(document.getElementById('map-col'));
        });

        // Update the map view center when the size of the container changes
        function updateMapSize() {
            map.invalidateSize();
            const mapCenter = map.getCenter();
            map.setView(mapCenter);
        }
    </script>
@endsection