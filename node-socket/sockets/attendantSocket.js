const Joi = require('joi');
const TrackingBufferService = require('../service/TrackingBufferService');

/**
 * WebSocket handler for "attendants" events
 * This handles messages sent on the "attendants" channel.
 */
const handleAttendantsSocket = (io, socket) => {
  socket.on('attendants', (message) => {
    // Define validation schema using Joi
    const schema = Joi.object().keys({
      bus_id: Joi.string().required(),
      // You can add more fields here for stricter validation
    });

    // Validate the incoming message
    const { error, value } = schema.validate(message);

    if (error) {
      console.error(`❌ Invalid message: ${error.message}`);
      return; // Exit early if validation fails
    }

    // Add the valid message to the tracking buffer
    TrackingBufferService.add(message);

    // Use bus_id as the event name to emit the message
    const eventName = value.bus_id;

    // Broadcast the message to all connected clients under the bus_id event
    io.sockets.emit(eventName, message);
  });
};

module.exports = handleAttendantsSocket;
