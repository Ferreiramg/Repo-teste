var io = require('socket.io').listen(3000);
io.on('connection', function(socket) {
    console.log('User connected!');
    socket.on('message', function(message) {
        console.log( message);
        io.sockets.emit('serverResponse', {'data': message});
    });
});



