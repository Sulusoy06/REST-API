<?php
$url = "https://nominatim.openstreetmap.org/search?format=json&q=Roermond";
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

$latitude = $data[0]['lat'];
$longitude = $data[0]['lon'];

$mapHTML = '
<!DOCTYPE html>
<html>
<head>
    <title>Kaart van Roermond</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
</head>
<body>
    <div id="map" style="width: 600px; height: 400px;"></div>
    <script>
        var map = L.map("map").setView([' . $latitude . ', ' . $longitude . '], 13);
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19
        }).addTo(map);
        L.marker([' . $latitude . ', ' . $longitude . ']).addTo(map)
            .bindPopup("Roermond")
            .openPopup();
    </script>
</body>
</html>
';

file_put_contents("map.html", $mapHTML);

echo "Kaart opgeslagen in map.html met coördinaten: ({$latitude}, {$longitude})";
?>
