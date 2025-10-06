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

scss
Copy code
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
┌──────────────┐ ┌──────────────┐
│ Nginx │ │ Socket.IO │
│ (Laravel) │ │ (Node.js) │
└──────┬───────┘ └──────┬───────┘
│ │
▼ ▼
┌──────────────┐ ┌──────────────┐
│ PHP-FPM App │ │ Redis (PubSub│
│ (Laravel) │ │ Cache) │
└──────┬───────┘ └──────────────┘
│
▼
┌──────────────┐
│ MySQL 8 DB │
└──────────────┘

yaml
Copy code

---

## 📂 Folder Structure

busaty-core/
├── laravel/ # Laravel backend (API, logic, queues)
│ ├── Dockerfile
│ ├── app/
│ ├── routes/
│ ├── config/
│ └── ...
│
├── node-socket/ # Node.js Socket.IO real-time server
│ ├── Dockerfile
│ ├── package.json
│ └── server.js
│
├── docker/
│ ├── caddy/ # Reverse proxy configs + SSL
│ │ └── Caddyfile
│ ├── nginx/ # Nginx config for Laravel
│ │ └── default.conf
│ └── php/ # Optional PHP configs
│
├── docker-compose.yml # Unified Docker environment
├── README.md # Project documentation
└── .gitignore

ruby
Copy code

---

## ⚙️ Core Technologies

| Component | Technology | Purpose |
|:----------|:------------|:---------|
| Backend Framework | **Laravel 11** | Main API, controllers, services, jobs |
| Realtime Server | **Node.js 20 + Socket.IO 4.7** | Handles live updates, push notifications |
| Web Server | **Nginx 1.25 Alpine** | Routes HTTP to Laravel |
| SSL Proxy | **Caddy 2** | Manages HTTPS & certificates automatically |
| Database | **MySQL 8.0** | Persistent relational data storage |
| Cache/Queue | **Redis 7 Alpine** | Caching, broadcasting & queues |
| Container Management | **Docker Compose 3.9** | Unified orchestration |
| Admin Panel | **phpMyAdmin** | Database GUI for quick inspection |

---

## 🧠 Environment Summary

### Subdomains

| Domain | Service | Description |
|:--------|:---------|:-------------|
| `app.elbayoumi.net` | Laravel | Main backend API |
| `ws.app.elbayoumi.net` | Node.js Socket.IO | Realtime WebSocket server |

Both are handled by **Caddy**, which routes requests to the correct internal container.

---

## 🚀 Setup & Installation

### 1️⃣ Clone or prepare the repository
```bash
git clone https://github.com/your-username/busaty-core.git
cd busaty-core
2️⃣ Configure environment variables
Copy .env.example inside laravel/ and set your values:

bash
Copy code
cp laravel/.env.example laravel/.env
Make sure to set:

env
Copy code
APP_URL=https://app.elbayoumi.net
DB_HOST=db
REDIS_HOST=redis
SOCKET_SERVER_URL=http://socket:3001
3️⃣ Build and start all services
bash
Copy code
docker compose up -d --build
4️⃣ Generate Laravel app key
bash
Copy code
docker exec -it bussaty_app php artisan key:generate
5️⃣ Run migrations & seeders
bash
Copy code
docker exec -it bussaty_app php artisan migrate --seed
6️⃣ Access phpMyAdmin (optional)
👉 http://localhost:8080

Host: db | User: root | Password: root

🌐 Access Points
Service	URL	Description
Laravel API	https://app.elbayoumi.net	Main backend app
Socket.IO	https://ws.app.elbayoumi.net	WebSocket endpoint
phpMyAdmin	http://localhost:8080	DB GUI tool

🔧 Common Commands
Laravel (inside container)
bash
Copy code
# Run artisan commands
docker exec -it bussaty_app php artisan migrate
docker exec -it bussaty_app php artisan tinker
docker exec -it bussaty_app php artisan queue:work
Node.js (inside container)
bash
Copy code
# View socket logs
docker logs -f bussaty_socket
Global
bash
Copy code
# Restart specific service
docker compose restart socket

# View all container logs
docker compose logs -f

# Rebuild everything
docker compose up -d --build
💬 Communication Flow
Client (Web/App) → connects to https://ws.app.elbayoumi.net via Socket.IO.

Laravel pushes messages/events to Redis.

Socket.IO server subscribes to Redis channels and emits events in real-time.

Caddy handles SSL and routes traffic internally.

🧱 Docker Network Layout
Service	Hostname	Purpose
app	Laravel PHP-FPM	Executes backend code
web	Nginx	Forwards to app:9000
socket	Node.js	WebSocket communication
db	MySQL	Relational data
redis	Redis	Cache, queues
caddy	HTTPS proxy	Public entry
queue	Worker	Background jobs
scheduler	Scheduler	Runs cron jobs
phpmyadmin	phpMyAdmin	DB interface

🔒 Security Recommendations
Disable phpMyAdmin in production or protect with IP whitelisting.

Use HTTPS enforced via Caddy.

Never expose MySQL or Redis ports publicly.

Keep .env files private and excluded from Git.

Limit container users with user: 1000:1000 (already configured).

Use Fail2ban or a Cloud firewall for SSH protection.

🧰 Development Tips
Task	Command
Rebuild only Laravel	docker compose build app
Rebuild only Socket	docker compose build socket
Enter Laravel container	docker exec -it bussaty_app bash
Enter Node container	docker exec -it bussaty_socket sh
Run PHP CS Fixer (optional)	docker exec -it bussaty_app vendor/bin/pint

⚡️ Scaling & Deployment
Scaling Socket.IO:

bash
Copy code
docker compose up -d --scale socket=3
Then use Redis as the adapter for horizontal scaling.

Zero Downtime:
Use Caddy load balancing + multiple containers for rolling updates.

Database Backups:
Mount /var/lib/mysql volume and schedule backup jobs.

🧩 Future Integrations
Feature	Description
Real-time notifications	Laravel ↔ Socket.IO integration
Monitoring	Prometheus + Grafana dashboards
Email service	Laravel mail queue via Mailpit
API Gateway	Kong / Traefik for multi-service routing
CI/CD	GitHub Actions or Jenkins pipeline

🧑‍💻 Author
Mohamed Ashraf Elbayoumi
CTO & Full-Stack Developer — Busaty Platform
📧 contact@elbayoumi.net
🌐 https://elbayoumi.net
💼 LinkedIn Profile

🪪 License
This repository is part of the Busaty Platform — a proprietary project.
All rights reserved © 2025 Mohamed Ashraf Elbayoumi.

Unauthorized use, copying, or redistribution is strictly prohibited.

yaml
Copy code

---

هل تحب أعمل كمان نسخة **عربية كاملة بنفس المستوى** تكون باسم `README.ar.md` (توضح الهدف التجاري والتقني بالعربي للمستثمر أو العميل) بحيث تبقى الريبو فيها اللغتين؟




You said:
