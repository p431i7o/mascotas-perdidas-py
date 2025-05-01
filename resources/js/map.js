window.marker_point = null;
window.cluster_markers = null;

class MapaLocal
{
    constructor(p_coordinates, p_zoom, p_action) {

        this.map = null;
        this.coordinates = p_coordinates;
        this.zoom = p_zoom;
        this.action = p_action;
        let lng = p_coordinates['lng'];

        // Add 1.65 to center Paraguay;
        let lat = (action === 'list')? p_coordinates['lat'] + 1.65 : p_coordinates['lat'];

        let minZoom = DEFAULT_MIN_ZOOM_MAP;
        let maxZoom = DEFAULT_MAX_ZOOM_MAP;

            mapaLocal = new L.map('map-container',
                {
                    center: [lat, lng],
                    minZoom: minZoom,
                    maxZoom: maxZoom,
                    zoom: p_zoom,
                    //scrollWheelZoom: false,
                    fullscreenControl: true,
                    fullscreenControlOptions:
                        {
                            position: 'topleft'
                        }
                });
            let url = 'https://tile.openstreetmap.org/{z}/{x}/{y}.png';
            L.tileLayer(url,
                {
                    minZoom: minZoom,
                    maxZoom: maxZoom,
                    attribution: 'Data \u00a9 <a href="http://www.openstreetmap.org/copyright">' +
                        'OpenStreetMap Contributors </a> Tiles \u00a9 HOT'
                }).addTo(mapaLocal);

            this.map = mapaLocal;


    }
    marker_point(p_zoom)
    {
        let map = this.map;
        let coordinates = get_coordinates_center_map(map);

        clean_marker();

        window.marker_point = new L.marker(coordinates,
            {
                id: 'vendor',
                draggable: 'true',
                title: 'Mi ubicación'
            });
        window.marker_point.bindPopup('Mi ubicación').openPopup();
        map.addLayer(marker_point);
        map.setView(coordinates, p_zoom);

        window.marker_point.on("dragend", ondragend);
        $('#latitude').val(coordinates[0]);
        $('#longitude').val(coordinates[1]);
    }
}


window.ondragend = function(e) {
    let marker = e.target;
    let position = marker.getLatLng();
    let lat = position.lat;
    let lng = position.lng;
    let lat_lng = new L.LatLng(lat, lng);

    marker.setLatLng(lat_lng,
    {
        draggable: 'true'
    });
    if(mapaLocal.panTo==undefined){
        mapaLocal.map.panTo(lat_lng);
    }else{
        mapaLocal.panTo(lat_lng);
    }

    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
};

window.clean_marker = function  ()
{
    if (marker_point != null)
    {
        marker_point.remove();
    }
}


window.get_coordinates_center_map = function  (map)
{
    let options = map.options;
    let coordinates = options.center;
    return coordinates;
}
window.MapaLocal = MapaLocal;
