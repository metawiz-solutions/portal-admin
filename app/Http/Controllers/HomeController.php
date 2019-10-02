<?php

namespace App\Http\Controllers;

use App\Note;
use App\Question;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $questions = Question::paginate(10);
        return view('home', [
            'title' => 'E-Note|Admin - Home',
            'questions' => $questions
        ]);
    }

    public function showScrape() {
        return view('scrape', [
            'title' => 'E-Note|Admin - Scrape'
        ]);
    }

    public function processScrape(Request $request) {
        if ($request->has('url')) {
            $note = new Note([
                'url' => $request->get('url'),
                'status' => 0,
                'added_by' => auth()->user()->email
            ]);
            $client = new Client();
            try {
                $guzzleRes = $client->post('http://ec2-13-58-156-104.us-east-2.compute.amazonaws.com:8090/scrap', [
                    RequestOptions::JSON => ['text' => $request->get('url')]
                ]);
                if ($guzzleRes->getStatusCode() === 200) {
                    $scraped = json_decode($guzzleRes->getBody()->getContents())->result;
                    $note->scraped_text = $scraped;
                    $note->status = 1;
                    $note->save();
                    toastr()->success('URL has been scraped.');
                    return back();
                } else {
                    $note->status = 2;
                    $note->save();
                    toastr()->error('URL scrape failed!.');
                    return back();
                }
            } catch (\Exception $exception) {
                return response()->json($exception->getMessage());
            }

        } else {
            toastr()->error('Invalid Request: URL required.');
            return back();
        }
    }

    public function showSummarize() {
        $notes = Note::whereIn('status', [1, 2])->paginate(10);
        return view('summarize', [
            'title' => 'E-Note|Admin - Summarize',
            'notes' => $notes
        ]);
    }

    public function showSummarizeNote($id) {
        $note = Note::find($id);
        if ($note instanceof Note) {
            return view('showScrape', [
                'note' => $note
            ]);
        }
        toastr()->error('Invalid Note ID');
        return back();
    }

    // process
    public function summarizeNote($id) {
        $client = new Client();
        $note = Note::findOrFail($id);
        try {
            $guzzleRes = $client->post('http://ec2-13-58-156-104.us-east-2.compute.amazonaws.com:8100/summarize', [
                RequestOptions::JSON => ['text' => $note->scraped_text]
            ]);
            if ($guzzleRes->getStatusCode() === 200) {
                $summarized = json_decode($guzzleRes->getBody()->getContents())->result;
                $note->summarized_text = $summarized;
                $note->status = 3;
                $note->save();
                toastr()->success('Text has been summarized.');
                return back();
            } else {
                $note->status = 4;
                $note->save();
                toastr()->error('Summarization failed!.');
                return back();
            }
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    public function showNotes() {
        $notes = Note::whereIn('status', [3, 4, 5])->paginate(10);
        return view('notes', [
            'title' => 'E-Note|Admin - Notes',
            'notes' => $notes
        ]);

    }

    public function showNote($id) {
        $note = Note::find($id);
        if ($note instanceof Note) {
            return view('showNote', [
                'note' => $note
            ]);
        }
        toastr()->error('Invalid Note ID');
        return back();
    }

    public function updateNoteAttributes(Request $request, $id) {
        $note = Note::findOrFail($id);
        switch ($request->get('fieldName')) {
            case 'title':
                $note->title = $request->get('value');
                $note->save();
                return response()->json(['code' => 200], 200);
            case 'subject':
                $note->subject = $request->get('value');
                $note->save();
                return response()->json(['code' => 200], 200);
            case 'grade':
                $note->grade = $request->get('value');
                $note->save();
                return response()->json(['code' => 200], 200);
            case 'summarized_text':
                $note->summarized_text = $request->get('value');
                $note->save();
                return response()->json(['code' => 200], 200);
            default:
                return response()->json(['code' => 422], 422);
        }
    }

    public function deleteNote($id) {
        toastr()->success('Note Deleted!');
        $note = Note::findOrFail($id);
        try {
            $note->delete();
            toastr()->success('Note Deleted!');
            return response()->json(['code' => 200], 200);
        } catch (\Exception $exception) {
            return response()->json(['code' => 500], 500);
        }
    }

    public function publish($id) {
        $note = Note::findOrFail($id);
        $note->status = 5;
        $note->save();
        toastr()->success('Note is now Published!');
        return response()->json(['status' => 'ok'], 200);
    }

    public function showQuestion($id) {
        $question = Question::where('question_id', $id)->first();
        if ($question instanceof Question) {
            $user = DB::table('students')->where('student_id', $question->question_from)->get();
            return view('question', [
                'question' => $question,
                'author' => $user[0]->student_fullname
            ]);
        }
        toastr()->error('Invalid ID');
        return back();
    }

    public function approve(Request $request) {
        if ($request->has('id')) {
            $question = Question::where('question_id', $request->get('id'))->first();
            if ($question instanceof Question) {
                DB::table('questions')->where('question_id', $request->get('id'))->update([
                    'question_status' => 1
                ]);
                return response()->json(['ok'], 200);
            }
            return response()->json(['fail'], 422);
        }
        return response()->json(['fail'], 422);
    }

    public function getTypeSuggestion(Request $request) {
        $arr = [
            'science' => ['tech', 'photosyntheis', 'cells', 'biology', 'mammalia', 'carniovore', 'chemistry', 'relativity', 'planck'],
            'math' => ['trignometry', 'cartesian', 'algebra', 'planer', 'mathematics', 'sin', 'cosine', 'tangent']
        ];
        $count = [
            'math' => 0,
            'science' => 0
        ];
        if ($request->has('id')) {
            $question = Question::where('question_id', $request->get('id'))->first();
            if ($question instanceof Question) {
                foreach ($arr as $row) {
                    foreach ($row as $col) {
                        if (strchr($question->question_content, $col)) {
                            if($row === 'math'){
                                $count['math']++;
                            }
                            else{
                                $count['science']++;
                            }

                        }
                    }
                }
                if ($count['math'] > $count['science']) {
                    return response()->json([
                        'type' => 'Math'
                    ]);
                }
                return response()->json([
                    'type' => 'Science'
                ]);
            }
        }
        return response()->json(['fail'], 422);
    }

    public function deleteQuestion(Request $request) {
        if ($request->has('id')) {
            $question = Question::where('question_id', $request->get('id'))->first();
            if ($question instanceof Question) {
                DB::table('questions')->where('question_id', $request->get('id'))->delete();
                return response()->json(['ok'], 200);
            }
            return response()->json(['fail'], 422);
        }
        return response()->json(['fail'], 422);
    }

    public function saveType(Request $request) {
        if ($request->has('id') && $request->has('value')) {
            $question = Question::where('question_id', $request->get('id'))->first();
            if ($question instanceof Question) {
                DB::table('questions')->where('question_id', $request->get('id'))->update([
                    'type' => $request->get('value')
                ]);
                return response()->json(['ok'], 200);
            }
            return response()->json(['fail'], 422);
        }
        return response()->json(['fail'], 422);
    }


}
