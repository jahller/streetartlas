angular.module('jahller.directives', []);

angular.module('jahller.directives').directive('customOnChange', function() {
  return {
    restrict: 'A',
    link: function (scope, element, attributes) {
      var onChangeHandler = scope.$eval(attributes.customOnChange);
      element.bind('change', onChangeHandler);
    }
  };
});

angular.module('jahller.directives').directive('fileread', [function () {
  return {
    scope: {
      fileread: '='
    },
    link: function (scope, element, attributes) {
      element.bind('change', function(changeEvent) {
        var reader = new FileReader();
        reader.onload = function(loadEvent) {
          scope.$apply(function() {
            scope.fileread = loadEvent.target.result;
          });
        };
        if ('undefined' !== typeof changeEvent.target.files[0]) {
          reader.readAsDataURL(changeEvent.target.files[0]);
        }
      });
    }
  }
}]);