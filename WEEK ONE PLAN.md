# Smart Buddy IoT Toilets - SDLC 3-Week Roadmap

This document outlines the complete Software Development Life Cycle (SDLC) for the Enterprise IoT Smart Toilet Management System. The roadmap is designed to take the project from initial requirements to full production and maintenance within an accelerated 3-week sprint cycle.

---

## 📅 WEEK 1: Requirements, Architecture & Foundation
*Focus: Understanding the business needs, designing the system architecture, and building the core database and security foundation.*

### Day 1-2: Requirements Gathering & System Architecture
- **Requirement Analysis:** Define core features for Admin, Operation, and Client (User) panels.
- **IoT Hardware Specs:** Finalize the data payload structure (JSON format) sent by the ESP32/IoT devices (e.g., `machine_id`, `water_level`, `coin_status`).
- **System Architecture:** Map out the flow between Hardware -> MQTT Broker / REST API -> Backend Server (PHP) -> Database (MySQL) -> Web Frontend.

### Day 3-4: Tech Stack Setup & UI/UX Strategy
- **Environment Setup:** Configure local development environments (XAMPP/Laragon), Git version control, and GitHub/GitLab repositories.
- **Frontend Framework:** Integrate and configure the base "SB Admin 2" template with custom Glassmorphism/Dark Mode CSS.
- **Component Library:** Standardize reusable UI components (DataTables, KPI Cards, Modals).

### Day 5-6: Database Design & Security Layer
- **Entity Relationship Diagram (ERD):** Design scalable tables (`machines`, `clients`, `trans` (transactions), `datatest` (IoT live data), `tblusers`).
- **Index Optimization:** Apply primary/foreign keys and compound indexes (e.g., `idx_machine_time`) to prepare for 1,000+ devices.
- **Authentication System:** Implement Role-Based Access Control (RBAC) with secure session handling and password hashing (BCrypt).

### Day 7: IoT Communication Protocol
- **API Endpoints:** Create secure HTTP POST webhooks or an MQTT subscriber script to receive hardware payloads.
- **Data Validation:** Ensure incoming hardware data is sanitized to prevent SQL injection and data corruption.

---

## 📅 WEEK 2: Core Development & Hardware Integration
*Focus: Building the business logic, dashboards, reports, and connecting the software to the actual IoT hardware.*

### Day 8-9: Backend Data Processing
- **Transaction Engine:** Build the logic to calculate revenue, track usage, and monitor success/failed status.
- **Machine Management:** Develop CRUD operations (Create, Read, Update, Delete) for registering new machines and assigning them to clients.

### Day 10-11: Dashboard & Analytics Development
- **KPI Generation:** Write optimized SQL range queries to calculate "Today's Revenue," "Total Uses," and "Active Machines" instantly.
- **Interactive Reports:** Integrate DataTables with Date Range pickers for deep analytics (e.g., `report_transmachineidwiseuser.php`).

### Day 12-13: Real-Time Features (Live Stream)
- **Server-Sent Events (SSE):** Implement `live_tank_stream.php` to push live hardware data to the browser without page reloads.
- **Hardware End-to-End Testing:** Connect physical IoT hardware (or simulators) to the local server to verify that button presses reflect instantly on the UI.

### Day 14: Quality Assurance (QA) & Refactoring
- **Code Audit:** Review code for bottlenecks (e.g., removing `DATE()` wrappers from SQL queries).
- **Security Check:** Prevent session hijacking, ensure the Live Stream only sends data belonging to authorized users.

---

## 📅 WEEK 3: Deployment, Production & Maintenance
*Focus: Moving the software to a live server, testing under heavy load, and establishing long-term support.*

### Day 15-16: Staging Deployment & Load Testing
- **Server Provisioning:** Set up an Ubuntu Linux VPS (AWS EC2 / DigitalOcean Droplet) with Nginx/Apache, PHP 8, and MySQL.
- **Load Testing:** Simulate 1,000+ IoT toilets sending data simultaneously to ensure the server CPU and RAM do not bottleneck.

### Day 17-18: Production Go-Live
- **Domain & Security:** Point the official domain name to the server and install an SSL Certificate (HTTPS via Let's Encrypt).
- **Database Migration:** Export the optimized database schema and import it into the production MySQL server.
- **Launch:** Clear all dummy/test data and officially deploy the IoT hardware to the field.

### Day 19-20: CI/CD Pipeline & Monitoring
- **Automated Deployments:** Set up GitHub Actions so future code changes automatically deploy to the server without manual FTP uploads.
- **Server Monitoring:** Install tools like New Relic, Datadog, or PM2 to monitor server health, memory leaks, and MySQL slow queries.
- **Automated Backups:** Create a Cron Job to automatically backup the MySQL database to AWS S3 or Google Drive every night.

### Day 21: Maintenance & Handover
- **Documentation:** Write technical manuals, API documentation, and Standard Operating Procedures (SOPs).
- **Client Training:** Hand over the Admin and Client panel credentials and provide a walkthrough of the features.
- **Maintenance Strategy:** Establish a ticketing system for bug reports and schedule bi-weekly check-ins for software updates and feature requests.
