window.map = null;
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
        navigator.geolocation.getCurrentPosition(getCoordinates, errors,
        {
			enableHighAccuracy: true,
			timeout: 2000,
			maximumAge: 0
		});
    }
    else
    {
    	defaultPosition();
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

    load_map(coordinates, zoom);
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
    defaultPosition();
}

/**
 * Method that positions default.
 * @method defaultPosition
 * @returns void
 */
window.defaultPosition = function ()
{
    let lng = DEFAULT_LNG;
	let lat = DEFAULT_LAT;
	let coordinates = new Array();
	let zoom = DEFAULT_ZOOM_MAP;

    coordinates['lng']  = lng;
    coordinates['lat'] = lat;

	load_map(coordinates, zoom);
}

/**
 * Method that loads the map.
 * @method load_map
 * @param p_coordinates
 * @param p_zoom
 * @returns void
 */
window.load_map = function (p_coordinates, p_zoom)
{
    switch (action)
    {
	    case 'marker':
			map = new Map(p_coordinates, p_zoom, action);
            break;
        case 'list':
        case 'default':
            map = new Map(p_coordinates, p_zoom, action);
            map.get_vendors();
            break;
	}
}

//
// function products_filter ()
// {
//     let products_filter = $("[name='products[]']").serializeArray();
//     let city_filter = $("[name='city']").val();

//     map.products_filter(products_filter, city_filter);

//     // Hide product filter.
//     let checkbox = $('#changeShip'),
//       chShipBlock = $('#changeShipInputs');

//     checkbox.prop('checked', false);
//     chShipBlock.hide();

//     // Go to the top product-filter section.
//     $(window).scrollTop($('#product-filter').offset().top);
// }

window.marker_point_map = function (p_e, p_zoom)
{
    p_e = p_e || window.event;
    p_e.preventDefault();
    window.map.marker_point(p_zoom);
}

// //
// function direccion_buscador() {
//     var v_entrada = document.getElementById("direccion");

//     $.getJSON('http://nominatim.openstreetmap.org/search?format=json&limit=5&q=' + v_entrada.value, function(p_data) {
//         var v_array_items = [];

//         $.each(p_data, function(key, val) {
//             bb = val.boundingbox;
//             console.log('val: ', val);

//             v_array_items.push("<li><a href='#' onclick='elegirDireccion(" + bb[0] + ", " + bb[2] + ", " + bb[1] + ", " + bb[3] + ", \"" + val.osm_type + "\");return false;'>" + val.display_name + '</a></li>');
//         });

//         $('#resultado').empty();
//         if (v_array_items.length != 0) {
//             $('<p>', { html: "Resultados de la b&uacute;queda:" }).appendTo('#resultado');
//             $('<ul/>', {
//                 'class': 'my-new-list',
//                 html: v_array_items.join('')
//             }).appendTo('#resultado');
//         }else{
//              $('<p>', { html: "Ningun resultado encontrado." }).appendTo('#resultado');
//         }
//     });
// }

// //
// function elegirDireccion(p_lat1, p_lng1, p_lat2, p_lng2, p_tipo_osm) {
//     v_mapa.marcar(p_lat1, p_lng1, p_lat2, p_lng2, p_tipo_osm);
// }

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
