@extends('layouts.app')

@section('title', 'Edit Mata Kuliah')

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
                    <div class="breadcrumb-item">Ruang Kelas</div>
                </div>
            </div>
            <div class="section-body">
                <h2 class="section-title">Ruang Kelas</h2>
                <div class="card">
                    <form action="{{ route('classrooms.update', $classroom->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-header">
                            <h4>Ubah Data</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Ruangan</label>
                                <input type="text"
                                    class="form-control @error('name')
                                is-invalid
                            @enderror"
                                    name="name" value="{{ $classroom->name }}">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Gedung</label>
                                <input type="text"
                                    class="form-control @error('building_name')
                                is-invalid
                            @enderror"
                                    name="building_name" value="{{ $classroom->building_name }}">
                                @error('building_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Latitude</label>
                                <input type="text"
                                    class="form-control timepicker @error('latitude')
                            is-invalid
                        @enderror"
                                    name="latitude" value="{{ $classroom->latitude }}">
                                @error('latitude')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Longitude</label>
                                <input type="text"
                                    class="form-control @error('longitude')
                                is-invalid
                            @enderror"
                                    name="longitude" value="{{ $classroom->longitude }}">
                                @error('longitude')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Jarak Absensi</label>
                                <input type="number"
                                    class="form-control @error('radius')
                                is-invalid
                            @enderror"
                                    name="radius" value="{{ $classroom->radius }}">
                                @error('radius')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <p>*dalam satuan meter</p>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
                </form>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
@endpush
