'use strict';
(function() {
    var main = angular.module('main', ['ngRoute', 'produtorStore']);

    main.controller('mainController', ['$scope', '$log', '$location',
        function($scope, $log, $location) {
            $scope.$log = $log;
            $scope.$linkA = "/dash";
            $scope.$active = function() {
                console.log($location.path());
                $scope.$linkA = $location.path();
            };
            $scope.visible = true;
            $scope.toggle = function() {
                $scope.visible = !$scope.visible;
            };
        }]);
}());