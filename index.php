<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.0.0/dist/geosearch.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />


    <title>Hello, world!</title>
</head>

<body>
    <h1>Hello, world!</h1>
    <div class="container mt-5">
        <div id="map" style="height: 400px;"></div>
        <div id="geocoder" class="leaflet-control"></div>

        <br>
        <button class="btn btn-primary" id="getme">Ambil Lokasi saya</button>
        <br>
        <br>
        <form action="">
            <div class="row">
                <div class="col">
                    <label for="name">Nama Lokasi</label>
                    <input type="text" id="name" class="form-control" placeholder="Name">
                </div>
                <div class="col">
                    <label for="lat">Latitude</label>
                    <input type="text" id="lat" class="form-control" placeholder="Lat">
                </div>
                <div class="col">
                    <label for="long">Longitude</label>
                    <input type="text" id="long" class="form-control" placeholder="Long">
                </div>
            </div>
        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet-geosearch@3.0.0/dist/geosearch.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.js"></script>


    <script>
        var map = L.map('map').setView([-6.599216, 106.798224], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
            maxZoom: 18,
        }).addTo(map);

        var marker;

        var geojsonLayer;

        fetch('geojson/indonesia.geojson')
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                geojsonLayer = L.geoJSON(data).addTo(map);
            });

        map.on('click', function(e) {
            document.getElementById('lat').value = '';
            document.getElementById('long').value = '';

            var lat = e.latlng.lat.toFixed(6);
            var lng = e.latlng.lng.toFixed(6);

            if (marker) {
                map.removeLayer(marker);
            }

            marker = L.marker([lat, lng]).addTo(map);
            document.getElementById('lat').value = lat;
            document.getElementById('long').value = lng;

            // Ambil nama lokasi dari GeoJSON
            var locationName = '';
            if (geojsonLayer) {
                geojsonLayer.eachLayer(function(layer) {
                    if (layer.getBounds().contains(e.latlng)) {
                        console.log(layer.feature.properties)
                        locationName = layer.feature.properties.state;
                        return;
                    }
                });
            }
            // document.getElementById('name').value = locationName;
        });


        var locateControl = L.control.locate({
            position: 'topleft',
            drawCircle: false,
            showPopup: false,
            locateOptions: {
                enableHighAccuracy: true,
            },
        }).addTo(map);

        map.on('locationfound', function(e) {
            var latlng = e.latlng;
            var lat = latlng.lat.toFixed(6);
            var lng = latlng.lng.toFixed(6);

            if (marker) {
                map.removeLayer(marker);
            }

            marker = L.marker(latlng).addTo(map);
            document.getElementById('lat').value = lat;
            document.getElementById('long').value = lng;
        });


        document.getElementById('getme').addEventListener('click', function() {
            map.locate({
                setView: true,
                maxZoom: 16
            });
        });


        // $(document).ready(function() {

        // })
    </script>
</body>

</html>