'use strict';
(function() {
    var main = angular.module('main',
            [
                'ngRoute',
                'produtorStore',
                'EntradaStore'
            ]).run(function($rootScope, $templateCache) {
        $rootScope.$on('$viewContentLoaded', function() {
            $templateCache.removeAll();
        });
    });

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
        }]).config(['$routeProvider', function($router) {
            $router.when('/tabela', {
                templateUrl: 'public/html/pdftabela.html'
            });
        }]);
}());
