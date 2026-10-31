@extends('layouts.seven.auth.auth_layout')
@section('title', __('auth.register'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="logo-container mb-4 text-center">
                <img src="{{ public_asset('/assets/img/air-jamaica-logo.png') }}" width="100" height="100" alt="Air Jamaica Virtual">
                <h3 class="text-center text-light mt-3 mb-0">Join Air Jamaica Virtual</h3>
                <p class="text-center text-light opacity-75">Create your pilot account and start your aviation journey</p>
            </div>
            <form method="post" action="{{ url('/register') }}" class="form-signin">
                @csrf
                <div class="card shadow-lg" style="background: #000000; backdrop-filter: blur(10px); color: white;">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, var(--jamaica-green) 0%, var(--jamaica-yellow) 100%); color: black !important;">
                        <h2 class="mb-0 text-center" style="color: black !important; font-weight: bold;">@lang('common.register')</h2>
                    </div>
                    <div class="card-body p-4" style="color: white;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label" style="color: white;">@lang('auth.fullname')</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background: var(--jamaica-green); border-color: var(--jamaica-green); color: white;"><i class="bi bi-person"></i></span>
                                        <input type="text" name="name" id="name"
                                            class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" style="border-left: none; background: #333; color: white; border-color: #555;" />
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label" style="color: white;">@lang('auth.emailaddress')</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background: var(--jamaica-green); border-color: var(--jamaica-green); color: white;"><i class="bi bi-envelope"></i></span>
                                        <input type="email" name="email" id="email"
                                            class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" style="border-left: none; background: #333; color: white; border-color: #555;" />
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="airline_id" class="form-label" style="color: white;">@lang('common.airline')</label>
                                    <select name="airline_id" id="airline_id"
                                        class="form-select select2 @error('airline_id') is-invalid @enderror" style="background: #333; color: white; border-color: #555;">
                                        @foreach ($airlines as $airline_id => $airline_label)
                                            <option value="{{ $airline_id }}" @if ($airline_id === old('airline_id')) selected @endif>
                                                {{ $airline_label }}</option>
                                        @endforeach
                                    </select>
                                    @error('airline_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="home_airport_id" class="form-label" style="color: white;">@lang('airports.home')</label>
                                    <select name="home_airport_id" id="home_airport_id"
                                        class="form-select airport_search @if ($hubs_only) hubs_only @endif @error('home_airport_id') is-invalid @enderror" style="background: #333; color: white; border-color: #555;">
                                        @foreach ($airports as $airport_id => $airport_label)
                                            <option value="{{ $airport_id }}">{{ $airport_label }}</option>
                                        @endforeach
                                    </select>
                                    @error('home_airport_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="country" class="form-label" style="color: white;">@lang('common.country')</label>
                                    <select name="country" id="country"
                                        class="form-select select2 @error('country') is-invalid @enderror" style="background: #333; color: white; border-color: #555;">
                                        @foreach ($countries as $country_id => $country_label)
                                            <option value="{{ $country_id }}" @if ($country_id === old('country')) selected @endif>
                                                {{ $country_label }}</option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="timezone" class="form-label" style="color: white;">@lang('common.timezone')</label>
                                    <select name="timezone" id="timezone"
                                        class="form-select @error('timezone') is-invalid @enderror" style="background: #333; color: white; border-color: #555;">
                                        @php
                                            $selectedTimezone = old('timezone', 'UTC');
                                            
                                            // Comprehensive timezone list organized by region
                                            $timezonesByRegion = [
                                                'General' => [
                                                    'UTC' => 'UTC (Coordinated Universal Time)',
                                                ],
                                                'North America - United States' => [
                                                    'America/New_York' => 'Eastern Time (New York, Miami, Washington DC, Boston)',
                                                    'America/Chicago' => 'Central Time (Chicago, Dallas, Houston, New Orleans)',
                                                    'America/Denver' => 'Mountain Time (Denver, Phoenix, Salt Lake City, Albuquerque)',
                                                    'America/Los_Angeles' => 'Pacific Time (Los Angeles, San Francisco, Seattle, Las Vegas)',
                                                    'America/Anchorage' => 'Alaska Time (Anchorage, Fairbanks, Juneau)',
                                                    'America/Honolulu' => 'Hawaii Time (Honolulu)',
                                                    'America/Phoenix' => 'Arizona (Phoenix, Tucson)',
                                                    'America/Detroit' => 'Eastern Time (Detroit)',
                                                    'America/Indianapolis' => 'Eastern Time (Indianapolis)',
                                                    'America/Boise' => 'Mountain Time (Boise)',
                                                    'America/Juneau' => 'Alaska Time (Juneau)',
                                                ],
                                                'North America - Canada' => [
                                                    'America/Toronto' => 'Eastern Time (Toronto, Montreal, Ottawa, Quebec)',
                                                    'America/Winnipeg' => 'Central Time (Winnipeg, Regina)',
                                                    'America/Edmonton' => 'Mountain Time (Edmonton, Calgary)',
                                                    'America/Vancouver' => 'Pacific Time (Vancouver, Victoria)',
                                                    'America/Halifax' => 'Atlantic Time (Halifax, Charlottetown)',
                                                    'America/St_Johns' => 'Newfoundland Time (St. John\'s)',
                                                    'America/Whitehorse' => 'Yukon Time (Whitehorse)',
                                                    'America/Yellowknife' => 'Mountain Time (Yellowknife)',
                                                    'America/Iqaluit' => 'Eastern Time (Iqaluit)',
                                                ],
                                                'North America - Mexico & Central America' => [
                                                    'America/Mexico_City' => 'Central Time (Mexico City, Guadalajara, Monterrey)',
                                                    'America/Cancun' => 'Eastern Time (Cancun, Merida)',
                                                    'America/Mazatlan' => 'Mountain Time (Mazatlan, Chihuahua)',
                                                    'America/Tijuana' => 'Pacific Time (Tijuana, Ensenada)',
                                                    'America/Merida' => 'Central Time (Merida)',
                                                    'America/Guatemala' => 'Central Time (Guatemala City)',
                                                    'America/Managua' => 'Central Time (Managua)',
                                                    'America/San_Jose' => 'Central Time (San Jose, Costa Rica)',
                                                    'America/Panama' => 'Eastern Time (Panama City)',
                                                    'America/Tegucigalpa' => 'Central Time (Tegucigalpa)',
                                                    'America/El_Salvador' => 'Central Time (San Salvador)',
                                                    'America/Belize' => 'Central Time (Belize City)',
                                                ],
                                                'South America' => [
                                                    'America/Sao_Paulo' => 'Brasilia Time (Sao Paulo, Rio de Janeiro, Brasilia)',
                                                    'America/Buenos_Aires' => 'Argentina Time (Buenos Aires, Cordoba, Rosario)',
                                                    'America/Santiago' => 'Chile Time (Santiago, Valparaiso)',
                                                    'America/Bogota' => 'Colombia Time (Bogota, Medellin, Cali)',
                                                    'America/Lima' => 'Peru Time (Lima, Arequipa)',
                                                    'America/Caracas' => 'Venezuela Time (Caracas, Maracaibo)',
                                                    'America/La_Paz' => 'Bolivia Time (La Paz, Santa Cruz)',
                                                    'America/Asuncion' => 'Paraguay Time (Asuncion)',
                                                    'America/Montevideo' => 'Uruguay Time (Montevideo)',
                                                    'America/Guayaquil' => 'Ecuador Time (Guayaquil, Quito)',
                                                    'America/Fortaleza' => 'Brasilia Time (Fortaleza, Recife)',
                                                    'America/Manaus' => 'Amazon Time (Manaus)',
                                                    'America/Belem' => 'Brasilia Time (Belem)',
                                                ],
                                                'Europe - Western' => [
                                                    'Europe/London' => 'London (GMT/BST)',
                                                    'Europe/Dublin' => 'Dublin',
                                                    'Europe/Lisbon' => 'Lisbon, Porto',
                                                    'Europe/Paris' => 'Paris, Lyon, Marseille',
                                                    'Europe/Amsterdam' => 'Amsterdam, Rotterdam',
                                                    'Europe/Brussels' => 'Brussels, Antwerp',
                                                    'Europe/Luxembourg' => 'Luxembourg',
                                                    'Europe/Monaco' => 'Monaco',
                                                    'Europe/Madrid' => 'Madrid, Seville',
                                                    'Europe/Barcelona' => 'Barcelona, Valencia',
                                                    'Europe/Gibraltar' => 'Gibraltar',
                                                    'Europe/Andorra' => 'Andorra',
                                                ],
                                                'Europe - Central & Eastern' => [
                                                    'Europe/Berlin' => 'Berlin, Munich, Frankfurt',
                                                    'Europe/Rome' => 'Rome, Milan, Naples',
                                                    'Europe/Vienna' => 'Vienna, Salzburg',
                                                    'Europe/Prague' => 'Prague, Brno',
                                                    'Europe/Budapest' => 'Budapest',
                                                    'Europe/Warsaw' => 'Warsaw, Krakow',
                                                    'Europe/Stockholm' => 'Stockholm, Gothenburg',
                                                    'Europe/Copenhagen' => 'Copenhagen, Aarhus',
                                                    'Europe/Oslo' => 'Oslo, Bergen',
                                                    'Europe/Helsinki' => 'Helsinki, Tampere',
                                                    'Europe/Athens' => 'Athens, Thessaloniki',
                                                    'Europe/Bucharest' => 'Bucharest, Cluj-Napoca',
                                                    'Europe/Sofia' => 'Sofia, Plovdiv',
                                                    'Europe/Zagreb' => 'Zagreb, Split',
                                                    'Europe/Belgrade' => 'Belgrade, Novi Sad',
                                                    'Europe/Sarajevo' => 'Sarajevo',
                                                    'Europe/Skopje' => 'Skopje',
                                                    'Europe/Tirane' => 'Tirana',
                                                    'Europe/Zurich' => 'Zurich, Geneva',
                                                    'Europe/Bratislava' => 'Bratislava',
                                                    'Europe/Ljubljana' => 'Ljubljana',
                                                ],
                                                'Europe - Russia & CIS' => [
                                                    'Europe/Moscow' => 'Moscow, St. Petersburg',
                                                    'Europe/Kiev' => 'Kiev, Kharkiv',
                                                    'Europe/Minsk' => 'Minsk',
                                                    'Europe/Riga' => 'Riga',
                                                    'Europe/Tallinn' => 'Tallinn',
                                                    'Europe/Vilnius' => 'Vilnius',
                                                    'Europe/Kaliningrad' => 'Kaliningrad',
                                                    'Europe/Istanbul' => 'Istanbul, Ankara',
                                                    'Asia/Yerevan' => 'Yerevan',
                                                    'Asia/Baku' => 'Baku',
                                                    'Asia/Tbilisi' => 'Tbilisi',
                                                    'Asia/Almaty' => 'Almaty, Astana',
                                                    'Asia/Tashkent' => 'Tashkent',
                                                    'Asia/Dushanbe' => 'Dushanbe',
                                                    'Asia/Ashgabat' => 'Ashgabat',
                                                    'Asia/Bishkek' => 'Bishkek',
                                                ],
                                                'Middle East' => [
                                                    'Asia/Dubai' => 'Dubai, Abu Dhabi, Sharjah',
                                                    'Asia/Muscat' => 'Muscat',
                                                    'Asia/Kuwait' => 'Kuwait City',
                                                    'Asia/Riyadh' => 'Riyadh, Jeddah, Mecca',
                                                    'Asia/Baghdad' => 'Baghdad, Basra',
                                                    'Asia/Tehran' => 'Tehran, Isfahan, Mashhad',
                                                    'Asia/Jerusalem' => 'Jerusalem, Tel Aviv, Haifa',
                                                    'Asia/Beirut' => 'Beirut, Tripoli',
                                                    'Asia/Damascus' => 'Damascus, Aleppo',
                                                    'Asia/Amman' => 'Amman',
                                                    'Asia/Qatar' => 'Doha',
                                                    'Asia/Bahrain' => 'Manama',
                                                    'Asia/Aden' => 'Aden, Sana\'a',
                                                    'Asia/Nicosia' => 'Nicosia',
                                                ],
                                                'Asia - South' => [
                                                    'Asia/Karachi' => 'Karachi, Islamabad, Lahore',
                                                    'Asia/Kolkata' => 'Mumbai, New Delhi, Bangalore, Chennai',
                                                    'Asia/Dhaka' => 'Dhaka, Chittagong',
                                                    'Asia/Colombo' => 'Colombo, Kandy',
                                                    'Asia/Kathmandu' => 'Kathmandu',
                                                    'Asia/Thimphu' => 'Thimphu',
                                                    'Asia/Male' => 'Male',
                                                    'Asia/Kabul' => 'Kabul',
                                                ],
                                                'Asia - Southeast' => [
                                                    'Asia/Bangkok' => 'Bangkok, Chiang Mai',
                                                    'Asia/Singapore' => 'Singapore',
                                                    'Asia/Kuala_Lumpur' => 'Kuala Lumpur, Penang',
                                                    'Asia/Jakarta' => 'Jakarta, Bandung, Surabaya',
                                                    'Asia/Makassar' => 'Makassar, Balikpapan',
                                                    'Asia/Jayapura' => 'Jayapura, Papua',
                                                    'Asia/Manila' => 'Manila, Cebu, Davao',
                                                    'Asia/Ho_Chi_Minh' => 'Ho Chi Minh City, Hanoi',
                                                    'Asia/Phnom_Penh' => 'Phnom Penh',
                                                    'Asia/Vientiane' => 'Vientiane',
                                                    'Asia/Yangon' => 'Yangon, Mandalay',
                                                    'Asia/Brunei' => 'Bandar Seri Begawan',
                                                ],
                                                'Asia - East' => [
                                                    'Asia/Hong_Kong' => 'Hong Kong',
                                                    'Asia/Shanghai' => 'Beijing, Shanghai, Guangzhou',
                                                    'Asia/Taipei' => 'Taipei, Kaohsiung',
                                                    'Asia/Tokyo' => 'Tokyo, Osaka, Yokohama',
                                                    'Asia/Seoul' => 'Seoul, Busan',
                                                    'Asia/Pyongyang' => 'Pyongyang',
                                                    'Asia/Ulaanbaatar' => 'Ulaanbaatar',
                                                    'Asia/Macau' => 'Macau',
                                                ],
                                                'Africa - North' => [
                                                    'Africa/Cairo' => 'Cairo, Alexandria',
                                                    'Africa/Casablanca' => 'Casablanca, Rabat, Marrakech',
                                                    'Africa/Algiers' => 'Algiers, Oran',
                                                    'Africa/Tunis' => 'Tunis, Sfax',
                                                    'Africa/Tripoli' => 'Tripoli, Benghazi',
                                                    'Africa/Nouakchott' => 'Nouakchott',
                                                ],
                                                'Africa - West' => [
                                                    'Africa/Lagos' => 'Lagos, Abuja, Kano',
                                                    'Africa/Accra' => 'Accra, Kumasi',
                                                    'Africa/Abidjan' => 'Abidjan',
                                                    'Africa/Dakar' => 'Dakar',
                                                    'Africa/Conakry' => 'Conakry',
                                                    'Africa/Bamako' => 'Bamako',
                                                    'Africa/Ouagadougou' => 'Ouagadougou',
                                                    'Africa/Niamey' => 'Niamey',
                                                    'Africa/Porto-Novo' => 'Porto-Novo, Cotonou',
                                                    'Africa/Lome' => 'Lome',
                                                    'Africa/Monrovia' => 'Monrovia',
                                                    'Africa/Freetown' => 'Freetown',
                                                ],
                                                'Africa - East' => [
                                                    'Africa/Nairobi' => 'Nairobi, Mombasa',
                                                    'Africa/Addis_Ababa' => 'Addis Ababa',
                                                    'Africa/Dar_es_Salaam' => 'Dar es Salaam, Zanzibar',
                                                    'Africa/Kampala' => 'Kampala',
                                                    'Africa/Khartoum' => 'Khartoum, Omdurman',
                                                    'Africa/Mogadishu' => 'Mogadishu',
                                                    'Africa/Djibouti' => 'Djibouti',
                                                    'Africa/Asmara' => 'Asmara',
                                                    'Africa/Kigali' => 'Kigali',
                                                    'Africa/Bujumbura' => 'Bujumbura',
                                                    'Africa/Lubumbashi' => 'Lubumbashi',
                                                    'Africa/Lusaka' => 'Lusaka',
                                                    'Africa/Blantyre' => 'Blantyre, Lilongwe',
                                                ],
                                                'Africa - South' => [
                                                    'Africa/Johannesburg' => 'Johannesburg, Cape Town, Durban',
                                                    'Africa/Harare' => 'Harare, Bulawayo',
                                                    'Africa/Gaborone' => 'Gaborone',
                                                    'Africa/Maputo' => 'Maputo, Beira',
                                                    'Africa/Windhoek' => 'Windhoek',
                                                    'Africa/Maseru' => 'Maseru',
                                                    'Africa/Mbabane' => 'Mbabane',
                                                ],
                                                'Australia & New Zealand' => [
                                                    'Australia/Sydney' => 'Sydney, Melbourne, Canberra',
                                                    'Australia/Brisbane' => 'Brisbane, Gold Coast',
                                                    'Australia/Adelaide' => 'Adelaide',
                                                    'Australia/Darwin' => 'Darwin',
                                                    'Australia/Perth' => 'Perth',
                                                    'Australia/Hobart' => 'Hobart',
                                                    'Australia/Lord_Howe' => 'Lord Howe Island',
                                                    'Pacific/Auckland' => 'Auckland, Wellington, Christchurch',
                                                    'Pacific/Chatham' => 'Chatham Islands',
                                                ],
                                                'Pacific Islands' => [
                                                    'Pacific/Honolulu' => 'Honolulu, Hawaii',
                                                    'Pacific/Fiji' => 'Fiji (Suva)',
                                                    'Pacific/Guam' => 'Guam',
                                                    'Pacific/Port_Moresby' => 'Port Moresby, Papua New Guinea',
                                                    'Pacific/Noumea' => 'Noumea, New Caledonia',
                                                    'Pacific/Tahiti' => 'Tahiti, French Polynesia',
                                                    'Pacific/Apia' => 'Apia, Samoa',
                                                    'Pacific/Tongatapu' => 'Nuku\'alofa, Tonga',
                                                    'Pacific/Pago_Pago' => 'Pago Pago, American Samoa',
                                                    'Pacific/Rarotonga' => 'Rarotonga, Cook Islands',
                                                    'Pacific/Niue' => 'Niue',
                                                    'Pacific/Palau' => 'Palau',
                                                    'Pacific/Port_Vila' => 'Port Vila, Vanuatu',
                                                    'Pacific/Honiara' => 'Honiara, Solomon Islands',
                                                    'Pacific/Nauru' => 'Nauru',
                                                    'Pacific/Tarawa' => 'Tarawa, Kiribati',
                                                    'Pacific/Majuro' => 'Majuro, Marshall Islands',
                                                ],
                                                'Atlantic Islands' => [
                                                    'Atlantic/Azores' => 'Azores',
                                                    'Atlantic/Madeira' => 'Madeira',
                                                    'Atlantic/Canary' => 'Canary Islands',
                                                    'Atlantic/Cape_Verde' => 'Cape Verde',
                                                    'Atlantic/Reykjavik' => 'Reykjavik, Iceland',
                                                    'Atlantic/Faroe' => 'Faroe Islands',
                                                    'Atlantic/Bermuda' => 'Bermuda',
                                                ],
                                                'Indian Ocean' => [
                                                    'Indian/Mauritius' => 'Mauritius',
                                                    'Indian/Reunion' => 'Reunion',
                                                    'Indian/Seychelles' => 'Seychelles',
                                                    'Indian/Maldives' => 'Maldives',
                                                    'Indian/Comoro' => 'Comoros',
                                                    'Indian/Mahe' => 'Seychelles',
                                                    'Indian/Chagos' => 'Chagos',
                                                    'Indian/Christmas' => 'Christmas Island',
                                                    'Indian/Cocos' => 'Cocos Islands',
                                                ],
                                                'Antarctica' => [
                                                    'Antarctica/McMurdo' => 'McMurdo Station',
                                                    'Antarctica/Davis' => 'Davis Station',
                                                    'Antarctica/Mawson' => 'Mawson Station',
                                                ],
                                            ];
                                        @endphp
                                        @foreach ($timezonesByRegion as $region => $timezones)
                                            <optgroup label="{{ $region }}">
                                                @foreach ($timezones as $tz_id => $tz_label)
                                                    <option value="{{ $tz_id }}" @if ($tz_id === $selectedTimezone) selected @endif>
                                                        {{ $tz_label }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    @error('timezone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                @if (setting('pilots.allow_transfer_hours') === true)
                                    <div class="mb-3">
                                        <label for="transfer_time" class="form-label" style="color: white;">@lang('auth.transferhours')</label>
                                        <div class="input-group">
                                            <span class="input-group-text" style="background: var(--jamaica-green); border-color: var(--jamaica-green); color: white;"><i class="bi bi-clock"></i></span>
                                            <input type="number" name="transfer_time" id="transfer_time"
                                                class="form-control @error('transfer_time') is-invalid @enderror"
                                                value="{{ old('transfer_time') }}" style="border-left: none; background: #333; color: white; border-color: #555;" />
                                        </div>
                                        @error('transfer_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label for="password" class="form-label" style="color: white;">@lang('auth.password')</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background: var(--jamaica-green); border-color: var(--jamaica-green); color: white;"><i class="bi bi-lock"></i></span>
                                        <input type="password" name="password" id="password"
                                            class="form-control @error('password') is-invalid @enderror" style="border-left: none; background: #333; color: white; border-color: #555;" />
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label" style="color: white;">@lang('passwords.confirm')</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background: var(--jamaica-green); border-color: var(--jamaica-green); color: white;"><i class="bi bi-shield-lock"></i></span>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="form-control @error('password_confirmation') is-invalid @enderror" style="border-left: none; background: #333; color: white; border-color: #555;" />
                                    </div>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @if ($userFields)
                            @foreach ($userFields as $field)
                                <div class="mb-3">
                                    <label for="field_{{ $field->slug }}" class="form-label" style="color: white;">{{ $field->name }}</label>
                                    <input type="text" name="field_{{ $field->slug }}" id="field_{{ $field->slug }}"
                                        class="form-control @error('field_' . $field->slug) is-invalid @enderror"
                                        value="{{ old('field_' . $field->slug) }}" style="background: #333; color: white; border-color: #555;" />
                                    @error('field_' . $field->slug)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        @endif

                        @if (!empty($captcha['site_key']) && $captcha['enabled'] === true)
                            <div class="mb-3">
                                <label for="g-recaptcha" class="form-label" style="color: white;">@lang('auth.fillcaptcha')</label>
                                <div class="g-recaptcha" data-sitekey="{{ $captcha['site_key'] }}" id="recaptcha-container" style="margin-bottom: 10px;"></div>
                                <div id="recaptcha-status" style="color: #ffcc00; font-size: 0.8rem;">
                                    If the reCAPTCHA doesn't appear, please refresh the page or try another browser.
                                </div>
                                @error('g-recaptcha-response')
                                    <div class="invalid-feedback" style="display: block; color: #ff6b6b;">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        @if ($invite)
                            <input type="hidden" name="invite" value="{{ $invite->id }}" />
                            <input type="hidden" name="invite_token" value="{{ base64_encode($invite->token) }}" />
                        @endif

                        <div class="mb-3">
                            @include('auth.toc')
                            <br />
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input @error('toc_accepted') is-invalid @enderror" type="checkbox"
                                name="toc_accepted" id="toc_accepted">
                            <label class="form-check-label" for="toc_accepted" style="color: white;">
                                @lang('auth.tocaccept')
                            </label>
                            @error('toc_accepted')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="hidden" name="opt_in" value="0" />
                            <input class="form-check-input" type="checkbox" name="opt_in" id="opt_in"
                                value="1" />
                            <label class="form-check-label" for="opt_in" style="color: white;">
                                @lang('profile.opt-in-descrip')
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-lg py-3" id="register_button" style="background: linear-gradient(135deg, var(--jamaica-green) 0%, var(--jamaica-yellow) 100%); border: none; color: black; font-weight: bold;">
                                <i class="bi bi-person-plus me-2"></i>@lang('auth.register')
                            </button>
                        </div>
                    </div>
                    <div class="card-footer text-center" style="background: rgba(0, 166, 81, 0.1); border-top: 1px solid var(--jamaica-green);">
                        <p class="mb-0">Already have an account? 
                            <a href="{{ url('/login') }}" class="text-decoration-none" style="color: var(--jamaica-green); font-weight: 600;">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Sign In Here
                            </a>
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@php
    // The parent view is responsible for loading scripts and initializing widgets.
@endphp

@section('scripts')
<script>
    // Ensure reCAPTCHA is properly loaded
    window.addEventListener('load', function() {
        // Check if reCAPTCHA is loaded after a delay
        setTimeout(function() {
            var recaptchaContainer = document.getElementById('recaptcha-container');
            var statusMessage = document.getElementById('recaptcha-status');
            
            if (recaptchaContainer && !recaptchaContainer.querySelector('iframe') && typeof grecaptcha === 'undefined') {
                console.log("reCAPTCHA not loading - attempting direct script injection");
                
                // Try direct script injection
                var recaptchaScript = document.createElement('script');
                recaptchaScript.src = "https://www.google.com/recaptcha/api.js";
                recaptchaScript.async = true;
                recaptchaScript.defer = true;
                document.head.appendChild(recaptchaScript);
                
                if (statusMessage) {
                    statusMessage.innerHTML = "Loading reCAPTCHA directly... Please wait.";
                }
            } else if (recaptchaContainer && recaptchaContainer.querySelector('iframe')) {
                console.log("reCAPTCHA loaded successfully");
                if (statusMessage) {
                    statusMessage.style.display = "none";
                }
            }
        }, 1500);
    });
</script>
@endsection
