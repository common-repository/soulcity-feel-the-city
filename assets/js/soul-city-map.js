/**
 * Created by gduvaux on 25/01/2016.
 */

function isset(){

    var a = arguments,
        l = a.length,
        c = null,
        undef;

    if (l === 0) {
        throw new Error('Empty isset');
    }

    c = a[0];

    for (i = 1; i < l; i++) {

        if (a[i] === undef || c[ a[i] ] === undef) {
            return false;
        }
        c = c[ a[i] ];
    }
    return true;
}

(function($){
    // no google
    if( !isset(window, 'google', 'load') ) {
        // load API
        $.getScript('https://www.google.com/jsapi', function(){
            // load maps
            google.load('maps', '3', { other_params: 'libraries=places', callback: initMap });
        });
        return false;
    }

    if( !isset(window, 'google', 'maps', 'places') ) {
        self.status = 'loading';
        // load maps
        google.load('maps', '3', { other_params: 'libraries=places', callback: initMap });
        return false;
    }
})(jQuery);

function initMap() {
    var map = new google.maps.Map(document.getElementById('soul_city_iframe_map'), {
        center: {lat: -33.8688, lng: 151.2195},
        zoom: 13
    });

    var input = document.getElementById('soul_city_iframe_place_field');

    var autocomplete = new google.maps.places.Autocomplete(input);
    var placesService = new google.maps.places.PlacesService(map);

    autocomplete.bindTo('bounds', map);

    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    var infowindow = new google.maps.InfoWindow();
    var marker = new google.maps.Marker({
        map: map
    });

    google.maps.event.addListenerOnce(map, 'tilesloaded', function(){
        var place_id = jQuery('#soul_city_iframe_place_id_field').val();

        var request = {
            placeId: place_id
        };

        placesService.getDetails(request, function(place, status) {
            if (status == google.maps.places.PlacesServiceStatus.OK) {
                marker.setPlace({
                    placeId: place.place_id,
                    location: place.geometry.location
                });

                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(15);
                }
                marker.setVisible(true);
            }
        });
    });

    autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();

        console.log(place);

        if (!place.geometry) {
            return;
        }

        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(15);
        }

        // Set the position of the marker using the place ID and location.
        marker.setPlace({
            placeId: place.place_id,
            location: place.geometry.location
        });
        marker.setVisible(true);

        //console.log(place.place_id);
        jQuery('#soul_city_iframe_place_id_field').val(place.place_id);
    });

    google.maps.event.addListener(map, "click", function (event) {
        var lat = event.latLng.lat(); //Get latitude from point of click
        var lng = event.latLng.lng(); // Get longitude from point of click
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({
            'latLng': new google.maps.LatLng(lat, lng)
        }, function (results, status) {
            //console.log(results[0]); //Final address from lat and lng
        });
    });

    //keep a reference to the original setPosition-function
    var fx = google.maps.InfoWindow.prototype.setPosition;

    //override the built-in setPosition-method
    google.maps.InfoWindow.prototype.setPosition = function () {

        //logAsInternal isn't documented, but as it seems
        //it's only defined for InfoWindows opened on POI's
        if (this.logAsInternal) {
            google.maps.event.addListenerOnce(this, 'map_changed',function () {
                var map = this.getMap();
                //the infoWindow will be opened, usually after a click on a POI
                if (map) {
                    //trigger the click
                    google.maps.event.trigger(map, 'click', {latLng: this.getPosition()});
                }
            });
        }
        //call the original setPosition-method
        fx.apply(this, arguments);
    };
}