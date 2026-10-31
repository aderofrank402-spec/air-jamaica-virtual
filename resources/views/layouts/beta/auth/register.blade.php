@extends('app')
@section('title', __('auth.register'))

@section('content')
  <div class="row">
    <div class="col-sm-3"></div>
    <div class="col-sm-6">

      <form method="post" action="{{ url('/register') }}" class="form-signin">
      @csrf
      <div class="panel periodic-login">
        <div class="panel-body">
          <h2>@lang('common.register')</h2>
          <label for="name" class="control-label">@lang('auth.fullname')</label>
          <div class="input-group form-group-no-border {{ $errors->has('name') ? 'has-danger' : '' }}">
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" />
          </div>
          @if ($errors->has('name'))
            <p class="text-danger">{{ $errors->first('name') }}</p>
          @endif

          <label for="email" class="control-label">@lang('auth.emailaddress')</label>
          <div class="input-group form-group-no-border {{ $errors->has('email') ? 'has-danger' : '' }}">
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" />
          </div>
          @if ($errors->has('email'))
            <p class="text-danger">{{ $errors->first('email') }}</p>
          @endif

          <label for="airline" class="control-label">@lang('common.airline')</label>
          <div class="input-group form-group-no-border {{ $errors->has('airline') ? 'has-danger' : '' }}">
            <select name="airline_id" id="airline_id" class="form-control select2">
              @foreach($airlines as $airline_id => $airline_label)
                <option value="{{ $airline_id }}" @if($airline_id === old('airline_id')) selected @endif>{{ $airline_label }}</option>
              @endforeach
            </select>
          </div>
          @if ($errors->has('airline_id'))
            <p class="text-danger">{{ $errors->first('airline_id') }}</p>
          @endif

          <label for="home_airport" class="control-label">@lang('airports.home')</label>
          <div class="input-group form-group-no-border {{ $errors->has('home_airport') ? 'has-danger' : '' }}">
            <select name="home_airport_id" id="home_airport_id" class="form-control airport_search @if($hubs_only) hubs_only @endif">
              @foreach($airports as $airport_id => $airport_label)
                <option value="{{ $airport_id }}">{{ $airport_label }}</option>
              @endforeach
            </select>
          </div>
          @if ($errors->has('home_airport_id'))
            <p class="text-danger">{{ $errors->first('home_airport_id') }}</p>
          @endif

          <label for="country" class="control-label">@lang('common.country')</label>
          <div class="input-group form-group-no-border {{ $errors->has('country') ? 'has-danger' : '' }}">
            <select name="country" id="country" class="form-control select2">
              @foreach($countries as $country_id => $country_label)
                <option value="{{ $country_id }}" @if($country_id === old('country')) selected @endif>{{ $country_label }}</option>
              @endforeach
            </select>
          </div>
          @if ($errors->has('country'))
            <p class="text-danger">{{ $errors->first('country') }}</p>
          @endif

          <label for="timezone" class="control-label">@lang('common.timezone')</label>
          <div class="input-group form-group-no-border {{ $errors->has('timezone') ? 'has-danger' : '' }}">
            <select name="timezone" id="timezone" class="form-control">
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
          </div>
          @if ($errors->has('timezone'))
            <p class="text-danger">{{ $errors->first('timezone') }}</p>
          @endif

          @if (setting('pilots.allow_transfer_hours') === true)
            <label for="transfer_time" class="control-label">@lang('auth.transferhours')</label>
            <div class="input-group form-group-no-border {{ $errors->has('transfer_time') ? 'has-danger' : '' }}">
              <input type="number" name="transfer_time" id="transfer_time" class="form-control" value="{{ old('transfer_time') }}" />
            </div>
            @if ($errors->has('transfer_time'))
              <p class="text-danger">{{ $errors->first('transfer_time') }}</p>
            @endif
          @endif

          <label for="password" class="control-label">@lang('auth.password')</label>
          <div class="input-group form-group-no-border {{ $errors->has('password') ? 'has-danger' : '' }}">
            <input type="password" name="password" id="password" class="form-control" />
          </div>
          @if ($errors->has('password'))
            <p class="text-danger">{{ $errors->first('password') }}</p>
          @endif

          <label for="password_confirmation" class="control-label">@lang('passwords.confirm')</label>
          <div class="input-group form-group-no-border {{ $errors->has('password_confirmation') ? 'has-danger' : '' }}">
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" />
          </div>
          @if ($errors->has('password_confirmation'))
            <p class="text-danger">{{ $errors->first('password_confirmation') }}</p>
          @endif

          @if($userFields)
            @foreach($userFields as $field)
              <label for="field_{{ $field->slug }}" class="control-label">{{ $field->name }}</label>
              <div class="input-group form-group-no-border {{ $errors->has('field_'.$field->slug) ? 'has-danger' : '' }}">
                <input type="text" name="field_{{ $field->slug }}" id="field_{{ $field->slug }}" class="form-control" value="{{ old('field_' .$field->slug) }}" />
              </div>
              @if ($errors->has('field_'.$field->slug))
                <p class="text-danger">{{ $errors->first('field_'.$field->slug) }}</p>
              @endif
            @endforeach
          @endif

          @if(!empty($captcha['site_key']) && $captcha['enabled'] === true)
            <label for="g-recaptcha" class="control-label">@lang('auth.fillcaptcha')</label>
            <div class="g-recaptcha" data-sitekey="{{ $captcha['site_key'] }}"></div>
            @if ($errors->has('g-recaptcha-response'))
              <p class="text-danger">{{ $errors->first('g-recaptcha-response') }}</p>
            @endif
          @endif

          @if($invite)
            <input type="hidden" name="invite" value="{{ $invite->id }}" />
            <input type="hidden" name="invite_token" value="{{ base64_encode($invite->token) }}" />
          @endif

          <div>
            @include('auth.toc')
            <br/>
          </div>

          <table>
            <tr>
              <td style="vertical-align: top; padding: 5px 10px 0 0">
                <div class="input-group form-group-no-border">
                  <input type="checkbox" name="toc_accepted" id="toc_accepted" />
                </div>
              </td>
              <td style="vertical-align: top;">
                <label for="toc_accepted" class="control-label">@lang('auth.tocaccept')</label>
                @if ($errors->has('toc_accepted'))
                  <p class="text-danger">{{ $errors->first('toc_accepted') }}</p>
                @endif
              </td>
            </tr>
            <tr>
              <td>
                <div class="input-group form-group-no-border">
                  <input type="hidden" name="opt_in" value="0"/>
                  <input type="checkbox" name="opt_in" id="opt_in" value="1"/>
                </div>
              </td>
              <td>
                <label for="opt_in" class="control-label">@lang('profile.opt-in-descrip')</label>
              </td>
            </tr>
          </table>

          <div style="width: 100%; text-align: right; padding-top: 20px;">
              <button type="submit" class="btn btn-primary" id="register_button" disabled>
                @lang('auth.register')
              </button>
          </div>

        </div>
      </div>
      </form>
    </div>
    <div class="col-sm-4"></div>
  </div>
@endsection

@section('scripts')
  @if (isset($captcha) && $captcha['enabled'] && !empty($captcha['site_key']))
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  @endif

  <script>
    window.addEventListener("load", function () {
    
    $('#toc_accepted').click(function () {
        if ($(this).is(':checked')) {
          $('#register_button').removeAttr('disabled');
        } else {
          $('#register_button').attr('disabled', 'true');
        }
      });
    });
  </script>
@include('scripts.airport_search')
@endsection
