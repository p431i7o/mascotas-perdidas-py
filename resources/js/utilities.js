window.mapaLocal = null;
window.action = null;
window.gps_active = false;

/**
 * Method localization by html5.
 * @method localization
 * @param p_action
 * @returns void
 */
window.localization = function  (p_action)
{
	action = p_action;

    if (navigator.geolocation)
    {
        navigator.geolocation.getCurrentPosition(getCoordinates,
            function(error){
                let lng = DEFAULT_LNG;
                let lat = DEFAULT_LAT;
                let coordinates = new Array();
                coordinates['lng']  = lng;
                coordinates['lat'] = lat;
                setView(coordinates,DEFAULT_ZOOM_MAP);
            },
        {
			enableHighAccuracy: true,
			timeout: 2000,
			maximumAge: 0
		});
    }
    else
    {
    	defaultPosition(false);

    }
}

window.setView = function  (p_coordinates, p_zoom)
{
    window.mapaLocal.map.setView([p_coordinates['lat'], p_coordinates['lng']], p_zoom);
}

window.setMarkerToLocation = function  (p_coordinates, p_id, p_label, p_zoom, p_gps_active=false,p_allow_drag=false)
{
    let coordinates = new Array();
    coordinates['lng']  = p_coordinates['lng'];
    coordinates['lat'] = p_coordinates['lat'];
    let zoom = p_zoom;
    gps_active = p_gps_active;
    setView(coordinates,zoom);

    var newMarker = L.marker([coordinates['lat'], coordinates['lng'] ],
        {
            id: -1,
            draggable: p_allow_drag?'true':'false',
        }
    )
    .addTo(window.mapaLocal.map)
    .bindPopup(p_label);

    if(p_allow_drag){
        newMarker.on('dragend',ondragend)
    }
    return newMarker;

}

window.setToMyLocation = function  ()
{
    clean_marker();
    if (navigator.geolocation)
    {
        navigator.geolocation.getCurrentPosition(
            function (p_position)
            {
                let coordinates = new Array();
                coordinates['lng']  = p_position.coords.longitude;
                coordinates['lat'] = p_position.coords.latitude;
                let zoom = DEFAULT_MAX_ZOOM_MAP;
                gps_active = true;
                window.marker_point = setMarkerToLocation(coordinates,-1,"Mi ubicación",zoom,gps_active,true);
                $('#latitude').val(coordinates['lat']);
                $('#longitude').val(coordinates['lng']);

            }, errors,
        {
            enableHighAccuracy: true,
            timeout: 2000,
            maximumAge: 0
        });
    }
    else
    {
    	defaultPosition(true);
    }
}

/**
 * Method that obtains the current coordinates by means of geolocation.
 * @method getCoordinates
 * @param p_position
 * @returns void
 */
window.getCoordinates = function  (p_position)
{

    let coordinates = new Array();
    coordinates['lng']  = p_position.coords.longitude;
	coordinates['lat'] = p_position.coords.latitude;

    let zoom = DEFAULT_ZOOM_MAP;
    gps_active = true;
    action = "marker";
}

/**
 * Method errors, be the error code that comes out, will default to load coordinates (latitude and longitude).
 * @method errors
 * @param error
 * @returns void
 */
window.errors = function  (error)
{
    switch (error.code)
    {
    	case error.PERMISSION_DENIED:
    		console.log("User denied the request for Geolocation.");
    		break;
    	case error.POSITION_UNAVAILABLE:
    		console.log("Location information is unavailable.");
    		break;
    	case error.TIMEOUT:
    		console.log("The request to get user location timed out.");
    		break;
    	case error.UNKNOWN_ERROR:
    		console.log("An unknown error occurred.");
    		break;
    }
    defaultPosition(true);
}

/**
 * Method that positions default.
 * @method defaultPosition
 * @returns void
 */
window.defaultPosition = function (setMarker=false)
{
    let lng = DEFAULT_LNG;
	let lat = DEFAULT_LAT;
	let coordinates = new Array();
	let zoom = DEFAULT_ZOOM_MAP;

    coordinates['lng']  = lng;
    coordinates['lat'] = lat;

    if(setMarker){
        marker_point = setMarkerToLocation(coordinates,-1,"Mi ubicación",zoom,false,true);
        $('#latitude').val(coordinates['lat']);
        $('#longitude').val(coordinates['lng']);
    }
}

window.setCookie = function(cname, cvalue, exdays=365) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }

window.getCookie=function(cname) {
    let name = cname + "=";
    let ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }
window. clickZoom=function(e) {
    mapaLocal.map.setView(e.target.getLatLng(),DEFAULT_MAX_ZOOM_MAP);
}
