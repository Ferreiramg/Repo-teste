'use strict';
var produtor_data = [];
(function() {

    function serializeData(data) {
        // If this is not an object, defer to native stringification.
        if (!angular.isObject(data)) {
            return((data == null) ? "" : data.toString());
        }

        var buffer = [];

        // Serialize each key in the object.
        for (var name in data) {

            if (!data.hasOwnProperty(name)) {

                continue;

            }
            var value = data[ name ];

            buffer.push(
                    encodeURIComponent(name) +
                    "=" +
                    encodeURIComponent((value == null) ? "" : value)
                    );

        }
        // Serialize the buffer and clean it up for transportation.
        var source = buffer
                .join("&")
                .replace(/%20/g, "+");

        return(source);

    }

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

    main.controller('produtorDataStore', ['$scope', '$http', 'ngProgress',
        function($scope, $http, progress) {

            var store = this;
            store.data = produtor_data;
            this.id = 1;
            this.add = false;

            this.addForm = function() {
                this.add = true;
            };
            this.addFormRemove = function() {
                this.add = false;
            };

            this.getId = function() {
                this.id = id;
            };

            this.unset = function() {
                produtor_data = this.data = [];
            };

            this.getData = function() {
                $scope.newdata = {};
                if (this.data.length === 0)
                    $http.get('/produtor_read').success(function(data) {
                        produtor_data = store.data = data;
                    });
            };

            this.new = function() {
                progress.start();
                $scope.newdata.acao = 'create';
                $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
                $http.post('/produtor', serializeData($scope.newdata)).success(
                        function(data) {
                            if (data[0].code > 0) {
                                progress.complete();
                                window.location.reload();
                            }
                        });
            };

            this.update = function(id) {
                console.log(this.data[id - 1]);
            };

            this.delete = function(id) {
                if (this.data[id - 1]) {
                    var cf = confirm('Deseja apagar produtor?');
                    if (cf) {
                        $http.delete('/produtor/deletar/' + id).success(function(data) {
                            if (data.code === 1) {
                                window.location.reload();
                            }
                        });
                    }
                }
            };
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