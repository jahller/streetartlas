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
      var self = this;
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

    showPieceModal: function() {
      $('#updatePieceModal').modal('show');
    },

    hidePieceModal: function() {
      $('#updatePieceModal').modal('hide');
    },

    savePiece: function(piece) {
      var self = this;
      $scope.pieceManager.save(piece, function() {
        self.hidePieceModal();
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
    }
  };

  $scope.actions.init();
};
FrontendController.$inject = ['$scope', '$timeout', 'PieceManager'];
frontendApp.controller('FrontendController', FrontendController);