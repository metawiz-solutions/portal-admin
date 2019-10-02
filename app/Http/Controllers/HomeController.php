<?php

namespace App\Http\Controllers;

use App\Note;
use foo\bar;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Mockery\Matcher\Not;

class HomeController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        return view('home', [
            'title' => 'E-Note|Admin - Home'
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
//            $note->save();
            $client = new Client();
            try {
                $guzzleRes = $client->request('GET', 'http://ec2-13-58-156-104.us-east-2.compute.amazonaws.com:8090/scrap', [
                    'url' => $request->get('url')
                ]);
                if ($guzzleRes->getStatusCode() === 200) {
                    return response()->json([
                        'body' => $guzzleRes->getBody()
                    ]);
                }
                toastr()->success('URL has been added to scrape.');
                return back();
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
    public function summarizeNote() {

    }

    public function showNotes() {
        $notes = Note::whereIn('status', [3, 4])->paginate(10);
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
        return $id;
        $note = Note::findOrFail($id);
        try {
            $note->delete();
            toastr()->success('Note Deleted!');
            return response()->json(['code' => 200], 200);
        } catch (\Exception $exception) {
            return response()->json(['code' => 500], 500);
        }
    }

}
