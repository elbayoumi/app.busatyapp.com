const handleDriversSocket = (io, socket) => {
    socket.on("drivers", (eventName, message) => {
        io.sockets.emit(eventName, message);
    });
};

const handleAdminsSocket = (io, socket) => {
    socket.on("admins", (eventName, message) => {
        io.sockets.emit(eventName, message);
    });
};

module.exports = {
    handleDriversSocket,
    handleAdminsSocket
};
