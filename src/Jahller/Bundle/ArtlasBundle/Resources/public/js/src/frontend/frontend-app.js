var frontendApp = angular.module('frontend', ['piece.manager'])
  .config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('{[').endSymbol(']}');
  });