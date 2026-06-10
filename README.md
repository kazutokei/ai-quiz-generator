# AI Quiz Generator

A Laravel-based application that allows users to upload educational PDFs and instantly generates a 15-question multiple-choice quiz using the Groq AI API.

## Requirements
- PHP 8.2+
- Composer
- MySQL Database
- Node.js (Optional, UI uses Tailwind CDN for simplicity)
- Groq API Key

## Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd ai-quiz-generator
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Edit `.env` and configure your `DB_*` variables for MySQL, and add your `GROQ_API_KEY`.*

4. **Run Migrations**
   ```bash
   php artisan migrate
   ```

5. **Start the Application**
   ```bash
   php artisan serve
   ```
   *The application will be available at http://localhost:8000*
