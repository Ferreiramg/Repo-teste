'use strict';
(function() {
    var main = angular.module('produtorStore', ['ngProgress']);

    main.controller('produtorDataStore', ['$http',
        function($http) {
            var store = this;
            store.produtores = [];
            this.id = 1;

            this.getId = function(id) {
                this.id = id;
            };

            this.unset = function() {
                store.produtores = [];
            };

            this.getData = function() {
                if (store.produtores.length === 0)
                    $http.get('/produtor_read').success(function(data) {
                        store.produtores = data;
                    });
            };
            return this;
        }]);

    main.controller('calendarController', ['$scope', '$http', 'ngProgress',
        function($scope,$http, ngProgress) {
            var store = this;
            $scope.calendardays = [];
            this.getCalendarData = function(/*produtorDataStore*/ produtor) {
                ngProgress.start();
                $http.get('/entrada_read/calendar/' + produtor.id).success(function(data) {                  
                    $scope.$apply(function() {
                        $scope.calendardays = data;
                    });
                    ngProgress.complete();
                });
            };
            this.isSaida = function(saida) {
                return saida > 0;
            };

        }]).directive('calendarGrid',
            function() {
                return {
                    restrict: 'E',
                    templateUrl: '/public/html/tableCalendar.html',
                    controllerAs: 'calendar',
                    controller: 'calendarController'
                };
            });
}());


