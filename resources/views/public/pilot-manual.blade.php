@extends('app')
@section('title', 'Pilot Manual')

@section('content')
<div class="container-fluid" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); min-height: 100vh; padding: 20px;">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card" style="background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); border: 2px solid #ffc107;">
                <div class="card-header" style="background: linear-gradient(135deg, #ffc107 0%, #ffdb4d 100%); border-bottom: 2px solid #ffc107;">
                    <h1 style="color: black; margin: 0;"><i class="fas fa-book me-2"></i>Air Jamaica Virtual - Pilot Manual</h1>
                </div>
                <div class="card-body" style="background: #2c3e50;">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="list-group" style="background: transparent;">
                                <a href="#getting-started" class="list-group-item list-group-item-action" style="background: #34495e; border: 1px solid #495057; color: white; margin-bottom: 5px;">
                                    <i class="fas fa-play me-2" style="color: #ffc107;"></i>Getting Started
                                </a>
                                <a href="#flight-operations" class="list-group-item list-group-item-action" style="background: #34495e; border: 1px solid #495057; color: white; margin-bottom: 5px;">
                                    <i class="fas fa-plane me-2" style="color: #ffc107;"></i>Flight Operations
                                </a>
                                <a href="#pirep-filing" class="list-group-item list-group-item-action" style="background: #34495e; border: 1px solid #495057; color: white; margin-bottom: 5px;">
                                    <i class="fas fa-file-alt me-2" style="color: #ffc107;"></i>PIREP Filing
                                </a>
                                <a href="#acars-guide" class="list-group-item list-group-item-action" style="background: #34495e; border: 1px solid #495057; color: white; margin-bottom: 5px;">
                                    <i class="fas fa-satellite me-2" style="color: #ffc107;"></i>ACARS Guide
                                </a>
                                <a href="#rank-progression" class="list-group-item list-group-item-action" style="background: #34495e; border: 1px solid #495057; color: white; margin-bottom: 5px;">
                                    <i class="fas fa-star me-2" style="color: #ffc107;"></i>Rank Progression
                                </a>
                                <a href="#policies" class="list-group-item list-group-item-action" style="background: #34495e; border: 1px solid #495057; color: white; margin-bottom: 5px;">
                                    <i class="fas fa-gavel me-2" style="color: #ffc107;"></i>Policies & Rules
                                </a>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div id="getting-started" class="mb-4">
                                <h2 style="color: #ffc107; border-bottom: 2px solid #ffc107; padding-bottom: 10px; margin-bottom: 20px;">
                                    <i class="fas fa-play me-2"></i>Getting Started
                                </h2>
                                <p style="color: white;">Welcome to Air Jamaica Virtual! This manual will guide you through all aspects of flying with our virtual airline.</p>
                                
                                <h4 style="color: #28a745; margin-top: 25px;">Account Setup</h4>
                                <ul style="color: white;">
                                    <li>Complete your profile information</li>
                                    <li>Set your home airport</li>
                                    <li>Configure notification preferences</li>
                                    <li>Join our Discord community</li>
                                </ul>

                                <h4 style="color: #28a745; margin-top: 25px;">First Flight</h4>
                                <ol style="color: white;">
                                    <li>Browse available flights</li>
                                    <li>Bid on a suitable route</li>
                                    <li>Download flight plan with SimBrief</li>
                                    <li>Complete your flight</li>
                                    <li>File your PIREP</li>
                                </ol>
                            </div>

                            <div id="flight-operations" class="mb-4">
                                <h2 style="color: #ffc107; border-bottom: 2px solid #ffc107; padding-bottom: 10px; margin-bottom: 20px;">
                                    <i class="fas fa-plane me-2"></i>Flight Operations
                                </h2>
                                
                                <h4 style="color: #28a745; margin-top: 25px;">Bidding System</h4>
                                <p style="color: white;">Our bidding system allows you to reserve flights. You can have multiple active bids, but please only bid on flights you intend to complete.</p>

                                <h4 style="color: #28a745; margin-top: 25px;">Flight Planning</h4>
                                <ul style="color: white;">
                                    <li>Use SimBrief for realistic flight planning</li>
                                    <li>Check weather conditions</li>
                                    <li>Review NOTAMs for your route</li>
                                    <li>Calculate fuel requirements</li>
                                </ul>

                                <h4 style="color: #28a745; margin-top: 25px;">Aircraft Selection</h4>
                                <p style="color: white;">Choose appropriate aircraft for your route. Consider:</p>
                                <ul style="color: white;">
                                    <li>Route distance and aircraft range</li>
                                    <li>Airport runway requirements</li>
                                    <li>Your pilot rank and aircraft restrictions</li>
                                </ul>
                            </div>

                            <div id="pirep-filing" class="mb-4">
                                <h2 style="color: #ffc107; border-bottom: 2px solid #ffc107; padding-bottom: 10px; margin-bottom: 20px;">
                                    <i class="fas fa-file-alt me-2"></i>PIREP Filing
                                </h2>
                                
                                <h4 style="color: #28a745; margin-top: 25px;">Manual PIREPs</h4>
                                <p style="color: white;">For manual PIREP filing, provide accurate information including:</p>
                                <ul style="color: white;">
                                    <li>Actual flight time</li>
                                    <li>Fuel used</li>
                                    <li>Landing rate (if known)</li>
                                    <li>Any incidents or deviations</li>
                                </ul>

                                <h4 style="color: #28a745; margin-top: 25px;">ACARS PIREPs</h4>
                                <p style="color: white;">ACARS automatically tracks your flight data and files PIREPs. Ensure your ACARS client is properly configured.</p>

                                <h4 style="color: #28a745; margin-top: 25px;">PIREP Review Process</h4>
                                <p style="color: white;">All PIREPs undergo review by our operations team. Factors considered include:</p>
                                <ul style="color: white;">
                                    <li>Flight time accuracy</li>
                                    <li>Route adherence</li>
                                    <li>Landing quality</li>
                                    <li>Fuel efficiency</li>
                                </ul>
                            </div>

                            <div id="acars-guide" class="mb-4">
                                <h2 style="color: #ffc107; border-bottom: 2px solid #ffc107; padding-bottom: 10px; margin-bottom: 20px;">
                                    <i class="fas fa-satellite me-2"></i>ACARS Guide
                                </h2>
                                
                                <h4 style="color: #28a745; margin-top: 25px;">Supported ACARS Clients</h4>
                                <ul style="color: white;">
                                    <li>smartCARS 3 - Primary ACARS client for Air Jamaica Virtual operations</li>
                                </ul>

                                <h4 style="color: #28a745; margin-top: 25px;">ACARS Setup</h4>
                                <ol style="color: white;">
                                    <li>Download ACARS client from Downloads section</li>
                                    <li>Configure with your pilot ID and API key</li>
                                    <li>Test connection before flight</li>
                                    <li>Start tracking before engine start</li>
                                    <li>Stop tracking after engine shutdown</li>
                                </ol>

                                <h4 style="color: #28a745; margin-top: 25px;">ACARS Benefits</h4>
                                <ul style="color: white;">
                                    <li>Automatic PIREP filing</li>
                                    <li>Real-time flight tracking</li>
                                    <li>Accurate flight data</li>
                                    <li>Higher pay rates for ACARS flights</li>
                                </ul>
                            </div>

                            <div id="rank-progression" class="mb-4">
                                <h2 style="color: #ffc107; border-bottom: 2px solid #ffc107; padding-bottom: 10px; margin-bottom: 20px;">
                                    <i class="fas fa-star me-2"></i>Rank Progression
                                </h2>
                                
                                <h4 style="color: #28a745; margin-top: 25px;">Pilot Ranks</h4>
                                <div class="table-responsive">
                                    <table class="table table-dark table-striped" style="background: #34495e;">
                                        <thead style="background: #ffc107;">
                                            <tr>
                                                <th style="color: black;">Rank</th>
                                                <th style="color: black;">Hours Required</th>
                                                <th style="color: black;">ACARS Pay Rate</th>
                                                <th style="color: black;">Manual Pay Rate</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($ranks as $rank)
                                            <tr>
                                                <td style="color: white;">{{ $rank->name }}</td>
                                                <td style="color: white;">{{ number_format($rank->hours) }}</td>
                                                <td style="color: white;">${{ number_format($rank->acars_base_pay_rate ?? 0, 2) }}</td>
                                                <td style="color: white;">${{ number_format($rank->manual_base_pay_rate ?? 0, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <h4 style="color: #28a745; margin-top: 25px;">Awards System</h4>
                                <p style="color: white;">Earn awards for various achievements:</p>
                                <ul style="color: white;">
                                    <li>Flight Hours Milestones</li>
                                    <li>Perfect Landing Awards (Landing rate < 150 fpm)</li>
                                    <li>Route Completion Certificates</li>
                                    <li>Safety Record Recognition</li>
                                    <li>Fuel Efficiency Excellence</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Aircraft Fleet Section -->
                        <div class="card mb-4" style="background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 50%, #2a2f3e 100%); border: 1px solid #00A651;">
                            <div class="card-header" style="background: linear-gradient(90deg, #00A651, #FED100); border-bottom: 2px solid #00A651;">
                                <h2 class="mb-0" style="color: black; font-weight: bold;">
                                    <i class="fas fa-plane me-2"></i>Aircraft Fleet
                                </h2>
                            </div>
                            <div class="card-body">
                                <h4 style="color: #28a745; margin-top: 15px;">Active Aircraft</h4>
                                <div class="table-responsive">
                                    <table class="table table-dark table-striped" style="background: #34495e;">
                                        <thead style="background: #ffc107;">
                                            <tr>
                                                <th style="color: black;">Aircraft</th>
                                                <th style="color: black;">Registration</th>
                                                <th style="color: black;">Type</th>
                                                <th style="color: black;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($aircraft as $craft)
                                            <tr>
                                                <td style="color: white;">{{ $craft->name }}</td>
                                                <td style="color: white;">{{ $craft->registration }}</td>
                                                <td style="color: white;">{{ $craft->subfleet->name ?? 'General Aviation' }}</td>
                                                <td style="color: white;">
                                                    @if($craft->active)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-warning">Inactive</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" style="color: white; text-align: center;">No aircraft available</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <h4 style="color: #28a745; margin-top: 25px;">Fleet Statistics</h4>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card" style="background: #34495e; border: 1px solid #00A651;">
                                            <div class="card-body text-center">
                                                <h5 style="color: #FED100;">Total Aircraft</h5>
                                                <h3 style="color: white;">{{ $aircraft->count() }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card" style="background: #34495e; border: 1px solid #00A651;">
                                            <div class="card-body text-center">
                                                <h5 style="color: #FED100;">Aircraft Types</h5>
                                                <h3 style="color: white;">{{ $aircraft->groupBy('subfleet.name')->count() }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card" style="background: #34495e; border: 1px solid #00A651;">
                                            <div class="card-body text-center">
                                                <h5 style="color: #FED100;">Active Pilots</h5>
                                                <h3 style="color: white;">{{ $stats['active_pilots'] }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card" style="background: #34495e; border: 1px solid #00A651;">
                                            <div class="card-body text-center">
                                                <h5 style="color: #FED100;">Total Flights</h5>
                                                <h3 style="color: white;">{{ number_format($stats['total_flights']) }}</h3>
                                            </div>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Training and Career Development -->
                        <div class="card mb-4" style="background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 50%, #2a2f3e 100%); border: 1px solid #00A651;">
                            <div class="card-header" style="background: linear-gradient(90deg, #00A651, #FED100); border-bottom: 2px solid #00A651;">
                                <h2 class="mb-0" style="color: black; font-weight: bold;">
                                    <i class="fas fa-graduation-cap me-2"></i>Training & Career Development
                                </h2>
                            </div>
                            <div class="card-body">
                                <h4 style="color: #28a745;">Type Rating Requirements</h4>
                                <p style="color: white;">Pilots must complete type rating for each aircraft category:</p>
                                
                                <div class="row">
                                    @php
                                        $aircraft_types = $aircraft->groupBy('subfleet.name');
                                    @endphp
                                    @foreach($aircraft_types as $type => $aircrafts)
                                    <div class="col-md-6 mb-3">
                                        <div class="card" style="background: #34495e; border: 1px solid #00A651;">
                                            <div class="card-body">
                                                <h5 style="color: #FED100;">{{ $type ?: 'General Aviation' }}</h5>
                                                <p style="color: white; margin: 0;">Aircraft: {{ $aircrafts->count() }}</p>
                                                <p style="color: white; margin: 0; font-size: 14px;">
                                                    {{ $aircrafts->pluck('name')->join(', ') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <h4 style="color: #28a745; margin-top: 25px;">Training Courses</h4>
                                @if($trainingCourses->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-dark table-striped" style="background: #34495e;">
                                        <thead style="background: #ffc107;">
                                            <tr>
                                                <th style="color: black;">Course Title</th>
                                                <th style="color: black;">Category</th>
                                                <th style="color: black;">Duration</th>
                                                <th style="color: black;">Requirements</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($trainingCourses as $course)
                                            <tr>
                                                <td style="color: white;">{{ $course->title }}</td>
                                                <td style="color: white;">{{ ucfirst($course->category) }}</td>
                                                <td style="color: white;">
                                                    @if($course->estimated_hours)
                                                        {{ $course->estimated_hours }} hours
                                                    @else
                                                        Variable
                                                    @endif
                                                </td>
                                                <td style="color: white;">
                                                    @if($course->is_mandatory)
                                                        <span class="badge bg-danger">Mandatory</span>
                                                    @else
                                                        <span class="badge bg-info">Optional</span>
                                                    @endif
                                                    @if($course->requires_instructor)
                                                        <span class="badge bg-warning">Instructor Required</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <p style="color: white;">Training courses will be available once set up by Air Jamaica Virtual management.</p>
                                @endif

                                <h4 style="color: #28a745; margin-top: 25px;">Performance Standards</h4>
                                <p style="color: white;">Performance expectations and any incentive programs will be communicated through official Air Jamaica Virtual announcements and management channels.</p>
                            </div>
                        </div>

                        <!-- Live Statistics Dashboard -->
                        @if($user)
                        <div class="card mb-4" style="background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 50%, #2a2f3e 100%); border: 1px solid #00A651;">
                            <div class="card-header" style="background: linear-gradient(90deg, #00A651, #FED100); border-bottom: 2px solid #00A651;">
                                <h2 class="mb-0" style="color: black; font-weight: bold;">
                                    <i class="fas fa-chart-line me-2"></i>Your Performance Dashboard
                                </h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card text-center" style="background: #34495e; border: 1px solid #00A651;">
                                            <div class="card-body">
                                                <h5 style="color: #FED100;">Total Flights</h5>
                                                <h3 style="color: white;">{{ number_format($user->flights) }}</h3>
                                                <p style="color: white; font-size: 12px;">Career flights completed</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card text-center" style="background: #34495e; border: 1px solid #00A651;">
                                            <div class="card-body">
                                                <h5 style="color: #FED100;">Flight Hours</h5>
                                                <h3 style="color: white;">{{ number_format($user->flight_time / 60, 1) }}</h3>
                                                <p style="color: white; font-size: 12px;">Total logged hours</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card text-center" style="background: #34495e; border: 1px solid #00A651;">
                                            <div class="card-body">
                                                <h5 style="color: #FED100;">Current Rank</h5>
                                                <h3 style="color: white;">{{ $user->rank->name ?? 'N/A' }}</h3>
                                                <p style="color: white; font-size: 12px;">Your current pilot rank</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card text-center" style="background: #34495e; border: 1px solid #00A651;">
                                            <div class="card-body">
                                                <h5 style="color: #FED100;">Home Base</h5>
                                                <h3 style="color: white;">{{ $user->home_airport_id ?? 'N/A' }}</h3>
                                                <p style="color: white; font-size: 12px;">Your assigned hub</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Company Policies and Contact -->
                        <div class="card mb-4" style="background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 50%, #2a2f3e 100%); border: 1px solid #00A651;">
                            <div class="card-header" style="background: linear-gradient(90deg, #00A651, #FED100); border-bottom: 2px solid #00A651;">
                                <h2 class="mb-0" style="color: black; font-weight: bold;">
                                    <i class="fas fa-handshake me-2"></i>Company Policies & Support
                                </h2>
                            </div>
                            <div class="card-body">
                                <h4 style="color: #28a745;">Code of Conduct</h4>
                                <div class="alert" style="background: #1976d2; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                                    <ul style="color: white; margin: 0;">
                                        <li>Maintain professional conduct at all times</li>
                                        <li>Follow all standard operating procedures</li>
                                        <li>Report any incidents or safety concerns immediately</li>
                                        <li>Respect fellow pilots and staff members</li>
                                        <li>Use appropriate communication channels</li>
                                    </ul>
                                </div>

                                <h4 style="color: #28a745;">Technical Support</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card" style="background: #34495e; border: 1px solid #00A651;">
                                            <div class="card-body">
                                                <h5 style="color: #FED100;"><i class="fas fa-envelope me-2"></i>Contact Management</h5>
                                                <p style="color: white;">For technical issues, route requests, or general inquiries, 
                                                contact the Air Jamaica Virtual management team through the website's contact form.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card" style="background: #34495e; border: 1px solid #00A651;">
                                            <div class="card-body">
                                                <h5 style="color: #FED100;"><i class="fas fa-life-ring me-2"></i>Flight Support</h5>
                                                <p style="color: white;">Need help with ACARS setup, route planning, or flight procedures? 
                                                Check our documentation or reach out to experienced pilots in the community.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <p style="color: #FED100; font-size: 18px; font-weight: bold;">
                                        <i class="fas fa-plane me-2"></i>Welcome to Air Jamaica Virtual - Fly Safe, Fly Professional!
                                    </p>
                                </div>

                        <!-- Standard Operating Procedures -->
                        <div class="card mb-4" style="background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 50%, #2a2f3e 100%); border: 1px solid #00A651;">
                            <div class="card-header" style="background: linear-gradient(90deg, #00A651, #FED100); border-bottom: 2px solid #00A651;">
                                <h2 class="mb-0" style="color: black; font-weight: bold;">
                                    <i class="fas fa-clipboard-check me-2"></i>Standard Operating Procedures
                                </h2>
                            </div>
                            <div class="card-body">
                                <h4 style="color: #28a745;">Pre-Flight Procedures</h4>
                                <div class="checklist" style="background: #34495e; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                                    <ol style="color: white;">
                                        <li>Review flight plan and weather conditions</li>
                                        <li>Complete aircraft inspection checklist</li>
                                        <li>Verify fuel quantity and passenger/cargo load</li>
                                        <li>File flight plan with ATC</li>
                                        <li>Brief cabin crew on safety procedures</li>
                                        <li>Start ACARS client and connect to network</li>
                                    </ol>
                                </div>

                                <h4 style="color: #28a745;">Taxi and Takeoff</h4>
                                <div class="checklist" style="background: #34495e; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                                    <ol style="color: white;">
                                        <li>Request taxi clearance from ground control</li>
                                        <li>Complete taxi checklist</li>
                                        <li>Contact tower for takeoff clearance</li>
                                        <li>Perform final checks before takeoff roll</li>
                                        <li>Execute smooth rotation (target: 150-200 fpm climb rate)</li>
                                        <li>Maintain noise abatement procedures where applicable</li>
                                    </ol>
                                </div>

                                <h4 style="color: #28a745;">Landing Procedures</h4>
                                <div class="checklist" style="background: #34495e; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                                    <ol style="color: white;">
                                        <li>Configure aircraft for approach</li>
                                        <li>Maintain stable approach profile</li>
                                        <li>Target landing rate: <strong style="color: #FED100;">-150 fpm or less for excellent rating</strong></li>
                                        <li>Apply appropriate braking and reverse thrust</li>
                                        <li>Taxi to gate as directed by ground control</li>
                                        <li>Complete shutdown checklist</li>
                                    </ol>
                                </div>

                                <h4 style="color: #28a745;">Emergency Procedures</h4>
                                <div class="alert" style="background: #d32f2f; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                                    <p style="color: white; margin: 0;">
                                        <strong><i class="fas fa-exclamation-triangle me-2"></i>Emergency Protocol:</strong>
                                        In case of emergency, prioritize aircraft safety. Contact ATC immediately, 
                                        declare emergency if necessary, and follow aircraft-specific emergency checklists.
                                        Report incidents to Air Jamaica Virtual management within 24 hours.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- ACARS and Flight Tracking -->
                        <div class="card mb-4" style="background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 50%, #2a2f3e 100%); border: 1px solid #00A651;">
                            <div class="card-header" style="background: linear-gradient(90deg, #00A651, #FED100); border-bottom: 2px solid #00A651;">
                                <h2 class="mb-0" style="color: black; font-weight: bold;">
                                    <i class="fas fa-satellite me-2"></i>ACARS & Flight Tracking
                                </h2>
                            </div>
                            <div class="card-body">
                                <h4 style="color: #28a745;">Supported ACARS Clients</h4>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card" style="background: #34495e; border: 1px solid #00A651; margin-bottom: 15px;">
                                            <div class="card-body">
                                                <h5 style="color: #FED100;"><i class="fas fa-desktop me-2"></i>smartCARS 3</h5>
                                                <p style="color: white;">Primary ACARS client for Air Jamaica Virtual flight operations</p>
                                                <ul style="color: white; font-size: 14px;">
                                                    <li>Automatic flight logging</li>
                                                    <li>Real-time position tracking</li>
                                                    <li>Fuel and passenger management</li>
                                                    <li>Direct Air Jamaica Virtual integration</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h4 style="color: #28a745; margin-top: 25px;">Flight Planning Integration</h4>
                                <div class="alert" style="background: #2e7d32; padding: 15px; border-radius: 8px;">
                                    <p style="color: white; margin: 0;">
                                        <strong><i class="fas fa-route me-2"></i>SimBrief Integration:</strong>
                                        Air Jamaica Virtual is fully integrated with SimBrief for professional flight planning.
                                        Generate your flight plans at <a href="https://www.simbrief.com" target="_blank" style="color: #FED100;">simbrief.com</a>
                                        and import them directly into your ACARS client.
                                    </p>
                                </div>

                                <h4 style="color: #28a745; margin-top: 25px;">Performance Tracking</h4>
                                <div class="table-responsive">
                                    <table class="table table-dark table-striped" style="background: #34495e;">
                                        <thead style="background: #ffc107;">
                                            <tr>
                                                <th style="color: black;">Metric</th>
                                                <th style="color: black;">Excellent</th>
                                                <th style="color: black;">Good</th>
                                                <th style="color: black;">Acceptable</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="color: white;">Landing Rate</td>
                                                <td style="color: #28a745;">≤ -150 fpm</td>
                                                <td style="color: #ffc107;">-151 to -300 fpm</td>
                                                <td style="color: #dc3545;">> -300 fpm</td>
                                            </tr>
                                            <tr>
                                                <td style="color: white;">Fuel Efficiency</td>
                                                <td style="color: #28a745;">± 5% of planned</td>
                                                <td style="color: #ffc107;">± 10% of planned</td>
                                                <td style="color: #dc3545;">> 10% variance</td>
                                            </tr>
                                            <tr>
                                                <td style="color: white;">On-Time Performance</td>
                                                <td style="color: #28a745;">± 5 minutes</td>
                                                <td style="color: #ffc107;">± 15 minutes</td>
                                                <td style="color: #dc3545;">> 15 minutes</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                        <!-- Route Network Section -->
                        <div class="card mb-4" style="background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 50%, #2a2f3e 100%); border: 1px solid #00A651;">
                            <div class="card-header" style="background: linear-gradient(90deg, #00A651, #FED100); border-bottom: 2px solid #00A651;">
                                <h2 class="mb-0" style="color: black; font-weight: bold;">
                                    <i class="fas fa-map me-2"></i>Route Network
                                </h2>
                            </div>
                            <div class="card-body">
                                <h4 style="color: #28a745; margin-top: 15px;">Available Routes</h4>
                                <div class="table-responsive">
                                    <table class="table table-dark table-striped" style="background: #34495e;">
                                        <thead style="background: #ffc107;">
                                            <tr>
                                                <th style="color: black;">Flight #</th>
                                                <th style="color: black;">From</th>
                                                <th style="color: black;">To</th>
                                                <th style="color: black;">Aircraft Type</th>
                                                <th style="color: black;">Distance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($routes->take(10) as $route)
                                            <tr>
                                                <td style="color: white;">{{ $route->flight_number }}</td>
                                                <td style="color: white;">{{ $route->dpt_airport_id }} - {{ $route->dpt_airport->name ?? 'Unknown' }}</td>
                                                <td style="color: white;">{{ $route->arr_airport_id }} - {{ $route->arr_airport->name ?? 'Unknown' }}</td>
                                                <td style="color: white;">{{ $route->subfleets->pluck('name')->join(', ') ?: 'Any' }}</td>
                                                <td style="color: white;">{{ is_numeric($route->distance) ? number_format($route->distance) : $route->distance }} NM</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" style="color: white; text-align: center;">No routes available</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if($routes->count() > 10)
                                <p style="color: #FED100; text-align: center; margin-top: 15px;">
                                    <i class="fas fa-info-circle me-2"></i>Showing first 10 routes. Total available routes: {{ $routes->count() }}
                                </p>
                                @endif

                                <h4 style="color: #28a745; margin-top: 25px;">Network Statistics</h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card" style="background: #34495e; border: 1px solid #00A651;">
                                            <div class="card-body text-center">
                                                <h5 style="color: #FED100;">Total Routes</h5>
                                                <h3 style="color: white;">{{ $routes->count() }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card" style="background: #34495e; border: 1px solid #00A651;">
                                            <div class="card-body text-center">
                                                <h5 style="color: #FED100;">Airports Served</h5>
                                                <h3 style="color: white;">{{ $airports->count() }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card" style="background: #34495e; border: 1px solid #00A651;">
                                            <div class="card-body text-center">
                                                <h5 style="color: #FED100;">Total Distance</h5>
                                                <h3 style="color: white;">{{ is_numeric($stats['total_distance']) ? number_format($stats['total_distance']) : $stats['total_distance'] }} NM</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <li>Flight hour milestones</li>
                                    <li>Route completions</li>
                                    <li>Perfect landing streaks</li>
                                    <li>Safety records</li>
                                </ul>
                            </div>

                            <div id="policies" class="mb-4">
                                <h2 style="color: #ffc107; border-bottom: 2px solid #ffc107; padding-bottom: 10px; margin-bottom: 20px;">
                                    <i class="fas fa-gavel me-2"></i>Policies & Rules
                                </h2>
                                
                                <h4 style="color: #28a745; margin-top: 25px;">Flight Rules</h4>
                                <ul style="color: white;">
                                    <li>Complete flights within reasonable time limits</li>
                                    <li>Use appropriate aircraft for routes</li>
                                    <li>Follow ATC instructions during online flights</li>
                                    <li>Maintain professional conduct</li>
                                </ul>

                                <h4 style="color: #28a745; margin-top: 25px;">Prohibited Activities</h4>
                                <ul style="color: white;">
                                    <li>Flight simulation while paused</li>
                                    <li>Unrealistic flight times</li>
                                    <li>Multiple accounts</li>
                                    <li>Sharing account credentials</li>
                                </ul>

                                <h4 style="color: #28a745; margin-top: 25px;">Reporting Issues</h4>
                                <p style="color: white;">If you encounter technical issues or have questions:</p>
                                <ul style="color: white;">
                                    <li>Check this manual first</li>
                                    <li>Visit our FAQ section</li>
                                    <li>Contact support through the website</li>
                                    <li>Submit a support ticket</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Highlight active navigation item
document.addEventListener('scroll', function() {
    const sections = document.querySelectorAll('[id]');
    const navLinks = document.querySelectorAll('.list-group-item');
    
    let current = '';
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        if (window.pageYOffset >= sectionTop - 100) {
            current = section.getAttribute('id');
        }
    });
    
    navLinks.forEach(link => {
        link.style.background = '#34495e';
        link.style.borderLeft = '3px solid transparent';
        if (link.getAttribute('href') === '#' + current) {
            link.style.background = '#495057';
            link.style.borderLeft = '3px solid #ffc107';
        }
    });
});
</script>
@endsection