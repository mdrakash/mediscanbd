# MediScan BD — Agent Instructions

## Project Overview
"MediScan BD" — একটি bilingual (Bangla/English) medical document analyzer।
Doctor prescription ও test report upload করা যাবে, OpenAI GPT-4o দিয়ে analyze হবে।

## Tech Stack
- Backend: Laravel 13, PHP 8.3+, MySQL 8
- Frontend: Vue.js 3, Composition API, <script setup> syntax ONLY
- Auth: Google OAuth (Socialite) + Laravel Sanctum (Bearer token)
- AI: OpenAI GPT-4o Vision API (openai-php/laravel package)
- CSS: Tailwind CSS v3
- State: Pinia | Router: Vue Router 4 | i18n: Vue I18n

## Strict Coding Rules
- Vue: সবসময় <script setup> syntax, Options API NEVER
- Vue: i18n key ব্যবহার করো, hardcode Bangla/English text NEVER
- Vue: সব API call composables/useApi.js দিয়ে করো
- Laravel: Form Request দিয়ে validation করো
- Laravel: Service class দিয়ে business logic আলাদা রাখো
- সব জায়গায় try/catch error handling করো
- Tailwind CSS only, আলাদা CSS file কম রাখো

## Important File Locations
resources/js/
├── pages/          → Vue page components
├── components/     → Reusable components
├── stores/         → Pinia stores (auth.js, analysis.js, language.js)
├── composables/    → useApi.js, useToast.js, useRelativeTime.js
├── router/         → index.js
└── i18n/           → bn.json, en.json

app/
├── Http/Controllers/Auth/   → GoogleController.php, AuthController.php
├── Http/Controllers/Api/    → AnalysisController.php
├── Http/Requests/           → AnalyzeRequest.php
├── Services/                → OpenAIService.php, FileStorageService.php
└── Models/                  → User.php, Upload.php, Analysis.php

## Database Tables
users: id, name, email, google_id, avatar, timestamps
uploads: id, user_id, type(prescription|test_report), original_filename,
         storage_path, mime_type, file_size, language(bn|en), timestamps
analyses: id, upload_id, user_id, medicines(JSON), parameters(JSON),
          test_summary, advice, next_steps, status(processing|completed|failed),
          language, report_type, raw_response, timestamps

## API Endpoints
POST   /api/analyze          → upload & analyze
GET    /api/history          → paginated list
GET    /api/analyses/{id}    → single analysis
DELETE /api/analyses/{id}    → delete
GET    /api/me               → current user
POST   /api/logout           → logout
GET    /auth/google          → OAuth redirect
GET    /auth/google/callback → OAuth callback

## Session Progress Tracker
✅ SESSION 01 — Environment Setup
✅ SESSION 02 — Database & Models
✅ SESSION 03 — Google OAuth
✅ SESSION 04 — OpenAI Service
🔄 SESSION 05 — File Upload API  ← CURRENT
⬜ SESSION 06 — Routes & Polish
⬜ SESSION 07 — Vue Setup
⬜ SESSION 08 — Auth Pages
⬜ SESSION 09 — Upload Page
⬜ SESSION 10 — Result Page
⬜ SESSION 11 — History Page
⬜ SESSION 12 — UI Polish
⬜ SESSION 13 — Deploy Config