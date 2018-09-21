    $(document).ready(function ()
    {
        $.initGeolocalisation = function (){
            if ($('#geolocation').length) {
                var debug = true;

                var options = {
                  enableHighAccuracy: true,
                  timeout: 5000,
                  maximumAge: 0
                };

                function success(pos) {
                  var crd = pos.coords;
                  if(debug){
                      console.log('Votre position actuelle est :');
                      console.log(`Latitude : ${crd.latitude}`);
                      console.log(`Longitude: ${crd.longitude}`);
                      console.log(`Plus ou moins ${crd.accuracy} mètres.`);
                  }

                  $("#photos_lat").val(crd.latitude);
                  $("#photos_lon").val(crd.longitude);
                };

                function error(err) {
                  console.warn(`ERROR(${err.code}): ${err.message}`);
                };

                navigator.geolocation.getCurrentPosition(success, error, options);
                }
        };

        $.initMap = function () {
        if ($('#map').length) {
            var lat = 48.8593829;
            var lon = 2.347227;
            var zoom = 0;
            if ($('#map').attr('data-lat') && $('#map').attr('data-lon')) {
                lat = $('#map').data('lat');
                lon = $('#map').data('lon');
            }
            if($('#map').attr('data-zoom')){
                zoom = $('#map').data('zoom');
            }

            var map = L.map('map').setView([lat, lon], zoom);

            L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var geojson = JSON.parse($('#map').attr('data-geojson'));
            var markers = [];

            var ascenseurIcon = L.icon({
                iconUrl: '../../elevator_inv_32px.svg',
                iconSize: [32, 32]
            });
            L.geoJson(geojson,
                    {
                        pointToLayer: function (feature, latlng) {
                            var marker = L.marker(latlng, {icon: ascenseurIcon});
                            markers[feature.properties._id] = marker;
                            return marker;
                        }
                    }
            ).addTo(map);

            }
        };

        $.initSignalement = function () {
            if(!$('form[name=signalement]')) {
                return;
            }
            $('#signalement_etageAtteint').on('change', function() {
                $('#signalement_duree').parents('.form-group').addClass('d-none');
                if($(this).val() == "1") {
                    $('#signalement_duree').parents('.form-group').removeClass('d-none');
                }
            });

            $('#signalement_abonnement').on('change', function() {
                $('#signalement_infos_abonnement').addClass('d-none');
                if($(this).prop('checked') == true) {
                    $('#signalement_infos_abonnement').removeClass('d-none');
                }
            });
            $('#signalement_etageAtteint').change();
            $('#signalement_abonnement').change();
        };

        $.initGeolocalisation();
        $.initMap();
        $.initSignalement();

      });
