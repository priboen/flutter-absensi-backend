@extends('layouts.app')

@section('title', 'Presensi Mata Kuliah')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Presensi Mata Kuliah</h1>
                <div class="section-header-button">
                    <a href="{{ route('attendance-schedules.create') }}" class="btn btn-primary">Tambah Data</a>
                </div>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Presensi Mata Kuliah</a></div>
                    <div class="breadcrumb-item">Data Presensi Mata Kuliah</div>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>
                <h2 class="section-title">Presensi Mata Kuliah</h2>
                <p class="section-lead">
                    Kamu dapat mengatur semua data presensi dari mata kuliah seperti mengedit, menghapus dan lainnya.
                </p>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>All Posts</h4>
                            </div>
                            <div class="card-body">
                                <div class="float-right">
                                    <form method="GET" action="{{ route('attendance-schedules.index') }}">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search by course name"
                                                name="name">
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
                                            <th>Tgl</th>
                                            <th>Mata Kuliah</th>
                                            <th>Kelas</th>
                                            <th>Jam Masuk</th>
                                            <th>Jam Keluar</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                        @foreach ($schedule as $sch)
                                            <tr>
                                                <td>{{ $sch->date }}</td>
                                                <td>{{ $sch->course->name }}</td>
                                                <td>{{ $sch->groupClass->name }}</td>
                                                <td>{{ $sch->time_start }}</td>
                                                <td>{{ $sch->time_end }}</td>
                                                <td>
                                                    <div class="form-group d-flex">
                                                        <label class="custom-switch mt-2">
                                                            <input type="checkbox" name="custom-switch-checkbox"
                                                                class="custom-switch-input toggle-switch"
                                                                data-id="{{ $sch->id }}"
                                                                data-status="{{ $sch->is_open }}"
                                                                {{ $sch->is_open ? 'checked' : '' }}>
                                                            <span class="custom-switch-indicator"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        <a href='{{ route('attendance-schedules.edit', $sch->id) }}'
                                                            class="btn btn-sm btn-info btn-icon">
                                                            <i class="fas fa-edit"></i>
                                                            Edit
                                                        </a>
                                                        <form
                                                            action="{{ route('attendance-schedules.destroy', $sch->id) }}"
                                                            method="POST" class="ml-2">
                                                            <input type="hidden" name="_method" value="DELETE" />
                                                            <input type="hidden" name="_token"
                                                                value="{{ csrf_token() }}" />
                                                            <button class="btn btn-sm btn-danger btn-icon confirm-delete">
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
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('js/page/features-posts.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.toggle-switch').change(function() {
                let scheduleId = $(this).data('id');
                let newStatus = $(this).prop('checked') ? 1 : 0;

                $.ajax({
                    url: '{{ route('attendance-schedules.toggleStatus') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: scheduleId,
                        is_open: newStatus
                    },
                    success: function(response) {
                        alert(response.message);
                    },
                    error: function(xhr) {
                        alert('An error occurred while updating status');
                    }
                });
            });
        });
    </script>
@endpush

