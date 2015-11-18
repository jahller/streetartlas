var BackendController = function($scope, $timeout, PieceManager) {
  $scope.orderAttribute = 'id';
  $scope.orderDirection = 'reverse';
  $scope.currentPiece = null;

  $scope.actions = {
    init: function () {
      var self = this;
      self.initPieces();
    },

    initPieces: function () {
      $scope.pieceManager = PieceManager;
      $scope.pieceManager.queryPieces();
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

    deletePiece: function($event, piece) {
      var buttonElement = null;
      if ('I' == $event.target.nodeName) {
        buttonElement = $($event.target).parent();
      }
      if ('BUTTON' == $event.target.nodeName) {
        buttonElement = $($event.target);
      }

      if (null !== buttonElement) {
        var isDeletable = buttonElement.hasClass('btn-danger') && buttonElement.find('i').hasClass('fa-question');
        if (isDeletable) {
          $scope.pieceManager.remove(piece);
        } else {
          buttonElement.removeClass('btn-warning').addClass('btn-danger');
          buttonElement.find('i').removeClass('fa-trash').addClass('fa-question');

          $timeout(function() {
            buttonElement.removeClass('btn-danger').addClass('btn-warning');
            buttonElement.find('i').removeClass('fa-question').addClass('fa-trash');
          }, 3000)
        }
      }
    },

    togglePieceActive: function(piece) {
      piece.active = !piece.active;
      $scope.pieceManager.save(piece);
    },

    showPieceModal: function() {
      $('#updatePieceModal').modal('show');
    },

    hidePieceModal: function() {
      $('#updatePieceModal').modal('hide');
    },

    editPiece: function(piece) {
      $scope.currentPiece = piece;
      this.showPieceModal(piece);
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
BackendController.$inject = ['$scope', '$timeout', 'PieceManager'];
backendApp.controller('BackendController', BackendController);