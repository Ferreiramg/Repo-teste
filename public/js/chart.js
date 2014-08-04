(function(angular) {
    'use stric';

    var main = angular.module('ChartController', ['ngRoute'])
            .config(['$routeProvider', function($router) {
                    $router.when('/inout/:id', {
                        controller: 'ChartInOut',
                        controllerAs: 'chart',
                        templateUrl: 'public/html/inoutchart.html'
                    });
                }]);

    main.controller('ChartInOut', ['$scope', '$routeParams', '$http', 'ngProgress',
        function($scope, $params, $http, progress) {
            var store = this;

           var chart = {
                type: "LineChart",
                options: {
                    title: 'Entradas e Saidas'
                }
            };
            this.getData = function() {
                progress.start();
                $http.get('/produtor_chart/outinchart/' + $params.id).success(function(data) {
                    store.days = data;
                    progress.complete();
                });
            };
        }]);

}(angular));