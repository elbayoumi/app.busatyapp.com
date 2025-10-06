# 🚀 Busaty Core Platform

**Busaty Core** is the unified backend environment powering the **Busaty Ecosystem**, designed to deliver a highly scalable, modular, and production-ready foundation for web and mobile applications.  
It combines **Laravel (PHP)** for API and business logic with **Node.js (Socket.IO)** for real-time features — all orchestrated through **Docker Compose**.

---

## 🧩 Overview

Busaty Core provides a **containerized infrastructure** that bundles all essential services:
- Laravel (PHP 8.2) for API, logic, and queues
- Node.js (Socket.IO) for real-time communication
- MySQL 8 for relational database management
- Redis 7 for caching, queues, and event broadcasting
- Nginx for serving Laravel efficiently
- Caddy for SSL termination and reverse proxying
- phpMyAdmin for quick DB inspection
- Queue & Scheduler containers for background jobs

It’s designed to work seamlessly in both **local development** and **production** environments.

---

## 🏗 Architecture Diagram

         ┌──────────────────────┐
         │   Client (Web/App)   │
         └──────────┬───────────┘
                    │ HTTPS
                    ▼
           ┌────────────────┐
           │     Caddy      │
           │ (SSL Proxy)    │
           └──────┬─────────┘
 ┌────────────────┴────────────────┐
 │                                 │
 ▼                                 ▼
