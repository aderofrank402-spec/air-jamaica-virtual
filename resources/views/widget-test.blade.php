<!DOCTYPE html>
<html>
<head>
    <title>Widget Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Air Jamaica Virtual7 Widget Test</h1>
        
        <div class="row">
            <div class="col-md-6">
                <h2>Active NOTAMs Widget</h2>
                <div class="border p-3">
                    {{ Widget::activeNotams(['count' => 3]) }}
                </div>
            </div>
            
            <div class="col-md-6">
                <h2>Dispatch Center Widget</h2>
                <div class="border p-3">
                    {{ Widget::dispatchCenter([
                        'hub_icao' => 'KJFK',
                        'show_active_flights' => true,
                        'show_weather' => false,
                        'show_notams' => false,
                        'show_pending_departures' => true
                    ]) }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>