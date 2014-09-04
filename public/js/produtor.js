'use strict';
var produtor_data = [];

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

var main = angular.module('produtorStore', [
    'ngRoute',
    'ngProgress',
    'angularFileUpload',
    'ui.bootstrap'])
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

main.controller('mailController',
        function($scope, $modalInstance, items, $upload, progress) {
            $scope.mail = items.email;
            $scope.message = {info:"Não existe E-mail salvo!",class:"text-danger"};
            $scope.per = 0;

            var file = {};
            
            $scope.cancel = function() {
                $modalInstance.dismiss('cancel');
            };
            $scope.send = function() {
                if (file.length === 1)
                    $scope.upload = $upload.upload({
                        url: '/debug.php',
                        data: {maile: $scope.message},
                        file: file
                    }).progress(function(evt) {
                        progress.set($scope.per = parseInt(100.0 * evt.loaded / evt.total));
                    }).success(function(data, status, headers, config) {
                        console.log(data);
                        progress.complete();
                    });
                else {

                }
            };
            $scope.upload = function($files) {
                file = $files;
            };
        });
main.controller('produtorDataStore', ['$scope', '$upload', '$modal', '$http', 'ngProgress',
    function($scope, $upload, $modal, $http, progress) {

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
                        var resp = data[0] || data;
                        if (resp.code === "1") {
                            progress.complete();
                            window.location.reload();
                        }
                    });
        };

        this.update = function(id) {
            var _data = this.data[id - 1] || false;
            if (_data) {
                progress.start();
                _data.acao = 'update';
                $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
                $http.post('/produtor', serializeData(_data)).success(
                        function(data) {
                            var resp = data[0] || data;
                            if (resp.code === "1") {
                                progress.complete();
                            }
                        });
            }
        };

        this.delete = function(id) {
            if (this.data[id - 1]) {
                var cf = confirm('Deseja apagar produtor?');
                if (cf) {
                    progress.start();
                    $http.delete('/produtor/deletar/' + id).success(function(data) {
                        var resp = data[0] || data;
                        if (resp.code === "1") {
                            progress.complete();
                            window.location.reload();
                        }
                    });
                }
            }
        };

        this.open = function(id) {
            var _data = this.data[id - 1] || false;
            $modal.open({
                templateUrl: 'mailContent.html',
                controller: 'mailController',
                resolve: {
                    items: function() {
                        return _data;
                    },
                    fileupload: function() {
                        return $upload;
                    },
                    progress: function() {
                        return progress;
                    }
                }
            });
        };
    }]);

main.controller('calendarController', ['$scope', '$routeParams', '$http', 'ngProgress',
    function($scope, $params, $http, ngProgress) {
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
        $scope.open = function(v) {
            var d = {};
            d.acao = 'makeQT';
            d.peso = v.saldo;
            d.produtor = $params.id;
            d.data = v.dia;
            ngProgress.start();
            $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
            $http.post('/entrada', serializeData(d)).success(
                    function(data) {
                        var resp = data[0] || data;
                        if (resp.code !== "0") {
                            ngProgress.complete();
                            window.location.reload();
                        }
                    });
        };
    }]);

main.filter("total", function() {
    return function(items, field) {
        var total = 0, i = 0;
        for (i = 0; i < items.length; i++)
            total += items[i][field];
        return total;
    };
});
