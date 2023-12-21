@extends('layouts.front_layout')
@section('title', 'GIS Kos Haversine')

@section('content')
    <div class="row mb-3">
        <div class="col-sm-8 mt-2">
            <div class="main-img img-cover">
                <img src="{{$kos->images[0]->image_path != null ? request()->getSchemeAndHttpHost(). '/' .$kos->images[0]->image_path : asset('dist').'/img/default_image.jpg' }}" alt="Photo">
            </div>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 mt-2">
            <div class="row">
                <div class="col-sm-12">
                    <div class="secondary-img img-cover">
                        <img class="img-fluid mb-3" src="{{$kos->images[1]->image_path != null ? request()->getSchemeAndHttpHost(). '/' .$kos->images[1]->image_path : asset('dist').'/img/default_image.jpg' }}" alt="Photo">

                        @if (count($kos->images->where('image_path', '!=', null)) > 3)

                        <div class="more-img-wrapper">
                            <img class="img-fluid" src="{{$kos->images[2]->image_path != null ? request()->getSchemeAndHttpHost(). '/' .$kos->images[2]->image_path : asset('dist').'/img/default_image.jpg' }}">
                            <div class="more-img-overlay overlay-text" id="more-images">Foto Lainnya</div>
                        </div>
                          
                        @else
                            <img class="img-fluid" src="{{$kos->images[2]->image_path != null ? request()->getSchemeAndHttpHost(). '/' .$kos->images[2]->image_path : asset('dist').'/img/default_image.jpg' }}" alt="Photo">
                        @endif
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row mt-4">
        <div class="col-sm-8">
            <div class="card">
                <div class="card-body">
                    <p class="h1 text-uppercase mb-0">{{ $kos->name }}</p>

                    <div>
                        <i class="fa fa-fw fa-star text-warning"></i> <span class="text-bold" id="boarding_house_rating">0,0</span> (<span id="boarding_house_rating_count"></span> Ulasan)
                    </div>

                    <p class="mt-3">
                        <button type="button" class="btn btn-light btn-sm active" role="button" aria-pressed="true">
                            @if ($kos->type == 0)
                            KOS PUTRA &nbsp; <i class="fas fa-male"></i>
                            @elseif ($kos->type == 1)
                            KOS PUTRI &nbsp; <i class="fas fa-female"></i>
                            @elseif ($kos->type == 2)
                            KOS CAMPUR &nbsp; <i class="fas fa-venus-mars"></i>
                            @endif
                        </button>
                        <span class="text-secondary ml-2 mr-2">&#8226;</span>
                        <i class="fas fa-map-marker-alt"></i> &nbsp; {{ $kos->address }}, Kec. {{ $kos->district->name }}, Kota Malang, Jawa Timur, {{ $kos->postal_code }}
                    </p>

                    <hr class="mt-4 mb-4">

                    <p class="h5 text-uppercase">Fasilitas Kamar</p>
                    <ul>
                        @forelse ($room_facilities as $item)
                            <li>{{ $item->facility->name }}</li>
                        @empty
                            <li>Tidak Mempunyai Fasilitas Kamar</li>
                        @endforelse
                    </ul>
                    <hr class="mt-4 mb-4">

                    <p class="h5 text-uppercase">Fasilitas Kamar Mandi</p>
                    <ul>
                        @forelse ($bathroom_facilities as $item)
                            <li>{{ $item->facility->name }}</li>
                        @empty
                            <li>Tidak Mempunyai Fasilitas Kamar Mandi</li>
                        @endforelse
                    </ul>
                    <hr class="mt-4 mb-4">

                    <p class="h5 text-uppercase">Fasilitas Umum</p>
                    <ul>
                        @forelse ($general_facilities as $item)
                            <li>{{ $item->facility->name }}</li>
                        @empty
                            <li>Tidak Mempunyai Fasilitas Umum</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <p class="h4 text-bold">Rp. {{ number_format($kos->price) }} <small>/ perbulan</small></p>
                    KOS TERSISA {{ $kos->capacity - $kos->filled_capacity }} KAMAR

                    <hr class="mt-3 mb-3">

                    <div class="card mb-3" style="max-width: 540px;">
                        <div class="row no-gutters">
                            <div class="col-md-4">
                                <div class="owner-img"></div>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">Kos dikelola oleh:</h5>
                                    <h6 class="card-text text-bold">{{ $kos->user->name }}</h6>

                                    <h5 class="card-title">Dapat menghubungi:</h5>
                                    <h6 class="card-text text-bold">{{ $kos->phone_number }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <form action="{{route('front.route_directions')}}" method="get">
                        <input type="hidden" name="boarding" value="{{ base64_encode(request()->id) }}">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-route"></i> &nbsp; Petunjuk Jalan
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title text-bold mt-1">Komentar dan Rating</h3>
                    <div class="card-tools">
                        <button class="btn btn-sm btn-outline-primary" id="addReviewBtn">Beri Ulasan</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive mt-0">
                        <table id="dtComments" class="table table-borderless" width="100%">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->

    @if (count($kos->images->where('image_path', '!=', null)) > 3)

    <!-- Modal -->
    <div class="modal fade" id="moreImagesModal" tabindex="-1" aria-labelledby="moreImagesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="col-12 text-center">
                                    <img class="img-fluid product-image" src="{{ request()->getSchemeAndHttpHost(). '/' .$kos->images[0]->image_path }}" style="width: 650px;" alt="Photo">
                                </div>
                                <div class="col-12 product-image-thumbs">
                                    <div class="product-image-thumb active"><img src="{{ request()->getSchemeAndHttpHost(). '/' .$kos->images[0]->image_path }}" alt="Product Image"></div>

                                    @for ($i = 1; $i < count($kos->images->where('image_path' ,'!=', null)); $i++)
                                        <div class="product-image-thumb"><img src="{{ request()->getSchemeAndHttpHost(). '/' .$kos_images[$i]->image_path }}" alt="Product Image"></div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endif

    <!-- Add Comment Modal -->
    <div class="modal fade" id="addReviewModal" tabindex="-1" aria-labelledby="addReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="review-form">
                    <div class="modal-header">
                        <h5 class="modal-title">Beri Ulasan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row mb-3">
                            <div class="col-sm-6 col-12">
                                <label for="reviewName">Nama Lengkap</label>
                                <input type="text" class="form-control" id="reviewName" name="name" placeholder="Masukkan nama anda" autocomplete="off" required>
                            </div>
                            <div class="col-sm-6 col-12">
                                <label for="reviewEmail">Email</label>
                                <input type="email" class="form-control" id="reviewEmail" name="email" placeholder="Masukkan email anda" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Rating</label>
                            <div id="rating-area"></div>
                        </div>
                        <div class="form-group">
                            <label for="reviewComment">Komentar</label>
                            <textarea class="form-control" id="reviewComment" name="comment" rows="3" placeholder="Masukkan komentar anda" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
@endsection

@section('style_extra')

    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('plugins')}}/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{asset('plugins')}}/datatables-responsive/css/responsive.bootstrap4.min.css">
    <!-- Raty -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/raty-js@2.8.0/lib/jquery.raty.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{asset('plugins')}}/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

    <style>
        .main-img img {
            width: 100%;
            height: 367px;
        }

        .secondary-img img {
            width: 100%;
            height: 175px;
        }

        .owner-img {
            position: static;
            background-image: url("{{asset('dist')}}/img/user_icon.png");
            background-repeat: no-repeat;
            background-size: cover;
            object-fit: cover;
            height: 100%;
        }

        .img-cover img {
            object-fit: cover;
        }

        .more-img-wrapper {
            position: relative;
        }

        .more-img-wrapper img {
            display: block;
            object-fit: cover;
        }

        .more-img-overlay {
            position: absolute; 
            background: rgba(0, 0, 0, 0.5);
            
            /* center overlay text */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .overlay-text {
            inset: 0;
            color: white;
            transition: opacity 0.2s ease-in-out;
        }

        .overlay-text:hover {
            cursor: pointer;
            opacity: 0.8;
        }

        .user-thumbnail {
            position: static;
            background-image: url("{{asset('dist')}}/img/logo/user_logo.png");
            background-repeat: no-repeat;
            background-size: cover;
            object-fit: cover;
            height: 100%;
        }

        .dataTables_processing {
            z-index: 999;
        }
    </style>
    
@endsection

@section('script_extra')

    <!-- DataTables  & Plugins -->
    <script src="{{asset('plugins')}}/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('plugins')}}/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{asset('plugins')}}/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{asset('plugins')}}/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <!-- Raty -->
    <script src="https://cdn.jsdelivr.net/npm/raty-js@2.8.0/lib/jquery.raty.js"></script>
    <!-- SweetAlert2 -->
    <script src="{{asset('plugins')}}/sweetalert2/sweetalert2.min.js"></script>

    <script>
        $(document).ready(function() {
            let boarding_id = "{{ request()->id }}";

            let commentTable = null;
            let commentDtUrl = "{{ route('dt-ajax.comments.get', ':id') }}";
            commentDtUrl = commentDtUrl.replace(':id', boarding_id);

            commentTable = $("#dtComments").DataTable({
                dom: '<"top"i<"clear">>rt<"bottom"p<"clear">>',
                searching: false,
                ordering: false,
                info: false,
                processing: true,
                serverSide: true,
                pageLength: 3,
                language: {
                    emptyTable: "Belum ada komentar",
                    processing: '<i class="fas fa-spinner fa-spin"></i> Memuat...'
                },
                ajax: {
                    url: commentDtUrl,
                    type: "GET",
                    dataType: "json",
                    data: function ( d ) {
                        d._token = "{{ csrf_token() }}"
                    },
                },
                columns: [
                    {
                        title: "Comment",
                        data: name,
                        name: name,
                        render: function (data, type, row) {
                            let comment = `
                                <div class="row">
                                    <div class="col-2">
                                        <div class="user-thumbnail rounded-circle" style="width: 50px; height: 50px;"></div>
                                    </div>
                                    <div class="col-10">
                                        <div class="row ml-2">
                                            <div class="col-12">
                                                <h5 class="font-weight-bold mb-0">${row.name}</h5>
                                                <div class="user-rating" data-score="${row.rating}"></div>
                                            </div>
                                        </div>
                                        <div class="row mt-2 ml-2">
                                            <div class="col-12">
                                                <p>${row.comment}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;

                            return comment;
                        }
                    },
                ],
                createdRow: function(row, data, dataIndex) {
                    $(row).find('.user-rating').raty({
                        starType: 'i',
                        starOff: 'fa fa-fw fa-star text-muted',
                        starOn: 'fa fa-fw fa-star text-warning',
                        starHalf: 'fa fa-fw fa-star-half text-warning',
                        readOnly: true,
                    });
                },
                fnInitComplete: function() {
                    $('#dtComments thead').hide();
                },
            });

            $('#addReviewBtn').click(function() {
                $('#addReviewModal').modal('show');
            });

            // #review-form submit
            $('#review-form').submit(function(e) {
                e.preventDefault();

                let rating = $('#rating-area').raty('score');
                let name = $('#reviewName').val();
                let email = $('#reviewEmail').val();
                let comment = $('#reviewComment').val();

                $.ajax({
                    url: "{{ route('ajax.comments.store') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "boarding_id": "{{ request()->id }}",
                        "name": name,
                        "email": email,
                        "rating": rating,
                        "comment": comment,
                    },
                    success: function(res) {
                        $('#addReviewModal').modal('hide');
                        $('#review-form').trigger('reset');
                        $('#rating-area').raty('set', { score: 0 });
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message
                        }).then((result) => {
                            commentTable.ajax.reload(null, false);
                            getRatingSummary();
                        });
                    },
                    error: function(res) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: res.responseJSON.message
                        });
                    }
                });
            });

            $('#more-images').click(function() {
                $('#moreImagesModal').modal('show');
            });

            $('.product-image-thumb').on('click', function () {
                var $image_element = $(this).find('img')
                $('.product-image').prop('src', $image_element.attr('src'))
                $('.product-image-thumb.active').removeClass('active')
                $(this).addClass('active')
            })

            $('#rating-area').raty({
                starType: 'i',
                starOff: 'fa fa-fw fa-star fa-2x text-muted',
                starOn: 'fa fa-fw fa-star fa-2x text-warning',
                starHalf: 'fa fa-fw fa-star-half fa-2x text-warning',
            });

            getRatingSummary();
            function getRatingSummary() {
                $.ajax({
                    url: "{{ route('ajax.comments.get_summary', request()->id) }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(res) {
                        $('#boarding_house_rating').html(res.data.rating);
                        $('#boarding_house_rating_count').html(res.data.rating_count);
                    }
                });
            }
        });
    </script>

@endsection