@extends('layouts.admin_layout')
@section('title', 'Rumah Kos')

@section('header')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Rumah Kos</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Rumah Kos</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
@endsection

@section('content')
<!-- Default box -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">List</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-sm btn-primary" id="addNewKost">
                <i class="fas fa-plus"></i> &nbsp; Tambah
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="dtBoardingHouses" class="table table-bordered table-hover" width="100%">

            </table>
        </div>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

<!-- Modal -->
<div class="modal fade" id="kostModal" tabindex="-1" aria-labelledby="kostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="" id="kostForm">
            @csrf
            <input type="hidden" name="id" id="kostId">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kostModalLabel">Tambah Kos Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kostName">Nama</label>
                        <input type="text" class="form-control" id="kostName" name="name" placeholder="Masukkan nama kos" autocomplete="off">
                    </div>
                    <div class="form-row mb-3">
                        <div class="col">
                            <label for="kostAddress">Alamat</label>
                            <input type="text" class="form-control" id="kostAddress" name="address" placeholder="Masukkan alamat lengkap" autocomplete="off">
                        </div>
                        <div class="col">
                            <label for="kostDistrict">Kecamatan</label>
                            <select class="form-control" id="kostDistrict" name="district_id">
                                <option value="">Pilih Salah Satu</option>
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
                    <div class="form-row mb-3">
                        <div class="col">
                            <label for="kostPrice">Harga</label>
                            <input type="text" class="form-control numberInput" id="kostPrice" name="price" placeholder="Masukkan harga sewa kos" autocomplete="off">
                        </div>
                        <div class="col">
                            <label for="kostCapacity">Total Kamar</label>
                            <input type="text" class="form-control numberInput" id="kostCapacity" name="capacity" placeholder="Masukkan total kamar yang tersedia" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="document_file">Dokumen Pendukung</label>
                        <div class="custom-file">
                            <input type="file" name="document_file" id="document_file" class="custom-file-input">
                            <label class="custom-file-label" for="document_file">Pilih file</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal update dokumen -->
<div class="modal fade" id="updateDocumentModal" tabindex="-1" aria-labelledby="updateDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="updateDocumentForm">
            @csrf
            <input type="hidden" name="id" id="updateDocumentBoardingId">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateDocumentModalLabel">Update Dokumen Kos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="update_document_file">Dokumen Pendukung</label>
                        <div class="custom-file">
                            <input type="file" name="document_file" id="update_document_file" class="custom-file-input">
                            <label class="custom-file-label" for="update_document_file">Pilih file</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Verifikasi Dokumen -->
<div class="modal fade" id="verifyDocumentModal" tabindex="-1" aria-labelledby="verifyDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="verifyDocumentForm">
            <input type="hidden" name="id" id="document-boarding-id">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verifyDocumentModalLabel">Preview Dokumen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6 class="text-bold">Status: <span class="font-weight-normal" id="docs-status"></span></h6>
                    <div class="text-center">
                        <img src="" id="user-document-image" class="img-fluid" style="max-height: 400px;" alt="Dokumen Pelengkap">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="reject-document-btn">Tolak</button>
                    <button type="button" class="btn btn-primary" id="accept-document-btn">Verifikasi</button>
                </div>
            </div>
        </form>
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
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{asset('plugins')}}/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{asset('plugins')}}/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
@endsection

