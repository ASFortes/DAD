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
    socket.on('cooker_online',(order)=>{
        socket.broadcast.emit('cooker_online', order)
        console.log( order)
    })
    
    socket.on('user_blocked',(id)=>{
        socket.broadcast.emit('user_blocked', id)
        console.log( 'user blocked:'+id)
    })











   
    socket.on('deliveryMan_ready',(id)=>{
        socket.broadcast.emit('deliveryMan_ready', id)
        console.log('delivery Id ASSIGN DELIVERYMEN' + id)
    })


    socket.on('order_delivered',(id)=>{
        socket.broadcast.emit('order_delivered', id)
        console.log('Order Id DELIVERED ' + id)
    })

    socket.on('order_cooked',(id)=>{
        socket.broadcast.emit('order_cooked', id)
        console.log('Order Id COOK ' + id)
    })

    socket.on('addToDeliveryMan',(id)=>{
        socket.broadcast.emit('addToDeliveryMan', id)
        console.log('Order Id COOKTOD ')
        console.log( id)
    })



    socket.on('new_order',(id)=>{
        socket.broadcast.emit('new_order', id)
        console.log('Order Id NEW ORDER ' + id+'aqui')
    })

    socket.on('create_user_socket',(id)=>{
        socket.broadcast.emit('create_user_socket', id)
        console.log('Created USER ' + id+'aqui')
    })

    // socket.on('change_Status_To_P',(id)=>{
    //     socket.broadcast.emit('change_Status_To_P', id)
    //     console.log(' MUDANÇA PARA [P]: ' + id+'aqui')
    // })

    socket.on('change_Status_To_D',(id)=>{
        socket.broadcast.emit('change_Status_To_D', id)
        console.log(' MUDANÇA PARA [D]: ' + id+'aqui')
    })
    socket.on('user_cooking',(id)=>{
        socket.broadcast.emit('user_cooking', id)
        console.log(' USER_COZINHAR [user]: ' + id+'aqui')
    })

    socket.on('user_delivering',(id)=>{
        socket.broadcast.emit('user_delivering', id)
        console.log(' USER_ENTREGAR [user]: ' + id+'aqui')
    })

    socket.on('user_offline',(id)=>{
        socket.broadcast.emit('user_offline', id)
        console.log(' USER_LOGOUT [user]: ' + id+'aqui')
    })

    socket.on('user_available',(id)=>{
        socket.broadcast.emit('user_available', id)
        console.log(' USER_LOGGED IN [user]: ' + id+'aqui')
    })

    socket.on('cook_back_avaiable',(id)=>{
        socket.broadcast.emit('cook_back_avaiable', id)
        console.log('cook_back_avai [user]: ' + id+'aqui')
    })

    socket.on('order_canceled',(id)=>{
        socket.broadcast.emit('order_canceled', id)
        console.log('cook_back_avai [user]: ' + id +  'aqui')
    })



})

