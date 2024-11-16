@extends('layouts.app')

@section('title', 'Presensi Mata Kuliah')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tambah Data Presensi Mata Kuliah</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Forms</a></div>
                    <div class="breadcrumb-item">Presensi Mata Kuliah</div>
                </div>
            </div>
            <div class="section-body">
                <h2 class="section-title">Presensi Mata Kuliah</h2>
                <div class="row mt-sm-4">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <form action="{{ route('attendance-schedules.store') }}" method="POST">
                                @csrf
                                <div class="card-header">
                                    <h4>Masukan Data Presensi Mata Kuliah</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Tanggal Presensi</label>
                                        <input type="text"
                                            class="form-control datepicker @error('date')
                                    is-invalid
                                @enderror"
                                            name="date">
                                        @error('date')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6 col-1">
                                            <label>Pilih Mata Mata Kuliah</label>
                                            <select name="course_id"
                                                class="form-control select2 @error('course_id') is-invalid @enderror">
                                                <option disabled selected>Ketuk untuk menambahkan data</option>
                                                @foreach ($course as $cs)
                                                    <option value="{{ $cs->id }}">{{ $cs->courses_code }} -
                                                        {{ $cs->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('course_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6 col-1">
                                            <label>Pilih Kelas</label>
                                            <select name="groupClass_id"
                                                class="form-control select2 @error('groupClass_id') is-invalid @enderror">
                                                <option disabled selected>Ketuk untuk menambahkan data</option>
                                                @foreach ($groupClass as $gc)
                                                    <option value="{{ $gc->id }}">{{ $gc->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('groupClass_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6 col-12">
                                            <label>Waktu Mulai</label>
                                            <input type="text"
                                                class="form-control timepicker @error('time_start')
                                    is-invalid
                                @enderror"
                                                name="time_start">
                                            @error('time_start')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6 col-12">
                                            <label>Waktu Selesai</label>
                                            <input type="text"
                                                class="form-control timepicker @error('time_end')
                                    is-invalid
                                @enderror"
                                                name="time_end">
                                            @error('time_end')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Jumlah Jadwal</label>
                                        <input type="number" class="form-control @error('count') is-invalid @enderror"
                                            name="count" min="1" value="1">
                                        @error('count')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <p>akan diulangi dalam interval 7 hari</p>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('library/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
    <script>
        $('.timepicker').timepicker({
            showMeridian: false,
            defaultTime: false,
            minuteStep: 1,
            showSeconds: false,
            icons: {
                up: 'fas fa-chevron-up',
                down: 'fas fa-chevron-down'
            }
        });
    </script>
@endpush
