var BackendController = function($scope, PieceManager) {
  $scope.orderAttribute = 'id';
  $scope.orderDirection = 'reverse';

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

    deletePiece: function(piece) {
      $scope.pieceManager.remove(piece);
    },

    togglePieceActive: function(piece) {
      piece.active = !piece.active;
      $scope.pieceManager.save(piece);
    },

    showPieceModal: function(piece) {
      var updatePieceModal = $('#updatePieceModal');
      updatePieceModal.find('.modal-body').load(
        Routing.generate('jahller_artlas_piece_update', { 'id': piece.id }), function() {
          updatePieceModal.modal('show');
        }
      );
    }
  };

  $scope.actions.init();
};
BackendController.$inject = ['$scope', 'PieceManager'];
backendApp.controller('BackendController', BackendController);