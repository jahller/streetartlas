var map;
function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: 48.1277926, lng: 11.5695072},
    zoom: 16,
    streetViewControl: false,
    mapTypeControl: false
  });

  google.maps.event.addListenerOnce(map, 'idle', function(){
    addMarker();
  });
}

$(document).ready(function() {
  init();
});

function init() {
  $('#createPiece').click(function() {
    $('#createPieceModal').modal('show');
  })
}