@extends('layouts.app')

@section('title', 'Jadwal Kuliah')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Jadwal Kuliah</h1>
                <div class="section-header-button">
                    <a href="{{ route('schedules.create') }}" class="btn btn-primary">Tambah</a>
                </div>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Beranda</a></div>
                    <div class="breadcrumb-item"><a href="#">Jadwal Kuliah</a></div>
                    <div class="breadcrumb-item">Seluruh Jadwal Kuliah</div>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>
                <h2 class="section-title">Jadwal Kuliah</h2>
                <p class="section-lead">
                    Kamu bisa mengatur seluruh Jadwal Kuliah, seperti mengubah data, menghapus dan lain-lain.
                </p>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Data Jadwal Kuliah</h4>
                            </div>
                            <div class="card-body">
                                <div class="float-right">
                                    <form method="GET" action="{{ route('schedules.index') }}">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search" name="search"
                                                value="{{ request('search') }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="clearfix mb-3"></div>
                                <div class="table-responsive">
                                    <table class="table-striped table">
                                        <tr>
                                            <th>Hari</th>
                                            <th>Mata Kuliah</th>
                                            <th>Kelas</th>
                                            <th>Ruang Kelas</th>
                                            <th>Jam Masuk</th>
                                            <th>Jam Selesai</th>
                                            <th>Action</th>
                                        </tr>
                                        @foreach ($schedule as $sc)
                                            <tr>
                                                <td>{{ $sc->day }}</td>
                                                <td>{{ $sc->course->name }}</td>
                                                <td>{{ $sc->groupClass->name }}</td>
                                                <td>{{ $sc->course->classroom->name }} -
                                                    {{ $sc->course->classroom->building_name }}</td>
                                                <td>{{ date('H:i', strtotime($sc->time_in)) }}</td>
                                                <td>{{ $sc->time_out }}</td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <a href='{{ route('schedules.edit', $sc->id) }}'
                                                            class="btn btn-sm btn-info btn-icon ml-2">
                                                            <i class="fas fa-edit"></i>
                                                            Edit
                                                        </a>
                                                        <form action="{{ route('schedules.destroy', $sc->id) }}"
                                                            method="POST" class="ml-2">
                                                            <input type="hidden" name="_method" value="DELETE" />
                                                            <input type="hidden" name="_token"
                                                                value="{{ csrf_token() }}" />
                                                            <button
                                                                class="btn btn-sm btn-danger btn-icon confirm-delete ml-2">
                                                                <i class="fas fa-times"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $schedule->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/features-posts.js') }}"></script>
@endpush
