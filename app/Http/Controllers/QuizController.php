<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Services\QuizService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuizController extends Controller
{
    /**
     * The quiz generation service.
     */
    public function __construct(private readonly QuizService $quizService)
    {
    }

    /**
     * Display the dashboard with all quizzes.
     */
    public function index()
    {
        $quizzes = Quiz::latest()->get();

        return view('dashboard', compact('quizzes'));
    }

    /**
     * Handle the uploaded PDF and generate a quiz via Groq AI.
     */
    public function generate(Request $request)
    {
        // Step 1 — Validate the uploaded PDF
        $request->validate([
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:102400'],
        ]);

        // Step 2 — Store the PDF to storage/app/pdfs
        $path = $request->file('pdf')->store('pdfs', 'local');

        // Step 3 — Extract text using smalot/pdfparser
        try {
            $parser     = new \Smalot\PdfParser\Parser();
            $parsedPdf  = $parser->parseFile(Storage::disk('local')->path($path));
            $text       = $parsedPdf->getText();
        } catch (\Throwable $e) {
            Storage::disk('local')->delete($path);

            return back()->withErrors([
                'pdf' => 'Failed to read the PDF: ' . $e->getMessage(),
            ]);
        }

        if (empty(trim($text))) {
            Storage::disk('local')->delete($path);

            return back()->withErrors([
                'pdf' => 'Could not extract text from this PDF. It may be empty or image-based.',
            ]);
        }

        // Step 4 — Generate questions via QuizService
        try {
            $questionsData = $this->quizService->generateQuiz($text);
        } catch (\Throwable $e) {
            Storage::disk('local')->delete($path);

            return back()->withErrors([
                'pdf' => 'AI generation failed: ' . $e->getMessage(),
            ]);
        }

        // Step 5 — Save the quiz and questions to the database
        $originalName = pathinfo($request->file('pdf')->getClientOriginalName(), PATHINFO_FILENAME);

        $quiz = Quiz::create([
            'title'    => $originalName,
            'pdf_path' => $path,
        ]);

        foreach ($questionsData as $item) {
            $quiz->questions()->create([
                'question'       => $item['question'],
                'choice_a'       => $item['choices']['A'],
                'choice_b'       => $item['choices']['B'],
                'choice_c'       => $item['choices']['C'],
                'choice_d'       => $item['choices']['D'],
                'correct_answer' => $item['correct_answer'],
                'explanation'    => $item['explanation'],
            ]);
        }

        // Step 6 — Redirect to the new quiz
        return redirect()->route('quiz.show', $quiz)
            ->with('success', 'Quiz generated successfully!');
    }

    /**
     * Display a single quiz with all its questions.
     */
    public function show(Quiz $quiz)
    {
        $questions = $quiz->questions;

        return view('quiz.show', compact('quiz', 'questions'));
    }

    /**
     * Delete a quiz (cascades to questions via DB constraint).
     */
    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Quiz deleted.');
    }
}
