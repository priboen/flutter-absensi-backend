@extends('layouts.app')

@section('title', 'Jadwal Kuliah')

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
                <h1>Tambah Data Jadwal Kuliah</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Forms</a></div>
                    <div class="breadcrumb-item">Jadwal Kuliah</div>
                </div>
            </div>
            <div class="section-body">
                <h2 class="section-title">Jadwal Kuliah</h2>
                <div class="card">
                    <form action="{{ route('schedules.store') }}" method="POST">
                        @csrf
                        <div class="card-header">
                            <h4>Masukan Data Jadwal Kuliah</h4>
                        </div>
                        <div class="card-body">
                            <div class="section-title">Hari</div>
                            <div class="form-group">
                                <label>Pilih Hari</label>
                                <select name="day" class="form-control select2 @error('day') is-invalid @enderror">
                                    <option disabled selected>Ketuk untuk menambahkan data</option>
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jumat">Jum'at</option>
                                    <option value="Sabtu">Sabtu</option>
                                </select>
                                @error('day')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="section-title">Waktu Mulai</div>
                            <div class="form-group">
                                <label>Masukan Waktu Mulai</label>
                                <input type="text"
                                    class="form-control timepicker @error('time_in')
                            is-invalid
                        @enderror"
                                    name="time_in">
                                @error('time_in')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="section-title">Mata Kuliah</div>
                            <div class="form-group">
                                <label>Pilih Mata Kuliah Mahasiswa</label>
                                <select name="course_id"
                                    class="form-control select2 @error('course_id') is-invalid @enderror">
                                    <option disabled selected>Ketuk untuk menambahkan data</option>
                                    @foreach ($course as $cs)
                                        <option value="{{ $cs->id }}">
                                            {{ $cs->name }} - {{ $cs->credits }} SKS</option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="section-title">Kelas</div>
                            <div class="form-group">
                                <label>Pilih Kelas</label>
                                <select name="groupClass_id"
                                    class="form-control select2 @error('groupClass_id') is-invalid @enderror">
                                    <option disabled selected>Ketuk untuk menambahkan data</option>
                                    @foreach ($groupClass as $cs)
                                        <option value="{{ $cs->id }}">{{ $cs->name }}</option>
                                    @endforeach
                                </select>
                                @error('groupClass_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
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
