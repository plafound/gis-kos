@extends('layouts.admin_layout')
@section('title', 'Admin Manager')

@section('header')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Admin Manager</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Admin Manager</li>
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
            <button type="button" class="btn btn-sm btn-primary" id="addNewAdmin">
                <i class="fas fa-plus"></i> &nbsp; Tambah
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="dtUsers" class="table table-bordered table-hover" width="100%">

            </table>
        </div>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

<!-- Modal -->
<div class="modal fade" id="adminModal" tabindex="-1" aria-labelledby="adminModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" id="adminForm">
            @csrf
            <input type="hidden" name="id" id="adminId">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adminModalLabel">Tambah Admin Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="adminName">Nama Lengkap</label>
                        <input type="text" class="form-control" id="adminName" name="name" placeholder="Masukkan nama lengkap" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="adminEmail">Email</label>
                        <input type="email" class="form-control" id="adminEmail" name="email" placeholder="Masukkan alamat email" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="adminPassword">Password</label>
                        <input type="password" class="form-control" id="adminPassword" name="password" placeholder="Masukkan password">
                    </div>
                    <div class="form-group">
                        <label for="adminPasswordConfirmation">Ulangi Password</label>
                        <input type="password" class="form-control" id="adminPasswordConfirmation" name="password_confirmation" placeholder="Masukkan ulang password">
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
    <!-- MomentJS -->
    <script src="{{asset('plugins')}}/moment/moment.min.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{asset('plugins')}}/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="{{asset('plugins')}}/sweetalert2/sweetalert2.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = null;

            table = $("#dtUsers").DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, 'All'],
                ],
                ajax: {
                    url: "{{ route('dt-ajax.user_manager.get') }}",
                    type: "GET",
                    dataType: "json",
                    data: function ( d ) {
                        d._token = "{{ csrf_token() }}",
                        d.role = "admin"
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
                        title: "Email",
                        data: "email",
                        name: "email",
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

            $('#adminModal').on('shown.bs.modal', function () {
                $('#adminName').trigger('focus');
            });

            $('#adminModal').on('hidden.bs.modal', function () {
                $('#adminId').val(null);
                $('#adminForm')[0].reset();
                $('#adminPassword').closest('.form-group').show();
                $('#adminPasswordConfirmation').closest('.form-group').show();
            });

            $('#addNewAdmin').click(function () {
                $('#adminModal').modal('show');
                $('#adminModalLabel').html('Tambah Admin');
            });

            $('#adminForm').submit(function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                let id = $('#adminForm').find('#adminId').val();

                let url = null;
                if (id == "") {
                    url = "{{ route('ajax.user_manager.create') }}";
                } else {
                    url = "{{ route('ajax.user_manager.update', ':id') }}";
                    url = url.replace(':id', $('#adminId').val());
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

                            $('#adminModal').modal('hide');
                            $('#adminForm')[0].reset();
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

            $(document).on('click', '.editAdmin', function() {
                let id = $(this).data('id');

                let url = "{{ route('ajax.user_manager.get', ':id') }}";
                url = url.replace(':id', id);

                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    success: function (res) {
                        if (!res.error) {
                            $('#adminModal').modal('show');
                            $('#adminModalLabel').html('Edit Admin');
                            $('#adminPassword').closest('.form-group').hide();
                            $('#adminPasswordConfirmation').closest('.form-group').hide();
                            $('#adminId').val(res.data.id);
                            $('#adminName').val(res.data.name);
                            $('#adminEmail').val(res.data.email);
                        }
                    },
                    error: function (res) {
                        console.log('Error:', res);
                    }
                });
            });

            $(document).on('click', '.deleteAdmin', function() {
                let id = $(this).data('id');

                let url = "{{ route('ajax.user_manager.delete', ':id') }}";
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
