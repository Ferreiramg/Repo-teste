'use strict';
(function() {
    var FLOAT_REGEXP = /^\-?\d+((\.|\,)\d+)?$/;
    var INTEGER_REGEXP = /^\-?\d+$/;

    var main = angular.module('main',
            [
                'ngRoute',
                'produtorStore',
                'ui.bootstrap.dropdown',
                'ui.bootstrap.tooltip',
                'EntradaStore',
                'ChartController',
                'angles'
            ]);

    main.controller('mainController', ['$http', '$scope', '$log', '$location',
        function($http, $scope, $log, $location) {
            $scope.$log = $log;
            $scope.$linkA = "/dash";
            var store = this;
            this.d_cotacao = [];
            $scope.$active = function() {
                console.log($location.path());
                $scope.$linkA = $location.path();
            };
            $scope.reload = function(id) {
            };
            $scope.tpTextSearch = "Exibir Filtro Pesquisa";
            $scope.visible = true;
            $scope.toggle = function() {
                $scope.visible = !$scope.visible;
                $scope.tpTextSearch = $scope.visible ? "Exibir Filtro Pesquisa" : "Fechar Filtro";
            };
            $scope.status = {
                isopen: false
            };
            $scope.cotacao = function() {
                $http.get('/cotacao-json.php').success(function(data) {
                    store.d_cotacao = data[0];
                });
            };
        }]).config(['$routeProvider', function($router) {
            $router.when('/tabela', {
                templateUrl: 'public/html/pdftabela.html'
            }).when('/resume/:id/:kg', {
                templateUrl: function(params) {
                    return '/produtor_report/' + params.id + '/' + params.kg;
                }
            }).when('/', {
                controller: 'mainController',
                controllerAs: 'home',
                templateUrl: 'public/html/home.html'
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

    var angles = angular.module("angles", []);

    angles.chart = function(type) {
        return {
            restrict: "A",
            scope: {
                data: "=",
                options: "=",
                id: "@",
                width: "=",
                height: "=",
                resize: "=",
                chart: "@",
                segments: "@",
                responsive: "=",
                tooltip: "=",
                legend: "="
            },
            link: function($scope, $elem) {
                var ctx = $elem[0].getContext("2d");
                var autosize = false;

                $scope.size = function() {
                    if ($scope.width <= 0) {
                        $elem.width($elem.parent().width());
                        ctx.canvas.width = $elem.width();
                    } else {
                        ctx.canvas.width = $scope.width || ctx.canvas.width;
                        autosize = true;
                    }

                    if ($scope.height <= 0) {
                        $elem.height($elem.parent().height());
                        ctx.canvas.height = ctx.canvas.width / 2;
                    } else {
                        ctx.canvas.height = $scope.height || ctx.canvas.height;
                        autosize = true;
                    }
                };

                $scope.$watch("data", function(newVal, oldVal) {
                    if (chartCreated)
                        chartCreated.destroy();

                    // if data not defined, exit
                    if (!newVal) {
                        return;
                    }
                    if ($scope.chart) {
                        type = $scope.chart;
                    }

                    if (autosize) {
                        $scope.size();
                        chart = new Chart(ctx);
                    }
                    ;

                    if ($scope.responsive || $scope.resize)
                        $scope.options.responsive = true;

                    if ($scope.responsive !== undefined)
                        $scope.options.responsive = $scope.responsive;

                    chartCreated = chart[type]($scope.data, $scope.options);
                    chartCreated.update();
                    if ($scope.legend)
                        angular.element($elem[0]).parent().after(chartCreated.generateLegend());
                }, true);

                $scope.$watch("tooltip", function(newVal, oldVal) {
                    if (chartCreated !== undefined)
                        chartCreated.draw();
                    if (newVal === undefined || !chartCreated.segments)
                        return;
                    if (!isFinite(newVal) || newVal >= chartCreated.segments.length || newVal < 0)
                        return;
                    var activeSegment = chartCreated.segments[newVal];
                    activeSegment.save();
                    activeSegment.fillColor = activeSegment.highlightColor;
                    chartCreated.showTooltip([activeSegment]);
                    activeSegment.restore();
                }, true);

                $scope.size();
                var chart = new Chart(ctx);
                var chartCreated;
            }
        };
    };


    /* Aliases for various chart types */
    angles.directive("chart", function() {
        return angles.chart();
    });
    angles.directive("linechart", function() {
        return angles.chart("Line");
    });
    angles.directive("barchart", function() {
        return angles.chart("Bar");
    });
    angles.directive("radarchart", function() {
        return angles.chart("Radar");
    });
    angles.directive("polarchart", function() {
        return angles.chart("PolarArea");
    });
    angles.directive("piechart", function() {
        return angles.chart("Pie");
    });
    angles.directive("doughnutchart", function() {
        return angles.chart("Doughnut");
    });
    angles.directive("donutchart", function() {
        return angles.chart("Doughnut");
    });
}());
