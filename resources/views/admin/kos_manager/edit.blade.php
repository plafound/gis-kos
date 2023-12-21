@extends('layouts.admin_layout')
@section('title', 'Rumah Kos')

@section('header')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Rumah Kos</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin.kos_manager.list')}}">Rumah Kos</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
@endsection

@section('content')
<!-- Default box -->
<div class="card">
    <form id="kostForm">
        @csrf
        <div class="card-header">
            <h3 class="card-title">Data Kos</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-sm btn-outline-success" id="facilitiesBtn">
                    <i class="fas fa-hand-holding-heart"></i> &nbsp; Fasilitas
                </button>
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-check"></i> &nbsp; Update
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="kostName">Nama</label>
                <input type="text" class="form-control" id="kostName" name="name" placeholder="Masukkan nama kos" autocomplete="off" autofocus>
            </div>
            <div class="form-row mb-3">
                <div class="col">
                    <label for="kostAddress">Alamat</label>
                    <input type="text" class="form-control" id="kostAddress" name="address" placeholder="Masukkan alamat lengkap" autocomplete="off">
                </div>
                <div class="col">
                    <label for="kostDistrict">Kecamatan</label>
                    <select class="form-control" id="kostDistrict" name="district_id">
                        <option value="">Pilih Salat Satu</option>
                        @foreach ($districts as $district)
                            <option value="{{$district->id}}">{{$district->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <label for="kostPostalCode">Kode Pos</label>
                    <input type="text" class="form-control" id="kostPostalCode" name="postal_code" placeholder="Masukkan kode pos" autocomplete="off">
                </div>
            </div>
            <div class="form-row mb-3">
                <div class="col">
                    <label for="kostLatitude">Latitude</label>
                    <input type="text" class="form-control" id="kostLatitude" name="latitude" placeholder="Masukkan latitude" autocomplete="off">
                </div>
                <div class="col">
                    <label for="kostLongitude">Longitude</label>
                    <input type="text" class="form-control" id="kostLongitude" name="longitude" placeholder="Masukkan longitude" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label for="kostPhoneNumber">Telepon</label>
                <input type="text" class="form-control" id="kostPhoneNumber" name="phone_number" placeholder="Masukkan nomor telepon" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="kostType">Jenis Kos</label>
                <select class="form-control" id="kostType" name="type">
                    <option value="0">Putra</option>
                    <option value="1">Putri</option>
                    <option value="2">Campur</option>
                </select>
            </div>
            <div class="form-group">
                <label for="kostPrice">Harga</label>
                <input type="text" class="form-control numberInput" id="kostPrice" name="price" placeholder="Masukkan harga sewa kos" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="kostCapacity">Total Kamar</label>
                <input type="text" class="form-control numberInput" id="kostCapacity" name="capacity" placeholder="Masukkan total kamar yang tersedia" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="kostFilledCapacity">Kamar Terisi</label>
                <input type="text" class="form-control numberInput" id="kostFilledCapacity" name="filled_capacity" placeholder="Masukkan kamar yang telah terisi" autocomplete="off">
            </div>
        </div>
        <!-- /.card-body -->
    </form>
</div>
<!-- /.card -->

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Foto Kos</h3>
    </div>
    <div class="card-body">
        <table id="dtCoverImages" class="table table-bordered table-hover" width="100%">

        </table>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

<!-- Modal -->
<div class="modal fade" id="facilitiesModal" tabindex="-1" aria-labelledby="facilitiesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="facilityForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="kostModalLabel">Fasilitas Kos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-primary card-outline card-outline-tabs">
                                <div class="card-header p-0 border-bottom-0">
                                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="bedroom-tab" data-toggle="pill" href="#bedroom" role="tab" aria-controls="bedroom" aria-selected="true">Kamar</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="bathroom-tab" data-toggle="pill" href="#bathroom" role="tab" aria-controls="bathroom" aria-selected="false">Kamar Mandi</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="general-tab" data-toggle="pill" href="#general" role="tab" aria-controls="general" aria-selected="false">Umum</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-four-tabContent">
                                        <div class="tab-pane fade show active" id="bedroom" role="tabpanel" aria-labelledby="bedroom-tab">
                                            <div class="form-group">
                                                @foreach ($bedroom as $item)
                                                    <div class="form-check">
                                                        <input class="form-check-input facility-checkbox" type="checkbox" id="facility_{{ $item->id }}_check" value="{{ $item->id }}">
                                                        <label class="form-check-label">{{ $item->name }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="bathroom" role="tabpanel" aria-labelledby="bathroom-tab">
                                            <div class="form-group">
                                                @foreach ($bathroom as $item)
                                                    <div class="form-check">
                                                        <input class="form-check-input facility-checkbox" type="checkbox" id="facility_{{ $item->id }}_check" value="{{ $item->id }}">
                                                        <label class="form-check-label">{{ $item->name }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="general" role="tabpanel" aria-labelledby="general-tab">
                                            <div class="form-group">
                                                @foreach ($general as $item)
                                                    <div class="form-check">
                                                        <input class="form-check-input facility-checkbox" type="checkbox" id="facility_{{ $item->id }}_check" value="{{ $item->id }}">
                                                        <label class="form-check-label">{{ $item->name }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="uploadImageModal" tabindex="-1" aria-labelledby="uploadImageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="imageUploaderForm">
                <input type="hidden" id="image_boarding_id" name="boarding_id" value="{{ request()->id }}">
                <input type="hidden" id="image_upload_type" name="upload_type" value="">
                <input type="hidden" id="image_sequence" name="sequence" value="">
                <div class="modal-header">
                    <h5 class="modal-title" id="kostModalLabel">Upload Foto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">File Foto</label>
                        <div class="custom-file">
                              <input type="file" name="image_file" id="image_file" class="custom-file-input" required>
                              <label class="custom-file-label" for="image_file">Pilih Foto</label>
                          </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="btn-image-upload">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('style_extra')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('plugins')}}/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet"
        href="{{asset('plugins')}}/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{asset('plugins')}}/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('plugins')}}/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('plugins')}}/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{asset('plugins')}}/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
@endsection

@section('script_extra')
    <!-- DataTables  & Plugins -->
    <script src="{{asset('plugins')}}/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('plugins')}}/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{asset('plugins')}}/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{asset('plugins')}}/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <!-- Select2 -->
    <script src="{{asset('plugins')}}/select2/js/select2.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="{{asset('plugins')}}/sweetalert2/sweetalert2.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="{{asset('plugins')}}/bs-custom-file-input/bs-custom-file-input.min.js"></script>

    <script>
        $(document).ready(function() {
            let id = "{{ request()->id }}";
            let url = "{{ route('ajax.kos_manager.get', ':id') }}";
            url = url.replace(':id', id);

            let table = null;

            const queryParams = new Proxy(new URLSearchParams(window.location.search), {
                get: (searchParams, prop) => searchParams.get(prop),
            });

            $(function () {
                bsCustomFileInput.init();
            });

            let initFacilities = [];
            let newFacilities = [];
            let oldFacilities = [];

            // Select2 #kostDistrict
            $('#kostDistrict').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Kecamatan',
            });

            // Show Data
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (res) {
                    console.log('Success:', res);
                    if (!res.error) {
                        $('#kostId').val(res.data.id);
                        $('#kostName').val(res.data.name);
                        $('#kostAddress').val(res.data.address);
                        $('#kostDistrict').val(res.data.district_id).change();
                        $('#kostPostalCode').val(res.data.postal_code);
                        $('#kostLatitude').val(res.data.latitude);
                        $('#kostLongitude').val(res.data.longitude);
                        $('#kostPhoneNumber').val(res.data.phone_number);
                        $('#kostType').val(res.data.type).change();
                        $('#kostCapacity').val(res.data.capacity.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
                        $('#kostFilledCapacity').val(res.data.filled_capacity.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
                        $('#kostPrice').val(res.data.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
                    }
                },
                error: function (res) {
                    console.log('Error:', res);

                    let no = 1;
                    let errorListHtml = '';
                    let errorList = res.responseJSON.data;
                    $.each(errorList, function(key, values) {
                        $.each(values, function(key, value) {
                            errorListHtml += no + '. ' + value + '<br>';
                            no++;
                        });
                    });

                    Swal.fire({
                        title: res.responseJSON.message,
                        html: errorListHtml,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });

            // Show cover images
            table = $("#dtCoverImages").DataTable({
                processing: true,
                serverSide: true,
                pageLength: 5,
                lengthMenu: [
                    [5, 10, -1],
                    [5, 10, 'All'],
                ],
                ajax: {
                    url: "{{ route('dt-ajax.kos_manager.cover_images') }}",
                    type: "GET",
                    dataType: "json",
                    data: function ( d ) {
                        d._token = "{{ csrf_token() }}",
                        d.id = id
                    },
                },
                columns: [
                    {
                        title: "#",
                        data: "DT_RowIndex",
                        orderable: false,
                        searchable: false,
                        width: "5%",
                    },
                    {
                        title: "Preview",
                        data: "image_path",
                        name: "image_path",
                        render: function (data, type, row) {
                            if (data == null) {
                                return `<img src="{{asset('dist')}}/img/default_image.jpg" class="img-fluid" style="max-width: 150px;">`;
                            } else {
                                return `<img src="{{ request()->getSchemeAndHttpHost() }}/` + data + `" class="img-fluid" style="max-width: 150px;">`;
                            }
                        },
                    },
                    {
                        title: "Action",
                        data: "action",
                        name: "action",
                        className: "text-center",
                        orderable: false,
                        searchable: false,
                        width: "10%",
                    },

                ],
                searching: false,
            });

            getFacilities();

            if (queryParams.state == 'first_create') {
                $('#facilitiesModal').modal('show');
            }

            $('#facilitiesBtn').click(function () {
                $('#facilitiesModal').modal('show');
            });

            // Update Data
            $('#kostForm').submit(function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                let id = "{{ request()->id }}";

                let url = "{{ route('ajax.kos_manager.update', ':id') }}";
                url = url.replace(':id', id);

                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (res) {
                        if (!res.error) {
                            Swal.fire({
                                title: 'Sukses!',
                                text: res.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                $('#kostName').focus();
                            });

                        }
                    },
                    error: function (res) {
                        console.log('Error:', res);

                        let no = 1;
                        let errorListHtml = '';
                        let errorList = res.responseJSON.data;
                        $.each(errorList, function(key, values) {
                            $.each(values, function(key, value) {
                                errorListHtml += no + '. ' + value + '<br>';
                                no++;
                            });
                        });

                        Swal.fire({
                            title: res.responseJSON.message,
                            html: errorListHtml,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            // Edit cover image
            $(document).on('click', '.edit-cover', function() {
                let sequence = $(this).data('sequence');

                $('#image_upload_type').val('update');
                $('#image_sequence').val(sequence);

                $('#uploadImageModal').modal('show');
            });

            // Delete cover image
            $(document).on('click', '.delete-cover', function() {
                let id = $(this).data('id');
                let url = "{{ route('ajax.kos_manager.delete_cover_image', ':boarding_img_id') }}";
                url = url.replace(':boarding_img_id', id);

                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Apakah anda yakin ingin menghapus gambar ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yakin',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: "POST",
                            dataType: "json",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function (res) {
                                if (!res.error) {
                                    Swal.fire({
                                        title: 'Sukses!',
                                        text: res.message,
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    });

                                    table.ajax.reload(null, false);
                                }
                            },
                            error: function (res) {
                                console.log('Error:', res);

                                let no = 1;
                                let errorListHtml = '';
                                let errorList = res.responseJSON.data;
                                $.each(errorList, function(key, values) {
                                    $.each(values, function(key, value) {
                                        errorListHtml += no + '. ' + value + '<br>';
                                        no++;
                                    });
                                });

                                Swal.fire({
                                    title: res.responseJSON.message,
                                    html: errorListHtml,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });

            // Upload image
            $('#uploadImageModal').on('submit', '#imageUploaderForm', function(e) {
                e.preventDefault();
                setLoadingButton('#btn-image-upload', 'Upload');

                let formData = new FormData(this);
                formData.append('_token', "{{ csrf_token() }}");

                $.ajax({
                    type: "POST",
                    url: "{{route('ajax.kos_manager.images.upload')}}",
                    dataType: "json",
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (res) {
                        if (!res.error) {
                            resetLoadingButton('#btn-image-upload', 'Upload');
                            table.ajax.reload(null, false);
                            
                            Swal.fire({
                                title: 'Berhasil!',
                                text: res.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                $('#uploadImageModal').modal('hide');
                                $('#imageUploaderForm').trigger('reset');
                            });
                        }
                    },
                    error: function (res) {
                        resetLoadingButton('#btn-image-upload', 'Upload');
                        console.log('Error:', res);

                        var errorListHtml = '';
                        var errorList = res.responseJSON.data;
                        $.each(errorList, function(key, values) {
                            $.each(values, function(key, value) {
                                errorListHtml += value + '<br>';
                            });
                        });

                        Swal.fire({
                            title: res.responseJSON.message,
                            html: errorListHtml,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        })
                    }
                });
            });

            $(document).on('click', '.delete', function() {
                let id = $(this).data('id');

                let url = "{{ route('ajax.kos_manager.delete', ':id') }}";
                url = url.replace(':id', id);

                // show sweetalert confirm
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Apakah anda yakin ingin menghapus data ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yakin',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: "POST",
                            dataType: "json",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function (res) {
                                if (!res.error) {
                                    Swal.fire({
                                        title: 'Sukses!',
                                        text: res.message,
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    });

                                    table.ajax.reload(null, false);
                                }
                            },
                            error: function (res) {
                                console.log('Error:', res);

                                let no = 1;
                                let errorListHtml = '';
                                let errorList = res.responseJSON.data;
                                $.each(errorList, function(key, values) {
                                    $.each(values, function(key, value) {
                                        errorListHtml += no + '. ' + value + '<br>';
                                        no++;
                                    });
                                });

                                Swal.fire({
                                    title: res.responseJSON.message,
                                    html: errorListHtml,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });

            // Facilities Data
            function getFacilities() {
                let getFacilitiesURL = "{{ route('ajax.boarding_houses.get_facilities', ':id') }}";
                getFacilitiesURL = getFacilitiesURL.replace(':id', id);
                $.ajax({
                    url: getFacilitiesURL,
                    type: "GET",
                    dataType: "json",
                    success: function (res) {
                        if (!res.error) {
                            oldFacilities = [];
                            newFacilities = [];

                            $.each(res.data, function(key, value) {
                                oldFacilities.push(value.facility_id);
                                newFacilities.push(value.facility_id);

                                $('#facility_' + value.facility_id + '_check').prop('checked', true);
                            });
                        }
                    },
                    error: function (res) {
                        console.log('Error:', res);
    
                        let no = 1;
                        let errorListHtml = '';
                        let errorList = res.responseJSON.data;
                        $.each(errorList, function(key, values) {
                            $.each(values, function(key, value) {
                                errorListHtml += no + '. ' + value + '<br>';
                                no++;
                            });
                        });
    
                        Swal.fire({
                            title: res.responseJSON.message,
                            html: errorListHtml,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }

            $('#facilityForm').submit(function(e) {
                e.preventDefault();

                let added = $(newFacilities).not(oldFacilities).get();
                let removed = $(oldFacilities).not(newFacilities).get();

                let facilityUpdateURL = "{{ route('ajax.boarding_houses.update_facilities', ':id') }}";
                facilityUpdateURL = facilityUpdateURL.replace(':id', id);

                $.ajax({
                    url: facilityUpdateURL,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        boarding_id: id,
                        added: added,
                        removed: removed
                    },
                    dataType: "json",
                    success: function (res) {
                        if (!res.error) {
                            Swal.fire({
                                title: 'Sukses!',
                                text: res.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                $('#facilitiesModal').modal('hide');
                                getFacilities();
                            });
                        }
                    },
                    error: function (res) {
                        console.log('Error:', res);

                        let no = 1;
                        let errorListHtml = '';
                        let errorList = res.responseJSON.data;
                        $.each(errorList, function(key, values) {
                            $.each(values, function(key, value) {
                                errorListHtml += no + '. ' + value + '<br>';
                                no++;
                            });
                        });

                        Swal.fire({
                            title: res.responseJSON.message,
                            html: errorListHtml,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            $(document).on('click', '.facility-checkbox', function () {
                var id = parseInt($(this).val());
                if ($(this).is(':checked')) {
                    newFacilities.push(id);
                } else {
                    newFacilities.splice($.inArray(id, newFacilities), 1);
                }
            });

            // Select2 search autofocus
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });
        });
    </script>
@endsection