@section('script_extra')
    <!-- DataTables  & Plugins -->
    <script src="{{asset('plugins')}}/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('plugins')}}/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{asset('plugins')}}/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{asset('plugins')}}/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="{{asset('plugins')}}/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{asset('plugins')}}/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="{{asset('plugins')}}/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="{{asset('plugins')}}/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="{{asset('plugins')}}/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="{{asset('plugins')}}/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- MomentJS -->
    <script src="{{asset('plugins')}}/moment/moment.min.js"></script>
    <!-- Select2 -->
    <script src="{{asset('plugins')}}/select2/js/select2.min.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{asset('plugins')}}/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="{{asset('plugins')}}/sweetalert2/sweetalert2.min.js"></script>

    <script>
        $(document).ready(function() {
            let table = null;

            table = $("#dtBoardingHouses").DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, 'All'],
                ],
                ajax: {
                    url: "{{ route('dt-ajax.kos_manager.get') }}",
                    type: "GET",
                    dataType: "json",
                    data: function ( d ) {
                        d._token = "{{ csrf_token() }}"
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
                        title: "Nama",
                        data: "name",
                        name: "name"
                    },
                    {
                        title: "Alamat",
                        data: "address",
                        name: "address",
                        render: function (data, type, row) {
                            return data + ", Kec. " + row.district.name + ", Kota Malang, Jawa Timur, " + row.postal_code;
                        }
                    },
                    {
                        title: "Telepon",
                        data: "phone_number",
                        name: "phone_number",
                    },
                    {
                        title: "Jenis Kos",
                        data: "type",
                        name: "type",
                        render: function (data, type, row) {
                            if (data == 0) {
                                return "Putra";
                            } else if (data == 1) {
                                return "Putri";
                            } else if (data == 2) {
                                return "Campur";
                            }
                        }
                    },
                    {
                        title: "Total Kamar",
                        data: "capacity",
                        name: "capacity",
                        render: function (data, type, row) {
                            // number separator
                            return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                    },
                    {
                        title: "Kamar Terisi",
                        data: "filled_capacity",
                        name: "filled_capacity",
                        render: function (data, type, row) {
                            // number separator
                            return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                    },
                    {
                        title: "Status",
                        data: "is_active",
                        name: "is_active",
                        render: function (data, type, row) {
                            let document_status = row.document?.status;
                            
                            if (document_status == 3) {
                                return "<span class='badge badge-danger'>Dokumen Ditolak</span>";
                            } else {
                                if (data == 0) {
                                    return "<span class='badge badge-danger'>Tidak Aktif</span>";
                                } else if (data == 1) {
                                    return "<span class='badge badge-success'>Aktif</span>";
                                }
                            }
                        }
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
                rowCallback: function (row, data, index) {
                    if (data.document?.status == 1) {
                        $(row).css('background-color', '#fff3cd');
                    } else if (data.document?.status == 3) {
                        $(row).css('background-color', '#f8d7da');
                    }
                },
            });

            $(function() {
                bsCustomFileInput.init();
            });

            // Select2 #kostDistrict
            $('#kostDistrict').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Kecamatan',
                dropdownParent: $('#kostModal'),
            });

            $('#kostModal').on('shown.bs.modal', function () {
                $('#kostName').trigger('focus');
            });

            $('#kostModal').on('hidden.bs.modal', function () {
                $('#kostId').val(null);
                $('#kostForm')[0].reset();
            });

            $('#addNewKost').click(function () {
                $('#kostModal').modal('show');
                $('#kostModalLabel').html('Tambah Kos');
            });

            $(document).on('click', '.document-preview', function() {
                let id = $(this).data('id');

                let url = "{{ route('ajax.kos_manager.document', ':id') }}";
                url = url.replace(':id', id);

                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    success: function (res) {
                        if (!res.error) {
                            let document_image_path = res.data != null ? "{{ asset('')}}" + res.data.document_path : "{{ asset('dist')}}/img/default_image.jpg";

                            // Set value to modal
                            $('#document-boarding-id').val(res.data != null ? res.data.id : null);
                            $('#user-document-image').attr('src', document_image_path);

                            // Set document status
                            let status = '';
                            if (res.data != null) {
                                if (res.data.status == 1) {
                                    status = 'Menunggu verifikasi';
                                } else if (res.data.status == 2) {
                                    status = 'Terverifikasi';
                                } else if (res.data.status == 3) {
                                    status = 'Ditolak';
                                }
                            } else {
                                status = 'Belum diunggah';
                            }
                            
                            $('#docs-status').html(status);

                            // Disable button if boarding house is active
                            if (res.data != null) {
                                if (res.data.boarding_house.is_active || res.data.status == 2 || res.data.status == 3) {
                                    $('#reject-document-btn').attr('disabled', true);
                                    $('#accept-document-btn').attr('disabled', true);
                                } else {
                                    $('#reject-document-btn').attr('disabled', false);
                                    $('#accept-document-btn').attr('disabled', false);
                                }
                            } else {
                                $('#reject-document-btn').attr('disabled', true);
                                $('#accept-document-btn').attr('disabled', true);
                            }
                            
                            // Show modal
                            $('#verifyDocumentModal').modal('show');
                        }
                    },
                    error: function (res) {
                        console.log('Get document error:', res);

                        Swal.fire({
                            title: 'Gagal!',
                            text: res.responseJSON.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            // Reject user document
            $(document).on('click', '#reject-document-btn', function() {
                let boarding_id = $('#document-boarding-id').val();
                verifyDocument(boarding_id, 'reject');
            });

            // Accept user document
            $(document).on('click', '#accept-document-btn', function() {
                let boarding_id = $('#document-boarding-id').val();
                verifyDocument(boarding_id, 'accept');
            });

            $('#kostForm').submit(function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                let id = $('#kostForm').find('#kostId').val();

                let url = null;
                if (id == "") {
                    url = "{{ route('ajax.kos_manager.create') }}";
                } else {
                    url = "{{ route('ajax.kos_manager.update', ':id') }}";
                    url = url.replace(':id', $('#kostId').val());
                }

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
                                if (result.isConfirmed) {
                                    $('#kostModal').modal('hide');
                                    $('#kostForm')[0].reset();

                                    let dataId = res.data.id;
                                    let detailUrl = "{{ route('admin.kos_manager.edit', ':id') }}";
                                    detailUrl = detailUrl.replace(':id', dataId);
        
                                    // Redirect to edit page
                                    window.location.href = detailUrl + "?state=first_create";
                                }
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

            $('#updateDocumentForm').submit(function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                let id = $('#updateDocumentForm').find('#updateDocumentBoardingId').val();
                let url = "{{ route('ajax.kos_manager.update_document') }}";

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
                                table.ajax.reload(null, false);
                                $('#updateDocumentModal').modal('hide');
                                $('#updateDocumentForm')[0].reset();
                            });
                        }
                    },
                    error: function (res) {
                        console.log('Error:', res);

                        let no = 1;
                        let errorListHtml = '';
                        let errorList = res.responseJSON.errors;
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

            $(document).on('click', '.change-status', function() {
                let id = $(this).data('id');
                let status = $(this).data('status');

                let is_active = null;
                let statusText = null;

                if (status == 0) {
                    is_active = 1;
                    statusText = 'mengaktifkan';
                } else if (status == 1) {
                    is_active = 0;
                    statusText = 'menonaktifkan';
                }

                // Question Swal
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Apakah anda yakin ingin " + statusText + " kos ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yakin',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let url = "{{ route('ajax.kos_manager.update_status', ':id') }}";
                        url = url.replace(':id', id);

                        $.ajax({
                            url: url,
                            type: "POST",
                            dataType: "json",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "is_active": is_active
                            },
                            success: function (res) {
                                if (!res.error) {
                                    Swal.fire({
                                        title: 'Sukses!',
                                        text: res.message,
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then((result) => {
                                        table.ajax.reload(null, false);
                                    });
                                }
                            },
                            error: function (res) {
                                console.log('Error:', res);
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.edit', function() {
                let id = $(this).data('id');

                let url = "{{ route('admin.kos_manager.edit', ':id') }}";
                url = url.replace(':id', id);

                // Redirect to edit page
                window.location.href = url;
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

            $(document).on('click', '.update-document', function() {
                let id = $(this).data('id');
                
                $('#updateDocumentBoardingId').val(id);
                $('#updateDocumentModal').modal('show');
            });

            $(document).on('click', '.pending-document', function() {
                Swal.fire({
                    title: 'Informasi',
                    text: "Dokumen rumah kos sedang diverifikasi oleh admin.",
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            });

            // Select2 search autofocus
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });

            /**
             * Verify document
             * 
             * @param {int} id
             * @param {string} status
             */
             function verifyDocument(id, status) {
                let url = "{{ route('ajax.kos_manager.document.verify', ':id') }}";
                url = url.replace(':id', id);

                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: status
                    },
                    success: function (res) {
                        if (!res.error) {
                            Swal.fire({
                                title: 'Sukses!',
                                text: res.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });

                            $('#verifyDocumentModal').modal('hide');
                            table.ajax.reload(null, false);
                        }
                    },
                    error: function (res) {
                        console.log('Error:', res);

                        Swal.fire({
                            title: 'Gagal!',
                            text: res.responseJSON.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    </script>
@endsection
