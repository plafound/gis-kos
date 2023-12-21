<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Dokumen | {{ env('APP_NAME') }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('plugins')}}/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist')}}/css/adminlte.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{asset('plugins')}}/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
</head>

<body class="hold-transition lockscreen">
    <!-- Automatic element centering -->
    <div class="lockscreen-wrapper">
        <div class="lockscreen-logo">
            <a href="/"><b>{{ env('APP_NAME') }}</b></a>
        </div>
        <!-- User name -->
        <div class="lockscreen-name">Halo, {{ Auth::user()->name }}!</div>

        <div class="help-block text-center">
            @if (Auth::user()->status == 1)
                Dokumen anda sedang dalam proses verifikasi dan diperkirakan selesai dalam waktu 1 - 2 hari. <br>
                Mohon untuk melakukan pengecekan secara berkala.
            @elseif (Auth::user()->status == 2)
                Dokumen anda tidak valid. <br>
                Mohon untuk mengupload ulang melalui form yang telah disediakan di bawah ini:

                <form id="reuploadForm">
                    @csrf
                    <div class="form-group mt-3">
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="document-file" name="document_file" required>
                                <label class="custom-file-label" style="text-align: left;" for="document-file">Pilih dokumen</label>
                            </div>
                            <div class="input-group-append">
                                <button class="btn btn-sm btn-success" id="btn-upload-document" type="submit">
                                    Upload
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            @elseif (Auth::user()->status == 3)
                Dokumen telah terverifikasi. <br>
                Anda akan diarahkan ke halaman dasboard. Klik <a href="{{ route('admin.dashboard') }}">disini</a> jika anda tidak diarahkan.
            @endif
        </div>
        <div class="text-center mt-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-sm btn-outline-danger" type="submit">
                    Logout
                </button>
            </form>
        </div>
        <div class="lockscreen-footer text-center mt-3">
            Copyright &copy; {{ date('Y') }} <b><a href="/" class="text-black">{{ env('APP_NAME') }}</a></b><br>
            All rights reserved
        </div>
    </div>
    <!-- /.center -->

    <!-- jQuery -->
    <script src="{{asset('plugins')}}/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('plugins')}}/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Helper -->
    @include('layouts.includes.helper')

    <!-- SweetAlert2 -->
    <script src="{{asset('plugins')}}/sweetalert2/sweetalert2.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="{{asset('plugins')}}/bs-custom-file-input/bs-custom-file-input.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            // Initialize bsCustomFileInput
            bsCustomFileInput.init();

            // Redirect to dashboard if user status is verified
            if ("{{ Auth::user()->status }}" == 3) {
                setTimeout(function() {
                    window.location.href = "{{ route('admin.dashboard') }}";
                }, 2000);
            }

            // Submit upload document form
            $('#reuploadForm').submit(function(e) {
                e.preventDefault();
                setLoadingButton('#btn-upload-document', 'Upload');

                let url = "{{route('ajax.user_manager.document.upload', ['id' => Auth::user()->id])}}";
                let formData = new FormData(this);
                // formData.append('_token', "{{ csrf_token() }}");

                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: "json",
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (res) {
                        if (!res.error) {
                            resetLoadingButton('#btn-upload-document', 'Upload');
                            
                            Swal.fire({
                                title: 'Berhasil!',
                                text: res.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        }
                    },
                    error: function (res) {
                        resetLoadingButton('#btn-upload-document', 'Upload');
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
        });
    </script>
</body>

</html>