var FrontendController = function($scope, $timeout, PieceManager) {
  $scope.orderAttribute = 'id';
  $scope.orderDirection = 'reverse';
  $scope.currentPiece = null;

  $scope.actions = {
    init: function () {
      var self = this;
      self.initPieces(function() {
        $('#pieces').show();
        $timeout(function() {
          $('.piece .image').each(function() {
            $(this).find('img').attr('src', self.getImageURL($(this).attr('data-id'), 'thumb'));
          });
        }, 1000);
      });
    },

    initPieces: function (callback) {
      $scope.pieceManager = PieceManager;
      $scope.pieceManager.queryPieces(callback);
    },

    sortPiecesBy: function (sorting) {
      if ($scope.orderAttribute == sorting) {
        $scope.orderAttribute = 'id';
        $scope.orderDirection = 'reverse';

        return;
      }

      $scope.orderDirection = '';
      $scope.orderAttribute = sorting;
    },

    getImageURL: function(id, size) {
      return Routing.generate('jahller_piece_image_preview', {'id': id, 'size': size})
    },

    showPieceModal: function(piece) {
      $scope.currentPiece = piece;
      var self = this;
      var showPieceModal = $('#showPieceModal');
      showPieceModal.find('.modal-body .image img').each(function() {
        $(this)
          .attr('src', self.getImageURL(piece.id, 'lightbox'))
          .attr('alt', piece.key)
        ;
      });
      showPieceModal.modal('show');
    },

    hidePieceModal: function() {
      $('#showPieceModal').modal('hide');
    },

    savePiece: function(piece) {
      var self = this;
      $scope.pieceManager.save(piece, function(newPiece) {
        $('#createPieceModal').modal('hide');
        $timeout(function() {
          $('.piece .image[data-id="' + newPiece.id + '"] img').attr('src', self.getImageURL(newPiece.id, 'thumb'));
        }, 1000);
      });
    },

    addTag: function($event, piece) {
      if ($event.which === 13) {
        piece.tags.push({
          title: $($event.target).val()
        });
        $($event.target).val('');
      }
    },

    removeTag: function($event, piece, tagKey) {
      var icon = $($event.target);
      var tag =  $($event.target).parent();
      var isDeletable = tag.hasClass('badge-danger') && icon.hasClass('fa-question');
      if (isDeletable) {
        piece.tags.splice(tagKey, 1);
      } else {
        tag.addClass('badge-danger');
        icon.removeClass('fa-times').addClass('fa-question');

        $timeout(function() {
          tag.removeClass('badge-danger');
          icon.removeClass('fa-question').addClass('fa-times');
        }, 3000)
      }
    },

    showEye: function($event) {
      $($event.target).parent().find('i').show();
    },

    hideEye: function($event) {
      $($event.target).parent().find('i').hide();
    },

    addFile: function($event) {
      var files = $event.target.files;
      $scope.pieceManager.newPiece.imageName = files[0].name;
      $scope.pieceManager.newPiece.imageMimeType = files[0].type;
    }
  };

  $scope.actions.init();
};

FrontendController.$inject = ['$scope', '$timeout', 'PieceManager', '$http', '$filter', '$window'];
frontendApp.controller('FrontendController', FrontendController);