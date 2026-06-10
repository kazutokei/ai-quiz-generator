<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Quiz;
use App\Models\Question;

class QuizTest extends TestCase
{
    use RefreshDatabase;

    public function test_quiz_detail_page_loads_and_displays_questions(): void
    {
        // DB testing: Create dummy data directly
        $quiz = Quiz::create([
            'title' => 'Biology 101',
            'pdf_path' => 'dummy/path/biology.pdf',
        ]);

        Question::create([
            'quiz_id' => $quiz->id,
            'question' => 'What is the powerhouse of the cell?',
            'choice_a' => 'Nucleus',
            'choice_b' => 'Mitochondria',
            'choice_c' => 'Ribosome',
            'choice_d' => 'Membrane',
            'correct_answer' => 'B',
            'explanation' => 'Mitochondria generate most of the cell supply of ATP.'
        ]);

        // UI testing: Assert page loads and shows the question without needing the API
        $response = $this->get(route('quiz.show', $quiz));

        $response->assertStatus(200);
        $response->assertSee('Biology 101');
        $response->assertSee('What is the powerhouse of the cell?');
        $response->assertSee('Mitochondria');
    }

    public function test_quiz_can_be_deleted(): void
    {
        // DB testing: Create a test quiz
        $quiz = Quiz::create([
            'title' => 'To Be Deleted',
            'pdf_path' => 'dummy/path/delete.pdf',
        ]);

        $this->assertDatabaseHas('quizzes', ['title' => 'To Be Deleted']);

        // Route testing: Call the destroy route
        $response = $this->delete(route('quiz.destroy', $quiz));

        // DB Assertions: Ensure it's deleted
        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseMissing('quizzes', ['title' => 'To Be Deleted']);
    }
}
