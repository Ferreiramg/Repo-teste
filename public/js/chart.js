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
                    }).when('/chartresumeservice', {
                        controller: 'ChartInOut',
                        controllerAs: 'tc',
                        templateUrl: 'public/html/chartsiloservico.html'
                    });
                }]);

    main.controller('ChartInOut', ['$scope', '$routeParams', '$http',
        function($scope, $params, $http) {
            var store = this;
            this.info = {};
            this.sinfo = {};
            $scope.table = {};
            $scope.ano = $params.ano;

            this.initServicos = function(data) {
                $scope.chart = data;
                this.sinfo.total = (data.total / 60);
                this.sinfo.media = (data.total / 60) / data.datasets[0].data.length;

                $scope.options = {
                    responsive: true,
                    scaleBeginAtZero: true,
                    scaleShowGridLines: true,
                    scaleGridLineColor: "rgba(0,0,0,.05)",
                    scaleGridLineWidth: 1,
                    barShowStroke: true,
                    barStrokeWidth: 2,
                    barValueSpacing: 5,
                    barDatasetSpacing: 1,
                    legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
                };

            };

            this.listAllDatasDetail = function() {
                $http.get('/silo/getAllDataProdutores/' + $params.ano).success(
                        function(data) {
                            $scope.table = data;
                        });
            };

            this.servicoGetData = function() {
                $http.get('/silo/siloPServicos').success(
                        function(data) {
                            data.datasets[0].label = 'Entrada';
                            data.datasets[0].fillColor = "rgba(0,205,0,0.2)";
                            data.datasets[0].strokeColor = "rgba(0,205,0,0.8)";
                            data.datasets[0].pointColor = "rgba(0,205,0,1)";
                            store.initServicos(data);
                        });
            };
            this.init = function(data) {
                $scope.produtor_nome = produtor_data[$params.id - 1].nome || null;
                $scope.chart = data;
                $scope.options = {
                    responsive: true,
                    animation: true,
                    bezierCurve: false,
                    tooltipTemplate: "<%if (label){%><%=label %>: <%}%><%= value %> Kg",
                    multiTooltipTemplate: "<%= datasetLabel %> - <%= value %> Kg"
                };
            };
            this.initopt = function(data) {
                $scope.chart2 = data;
                $scope.options2 = {
                    responsive: true,
                    animation: true,
                    bezierCurve: false,
                    tooltipTemplate: "<%if (label){%><%=label %>: <%}%><%= value %> s60Kg",
                    multiTooltipTemplate: "<%= datasetLabel %> - <%= value %> s60Kg"
                };
            };
            this.armzChart = function() {

                $http.get('/silo/armzChart/' + $params.ano).success(function(data) {
                    data.datasets[0].label = 'Armazenagem';
                            data.datasets[0].fillColor = "rgba(255, 255, 137,0.4)";
                            data.datasets[0].strokeColor = "rgba(255, 255, 137,1)";
                            data.datasets[0].pointColor = "rgba(255, 255, 0,1)";
                            data.datasets[0].pointStrokeColor = "#fff";
                            data.datasets[0].pointHighlightFill = "#fff";
                            data.datasets[0].pointHighlightStroke = "rgba(255, 255, 137,1)";
                    store.initopt(data);
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
                $http.get('/silo/totalEstocado/' + $params.ano).success(function(data) {
                    store.info = data;
                    var chart = [
                        {
                            value: data.amz,
                            color: "#F7464A",
                            highlight: "#FF5A5E",
                            label: "Armazenado"
                        },
                        {
                            value: data.ts,
                            color: "#fada0a",
                            highlight: "#f0e38d",
                            label: "Serviço"
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

        }]);
}());