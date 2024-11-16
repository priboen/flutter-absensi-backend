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
                <h1>Edit Form</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Beranda</a></div>
                    <div class="breadcrumb-item"><a href="#">Forms</a></div>
                    <div class="breadcrumb-item">Jadwal Kuliah</div>
                </div>
            </div>
            <div class="section-body">
                <h2 class="section-title">Jadwal Kuliah</h2>
                <div class="card">
                    <form action="{{ route('classes.update', $class->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-header">
                            <h4>Ubah Data</h4>
                        </div>
                        <div class="card-body">
                            <div class="section-title">Mahasiswa</div>
                            <div class="form-group">
                                <label>Pilih Mahasiswa</label>
                                <select name="user_id" class="form-control select2 @error('user_id') is-invalid @enderror">
                                    <option disabled selected>Ketuk untuk menambahkan data</option>
                                    @foreach ($user as $us)
                                        <option value="{{ $us->id }}" {{ $us->id == $class->user_id ? 'selected' : '' }}>
                                            {{ $us->name }} -
                                            {{ $us->unique_number }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
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
                                        <option value="{{ $cs->id }} "
                                            {{ $cs->id == $class->course_id ? 'selected' : '' }}>
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
                                    @foreach ($groupClass as $gc)
                                        <option value="{{ $gc->id }}"
                                            {{ $gc->id == $class->groupClass_id ? 'selected' : '' }}>
                                            {{ $gc->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('groupClass_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
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
@endpush
