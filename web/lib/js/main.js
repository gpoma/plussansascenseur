    $(document).ready(function ()
    {
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

          $("#photo_lat").val(crd.latitude);
          $("#photo_lon").val(crd.longitude);
        };

        function error(err) {
          console.warn(`ERROR(${err.code}): ${err.message}`);
        };

        navigator.geolocation.getCurrentPosition(success, error, options);
      });
