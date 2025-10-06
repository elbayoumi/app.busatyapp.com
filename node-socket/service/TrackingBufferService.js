const TrackingService = require('./TrackingService');
const LocalBackupService = require('./LocalBackupService');

let trackingBuffer = []; 
const BATCH_SIZE = 50;           // Number of messages before sending batch
const BATCH_INTERVAL = 10000;    // Max interval between sends (10 seconds)
let lastSentAt = Date.now();     // Last time we sent a batch

const TrackingBufferService = {
    // Add a new tracking message
    add(message) {
        if (typeof message === 'string') {
            try {
                message = JSON.parse(message);
            } catch (e) {
                console.warn('⚠️ Invalid JSON string. Message ignored.');
                return;
            }
        }
            if (!this.isValidMessage(message)) {
            console.warn('⚠️ Message ignored: Missing or invalid fields.');
            return;
        }

        trackingBuffer.push({
            trip_id: message.trip_id,
            bus_id: message.bus_id,
            latitude: message.latitude,
            longitude: message.longitude,
            type: message.type,
            created_at: new Date().toISOString() // Use message timestamp or fallback to now
        });

        const now = Date.now();

        if (trackingBuffer.length >= BATCH_SIZE || (now - lastSentAt) >= BATCH_INTERVAL) {
            this.flush();
        }
    },

    // Send all buffered data
    async flush() {
        if (trackingBuffer.length === 0) return;

        const payload = {
            data: trackingBuffer,
        };

        try {
            await TrackingService.saveTrackingData(payload);
            trackingBuffer = [];
            lastSentAt = Date.now();
            console.log(`✅ Successfully sent batch of ${payload.data.length} records.`);
        } catch (error) {
            console.error('❌ Sending failed. Saving locally.');
            LocalBackupService.save(payload);
            trackingBuffer = [];
            lastSentAt = Date.now();
        }
    },

    // Validate that a message contains all required fields
    isValidMessage(message) {
        return (
            message &&
            typeof message.trip_id === 'number' &&
            typeof message.bus_id === 'number' &&
            typeof message.latitude === 'string' &&
            typeof message.longitude === 'string' &&
            typeof message.type === 'string'
        );
    }
};

module.exports = TrackingBufferService;
