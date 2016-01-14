angular.module('piece.manager', ['piece.repository'])
  .factory('PieceManager', function(PieceRepository) {
    return {
      _masterPieces: [],
      pieces: [],
      piecesLoaded: false,
      newPiece: {
        imageFile: null,
        imageName: null,
        imageMimeType: null,
        tags: []
      },

      hasImage: function(piece) {
        return null !== piece.imageFile && null !== piece.imageName && null !== piece.imageMimeType;
      },

      removeImage: function(piece, callback) {
        piece.imageFile = null;
        piece.imageName = null;
        piece.imageMimeType = null;

        if ('function' == typeof callback) {
          callback();
        }
      },

      findPieceById: function (pieceId, pieceCollection) {
        var filtered =  pieceCollection.filter(function(currentPiece) {
          return pieceId == currentPiece.id;
        });

        if (filtered.length >= 1) {
          return filtered[0];
        }

        return null;
      },

      queryPieces: function(callback) {
        var self = this;
        PieceRepository.queryAll({},
          /* OnSuccess */
          function (pieces) {
            self.pieces = pieces;
            self._masterPieces = angular.copy(self.pieces);

            if ('function' == typeof callback) {
              callback();
            }
          },
          /* OnError */
          function (errorObject) {
            console.log('ERROR on piece query: ', errorObject.data);
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

            /**
             * @todo show piece delete success message
             */
          },
          /* OnError */
          function(errorObject) {
            console.log('ERROR on piece delete: ', errorObject.data);
          }
        );
      },

      save: function(piece, callback) {
        if (piece.id) {
          this._update(piece, callback);
        } else {
          this._create(piece, callback);
        }
      },

      /**
       * Update Action
       *
       * @param piece Object
       * @param callback function
       * @private
       */
      _update: function(piece, callback) {
        var self = this;

        PieceRepository.update(piece,
          /**
           * OnSuccess
           */
          function(newPiece, headers) {
            var masterIndex = self._masterPieces.indexOf(self.findPieceById(piece.id, self._masterPieces));
            var pieceIndex = self.pieces.indexOf(self.findPieceById(piece.id, self.pieces));

            self._masterPieces[masterIndex] = angular.copy(newPiece);
            self.pieces[pieceIndex] = newPiece;

            /**
             * @todo show piece update success message
             */
            if ('function' == typeof callback) {
              callback();
            }
          },
          /**
           * OnError
           */
          function(errorObject) {
            console.log('ERROR on piece update: ', errorObject.data);
          }
        );
      },

      /**
       * Create Action
       *
       * @param piece Object
       * @param callback function
       * @private
       */
      _create: function(piece, callback) {
        var self = this;

        PieceRepository.create(piece,
          /**
           * OnSuccess
           */
          function(newPiece, headers) {
            self._masterPieces.push(angular.copy(newPiece));
            self.pieces.push(newPiece);

            /**
             * @todo show piece create success message
             */
            if ('function' == typeof callback) {
              callback(newPiece);
            }
          },
          /**
           * OnError
           */
          function(errorObject) {
            console.log('ERROR on piece create: ', errorObject.data);
          }
        )
      }
    };
  });