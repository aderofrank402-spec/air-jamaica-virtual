@extends('app')
@section('title', $airport->full_name)

@section('content')
{{-- Breadcrumb Navigation --}}
@php
$breadcrumbs = [
    [
        'title' => 'Pilot Center',
        'url' => Auth::check() ? route('frontend.dashboard.index') : route('frontend.home'),
        'icon' => Auth::check() ? 'bi bi-speedometer2' : 'bi bi-house-fill'
    ],
    [
        'title' => 'Airport Details',
        'icon' => 'bi bi-airplane-fill'
    ],
    [
        'title' => $airport->icao . ' - ' . $airport->name,
        'icon' => 'bi bi-geo-alt-fill'
    ]
];
@endphp
@include('layouts.seven.components.breadcrumb', ['breadcrumbs' => $breadcrumbs])

<style>
  /* Air Jamaica Virtual Airport Details Page Styling */
  body {
    background: linear-gradient(135deg, #0f1419 0%, #1a2332 50%, #0f1419 100%);
    min-height: 100vh;
  }
  
  .content-wrapper {
    background: rgba(15, 20, 25, 0.95);
    border-radius: 12px;
    padding: 2rem;
    margin-top: 1rem;
    border: 1px solid rgba(25, 135, 84, 0.2);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
  }
  
  .airport-header {
    background: linear-gradient(135deg, #00a651 0%, #1976d2 100%);
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    color: white;
    box-shadow: 0 4px 16px rgba(0, 166, 81, 0.2);
    text-align: center;
  }
  
  .airport-header h2 {
    color: white;
    font-weight: 600;
    margin: 0;
    font-size: 2.2rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    margin-bottom: 0.5rem;
  }
  
  .airport-elevation {
    color: #ffc107;
    font-size: 1.1rem;
    font-weight: 500;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
  }
  
  .card {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    border: 1px solid rgba(25, 135, 84, 0.2);
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    margin-bottom: 1.5rem;
    overflow: hidden;
  }
  
  .card-header {
    background: linear-gradient(135deg, #00a651 0%, #1976d2 100%);
    color: white;
    border-radius: 0;
    padding: 1rem 1.5rem;
    font-weight: 600;
    border: none;
    font-size: 1.1rem;
  }
  
  .card-body {
    background: rgba(30, 41, 59, 0.8);
    color: #e2e8f0;
    padding: 1.5rem;
  }
  
  .table {
    background: rgba(30, 41, 59, 0.9);
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid rgba(25, 135, 84, 0.2);
    margin-bottom: 0;
  }
  
  .table th {
    background: linear-gradient(135deg, #00a651 0%, #1976d2 100%);
    color: white;
    border: none;
    padding: 1rem;
    font-weight: 600;
    font-size: 0.9rem;
  }
  
  .table td {
    background: rgba(30, 41, 59, 0.6);
    color: #e2e8f0;
    border-color: rgba(25, 135, 84, 0.1);
    padding: 0.75rem 1rem;
    vertical-align: middle;
  }
  
  .table-striped tbody tr:nth-of-type(odd) {
    background: rgba(30, 41, 59, 0.4);
  }
  
  .table-hover tbody tr:hover {
    background: rgba(30, 41, 59, 0.8);
  }
  
  .jumbotron {
    background: rgba(15, 23, 42, 0.6);
    border: 2px dashed rgba(25, 135, 84, 0.3);
    border-radius: 12px;
    padding: 2rem;
    color: #94a3b8;
    font-style: italic;
  }
  
  a {
    color: #00a651;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
  }
  
  a:hover {
    color: #ffc107;
    text-shadow: 0 2px 8px rgba(255, 193, 7, 0.5);
  }
  
  /* Weather widget styling */
  .weather-widget {
    background: rgba(15, 23, 42, 0.6);
    border-radius: 8px;
    padding: 1rem;
    border: 1px solid rgba(25, 135, 84, 0.2);
  }
  
  /* Map container styling */
  .map-container {
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid rgba(25, 135, 84, 0.3);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
  }
  
  /* Airport notes styling */
  .airport-notes {
    background: rgba(15, 23, 42, 0.6);
    border-radius: 8px;
    padding: 1rem;
    border: 1px solid rgba(25, 135, 84, 0.2);
    line-height: 1.6;
  }
  
  /* Downloads section styling */
  .downloads-section {
    background: rgba(15, 23, 42, 0.6);
    border-radius: 8px;
    padding: 1rem;
    border: 1px solid rgba(25, 135, 84, 0.2);
  }
  
  /* Flight sections styling */
  .flight-section-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .flight-direction-icon {
    font-size: 1.2rem;
  }
  
  /* Enhanced text styling */
  h2, h3, h4, h5, h6 {
    color: #e2e8f0;
    font-weight: 600;
  }
  
  .text-muted {
    color: #94a3b8 !important;
  }
  
  /* Section spacing */
  .section-gap {
    margin-top: 2rem;
  }
  
  /* Responsive adjustments */
  @media (max-width: 768px) {
    .content-wrapper {
      margin: 0.5rem;
      padding: 1rem;
    }
    
    .airport-header {
      padding: 1.5rem;
    }
    
    .airport-header h2 {
      font-size: 1.8rem;
    }
    
    .card-body {
      padding: 1rem;
    }
    
    .table th,
    .table td {
      padding: 0.5rem;
      font-size: 0.875rem;
    }
  }
</style>

<div class="container-fluid">
  <div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="airport-header">
                <h2>🏛️ {{ $airport->full_name }}</h2>
                @if (filled($airport->elevation))
                    <div class="airport-elevation">📊 {{ $airport->elevation }}ft elevation</div>
                @endif
            </div>
        </div>

        {{-- Show the weather widget in one column --}}
        <div class="col-5">
            <div class="card">
                <div class="card-header">
                    <div class="flight-section-header">
                        <span class="flight-direction-icon">🌤️</span>
                        @lang('dashboard.weatherat', ['ICAO' => $airport->icao])
                    </div>
                </div>
                <div class="card-body d-flex flex-column gap-4">
                    <div class="weather-widget">
                        {{ Widget::Weather(['icao' => $airport->icao]) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Show the airspace map in the other column --}}
        <div class="col-7">
            <div class="card">
                <div class="card-header">
                    <div class="flight-section-header">
                        <span class="flight-direction-icon">🗺️</span>
                        {{ __('common.map') }}
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="map-container">
                        {{ Widget::AirspaceMap(['width' => '100%', 'height' => '400px', 'lat' => $airport->lat, 'lon' => $airport->lon]) }}
                    </div>
                </div>
            </div>

            @if (filled($airport->notes))
                <div class="card mt-4">
                    <div class="card-header">
                        <div class="flight-section-header">
                            <span class="flight-direction-icon">📝</span>
                            {{ __('airports.notes') }}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="airport-notes">
                            {!! $airport->notes !!}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @if (count($airport->files) > 0 && Auth::check())
        {{-- There are files uploaded and a user is logged in --}}
        <div class="row section-gap">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flight-section-header">
                            <span class="flight-direction-icon">📥</span>
                            {{ trans_choice('common.download', 2) }}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="downloads-section">
                            @include('downloads.table', ['files' => $airport->files])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    <div class="row section-gap">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <div class="flight-section-header">
                        <span class="flight-direction-icon">🛬</span>
                        @lang('flights.inbound')
                    </div>
                </div>
                <div class="card-body">
                    @if (!$inbound_flights)
                        <div class="jumbotron text-center">
                            <span style="font-size: 2rem; color: #6b7280;">✈️</span><br>
                            @lang('flights.none')
                        </div>
                    @else
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-left">✈️ @lang('airports.ident')</th>
                                    <th class="text-left">🛫 @lang('airports.departure')</th>
                                    <th>⏰ @lang('flights.dep')</th>
                                    <th>⏰ @lang('flights.arr')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inbound_flights as $flight)
                                    <tr>
                                        <td class="text-left">
                                            <a href="{{ route('frontend.flights.show', [$flight->id]) }}">
                                                {{ $flight->ident }}
                                            </a>
                                        </td>
                                        <td class="text-left">{{ optional($flight->dpt_airport)->name }}
                                            (<a href="{{ route('frontend.airports.show', ['id' => $flight->dpt_airport_id]) }}">{{ $flight->dpt_airport_id }}</a>)
                                        </td>
                                        <td style="color: #ffc107;">{{ $flight->dpt_time }}</td>
                                        <td style="color: #ffc107;">{{ $flight->arr_time }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <div class="flight-section-header">
                        <span class="flight-direction-icon">🛫</span>
                        @lang('flights.outbound')
                    </div>
                </div>
                <div class="card-body">
                    @if (!$outbound_flights)
                        <div class="jumbotron text-center">
                            <span style="font-size: 2rem; color: #6b7280;">✈️</span><br>
                            @lang('flights.none')
                        </div>
                    @else
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-left">✈️ @lang('airports.ident')</th>
                                    <th class="text-left">🛬 @lang('airports.arrival')</th>
                                    <th>⏰ @lang('flights.dep')</th>
                                    <th>⏰ @lang('flights.arr')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($outbound_flights as $flight)
                                    <tr>
                                        <td class="text-left">
                                            <a href="{{ route('frontend.flights.show', [$flight->id]) }}">
                                                {{ $flight->ident }}
                                            </a>
                                        </td>
                                        <td class="text-left">{{ $flight->arr_airport->name }}
                                            (<a href="{{ route('frontend.airports.show', ['id' => $flight->arr_airport->icao]) }}">{{ $flight->arr_airport->icao }}</a>)
                                        </td>
                                        <td style="color: #ffc107;">{{ $flight->dpt_time }}</td>
                                        <td style="color: #ffc107;">{{ $flight->arr_time }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

  </div>
</div>
@endsection
