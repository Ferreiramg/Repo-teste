'use strict';
var main = angular.module('SistemaConf', [])

        .config(['$routeProvider', function($router) {
                $router.when('/configsys', {
                    controller: 'ConfiguracaoSistema',
                    controllerAs: 'config',
                    templateUrl: 'public/html/configs.html'
                });
            }]);

main.controller('ConfiguracaoSistema', ['$scope', '$http', function($scope, $http) {
        $scope.data = {year: '2015'};
        $scope.y = ['2013', '2014', '2015'];
        $scope.ss = sessionStorage.year;
        this.changeyear = function() {
            if (sessionStorage.year === $scope.data.year)
                return null;
            sessionStorage.year = $scope.data.year;
            $http.get('/silo/changeYear/' + $scope.data.year);
        };
        $scope.$watch(function() {
            $scope.ss = sessionStorage.year;
        });
    }]);



