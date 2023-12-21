@extends('layouts.admin_layout')
@section('title', 'Fasilitas')

@section('header')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Fasilitas</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Fasilitas</li>
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
            <button type="button" class="btn btn-sm btn-primary" id="addNew">
                <i class="fas fa-plus"></i> &nbsp; Tambah
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="dtFacilities" class="table table-bordered table-hover" width="100%">

            </table>
        </div>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

<!-- Modal -->
<div class="modal fade" id="facilityModal" tabindex="-1" aria-labelledby="facilityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" id="facilityForm">
            @csrf
            <input type="hidden" name="id" id="facilityId">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="facilityModalLabel">Tambah Admin Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="facilityName">Nama Fasilitas</label>
                        <input type="text" class="form-control" id="facilityName" name="name" placeholder="Masukkan nama fasilitas" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="facilityCategory">Kategori</label>
                        <select class="form-control" name="category" id="facilityCategory">
                            <option value="1">Fasilitas Kamar</option>
                            <option value="2">Fasilitas Kamar Mandi</option>
                            <option value="3">Fasilitas Umum</option>
                        </select>
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
@endsection

@section('style_extra')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('plugins')}}/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet"
        href="{{asset('plugins')}}/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{asset('plugins')}}/datatables-buttons/css/buttons.bootstrap4.min.css">
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
    <!-- MomentJS -->
    <script src="{{asset('plugins')}}/moment/moment.min.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{asset('plugins')}}/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="{{asset('plugins')}}/sweetalert2/sweetalert2.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = null;

            table = $("#dtFacilities").DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, 'All'],
                ],
                ajax: {
                    url: "{{ route('dt-ajax.facilities_manager.get') }}",
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
                        title: "Kategori",
                        data: "category",
                        name: "category",
                        render: function (data, type, row) {
                            if (data == 1) {
                                return 'Fasilitas Kamar';
                            } else if (data == 2) {
                                return 'Fasilitas Kamar Mandi';
                            } else if (data == 3) {
                                return 'Fasilitas Umum';
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
                        width: "15%",
                    },

                ],
            });

            $('#facilityModal').on('shown.bs.modal', function () {
                $('#facilityName').trigger('focus');
            });

            $('#facilityModal').on('hidden.bs.modal', function () {
                $('#facilityId').val(null);
                $('#facilityForm')[0].reset();
            });

            $('#addNew').click(function () {
                $('#facilityModal').modal('show');
                $('#facilityModalLabel').html('Tambah Fasilitas');
            });

            $('#facilityForm').submit(function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                let id = $('#facilityForm').find('#facilityId').val();

                let url = null;
                if (id == "") {
                    url = "{{ route('ajax.facilities_manager.create') }}";
                } else {
                    url = "{{ route('ajax.facilities_manager.update', ':id') }}";
                    url = url.replace(':id', $('#facilityId').val());
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
                            });

                            $('#facilityModal').modal('hide');
                            $('#facilityForm')[0].reset();
                            table.ajax.reload(null, false);
                        }
                    },
                    error: function (res) {
                        console.log('Error:', res);

                        let errorListHtml = '';
                        let errorList = res.responseJSON.data;
                        $.each(errorList, function(key, values) {
                            $.each(values, function(key, value) {
                                errorListHtml += value + '<br>';
                            });
                        });

                        Swal.fire({
                            title: 'Gagal!',
                            html: errorListHtml,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            $(document).on('click', '.edit', function() {
                let id = $(this).data('id');

                let url = "{{ route('ajax.facilities_manager.get', ':id') }}";
                url = url.replace(':id', id);

                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    success: function (res) {
                        if (!res.error) {
                            $('#facilityModal').modal('show');
                            $('#facilityModalLabel').html('Edit Fasilitas');
                            $('#facilityId').val(res.data.id);
                            $('#facilityName').val(res.data.name);
                            $('#facilityCategory').val(res.data.category).change();
                        }
                    },
                    error: function (res) {
                        console.log('Error:', res);
                    }
                });
            });

            $(document).on('click', '.delete', function() {
                let id = $(this).data('id');

                let url = "{{ route('ajax.facilities_manager.delete', ':id') }}";
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
            });

        });
    </script>
@endsection
