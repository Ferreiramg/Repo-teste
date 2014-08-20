(function() {
    'use stric';

    var main = angular.module('ChartController', ['ngRoute', 'angles'])
            .config(['$routeProvider', function($router) {
                    $router.when('/inout/:id', {
                        controller: 'ChartInOut',
                        controllerAs: 'gp',
                        templateUrl: 'public/html/inoutchart.html'
                    }).when('/amzsilo/:ano', {
                        controller: 'ChartInOut',
                        controllerAs: 'tc',
                        templateUrl: 'public/html/chartsilo.html'
                    });
                }]);

    main.controller('ChartInOut', ['$scope', '$routeParams', '$http',
        function($scope, $params, $http) {
            var store = this;
            this.info = {};
            
            this.init = function(data) {
                $scope.produtor_nome = produtor_data[$params.id - 1].nome || null;
                if (data.lenght === 0)
                    return null;
                $scope.chart = data;
                $scope.options = {
                    responsive: true,
                    animation: true,
                    bezierCurve: false,
                    tooltipTemplate: "<%if (label){%><%=label %>: <%}%><%= value %> Kg",
                    multiTooltipTemplate: "<%= datasetLabel %> - <%= value %> Kg"
                };
            };

            this.initTotalChart = function(data) {
                $scope.chart = data;
                $scope.options = {
                    responsive: true,
                    animationSteps: 100,
                    animationEasing: "easeOutBounce",
                    tooltipTemplate: "<%if (label){%><%=label %>: <%}%><%= value %> sc/60Kg"
                };
            };
            this.getDataTotal = function() {
                $http.get('/silo/totalEstocado/'+$params.ano).success(function(data) {
                    store.info = data;
                    var chart = [
                        {
                            value: data.amz,
                            color: "#F7464A",
                            highlight: "#FF5A5E",
                            label: "Armazenado"
                        },
                        {
                            value: data.espaco,
                            color: "#FDB45C",
                            highlight: "#FFC870",
                            label: "Livre"
                        }
                    ];
                    store.initTotalChart(chart);
                });
            };
            this.getData = function() {
                $http.get('/produtor_chart/outinchart/' + $params.id).success(
                        function(data) {
                            data.datasets[0].label = 'Entrada';
                            data.datasets[0].fillColor = "rgba(220,220,220,0.2)";
                            data.datasets[0].strokeColor = "rgba(220,220,220,1)";
                            data.datasets[0].pointColor = "rgba(220,220,220,1)";
                            data.datasets[0].pointStrokeColor = "#fff";
                            data.datasets[0].pointHighlightFill = "#fff";
                            data.datasets[0].pointHighlightStroke = "rgba(220,220,220,1)";
                            data.datasets[1].label = 'Saida';
                            data.datasets[1].fillColor = "rgba(151,187,205,0.2)";
                            data.datasets[1].strokeColor = "rgba(151,187,205,1)";
                            data.datasets[1].pointColor = "rgba(151,187,205,1)";
                            data.datasets[1].pointStrokeColor = "#fff";
                            data.datasets[1].pointHighlightFill = "#fff";
                            data.datasets[1].pointHighlightStroke = "rgba(151,187,205,1)";
                            store.init(data);
                        });
            };
        }]);
}());