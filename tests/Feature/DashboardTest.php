<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Quiz;
use App\Models\Question;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('QuizR');
    }

    public function test_dashboard_displays_empty_state_when_no_quizzes(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('No Quizzes Generated Yet');
    }

    public function test_dashboard_displays_quiz_list_when_quizzes_exist(): void
    {
        // UI & DB Test: Create a dummy quiz directly in the DB to avoid API calls
        $quiz = Quiz::create([
            'title' => 'Test Physics Quiz',
            'pdf_path' => 'dummy/path/physics.pdf',
        ]);

        Question::create([
            'quiz_id' => $quiz->id,
            'question' => 'What is gravity?',
            'choice_a' => 'A force',
            'choice_b' => 'A color',
            'choice_c' => 'A taste',
            'choice_d' => 'A sound',
            'correct_answer' => 'A',
            'explanation' => 'Gravity is a fundamental interaction.'
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Test Physics Quiz');
        $response->assertDontSee('No Quizzes Generated Yet');
    }
}
