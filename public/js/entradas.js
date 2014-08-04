
(function() {
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
    var main = angular.module('EntradaStore', [
        'ngRoute',
        'ui.bootstrap.buttons',
        'ui.bootstrap.typeahead',
        'ngProgress',
        'produtorStore'])
            .config(['$routeProvider', function($router) {
                    $router.when('/entrada', {
                        controller: 'EntradasController',
                        controllerAs: 'entradas',
                        templateUrl: 'public/html/entradaview.html'
                    });
                }]);

    main.controller('EntradasController', ['$scope', '$http', 'ngProgress',
        function($scope, $http, progress) {

            this.radio = '1';
            this.dt = {};
            $scope.newd = {produtor: 1};
            $scope.register = [];

            this.add = function() {
                $scope.newd.data = this.dt;
                $scope.newd.tipo = this.radio;

                if (this.radio === '0') {
                    $scope.newd.umidade = 0;
                    $scope.newd.impureza = 0;
                }
                progress.start();
                $scope.newd.motorista = typeof $scope.newd.motorista === 'object' ? $scope.newd.motorista.mt : $scope.newd.motorista;
                $scope.newd.acao = 'create';
                $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
                $http.post('/entrada', serializeData($scope.newd)).success(
                        function(data) {
                            if (data[0].code === "1") {
                                progress.complete();
                                $scope.register.push({
                                    mt: $scope.newd.motorista,
                                    tc: $scope.newd.ticket,
                                    dt: $scope.newd.data
                                });
                            }
                        });
            };

            var currentDate = new Date();
            var day = currentDate.getDate();
            var month = currentDate.getMonth() + 1;
            var year = currentDate.getFullYear();
            this.dt = day + "-" + month + "-" + year;
        }]);

}());