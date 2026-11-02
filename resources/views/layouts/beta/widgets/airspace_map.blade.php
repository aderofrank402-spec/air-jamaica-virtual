<div id="map" style="width: {{ $config['width'] }}; height: {{ $config['height'] }}"></div>

@section('scripts')
  <script>
    ajv.map.render_airspace_map({
      lat: "{{$config['lat']}}",
      lon: "{{$config['lon']}}",
      metar_wms: {!! json_encode(config('map.metar_wms')) !!},
    });
  </script>
@endsection
