<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        🗺️ Flight Route Map
      </div>
      <div class="card-body">
        <div id="map" style="width: 100%; height: 600px; border-radius: 8px;"></div>
      </div>
    </div>
  </div>
</div>

@section('scripts')
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
      phpvms.map.render_route_map({
        route_points: {!! json_encode($map_features['route_points']) !!},
        planned_route_line: {!! json_encode($map_features['planned_route_line']) !!},
        metar_wms: {!! json_encode(config('map.metar_wms')) !!},
        circle_color: '#00a651',
        flightplan_route_color: '#1976d2',
      });
    });
  </script>
@endsection
