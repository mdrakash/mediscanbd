# MediScan BD

MediScan BD is a bilingual (Bangla/English) medical document analyzer. It allows users to upload doctor prescriptions and test reports, analyzes them using OpenAI GPT-4o Vision API, and provides structured results.

## Tech Stack

- **Backend**: Laravel 13, PHP 8.3+, MySQL 8
- **Frontend**: Vue.js 3, Composition API, `<script setup>` syntax only
- **Authentication**: Google OAuth + Laravel Sanctum (Bearer token)
- **AI**: OpenAI GPT-4o Vision API (via openai-php/laravel package)
- **Styling**: Tailwind CSS v3
- **State Management**: Pinia
- **Routing**: Vue Router 4
- **Internationalization**: Vue I18n

## Prerequisites

- PHP 8.3 or higher
- Composer
- Node.js (v20+) and npm
- MySQL 8
- Git

## Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/mdrakash/mediscanbd.git
   cd mediscanbd
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies:**
   ```bash
   npm install
   ```

4. **Environment Configuration:**
   - Copy `.env.example` to `.env`:
     ```bash
     cp .env.example .env
     ```
   - Configure your environment variables in `.env`:
     - Database settings (DB_HOST, DB_DATABASE, etc.)
     - Google OAuth credentials (GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET)
     - OpenAI API key (OPENAI_API_KEY)
     - App URL and other Laravel settings

5. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

6. **Run Database Migrations:**
   ```bash
   php artisan migrate
   ```

7. **Build Frontend Assets:**
   ```bash
   npm run build
   ```
   Or for development:
   ```bash
   npm run dev
   ```

## Running the Application

1. **Start the Laravel server:**
   ```bash
   php artisan serve
   ```
   The application will be available at `http://localhost:8000`

2. **For development, you may also need to run the frontend dev server:**
   ```bash
   npm run dev
   ```

## API Endpoints

- `POST /api/register` - User registration
- `POST /api/login` - User login
- `POST /api/analyze` - Upload and analyze documents
- `GET /api/history` - Paginated analysis history
- `GET /api/analyses/{id}` - Single analysis details
- `DELETE /api/analyses/{id}` - Delete analysis
- `GET /api/me` - Current user info
- `POST /api/logout` - Logout
- `GET /auth/google` - Google OAuth redirect
- `GET /auth/google/callback` - Google OAuth callback

## Database Tables

- `users` - User accounts
- `uploads` - File uploads
- `analyses` - Analysis results

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests and linting
5. Submit a pull request

## License

This project is open-sourced software licensed under the MIT license.