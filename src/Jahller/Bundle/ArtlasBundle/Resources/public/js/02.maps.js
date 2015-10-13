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

function openPieceDialog(pieceId) {
  var showPieceModal = $('#showPieceModal');
  showPieceModal.find('.modal-body').load(
    Routing.generate('jahller_artlas_piece_show', { id: pieceId }), function() {
    showPieceModal.modal('show');
  });
}

$(document).ready(function() {
  $('#createPiece').click(function() {
    $('#createPieceModal').modal('show');
  });

  $('[data-toggle="tooltip"]').tooltip()

  $('#add-another-tag').click(function(e) {
    e.preventDefault();

    var tagList = $('#tag-fields-list');

    // grab the prototype template
    var newWidget = tagList.attr('data-prototype');
    // replace the "__name__" used in the id and name of the prototype
    // with a number that's unique to your tags
    // end name attribute looks like name="contact[tags][2]"
    newWidget = newWidget.replace(/__name__/g, tagCount);
    tagCount++;

    // create a new list element and add it to the list
    var newLi = $('<li></li>').html(newWidget);
    newLi.appendTo(tagList);
  });
});