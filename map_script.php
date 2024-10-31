<?php

$url = "https://nominatim.openstreetmap.org/search?format=json&q=Roermond";
$options = ['http' => ['header' => "Accept: application/json\r\n" . "User-Agent: MyCustomAgent/1.0 (your_email@example.com)\r\n"] ];
$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

if ($response !== false) {
    $data = json_decode($response, true);
    
    if (!empty($data)) {
        $lat = floatval($data[0]['lat']);
        $lon = floatval($data[0]['lon']);
        
        $html = <<<HTML
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaart van Roermond</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f4f4f9;
            color: #333;
        }
        h1 {
            font-size: 24px;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        #map {
            height: 400px;
            width: 80%;
            max-width: 800px;
            margin: 0 auto;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border: 2px solid #ddd;
        }
    </style>
</head>
<body>
    <h1>Kaart van Roermond</h1>
    <div id="map"></div>
    <script>
        var map = L.map('map').setView([$lat, $lon], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        L.marker([$lat, $lon]).addTo(map)
            .bindPopup('Roermond')
            .openPopup();
    </script>
</body>
</html>
HTML;

        file_put_contents('roermond_map.html', $html);
        echo "Kaart is opgeslagen als roermond_map.html";
    } else {
        echo "Geen resultaten gevonden voor Roermond";
    }
} else {
    echo "Fout bij het ophalen van gegevens";
}