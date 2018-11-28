    $(document).ready(function ()
    {
        $.initGeolocalisation = function (){

            if ($('#geolocation').length) {
                var debug = false;

                var options = {
                  enableHighAccuracy: true,
                  timeout: 10000,
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

                  if(crd.latitude+crd.longitude){
                      window.location = window.location.origin + $('#geolocation').data('url').replace("lat",crd.latitude).replace("lon",crd.longitude);
                  }
                };

                function error(err) {
                  console.warn(`ERROR(${err.code}): ${err.message}`);
                };

                navigator.geolocation.getCurrentPosition(success, error, options);

                $('#demander_position').on('click', function() {
                navigator.geolocation.getCurrentPosition(success, error, options);

                return false;
                });

            }
        };

        $.initCamera = function(){
            $('#camera-button').on('click', function() {
                $("#photos_imageFile_file").trigger('click');
                return false;
            });
            $("#photos_imageFile_file").bind('change', function() {
                $("form").submit();
            });

        }


        $.initMap = function () {
            if (!$('#map').length) {
                return;
            }

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

            L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var geojson = JSON.parse($('#map').attr('data-geojson'));
            var markers = [];
            L.marker([lat, lon]).addTo(map);
        };

        $.initAddrSearch = function() {

            if (!$('#addrSearch').length) {
                return;
            }


            var target = $('#addrSearch').data('target');

        	var address = new Bloodhound({
        		datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
        		queryTokenizer: Bloodhound.tokenizers.whitespace,
        		remote: {
        			url: searchUri,
                    wildcard: '_query_',
                    transform: function(response) {return response.features}
        		}
        	});

        	$('#addrSearch .typeahead').typeahead({hint: false, highlight: true, minLength: 1},
        			{
                		limit: 10,
                		source: address,
                		async: true,
                    displayKey: 'id',
                		templates: {
                				suggestion: function(e) {
                					var result = '<a href="'+target.replace('_coordinates_', e.geometry.coordinates)+'">'+e.properties.label;
                					return $('<div class="searchable_result">'+result+'</div>');
                				}

                		},
                		notFound: function(query) {
                				return '<div class="searchable_result">aucun résultat</div>';
                		}
        			}
            );


        	$('#addrSearch .typeahead').bind('typeahead:asyncreceive', function (event, suggestion) {
                $('#addrSearch').find(".tt-dataset .tt-suggestion:first").addClass('tt-cursor');
            });

        	$('#addrSearch .typeahead').bind('typeahead:select', function(ev, suggestion) {
            	document.location.href=target.replace('_coordinates_', suggestion.geometry.coordinates);
            });
        }

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

        $.initClickableRow = function () {

            if (!$('.clickable-row').length) {
                return;
            }

            $(".clickable-row").click(function() {
                document.location.href = $(this).data("href");
            });
        };

        $.initGeolocalisation();
        $.initMap();
        $.initAddrSearch();
        $.initSignalement();
        $.initClickableRow();
        $.initCamera();

      });
