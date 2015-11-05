angular.module('piece.manager', ['piece.repository'])
  .factory('PieceManager', function(PieceRepository) {
    return {
      _masterPieces: [],
      pieces: [],
      piecesLoaded: false,

      findPieceById: function (pieceId, pieceCollection) {
        var filtered =  pieceCollection.filter(function(currentPiece) {
          return pieceId == currentPiece.id;
        });

        if (filtered.length >= 1) {
          return filtered[0];
        }

        return null;
      },

      queryPieces: function() {
        var self = this;
        PieceRepository.queryAll({},
          /* OnSuccess */
          function (pieces) {
            self.pieces = pieces;
            self._masterPieces = angular.copy(self.pieces);
          },
          /* OnError */
          function (errorObject) {
            /*seedpm.flash('error', 'Fetch pieces failed');*/
          }
        )
      },

      remove: function(piece) {
        var self = this;

        PieceRepository.remove(piece,
          /* OnSuccess */
          function(headers) {
            var masterIndex = self._masterPieces.indexOf(self.findPieceById(piece.id, self._masterPieces));
            var pieceIndex = self.pieces.indexOf(self.findPieceById(piece.id, self.pieces));

            self._masterPieces.splice(masterIndex, 1);
            self.pieces.splice(pieceIndex, 1);

            /*seedpm.flash('success', 'Piece successfully deleted');*/
          },

          /* OnError */
          function(errorObject) {
            console.log(errorObject.data);
            /*seedpm.flash('error', 'Error on deleting a piece');*/
          }
        );
      },

      save: function(piece) {
        if (piece.id) {
          this._update(piece);
        } else {
          //this._create(project);
        }
      },

      /**
       * Update Action
       *
       * @param piece
       * @private
       */
      _update: function(piece) {
        var self = this;

        PieceRepository.save(piece,
          /**
           * OnSuccess
           */
          function(newPiece, headers) {
            var masterIndex = self._masterPieces.indexOf(self.findPieceById(piece.id, self._masterPieces));
            var pieceIndex = self.pieces.indexOf(self.findPieceById(piece.id, self.pieces));

            self._masterPieces[masterIndex] = angular.copy(newPiece);
            self.pieces[pieceIndex] = newPiece;

            //seedpm.flash('success', 'Piece successfully updated');
          },

          /**
           * OnError
           */
          function(errorObject) {
            console.log(errorObject.data);
            //ErrorHandler.handleErrors(errorObject.data);
            //seedpm.flash('error', 'An error occurred while updating the piece');
          }
        );
      }
    };
  });