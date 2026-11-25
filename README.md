# AEEE Backend API & Admin Portal

High-performance, scalable Laravel backend engine designed for **millions of students**, powering the **AEEE Flutter Mobile App** and providing a real-time Admin Portal.

---

## ⚡ High-Scale Architecture (Millions of Students)

1. **Strategic Database Indexing (B-Tree)**:
   - Unique indexes on `google_id` and `email`.
   - Single-column indexes on `phone`, `status`, `is_phone_verified`, `is_profile_completed`.
   - High-throughput composite indexes:
     - `idx_profile_created`: `(is_profile_completed, created_at)`
     - `idx_audience_targeting`: `(class_id, state_id, preparing_for_id)`
     - `idx_status_verified`: `(status, is_phone_verified, created_at)`
     - `idx_phone_valid_otp`: `(phone, is_verified, expires_at)`
2. **Zero `COUNT(*)` Table Scan Bottlenecks**:
   - Uses `simplePaginate()` and indexed cursor streaming for student listings to avoid table scans over millions of rows.
3. **RAM Micro-Caching**:
   - Dynamic profile options (`classes`, `states`, `preparing_for`) are cached in Redis / Memory for 24 hours.
   - When an Admin updates options, `DynamicFieldCacheService::clearCache()` invalidates the cache immediately. Responses are served in `< 1ms`.
4. **Asynchronous Queued Processing**:
   - Fast2SMS OTP dispatch handled asynchronously.
   - Push Notifications (Firebase FCM) batched in chunks of 500 devices using `chunkById(500)` via `SendFirebasePushNotificationJob`.

---

## 🚀 Quick Setup Guide

### 1. Requirements
- PHP 8.2+
- Composer 2+
- MySQL 8.0+ or PostgreSQL
- Redis (Recommended for production caching and queues)

### 2. Environment Configuration
Copy `.env.example` to `.env`:
```bash
cp .env.example .env
```
Update database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aeee_db
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Fast2SMS Setup
Add your API key from [Fast2SMS Dashboard](https://www.fast2sms.com/dashboard/dev-api):
```env
FAST2SMS_API_KEY=your_fast2sms_api_key
FAST2SMS_ROUTE=otp
FAST2SMS_MOCK_OTP=false  # Set to true for offline local testing with OTP 123456
```

### 4. Firebase Cloud Messaging (FCM) Setup
```env
FIREBASE_SERVER_KEY=your_fcm_server_key
FIREBASE_PROJECT_ID=aeee-mobile-app
```

### 5. Run Migrations & Seeders
```bash
php artisan migrate --seed
```

### 6. Start the Server & Queue Worker
```bash
php artisan serve
php artisan queue:work --tries=3
```

---

## 🛡️ Admin Portal

- **URL**: `http://localhost:8000/admin/login`
- **Default Email**: `admin@aeee.edu`
- **Default Password**: `Admin@123456`

### Features:
1. **Analytics Dashboard**: Real-time KPI counters, Class breakdowns, Target exam breakdowns, Top states.
2. **Students Directory**: High-speed filterable and searchable student table.
3. **Dynamic Fields**: Live CRUD for Classes, States, and Target Exams (`Preparing For`).
4. **Fast2SMS Key Management**: Manage API Keys, Routes (`otp`/`dlt`), Sender IDs, Mock Mode, and live OTP test tool directly from the web UI (`/admin/settings/fast2sms`).
5. **Push Notifications**: Compose and broadcast notifications to all or segmented student groups via Firebase FCM.

---

## 📡 Mobile API Endpoints (`/api/v1`)

| Method | Endpoint | Description | Auth Required |
|---|---|---|---|
| `GET` | `/api/v1/options/profile-fields` | Get dynamic dropdowns (Classes, States, Exams) | No (Cached) |
| `POST` | `/api/v1/auth/google` | Screen 1: Continue with Google Sign-in | No |
| `POST` | `/api/v1/auth/complete-profile` | Screen 2: Save Profile & trigger Fast2SMS OTP | Yes (Sanctum) |
| `POST` | `/api/v1/auth/verify-otp` | Screen 3: Verify OTP code & complete profile | Yes (Sanctum) |
| `POST` | `/api/v1/auth/resend-otp` | Resend Fast2SMS OTP | Yes (Sanctum) |
| `GET` | `/api/v1/auth/me` | Fetch authenticated student profile | Yes (Sanctum) |
| `POST` | `/api/v1/device/fcm-token` | Register Firebase device token | Yes (Sanctum) |
| `POST` | `/api/v1/auth/logout` | Revoke current access token | Yes (Sanctum) |
