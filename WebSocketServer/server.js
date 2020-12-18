var httpServer = require('http').createServer()


//var LoggedUsers = require('./loggedusers.js');


let SessionManager = require("./SessionManager.js")
let sessions = new SessionManager()


// server-side
let io = require('socket.io')(httpServer)

httpServer.listen(8080, function () {
 console.log('listening on *:8080')
})

//let loggedUsers = new LoggedUsers();

io.on('connection', function (socket) {
    console.log('Client has connected. Socket ID = ' + socket.id)
    socket.on('disconnect', (reason) => {
        sessions.removeSocketIDSession(socket.id)
        console.log('Disconnect  Socket ID= ' + socket.id)
        console.log('  -> Total Sessions= ' + sessions.users.size)
    })
  
    socket.on('user_logged', (user) => {
        if (user) {
            sessions.addUserSession(user, socket.id)
            socket.join(user.type)
            console.log('User Logged: UserID= ' + user.id + ' Socket ID= ' + socket.id)
            console.log('  -> Total Sessions= ' + sessions.users.size)
        }
    })
    socket.on('user_logged_out', (user) => {
        if (user) {
            socket.leave(user.type)
            sessions.removeUserSession(user.id)
            console.log('User Logged OUT: UserID= ' + user.id + ' Socket ID= ' + socket.id)
            console.log('  -> Total Sessions= ' + sessions.users.size)
        }
    })

    socket.on('cooker_ready',(id)=>{
        socket.broadcast.emit('cooker_ready', id)
        console.log('Cooker Id ' + id)
    })

})

