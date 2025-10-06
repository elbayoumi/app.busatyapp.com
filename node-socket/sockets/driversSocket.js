const TrackingBufferService = require('../service/TrackingBufferService');

/**
 * WebSocket handler for "drivers" events
 * Receives tracking messages from drivers and broadcasts them using the provided event name.
 */
const handleDriversSocket = (io, socket) => {
    socket.on("drivers", (eventName, message) => {
        // Check if the message is a valid tracking object
        if (typeof message === 'object' && message.trip_id && message.bus_id) {
            // Add created_at timestamp if not already set
            message.created_at = message.created_at || new Date().toISOString();

            // Add the message to the tracking buffer for batch saving
            TrackingBufferService.add(message);
        }

        // Broadcast the message to all connected clients using the given event name
        io.sockets.emit(eventName, message);
    });
};

module.exports = handleDriversSocket;
