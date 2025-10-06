const axios = require('axios');
const logger = require('./logger'); // Import the logger

const TrackingService = {
    async saveTrackingData(payload, retries = 3) {
        try {
            logger.info('🚀 Sending batch to API with payload:', { payload });

            const response = await axios.post('https://stage.busatyapp.com/api/track/batch', payload, {
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            logger.info(`✅ API responded with status: ${response.status}`, { data: response.data });

            return response.data;
        } catch (error) {
            logger.error(`❌ Error sending tracking data. Retries left: ${retries - 1}`, { error: error.message });

            if (error.response) {
                logger.error('❌ API responded with error status:', { status: error.response.status, data: error.response.data });
            } else {
                logger.error('❌ Request error:', { error: error.message });
            }

            if (retries > 1) {
                await new Promise(resolve => setTimeout(resolve, 2000));
                return await this.saveTrackingData(payload, retries - 1);
            }

            throw error;
        }
    }
};

module.exports = TrackingService;
