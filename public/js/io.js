angular.module('io.service', []).
        factory('io', function($http) {
            var socket,
                    apiServer, that = {};

            return {
                init: function(conf) {
                    apiServer = conf.apiServer;
                    socket = io.connect(conf.ioServer);
                    
                    socket.on('disconnect', function() {
                        console.log('disconnect');
                    });

                },
                disconnect: function() {
                    return socket.disconnect();
                },
                emit: function(arguments) {
                    $http.get(apiServer + '/console/' + arguments);
                },
                socket: function() {
                    return socket;
                }
            };
        });


