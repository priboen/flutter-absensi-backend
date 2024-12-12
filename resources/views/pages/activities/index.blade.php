@extends('layouts.app')

@section('title', 'Activities')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Activities</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Activities</div>
                </div>
            </div>
            <div class="section-body">
                <h2 class="section-title">{{ now()->format('F Y') }}</h2>
                <div class="row">
                    <div class="col-12">
                        <div class="activities">
                            @foreach ($activities as $activity)
                                <div class="activity">
                                    <div class="activity-icon bg-primary shadow-primary text-white">
                                        <i class="fa-solid fa-chart-line"></i>
                                    </div>
                                    <div class="activity-detail">
                                        <div class="mb-2">
                                            <span
                                                class="text-job text-primary">{{ $activity->created_at->diffForHumans() }}</span>
                                            <span class="bullet"></span>
                                        </div>
                                        <p>
                                            @if ($activity->causer)
                                                <strong>{{ $activity->causer->name }}</strong>
                                            @endif
                                            melakukan aktivitas: "<strong>{{ ucfirst($activity->description) }}</strong>".
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="pagination">
                            {{ $activities->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
