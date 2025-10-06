# syntax=docker/dockerfile:1
##############################################
# Node.js 20 Alpine — Socket.IO Service
##############################################
FROM node:20-alpine AS base

# Set working directory
WORKDIR /usr/src/app

# Copy package files first (for layer caching)
COPY package*.json ./

# Install dependencies (production only for smaller image)
RUN npm ci --only=production

# Copy source code into image
COPY . .

# Environment defaults
ENV NODE_ENV=production
ENV PORT=3001

# Expose internal port (Caddy will proxy to this)
EXPOSE 3001

# Simple healthcheck endpoint (optional check by Docker)
HEALTHCHECK --interval=30s --timeout=5s --retries=3 \
  CMD wget -qO- http://127.0.0.1:3001/health || exit 1

# Start the server
CMD ["node", "server.js"]
