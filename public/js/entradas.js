
(function () {
    'use stric';
    function serializeData(data) {
        // If this is not an object, defer to native stringification.
        if (!angular.isObject(data)) {
            return((data === null) ? "" : data.toString());
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
                    encodeURIComponent((value === null) ? "" : value)
                    );
        }
        // Serialize the buffer and clean it up for transportation.
        var source = buffer.join("&").replace(/%20/g, "+");

        return(source);
    }
    ;
    function convertKG(value, field) {
        var kg = parseInt(field);
        return value / kg;
    }
    ;
    var main = angular.module('EntradaStore', [
        'ngRoute',
        'ui.bootstrap.buttons',
        'ui.bootstrap.typeahead',
        'ui.bootstrap.tabs',
        'ngProgress',
        'produtorStore'])
            .config(['$routeProvider', function ($router) {
                    $router.when('/entrada', {
                        controller: 'EntradasController',
                        controllerAs: 'entradas',
                        templateUrl: 'public/html/entradaview.html'
                    }).when('/read/:id', {
                        controller: 'EntradasController',
                        controllerAs: 'entradas',
                        templateUrl: 'public/html/entradagrid.html'
                    }).when('/simulator', {
                        controller: 'SimuladorEntrada',
                        controllerAs: 'S',
                        templateUrl: 'public/html/simulador.html'
                    });
                }]);

    main.controller('SimuladorEntrada', ['$http', 'ngProgress', function ($http, progress) {
            this.data = {};
            this.params = {};
            this.params.acao = 'simulador';
            this.title = "IFORMAÇÕES";
            var store = this;
            this.run = function () {
                if (this.params.peso !== undefined) {
                    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
                    $http.post('/simulator', serializeData(this.params))
                            .success(function (data) {
                                store.title = data.msg || "IFORMAÇÕES";
                                store.data = data;
                            });
                }

            };
        }]);
    main.controller('EntradasController', ['$scope', '$routeParams', '$http', 'ngProgress',
        function ($scope, $params, $http, progress) {

            var store = this;
            $scope.id = 0;
            this.data = [];
            this.radio = '1';
            this.kg = '1';
            this.cvalue = 0;
            this.d = 0;

            this.dt = {};
            $scope.errorEntrada = null;
            $scope.newd = {produtor: 1};
            $scope.register = [];
            $scope.disable = [];
            $scope.Selected = {};

            function sortOn(collection, name) {
                collection.sort(
                        function (a, b) {
                            if (a.group === name) {
                                return(-1);
                            }
                            return(1);
                        }
                );
            }

            $scope.groupBy = function (attribute) {

                // First, reset the groups.
                $scope.groups = [];

                // Now, sort the collection of friend on the
                // grouping-property. This just makes it easier
                // to split the collection.
                sortOn(store.data, attribute);

                // I determine which group we are currently in.
                var groupValue = "_INVALID_GROUP_VALUE_";

                // As we loop over each friend, add it to the
                // current group - we'll create a NEW group every
                // time we come across a new attribute value.
                for (var i = 0; i < store.data.length; i++) {

                    var friend = store.data[ i ];

                    // Should we create a new group?
                    if (friend[ attribute ] !== groupValue) {

                        var group = {
                            label: friend[ attribute ],
                            data: []
                        };

                        groupValue = group.label;

                        $scope.groups.push(group);

                    }

                    // Add the friend to the currently active
                    // grouping.
                    group.data.push(friend);

                }
            };

            $scope.setSelected = function (Selected, i) {
                Selected.index = i;
                $scope.Selected = Selected;
            };
            $scope.closeAlert = function () {
                $scope.errorEntrada = null;
                progress.complete();
            };
            this.wasTrans = function (value) {
                return value === "1";
            };
            this.converter = function () {
                $scope.newd.peso = $scope.newd.peso * 60;
            };
            this.getData = function () {
                $scope.id = $params.id;
                var id = $scope.id;
                $http.get('/entrada_read/' + id).success(function (data) {
                    store.data = data;
                    angular.forEach(data, function (value, key) {
                        this.push({d: false});
                    }, $scope.disable);
                });
            };
            this.deletar = function (id, i) {
                var cf = confirm('Deseja apagar Entrada?');
                if (cf) {
                    progress.start();
                    $http.delete('/entrada/deletar/' + id).success(function (data) {
                        var resp = data[0] || data;
                        if (resp.code === "1") {
                            $scope.disable[i].d = true;
                            progress.complete();
                        }
                    });
                }
            };
            this.add = function () {
                $scope.newd.data = this.dt;
                $scope.newd.tipo = this.radio;
                $scope.newd.wastrans = this.radio === '2' ? 1 : 0;
                if (this.radio === '0') {
                    $scope.newd.umidade = 0;
                    $scope.newd.impureza = 0;
                }
                progress.start();
                $scope.newd.motorista = typeof $scope.newd.motorista === 'object' ? $scope.newd.motorista.mt : $scope.newd.motorista;
                $scope.newd.acao = 'create';
                $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
                $http.post('/entrada', serializeData($scope.newd)).success(
                        function (data) {
                            var resp = data[0] || data;
                            if (resp.code !== "0") {
                                progress.complete();
                                $scope.disable.push({d: false});
                                $scope.register.push({
                                    id: resp.code,
                                    mt: $scope.newd.motorista,
                                    tc: $scope.newd.ticket,
                                    dt: $scope.newd.data
                                });

                            }
                            $scope.errorEntrada = resp.message;
                        });
            };

            var currentDate = new Date();
            var day = currentDate.getDate();
            var month = currentDate.getMonth() + 1;
            var year = currentDate.getFullYear();
            this.dt = day + "-" + month + "-" + year;
        }]);

    main.filter("total", function () {
        return function (items, field) {
            var total = 0, i = 0;
            for (i = 0; i < items.length; i++)
                total += items[i][field];
            return total;
        };
    });
    main.filter("kgConverte", function () {
        return convertKG;
    });
}());