<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\LaravelAdapter;

class NotesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']); 
        $this->middleware(['owner'])->only('show', 'edit', 'destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = Note::where("user_id", Auth::user()->id)->get();
        $other = Auth::user()->shared;
        $notes = $notes ->merge($other);
        return view("notes.index", compact(["notes"]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $isEdit = false;
        return view ("notes.create-edit", compact(["isEdit"]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "title"=>["required","string","min:8", "unique:notes,title"],
            "description"=>["required","min:8"]
        ]);
        $note = new Note();
        $note->title = $request->title;
        $note->description = $request->description;
        $note->user_id = Auth::user()->id; // Assuming user is authenticated
        $note->save();

        $note->shared()->attach($request->share);
        return redirect()->route("notes.show", $note->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        return view('notes.show', compact(['note']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        $isEdit = true;
        return view ('notes.create-edit', compact(['isEdit','note']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        $request->validate([
            "title"=>["required","string",""],
            "description"=>"required",
            "share"=>"required",
        ]);

        

        $note->title = $request->title;
        $note->description = $request->description;
        $note->user_id = Auth::user()->id; // Assuming user is authenticated
        $note->update();

        $note->shared()->detach();
        $note->shared()->attach($request->share);

        return redirect()->route("notes.show", $note->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        $note->delete();
        return redirect(route('notes.index'));
    }

    


    public function getData(){
        // $sqlBuilder = Note::select('notes.id','notes.title','users.name', 'notes.created_at')
        // ->join('users', 'users.id', 'notes.user_id')
        // ->where('user_id', Auth::id());
        
        $sqlBuilder = 'SELECT a.id, a.title, a.name, a.created_at FROM ((SELECT notes.id, notes.title, users.name, notes.created_at
        FROM notes notes
        JOIN users users ON users.id = notes.user_id
        WHERE notes.user_id = '.Auth::id().')
        UNION 
        (
            SELECT notes.id, notes.title, (SELECT name from users where users.id = notes.user_id), notes.created_at
            FROM note_user note_user
            JOIN notes notes ON notes.id = note_user.note_id
            JOIN users users ON users.id = note_user.user_id
            WHERE note_user.user_id = '.Auth::id().'
        )) a';

        
        $dt = new Datatables(new LaravelAdapter);
        $dt->query($sqlBuilder);

        $dt->edit('title', function ($data) {
            return '<a href="'.route('notes.show', $data['id']).'">'.$data['title'] .'</a> ';
        });

        // $dt->add('action', function ($data) {
        //     return '<a href="'.route('notes.edit', $data['id']).'">#edit </a> ' . 
        //     '/  <a href="'.route('notes.destroy', $data['id']).'">#delete </a> ';
        //     // return '<a href="'.route('notes.destroy', $data['id']).'">#delete </a>';
        // });

        $dt->add('action', function ($data) {
            return '<a href="'.route('notes.edit', $data['id']).'">Edit</a>  ' . 
            '<form method="POST" action="'.route('notes.destroy', $data['id']).'" style="display:inline">' .
            csrf_field() .
            method_field('DELETE') .
            '<button type="submit" onclick="return confirm(\'Are you sure?\')">delete</button></form>';
        });
        $dt->hide('id');

        return $dt->generate();
    }
}
