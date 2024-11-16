@extends('layouts.app')

@section('title', 'Permission Detail')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/assets/css/bootstrap.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Permission Detail</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Permission Detail</div>
                </div>
            </div>
            <div class="section-body">
                <h2 class="section-title">Permission Detail</h2>
                <p class="section-lead">
                    Informasi tentang detail izin mahasiswa.
                </p>

                <div class="row mt-sm-4">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Nama Mahasiswa</label>
                                        <p>{{ $permissions->class->user->name }}</p>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>Nomor Induk</label>
                                        <p>{{ $permissions->class->user->unique_number }}</p>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Email</label>
                                        <p>{{ $permissions->class->user->email }}</p>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>Program Studi</label>
                                        <p>{{ $permissions->class->user->department }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Tanggal Pengajuan</label>
                                        <p>{{ $permissions->date_permission }}</p>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>Alasan</label>
                                        <p>{{ $permissions->reason }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Bukti Dukung</label>
                                        @if ($permissions->image)
                                            <div>
                                                <img src="{{ asset('images/permission/' . $permissions->image) }}"
                                                    alt="Bukti Dukung" class="img-thumbnail mb-3" style="max-width: 200px;">
                                            </div>
                                        @else
                                            <p>Tidak ada bukti dukung</p>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>Status Izin</label>
                                        <p>
                                            @if (is_null($permissions->is_approved))
                                                <div class="badge badge-warning">Perlu di tinjau</div>
                                            @elseif ($permissions->is_approved == 1)
                                                <div class="badge badge-success">Di Izinkan</div>
                                            @elseif ($permissions->is_approved == 0)
                                                <div class="badge badge-danger">Di Tolak</div>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <a href="{{ route('permissions.edit', $permissions->id) }}" class="btn btn-primary">Edit
                                    Permission For Approve</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>
@endpush
