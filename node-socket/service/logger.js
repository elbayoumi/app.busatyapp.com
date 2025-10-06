const { createLogger, format, transports } = require('winston');
const { combine, timestamp, printf, errors } = format;
const path = require('path');

// Define custom format for logs
const myFormat = printf(({ level, message, timestamp, stack }) => {
  return `${timestamp} ${level}: ${stack || message}`;
});

// Create a logger instance
const logger = createLogger({
  level: 'info', // You can set it to 'debug' in development
  format: combine(
    timestamp({ format: 'YYYY-MM-DD HH:mm:ss' }),
    errors({ stack: true }), // Log stack trace if available
    myFormat
  ),
  transports: [
    new transports.Console(), // Show logs in terminal
    new transports.File({ filename: path.join(__dirname, '../logs/error.log'), level: 'error' }), // Save errors in file
    new transports.File({ filename: path.join(__dirname, '../logs/combined.log') }) // Save all logs
  ]
});

module.exports = logger;

