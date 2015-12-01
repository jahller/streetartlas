var frontendApp = angular.module('frontend', [
    'piece.manager',
    'jahller.directives'
  ])
  .config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('{[').endSymbol(']}');
  });