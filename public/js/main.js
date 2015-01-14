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
                'ui.bootstrap.alert',
                'EntradaStore',
                'ChartController',
                //'io.service',
                'angles'
            ])
            .run(function() {
                console.log('app init.');
            });

    main.controller('ioController', function($scope, io) {
        $scope.io = io;
    });

    main.controller('mainController', ['$http', '$scope', '$log', '$location',
        function($http, $scope, $log, $location) {
            $scope.$log = $log;
            $scope.$linkA = "/dash";
            var store = this;
            var currentDate = new Date();
            this.d_cotacao = [];
            this.clima_tempo = [];
            $scope.tpTextSearch = "Exibir Filtro Pesquisa";
            $scope.visible = true;

            $scope.year = currentDate.getFullYear();
            $scope.$active = function() {
                console.log($location.path());
                $scope.$linkA = $location.path();
            };
            $scope.reload = function(id) {
                var params = $location.url().split('/');
                params.shift();
                var _p1 = params[0] || null;
                var _p2 = params[1] || null;
                if (!_p2) {
                    $scope.param_u = _p1;
                    return null;
                }
                var _id = parseInt(_p2) > 2000 ? _p2 : id;
                var _p3 = params[2] ? '/' + params[2] : '';
                var _p4 = params[3] ? '/' + params[3] : '';
                $scope.param_u = _p1 + '/' + _id + _p3 + _p4;
            };

            $scope.toggle = function() {
                $scope.visible = !$scope.visible;
                $scope.tpTextSearch = $scope.visible ? "Exibir Filtro Pesquisa" : "Fechar Filtro";
            };
            $scope.cotacao = function() {
                $http.get('/cotacao-json.php').success(function(data) {
                    store.d_cotacao = data[0];
                    store.getbycookie = data.cookie || false;
                });
            };
            $scope.tempo = function() {
                $http.get('/clima.php').success(function(data) {
                    store.clima_tempo = data;
                });
            };
            $scope.opt_theader =
                    {
                        scrollingTop: 50,
                        useAbsolutePositioning: false
                    };
        }]).config(['$routeProvider', function($router) {
            $router.when('/tabela', {
                templateUrl: 'public/html/pdftabela.html'
            }).when('/', {
                controller: 'mainController',
                controllerAs: 'home',
                templateUrl: 'public/html/home.html'
            });
        }]);
    /*directives*/
//    var texto_help = 'Forma de Uso: <command> [<args>]\n\
//    version,                 Exibe a versão.\n\
//Principais Comandos:\n\
//    restart             reinicia sistema.\n\
//    backup              Serviço de backup.\n\
//    mail                Serviço de E-mail.\n\
//    dbase               Banco de dados.\n\
//    status              Atualização do sistema.\n\
//    self-update         Checa e atualiza sistema.\n\
//    rollback            Reverte ultima atualização.\n\
//\n\
//Sub-Comandos:\n\
//\n\
//    Para exibir sub-comandos individuais, execute: `sas COMANDO -h`\n\
//\n\
//';
//    main.directive('terminal', [function() {
//            return {
//                require: '?ngModel',
//                controller: 'ioController',
//                restrict: 'A',
//                link: function(scope, element, attrs, ctrl) {
//                    $(element).terminal(function(command, term) {
//                      ///refazer com web sockets
//                           
//                    }, {
//                        greetings: 'Console Terminal. Digite [ help ] para exibir comados disponiveis.',
//                        name: 'sys console',
//                        height: 300,
//                        prompt: '@sas> '});
//                }
//            };
//        }]);
    main.directive('reload', ['$location', function(location) {
            return {
                restrict: 'E',
                replace: true,
                template: '<a class="btn btn-default btn-sm" href="#/{{param_u}}" ><span class="glyphicon glyphicon-refresh"></span></a>',
            };
        }]);
    main.directive('produtorName', function() {
        return{
            restrict: 'E',
            controller: 'produtorDataStore',
            controllerAs: 'produtor',
            template: '<b>{{produtor.getProdutor().nome}}</b>'
        };
    });
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
    main.directive('floatThead', ['$timeout', '$log', floatThead]);

    function floatThead($timeout, $log) {
        return {
            require: '?ngModel',
            link: link,
            restrict: 'A'
        };

        function link(scope, element, attrs, ngModel) {
            $(element).floatThead(scope.$eval(attrs.floatThead));

            if (ngModel) {
                // Set $watch to do a deep watch on the ngModel (collection) by specifying true as a 3rd parameter
                scope.$watch(attrs.ngModel, function() {
                    $(element).floatThead('reflow');
                }, true);
            } else {
                $log.info('floatThead: ngModel not provided!');
            }

            element.bind('update', function() {
                $timeout(function() {
                    $(element).floatThead('reflow');
                }, 0);
            });

            element.bind('$destroy', function() {
                $(element).floatThead('destroy');
            });
        }
    }
    ;
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
