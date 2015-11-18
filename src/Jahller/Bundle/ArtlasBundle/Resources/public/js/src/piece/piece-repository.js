angular.module('piece.repository', ['ngResource'])
  .factory('PieceRepository', function ($resource) {
    return {

      /**
       * @param piece
       * @returns {{}}
       * @private
       */
      _preparePiece: function(piece) {
        return {
          id: piece.id,
          active: piece.active,
          tags: piece.tags,
          image: {
            id: piece.image.id,
            path: piece.image.path,
            name: piece.image.name,
            size: piece.image.size,
            extension: piece.image.extension,
            mimeType: piece.image.mime_type,
            exifData: {
              latitude: piece.image.exif_data.latitude,
              longitude: piece.image.exif_data.longitude
            }
          }
        };
      },

      /**
       * @param params
       * @param successCallback
       * @param errorCallback
       * @returns {*}
       */
      queryAll: function (params, successCallback, errorCallback) {
        var repository = $resource(Routing.generate('get_pieces'),
          {},
          {
            query: {
              method: 'GET',
              isArray: true
            }
          });

        return repository.query(params, successCallback, errorCallback);
      },

      /**
       * @param piece
       * @param successCallback
       * @param errorCallback
       * @returns {*}
       */
      remove: function(piece, successCallback, errorCallback) {
        var repository = $resource(decodeURIComponent(Routing.generate('delete_piece', {id: ':id'})),
          {},
          {
            remove: {
              method:'DELETE'
            }
          });

        return repository.remove({id: piece.id}, successCallback, errorCallback);
      },

      /**
       * @param piece
       * @param successCallback
       * @param errorCallback
       * @returns {*}
       */
      update: function(piece, successCallback, errorCallback) {
        piece = this._preparePiece(piece);
        var repository = $resource(decodeURIComponent(Routing.generate('put_piece', {piece: ':piece'})),
          {
            piece: '@id'
          },
          {
            save: {
              method:'PUT'
            }
          });

        return repository.save(piece, successCallback, errorCallback);
      }
    };
  });
