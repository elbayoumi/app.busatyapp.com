const TrackingBufferService = require('../service/TrackingBufferService');

// Handler for admins socket connection
const handleAdminsSocket = (io, socket) => {
    socket.on("admins", (eventName, message) => {
        // Broadcast the message to all connected clients
        io.sockets.emit(eventName, message);

        // Add the incoming message to the tracking buffer for batch saving
        
        TrackingBufferService.add(message);
    });
};

module.exports = handleAdminsSocket;
