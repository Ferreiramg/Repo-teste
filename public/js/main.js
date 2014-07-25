'use strict';
(function() {
    var main = angular.module('main', ['produtorStore'])
            .controller('infoLog', ['$scope', '$log', function($scope, $log) {
                    $scope.$log = $log;
                }]);
    main.directive('navMenu', function() {
        return {
            restrict: 'E',
            templateUrl: '/public/html/navMenu.html'
        };
    });

}());