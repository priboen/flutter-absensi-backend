@extends('layouts.app')

@section('title', 'Mata Kuliah')

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
                <h1>Tambah Data Mata Kuliah</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Forms</a></div>
                    <div class="breadcrumb-item">Mata Kuliah</div>
                </div>
            </div>
            <div class="section-body">
                <h2 class="section-title">Mata Kuliah</h2>
                <div class="row mt-sm-4">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <form action="{{ route('courses.store') }}" method="POST">
                                @csrf
                                <div class="card-header">
                                    <h4>Masukan Data Mata Kuliah</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-6 col-12">
                                            <label>Kode Mata Kuliah</label>
                                            <input type="text"
                                                class="form-control @error('courses_code')
                                            is-invalid
                                        @enderror"
                                                name="courses_code">
                                            @error('courses_code')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6 col-12">
                                            <label>Nama Mata Kuliah</label>
                                            <input type="text"
                                                class="form-control @error('name')
                                            is-invalid
                                        @enderror"
                                                name="name">
                                            @error('name')
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
                                        <div class="form-group col-md-6 col-12">
                                            <label>Kredit Semester</label>
                                            <input type="text"
                                                class="form-control @error('credits')
                                            is-invalid
                                        @enderror"
                                                name="credits">
                                            @error('credits')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Pilih Mata Ruang Kelas</label>
                                        <select name="classroom_id"
                                            class="form-control select2 @error('classroom_id') is-invalid @enderror">
                                            <option disabled selected>Ketuk untuk menambahkan data</option>
                                            @foreach ($classrooms as $classroom)
                                                <option value="{{ $classroom->id }}">{{ $classroom->name }} -
                                                    {{ $classroom->building_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('classroom_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="card-footer text-right">
                                        <button class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                        </div>
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
