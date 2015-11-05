var backendApp = angular.module('backend', ['piece.manager'])
  .config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('{[').endSymbol(']}');
  });