'use strict';
var produtor_data = [];
(function() {
    var main = angular.module('produtorStore', ['ngRoute', 'ngProgress'])
            .config(['$routeProvider', function($router) {
                    $router.when('/calendar/:id', {
                        controller: 'calendarController',
                        controllerAs: 'calendar',
                        templateUrl: 'public/html/tableCalendar.html'
                    }).when('/produtor', {
                        controller: 'produtorDataStore',
                        controllerAs: 'produtor2',
                        templateUrl: 'public/html/produtorlist.html'
                    });
                }]);

    main.controller('produtorDataStore', ['$scope', '$http',
        function($scope,$http) {
            var store = this;
            store.data = produtor_data;
            this.id = 1;

            this.getId = function() {
                this.id = id;
            };

            this.unset = function() {
                produtor_data = this.data = [];
            };

            this.getData = function() {
                if (this.data.length === 0)
                    $http.get('/produtor_read').success(function(data) {
                        produtor_data = store.data = data;
                    });
            };
            
            this.update = function(id){
                console.log(this.data[id-1]);
            };
            return this;
        }]);

    main.controller('calendarController', ['$routeParams', '$http', 'ngProgress',
        function($params, $http, ngProgress) {
            var store = this;
            this.days = [];
            this.model = [];
            this.getData = function() {
                ngProgress.start();
                $http.get('/entrada_read/calendar/' + $params.id).success(function(data) {
                    store.days = data;
                    ngProgress.complete();
                });
            };
            this.isSaida = function(saida) {
                return saida > 0;
            };

        }]);
}());