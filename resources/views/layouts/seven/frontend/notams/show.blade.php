@extends('app')
@section('title', 'NOTAM Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="fas fa-exclamation-triangle text-warning me-2"></i>NOTAM Details</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('frontend.notams.index') }}">NOTAMs</a></li>
                            <li class="breadcrumb-item active">{{ $notam->identifier }}</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('frontend.notams.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to NOTAMs
                </a>
            </div>

            {{-- NOTAM Details Card --}}
            <div class="row">
                <div class="col-lg-8">
                    <div class="card border-start border-{{ $notam->priority_color ?? 'secondary' }} border-4">
                        <div class="card-header bg-{{ $notam->priority_color ?? 'secondary' }} text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">
                                    <span class="badge bg-light text-dark me-2">{{ strtoupper($notam->identifier) }}</span>
                                    {{ $notam->title }}
                                </h4>
                                <span class="badge bg-light text-dark">
                                    {{ ucfirst($notam->priority) }} Priority
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            {{-- Description --}}
                            <div class="mb-4">
                                <h5>Description</h5>
                                <div class="alert alert-light">
                                    {{ $notam->description }}
                                </div>
                            </div>

                            {{-- Effective Period --}}
                            <div class="mb-4">
                                <h5>Effective Period</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>From:</strong><br>
                                        <i class="fas fa-calendar-alt text-success me-1"></i>
                                        {{ $notam->effective_from->format('F d, Y') }}<br>
                                        <i class="fas fa-clock text-success me-1"></i>
                                        {{ $notam->effective_from->format('H:i') }} UTC
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Until:</strong><br>
                                        @if($notam->effective_until)
                                            <i class="fas fa-calendar-alt text-danger me-1"></i>
                                            {{ $notam->effective_until->format('F d, Y') }}<br>
                                            <i class="fas fa-clock text-danger me-1"></i>
                                            {{ $notam->effective_until->format('H:i') }} UTC
                                        @else
                                            <span class="text-muted">Indefinite</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Status Indicator --}}
                            <div class="mb-3">
                                <h5>Status</h5>
                                @if($notam->isEffective())
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check-circle me-1"></i>Currently Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary fs-6">
                                        <i class="fas fa-clock me-1"></i>Not Currently Active
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar Info --}}
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">NOTAM Information</h5>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-4">Identifier:</dt>
                                <dd class="col-sm-8">{{ $notam->identifier }}</dd>

                                <dt class="col-sm-4">Airport:</dt>
                                <dd class="col-sm-8">
                                    <strong>{{ $notam->icao_code }}</strong>
                                    @if($notam->airport)
                                        <br><small class="text-muted">{{ $notam->airport->name }}</small>
                                    @endif
                                </dd>

                                <dt class="col-sm-4">Priority:</dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-{{ $notam->priority_color ?? 'secondary' }}">
                                        {{ ucfirst($notam->priority) }}
                                    </span>
                                </dd>

                                <dt class="col-sm-4">Issued By:</dt>
                                <dd class="col-sm-8">
                                    @if($notam->user)
                                        {{ $notam->user->name_private }}
                                    @else
                                        <span class="text-muted">System</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-4">Created:</dt>
                                <dd class="col-sm-8">
                                    {{ $notam->created_at->format('M d, Y H:i') }} UTC
                                </dd>

                                @if($notam->updated_at != $notam->created_at)
                                    <dt class="col-sm-4">Updated:</dt>
                                    <dd class="col-sm-8">
                                        {{ $notam->updated_at->format('M d, Y H:i') }} UTC
                                    </dd>
                                @endif
                            </dl>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('frontend.notams.index', ['icao' => $notam->icao_code]) }}" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-search me-1"></i>View NOTAMs for {{ $notam->icao_code }}
                                </a>
                                
                                @if($notam->icao_code)
                                    <a href="{{ route('frontend.airports.show', $notam->icao_code) }}" 
                                       class="btn btn-outline-info">
                                        <i class="fas fa-plane me-1"></i>Airport Information
                                    </a>
                                @endif
                                
                                <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                                    <i class="fas fa-print me-1"></i>Print NOTAM
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .card-header, nav {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endsection