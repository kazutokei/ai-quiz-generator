# QuizR: AI Quiz Generator

*Fueling curiosity, instantly.*

QuizR is a sleek, modern web application built with **Laravel 11** that allows users to seamlessly upload PDF study modules and instantly generate a 15-question interactive multiple-choice quiz using the blazing-fast **Groq AI (Llama 3)**.

Designed with a strict adherence to a "No Frontend Framework" rubric, QuizR delivers a premium, highly interactive user experience using **100% Vanilla JavaScript** and Tailwind CSS.

---

## Features

- **AI PDF Parsing:** Upload any PDF document and let AI analyze the contents.
- **Instant Quiz Generation:** Uses Groq's API to generate a structured, 15-item multiple-choice quiz in seconds.
- **Interactive Dual-Mode Quizzes:**
  - **Reviewer Mode:** (Default) Instantly displays all questions, correct answers, and explanations.
  - **Instant Feedback Mode:** Answer questions interactively with immediate grading and feedback.
  - **Exam Mode:** Answer all questions, submit, and receive a calculated final score with a beautiful modal popup.
- **Automated Testing:** Includes a robust test suite to verify UI and Database logic.
- **Premium UI/UX:** Built with Tailwind CSS, featuring glassmorphism, fluid micro-animations, and full dark/light mode support.

---

## Tech Stack

- **Backend:** Laravel 11 (PHP 8.2+)
- **Frontend HTML/Logic:** Laravel Blade Templates & 100% Vanilla JavaScript
- **Styling:** Tailwind CSS
- **Database:** MySQL
- **AI Integration:** Groq API (`llama3-70b-8192`)

---

## Local Setup Instructions

Follow these steps to get QuizR running on your local machine.

### Prerequisites
- PHP 8.2 or higher
- Composer
- A free [Groq API Key](https://console.groq.com/keys)

### 1. Clone the Repository
```bash
git clone https://github.com/kazutokei/ai-quiz-generator.git
cd ai-quiz-generator
```

### 2. Install Dependencies
```bash
composer install
npm install
npm run build
```

### 3. Environment Configuration
Copy the example environment file and generate your application key:
```bash
cp .env.example .env
php artisan key:generate
```

Open the `.env` file and configure your database and add your Groq API key:
```env
DB_CONNECTION=mysql

# AI Configuration
GROQ_API_KEY=your_groq_api_key_here
```

### 4. Database Setup
Run the database migrations to create the necessary tables for Quizzes and Questions:
```bash
php artisan migrate
```

### 5. Start the Application
Start the local development server:
```bash
php artisan serve
```

Visit `http://127.0.0.1:8000` in your browser!

---

## Running Tests

QuizR includes automated Feature tests. Ensure your database is running before executing tests.

To run the test suite:
```bash
php artisan test
```

---

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
