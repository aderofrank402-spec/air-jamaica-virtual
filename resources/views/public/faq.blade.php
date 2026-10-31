@extends('app')
@section('title', 'FAQ - Frequently Asked Questions')

@section('content')
<div class="container-fluid" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); min-height: 100vh; padding: 20px;">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card" style="background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); border: 2px solid #ffc107;">
                <div class="card-header" style="background: linear-gradient(135deg, #ffc107 0%, #ffdb4d 100%); border-bottom: 2px solid #ffc107;">
                    <h1 style="color: black; margin: 0;"><i class="fas fa-question-circle me-2"></i>Frequently Asked Questions</h1>
                    <p class="mb-0" style="color: black;">Find answers to common questions about Air Jamaica Virtual</p>
                </div>
                <div class="card-body" style="background: #2c3e50;">
                    <!-- Search Box -->
                    <div class="row mb-4">
                        <div class="col-md-8 mx-auto">
                            <div class="input-group">
                                <input type="text" class="form-control" id="faq-search" placeholder="Search FAQs..." style="background: #34495e; border: 1px solid #495057; color: white;">
                                <div class="input-group-append">
                                    <span class="input-group-text" style="background: #ffc107; border: 1px solid #ffc107; color: black;"><i class="fas fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="btn-group-toggle d-flex flex-wrap gap-2" data-toggle="buttons">
                                <button class="btn active" data-category="all" style="background: #ffc107; color: black; border: 1px solid #ffc107; margin-right: 5px; margin-bottom: 5px;">All</button>
                                <button class="btn" data-category="getting-started" style="background: #34495e; color: white; border: 1px solid #495057; margin-right: 5px; margin-bottom: 5px;">Getting Started</button>
                                <button class="btn" data-category="flights" style="background: #34495e; color: white; border: 1px solid #495057; margin-right: 5px; margin-bottom: 5px;">Flights & Routes</button>
                                <button class="btn" data-category="acars" style="background: #34495e; color: white; border: 1px solid #495057; margin-right: 5px; margin-bottom: 5px;">ACARS & Technology</button>
                                <button class="btn" data-category="ranks" style="background: #34495e; color: white; border: 1px solid #495057; margin-right: 5px; margin-bottom: 5px;">Ranks & Awards</button>
                                <button class="btn" data-category="account" style="background: #34495e; color: white; border: 1px solid #495057; margin-right: 5px; margin-bottom: 5px;">Account & Profile</button>
                                <button class="btn" data-category="troubleshooting" style="background: #34495e; color: white; border: 1px solid #495057; margin-right: 5px; margin-bottom: 5px;">Troubleshooting</button>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Accordion -->
                    <div class="accordion" id="faqAccordion">
                        
                        <!-- Getting Started FAQs -->
                        <div class="faq-item" data-category="getting-started">
                            <div class="card" style="background: #34495e; border: 1px solid #495057; margin-bottom: 10px;">
                                <div class="card-header" style="background: #495057; border-bottom: 1px solid #ffc107;">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" style="color: white; text-decoration: none; font-weight: 500;">
                                            <i class="fas fa-play me-2" style="color: #ffc107;"></i>How do I join Air Jamaica Virtual?
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapse1" class="collapse" data-bs-parent="#faqAccordion">
                                    <div class="card-body" style="color: white;">
                                        <p>To join Air Jamaica Virtual:</p>
                                        <ol>
                                            <li>Click "Register" on our homepage</li>
                                            <li>Fill out the registration form with accurate information</li>
                                            <li>Wait for approval from Air Jamaica Virtual management</li>
                                            <li>Once approved, complete your profile</li>
                                            <li>Start flying and enjoy!</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="faq-item" data-category="getting-started">
                            <div class="card" style="background: #34495e; border: 1px solid #495057; margin-bottom: 10px;">
                                <div class="card-header" style="background: #495057; border-bottom: 1px solid #ffc107;">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" style="color: white; text-decoration: none; font-weight: 500;">
                                            <i class="fas fa-plane me-2" style="color: #ffc107;"></i>What flight simulator do I need?
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapse2" class="collapse" data-bs-parent="#faqAccordion">
                                    <div class="card-body" style="color: white;">
                                        <p>Air Jamaica Virtual supports multiple flight simulators:</p>
                                        <ul>
                                            <li><strong style="color: #ffc107;">Microsoft Flight Simulator (2020)</strong> - Recommended</li>
                                            <li><strong style="color: #ffc107;">Prepar3D v4/v5</strong> - Fully supported</li>
                                            <li><strong style="color: #ffc107;">X-Plane 11/12</strong> - Supported with ACARS</li>
                                            <li><strong style="color: #ffc107;">FSX</strong> - Legacy support</li>
                                        </ul>
                                        <p>You can also file manual PIREPs if you don't have ACARS support for your simulator.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Flights & Routes FAQs -->
                        <div class="faq-item" data-category="flights">
                            <div class="card" style="background: #34495e; border: 1px solid #495057; margin-bottom: 10px;">
                                <div class="card-header" style="background: #495057; border-bottom: 1px solid #ffc107;">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" style="color: white; text-decoration: none; font-weight: 500;">
                                            <i class="fas fa-route me-2" style="color: #ffc107;"></i>How do I find and bid on flights?
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapse3" class="collapse" data-bs-parent="#faqAccordion">
                                    <div class="card-body" style="color: white;">
                                        <p>Finding and bidding on flights is easy:</p>
                                        <ol>
                                            <li>Go to the "Flights" section from the main menu</li>
                                            <li>Use filters to find flights by departure/arrival airports</li>
                                            <li>Click on a flight to see details</li>
                                            <li>Click "Bid" to reserve the flight</li>
                                            <li>The flight will appear in your "My Bids" section</li>
                                        </ol>
                                        <p><strong style="color: #ffc107;">Tip:</strong> You can have multiple active bids, but please only bid on flights you plan to complete soon.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="faq-item" data-category="flights">
                            <div class="card" style="background: #34495e; border: 1px solid #495057; margin-bottom: 10px;">
                                <div class="card-header" style="background: #495057; border-bottom: 1px solid #ffc107;">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" style="color: white; text-decoration: none; font-weight: 500;">
                                            <i class="fas fa-file-alt me-2" style="color: #ffc107;"></i>How do I file a PIREP?
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapse4" class="collapse" data-bs-parent="#faqAccordion">
                                    <div class="card-body" style="color: white;">
                                        <p>You can file PIREPs in two ways:</p>
                                        
                                        <h5 style="color: #28a745;">Manual PIREP:</h5>
                                        <ol>
                                            <li>Go to "PIREPs" → "File New PIREP"</li>
                                            <li>Select your flight from active bids</li>
                                            <li>Fill in flight details (time, fuel, landing rate)</li>
                                            <li>Add any comments or screenshots</li>
                                            <li>Submit for review</li>
                                        </ol>

                                        <h5 style="color: #28a745;">ACARS PIREP (Recommended):</h5>
                                        <ol>
                                            <li>Start your ACARS client before flight</li>
                                            <li>Select your bid in ACARS</li>
                                            <li>Begin tracking before engine start</li>
                                            <li>Fly your route normally</li>
                                            <li>End tracking after engine shutdown</li>
                                            <li>PIREP is automatically filed!</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ACARS & Technology FAQs -->
                        <div class="faq-item" data-category="acars">
                            <div class="card" style="background: #34495e; border: 1px solid #495057; margin-bottom: 10px;">
                                <div class="card-header" style="background: #495057; border-bottom: 1px solid #ffc107;">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" style="color: white; text-decoration: none; font-weight: 500;">
                                            <i class="fas fa-satellite me-2" style="color: #ffc107;"></i>What is ACARS and do I need it?
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapse5" class="collapse" data-bs-parent="#faqAccordion">
                                    <div class="card-body" style="color: white;">
                                        <p><strong style="color: #ffc107;">ACARS</strong> (Aircraft Communications Addressing and Reporting System) is software that tracks your flight automatically.</p>
                                        
                                        <h5 style="color: #28a745;">Benefits of ACARS:</h5>
                                        <ul>
                                            <li>Automatic PIREP filing</li>
                                            <li>Real-time flight tracking</li>
                                            <li>Higher pay rates (typically 2x manual rates)</li>
                                            <li>More accurate flight data</li>
                                            <li>Live map integration</li>
                                        </ul>

                                        <p><strong style="color: #ffc107;">Do you need it?</strong> No, ACARS is optional. You can fly manually and file manual PIREPs, but ACARS provides a much better experience and higher pay.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ranks & Awards FAQ -->
                        <div class="faq-item" data-category="ranks">
                            <div class="card" style="background: #34495e; border: 1px solid #495057; margin-bottom: 10px;">
                                <div class="card-header" style="background: #495057; border-bottom: 1px solid #ffc107;">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6" style="color: white; text-decoration: none; font-weight: 500;">
                                            <i class="fas fa-star me-2" style="color: #ffc107;"></i>How do pilot ranks work?
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapse6" class="collapse" data-bs-parent="#faqAccordion">
                                    <div class="card-body" style="color: white;">
                                        <p>Pilot ranks are based on your total flight hours:</p>
                                        @if($ranks->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-dark table-striped" style="background: #495057;">
                                                    <thead style="background: #ffc107;">
                                                        <tr>
                                                            <th style="color: black; font-weight: 600;">Rank</th>
                                                            <th style="color: black; font-weight: 600;">Hours Required</th>
                                                            <th style="color: black; font-weight: 600;">ACARS Pay Rate</th>
                                                            <th style="color: black; font-weight: 600;">Manual Pay Rate</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($ranks as $rank)
                                                        <tr>
                                                            <td style="color: white;">{{ $rank->name }}</td>
                                                            <td style="color: white;">{{ number_format($rank->hours) }} hours</td>
                                                            <td style="color: #28a745;">${{ number_format($rank->acars_base_pay_rate ?? 0, 2) }}</td>
                                                            <td style="color: #28a745;">${{ number_format($rank->manual_base_pay_rate ?? 0, 2) }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <p><strong style="color: #ffc107;">Note:</strong> Ranks and pay rates are updated automatically as you accumulate flight hours.</p>
                                        @else
                                            <p>Rank information is being configured. Please contact Air Jamaica Virtual management for current details.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account & Profile FAQ -->
                        <div class="faq-item" data-category="account">
                            <div class="card" style="background: #34495e; border: 1px solid #495057; margin-bottom: 10px;">
                                <div class="card-header" style="background: #495057; border-bottom: 1px solid #ffc107;">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse7" style="color: white; text-decoration: none; font-weight: 500;">
                                            <i class="fas fa-user me-2" style="color: #ffc107;"></i>How do I update my profile?
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapse7" class="collapse" data-bs-parent="#faqAccordion">
                                    <div class="card-body" style="color: white;">
                                        <p>To update your pilot profile:</p>
                                        <ol>
                                            <li>Log into your account</li>
                                            <li>Click your name in the top-right corner</li>
                                            <li>Select "Edit Profile"</li>
                                            <li>Update your information</li>
                                            <li>Upload a new avatar if desired</li>
                                            <li>Save your changes</li>
                                        </ol>
                                        
                                        <p><strong style="color: #ffc107;">Important:</strong> Keep your email address current for important notifications.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Air Jamaica Virtual Fleet & Network FAQs -->
                        <div class="faq-item" data-category="flights">
                            <div class="card" style="background: #34495e; border: 1px solid #495057; margin-bottom: 10px;">
                                <div class="card-header" style="background: #495057; border-bottom: 1px solid #ffc107;">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFleet" style="color: white; text-decoration: none; font-weight: 500;">
                                            <i class="fas fa-plane me-2" style="color: #ffc107;"></i>What aircraft can I fly with Air Jamaica Virtual?
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseFleet" class="collapse" data-bs-parent="#faqAccordion">
                                    <div class="card-body" style="color: white;">
                                        @if($aircraft_types->count() > 0)
                                            <p>Air Jamaica Virtual currently operates the following aircraft types:</p>
                                            <ul>
                                                @foreach($aircraft_types as $type => $aircrafts)
                                                    <li><strong style="color: #ffc107;">{{ $type ?: 'General Aviation' }}</strong> - {{ $aircrafts->count() }} aircraft</li>
                                                @endforeach
                                            </ul>
                                            <p><strong style="color: #28a745;">Total Fleet Size:</strong> {{ $stats['total_aircraft'] }} aircraft</p>
                                        @else
                                            <p>Our aircraft fleet is currently being configured. Please check back soon for available aircraft information.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="faq-item" data-category="getting-started">
                            <div class="card" style="background: #34495e; border: 1px solid #495057; margin-bottom: 10px;">
                                <div class="card-header" style="background: #495057; border-bottom: 1px solid #ffc107;">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseStats" style="color: white; text-decoration: none; font-weight: 500;">
                                            <i class="fas fa-chart-bar me-2" style="color: #ffc107;"></i>How big is Air Jamaica Virtual?
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseStats" class="collapse" data-bs-parent="#faqAccordion">
                                    <div class="card-body" style="color: white;">
                                        <p>Here are our current live statistics:</p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <ul style="list-style: none; padding-left: 0;">
                                                    <li><i class="fas fa-users" style="color: #ffc107;"></i> <strong>Active Pilots:</strong> {{ number_format($stats['total_pilots']) }}</li>
                                                    <li><i class="fas fa-plane" style="color: #ffc107;"></i> <strong>Aircraft Fleet:</strong> {{ number_format($stats['total_aircraft']) }}</li>
                                                    <li><i class="fas fa-route" style="color: #ffc107;"></i> <strong>Available Routes:</strong> {{ number_format($stats['total_routes']) }}</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <ul style="list-style: none; padding-left: 0;">
                                                    <li><i class="fas fa-map-marker-alt" style="color: #ffc107;"></i> <strong>Airports Served:</strong> {{ number_format($stats['total_airports']) }}</li>
                                                    <li><i class="fas fa-file-alt" style="color: #ffc107;"></i> <strong>Flights Completed:</strong> {{ number_format($stats['total_flights_completed']) }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <p style="color: #28a745; margin-top: 15px;"><em>Statistics updated in real-time from our database.</em></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="faq-item" data-category="ranks">
                            <div class="card" style="background: #34495e; border: 1px solid #495057; margin-bottom: 10px;">
                                <div class="card-header" style="background: #495057; border-bottom: 1px solid #ffc107;">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePayRates" style="color: white; text-decoration: none; font-weight: 500;">
                                            <i class="fas fa-dollar-sign me-2" style="color: #ffc107;"></i>How much do pilots earn at Air Jamaica Virtual?
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapsePayRates" class="collapse" data-bs-parent="#faqAccordion">
                                    <div class="card-body" style="color: white;">
                                        <p>Pay rates vary by rank and flight type. Here are our starting rates:</p>
                                        @if($ranks->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-sm table-dark">
                                                    <thead style="background: #ffc107; color: black;">
                                                        <tr>
                                                            <th>Rank</th>
                                                            <th>ACARS Rate</th>
                                                            <th>Manual Rate</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($ranks as $rank)
                                                        <tr>
                                                            <td style="color: white;">{{ $rank->name }}</td>
                                                            <td style="color: #28a745;">${{ number_format($rank->acars_base_pay_rate ?? 0, 2) }}</td>
                                                            <td style="color: #28a745;">${{ number_format($rank->manual_base_pay_rate ?? 0, 2) }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <p><strong style="color: #ffc107;">Note:</strong> Rates increase with rank progression and flight hours.</p>
                                        @else
                                            <p>Pay rates are being configured. Please contact Air Jamaica Virtual management for current rate information.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="faq-item" data-category="flights">
                            <div class="card" style="background: #34495e; border: 1px solid #495057; margin-bottom: 10px;">
                                <div class="card-header" style="background: #495057; border-bottom: 1px solid #ffc107;">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHubs" style="color: white; text-decoration: none; font-weight: 500;">
                                            <i class="fas fa-map-marker-alt me-2" style="color: #ffc107;"></i>What are Air Jamaica Virtual's main hubs?
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseHubs" class="collapse" data-bs-parent="#faqAccordion">
                                    <div class="card-body" style="color: white;">
                                        @if($hubs->count() > 0)
                                            <p>Our pilots are based at the following hub airports:</p>
                                            <ul>
                                                @foreach($hubs as $hub)
                                                    <li><strong style="color: #ffc107;">{{ $hub->id }}</strong> - {{ $hub->name }}</li>
                                                @endforeach
                                            </ul>
                                            <p>You can choose any of these airports as your home base when you join Air Jamaica Virtual.</p>
                                        @else
                                            <p>Hub information will be available once pilot bases are established.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Troubleshooting FAQs -->
                        <div class="faq-item" data-category="troubleshooting">
                            <div class="card" style="background: #34495e; border: 1px solid #495057; margin-bottom: 10px;">
                                <div class="card-header" style="background: #495057; border-bottom: 1px solid #ffc107;">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse8" style="color: white; text-decoration: none; font-weight: 500;">
                                            <i class="fas fa-exclamation-triangle me-2" style="color: #ffc107;"></i>My ACARS won't connect. What should I do?
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapse8" class="collapse" data-bs-parent="#faqAccordion">
                                    <div class="card-body" style="color: white;">
                                        <p>If your ACARS client won't connect, try these steps:</p>
                                        
                                        <h5 style="color: #28a745;">Check Your Settings:</h5>
                                        <ul>
                                            <li>Verify your Pilot ID is correct</li>
                                            <li>Check your API key in your profile</li>
                                            <li>Ensure the server URL is correct</li>
                                        </ul>

                                        <h5 style="color: #28a745;">Network Issues:</h5>
                                        <ul>
                                            <li>Check your internet connection</li>
                                            <li>Temporarily disable firewall/antivirus</li>
                                            <li>Try running ACARS as administrator</li>
                                        </ul>

                                        <h5 style="color: #28a745;">Still Having Issues?</h5>
                                        <ul>
                                            <li>Submit a support ticket through the website</li>
                                            <li>Contact Air Jamaica Virtual management</li>
                                            <li>Include screenshots of error messages</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Contact Section -->
                    <div class="mt-5 text-center">
                        <div class="card" style="background: #34495e; border: 1px solid #495057;">
                            <div class="card-body">
                                <h3 style="color: #ffc107;"><i class="fas fa-headset me-2"></i>Still Need Help?</h3>
                                <p style="color: white;">Can't find the answer you're looking for? We're here to help!</p>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('frontend.contact') }}" class="btn" style="background: #ffc107; color: black; border: 1px solid #ffc107;">
                                        <i class="fas fa-envelope me-2"></i>Contact Support
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('faq-search');
    const faqItems = document.querySelectorAll('.faq-item');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        faqItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Category filtering
    const categoryButtons = document.querySelectorAll('[data-category]');
    
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            
            // Update active button
            categoryButtons.forEach(btn => {
                btn.style.background = '#34495e';
                btn.style.color = 'white';
                btn.classList.remove('active');
            });
            this.style.background = '#ffc107';
            this.style.color = 'black';
            this.classList.add('active');
            
            // Filter items
            faqItems.forEach(item => {
                if (category === 'all' || item.getAttribute('data-category') === category) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Hover effects for buttons
    categoryButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            if (!this.classList.contains('active')) {
                this.style.background = '#495057';
            }
        });
        
        button.addEventListener('mouseleave', function() {
            if (!this.classList.contains('active')) {
                this.style.background = '#34495e';
            }
        });
    });
});
</script>
@endsection