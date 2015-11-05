angular.module('piece.repository', ['ngResource'])
  .factory('PieceRepository', function ($resource) {
    return {
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
       *
       * @param piece
       * @param successCallback
       * @param errorCallback
       * @returns {*}
       */
      save: function(piece, successCallback, errorCallback) {
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
