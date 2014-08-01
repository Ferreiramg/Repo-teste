'use strict';
(function() {
    var FLOAT_REGEXP = /^\-?\d+((\.|\,)\d+)?$/;
    var INTEGER_REGEXP = /^\-?\d+$/;

    var main = angular.module('main',
            [
                'ngRoute',
                'produtorStore',
                'ui.bootstrap.dropdown',
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
            $scope.status = {
                isopen: false
            };
        }]).config(['$routeProvider', function($router) {
            $router.when('/tabela', {
                templateUrl: 'public/html/pdftabela.html'
            });
        }]);
    main.directive('smartFloat', function() {
        return {
            require: 'ngModel',
            link: function(scope, elm, attrs, ctrl) {
                ctrl.$parsers.unshift(function(viewValue) {
                    if (FLOAT_REGEXP.test(viewValue)) {
                        ctrl.$setValidity('float', true);
                        return parseFloat(viewValue.replace(',', '.'));
                    } else {
                        ctrl.$setValidity('float', false);
                        return undefined;
                    }
                });
            }
        };
    });

    main.directive('integer', function() {
        return {
            require: 'ngModel',
            link: function(scope, elm, attrs, ctrl) {
                ctrl.$parsers.unshift(function(viewValue) {
                    if (INTEGER_REGEXP.test(viewValue)) {
                        // it is valid
                        ctrl.$setValidity('integer', true);
                        return viewValue;
                    } else {
                        // it is invalid, return undefined (no model update)
                        ctrl.$setValidity('integer', false);
                        return undefined;
                    }
                });
            }
        };
    });
}());
