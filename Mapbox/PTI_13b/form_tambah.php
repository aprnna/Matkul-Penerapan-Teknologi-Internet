<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>TAMBAH DATA</title>
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
    <!-- import elemen dasar mapbox -->
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.css" rel="stylesheet">
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.js"></script>
    <!-- import geocoder mapbox -->
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css" type="text/css">
    <!-- import tailwind dan daisyui css -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@2.18.1/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- import reactjs untuk menggunakan hook useState -->
    <script src="https://unpkg.com/react@18/umd/react.development.js" crossorigin></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js" crossorigin></script>

</head>

<body>
    <form name="formInput" method="post" action="proses_simpan.php" enctype="multipart/form-data" data-theme="corporate" onsubmit="return validateForm()">
        <div class="h-full w-full space-y-5 text-black px-10 bg-white">
            <div class="text-xl font-bold pt-5 text-center">
                TAMBAH DATA
            </div>
            <div class="h-screen w-full px-20">
                <div id="map" class="w-full h-4/5 top-0 rounded"></div>
            </div>
            <div id="formLokasi" class="grid-none md:grid md:grid-cols-2 md:space-x-5 space-x-none">
                <div class="space-y-5">
                    <div>
                        <p>Cari lokasi : </p>
                        <div name="geocoder" id="geocoder" class="w-full"></div>
                    </div>
                    <div>
                        <p>Beri nomor data : </p>
                        <input type="text" name="no" id="no" class="text-black h-9 rounded w-full shadow-lg" placeholder=" Silakan isi nomor data lokasi" />
                    </div>
                    <div>
                        <p>Beri nama lokasi : </p>
                        <input type="text" name="alamat" id="alamat" class="text-black h-9 rounded w-full shadow-lg" placeholder=" Silakan isi nama lokasi" />
                    </div>
                </div>
                <div class="space-y-5 px-10">
                    <div>
                        <p>Latitude : </p>
                        <input type="text" name="latitude" id="latitude" class="text-black h-9 rounded w-full shadow-lg" placeholder=" Terisi otomatis saat klik maps" />
                    </div>
                    <div>
                        <p>Longitude : </p>
                        <input type="text" name="longitude" id="longitude" class="text-black h-9 rounded w-full shadow-lg" placeholder=" Terisi otomatis saat klik maps" />
                    </div>
                    <div>
                        <p>Kelurahan : </p>
                        <input type="text" name="kelurahan" id="kelurahan" class="text-black h-9 rounded w-full shadow-lg" placeholder=" Terisi otomatis saat klik maps" />
                    </div>
                    <div>
                        <p>Kecamatan : </p>
                        <input type="text" name="kecamatan" id="kecamatan" class="text-black h-9 rounded w-full shadow-lg" placeholder=" Terisi otomatis saat klik maps" />
                    </div>
                    <div>
                        <p>Kota/Kabupaten : </p>
                        <input type="text" name="kota" id="kota" class="text-black h-9 rounded w-full shadow-lg" placeholder=" Terisi otomatis saat klik maps" />
                    </div>
                    <div>
                        <p>Provinsi : </p>
                        <input type="text" name="provinsi" id="provinsi" class="text-black h-9 rounded w-full shadow-lg" placeholder=" Terisi otomatis saat klik maps" />
                    </div>
                    <div>
                        <p>Kode Pos : </p>
                        <input type="text" name="kode_pos" id="kode_pos" class="text-black h-9 rounded w-full shadow-lg" placeholder=" Terisi otomatis saat klik maps" />
                    </div>
                </div>
            </div>
            <div class="text-center pt-5">
                <button type="submit" class="text-center btn btn-wide bg-blue-500 width-30 border-none">SIMPAN</button>
            </div>
            <br>
            <hr>
            <div class="text-xl font-bold pt-5 text-center">
                PRAKIRAAN CUACA
            </div>
            <p class="text-center">
                Mendapatkan info tentang prakiraan cuaca dari kecamatan/desa yang telah dipilih
            </p>

            <div id="tombol_cek_cuaca" class="text-center bg-blue-500 hover:bg-black py-2 w-1/4 rounded-lg m-auto text-white"></div><br>
            <!-- Load React component -->
            <script src="cuaca.js"></script>
            <div class="grid grid-cols-3 text-center h-1/4">
                <div><br>Cuaca Hari Ini :
                    <p id="cuaca1" class="py-10"></p><br><br>
                </div>
                <div><br>Cuaca Besok :
                    <p id="cuaca2" class="py-10"></p><br><br>
                </div>
                <div><br>Cuaca Lusa :
                    <p id="cuaca3" class="py-10"></p><br><br>
                </div>
                <script>
                    window.onerror = function myErrorHandler() {
                        alert("Maaf, prakiraan cuaca tidak dapat diperoleh");
                        return false;
                    }
                </script>
            </div>
        </div>
    </form>

    <script>
        mapboxgl.accessToken = 'pk.eyJ1Ijoic3RlZmFudXMtMDUiLCJhIjoiY201eTFvcHN0MGM1ODJscG5rZXgwdWRneSJ9.92VB3F3stWv7SK_1eFLeEg';
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [107.6145, -6.9167],
            zoom: 12
        });
        // menambahkan control geocoder
        const geocoder = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            mapboxgl: mapboxgl
        });
        document.getElementById('geocoder').appendChild(geocoder.onAdd(map));


        map.on('style.load', function() {
            map.on('click', function(e) {
                // get latitude dan longitude
                var longitude = e.lngLat.lng;
                var latitude = e.lngLat.lat;
                document.getElementById("latitude").value = latitude;
                document.getElementById("longitude").value = longitude;

                const xhttp = new XMLHttpRequest();
                // get data info lokasi
                xhttp.onload = function() {
                    let responseJSON = JSON.parse(this.responseText);
		    kelurahan = responseJSON.features[0].text;
                    kode_pos = responseJSON.features[0].context[0].text;
                    kecamatan = responseJSON.features[0].context[1].text;
                    kota = responseJSON.features[0].context[2].text;
                    provinsi = responseJSON.features[0].context[3].text;
                    document.getElementById("kelurahan").value = kelurahan;
                    document.getElementById("kode_pos").value = kode_pos;
                    document.getElementById("kecamatan").value = kecamatan;
                    document.getElementById("kota").value = kota;
                    document.getElementById("provinsi").value = provinsi;
                }
                xhttp.open("GET", "https://api.mapbox.com/geocoding/v5/mapbox.places/" + longitude + "," + latitude + ".json?country=id&types=neighborhood%2Clocality%2Cplace%2Cdistrict%2Cpostcode%2Cregion%2Caddress%2Cpoi&language=id&limit=1&&access_token=pk.eyJ1Ijoic3RlZmFudXMtMDUiLCJhIjoiY201eTFvcHN0MGM1ODJscG5rZXgwdWRneSJ9.92VB3F3stWv7SK_1eFLeEg", true);
                xhttp.send();
            });
        });
        // validasi input
        function validateForm() {
            var a = document.getElementById("alamat").value;
            var b = document.getElementById("no").value;
            var c = document.getElementById("latitude").value;
            var d = document.getElementById("longitude").value;
            if (a == null || a == "" || b == null || b == "" || c == null || c == "") {
                alert("Harap melengkapi semua data yang diperlukan");
                return false;
            }
        }
    </script>
</body>

</html>