'use stric';
(function() {
    var main = angular.module('EntradaStore', [
        'ngRoute', 
        'mgcrea.ngStrap.button', 
        'mgcrea.ngStrap.datepicker',
        'produtorStore'])
            .config(['$routeProvider', function($router) {
                    $router.when('/entrada', {
                        controller: 'EntradasController',
                        controllerAs: 'entradas',
                        templateUrl: 'public/html/entradaview.html'
                    });
                }]);

    main.controller('EntradasController', function($route) {
    });

}());