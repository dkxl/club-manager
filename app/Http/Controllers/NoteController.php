<?php
/**
 * NoteController.php
 * @author davidh
 * @package dk-appt
 */
namespace App\Http\Controllers;

use App\Http\Requests\NoteRequest;
use App\Models\Note;

class NoteController extends BaseController
{

    /**
     * Show the list of notes for the current member, newest first.
     * GET /member/{$member_id}/note
     *
     * @param  string $member_id
     * @return string | \Illuminate\Http\Response
     */
    public function index(string $member_id)
    {
        return view('notes.index',[
            'notes' => Note::where('member_id', $member_id)
                ->orderBy('created_at','desc')
                ->get(),
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(string $member_id)
    {
        return view('notes.form', [
            'note' => new Note([
                'member_id' => $member_id,
            ]),
            'action' => route('members.notes.store', $member_id),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NoteRequest $request)
    {
        $note = new Note($request->validated());
        $note->created_by = $request->user()->id;
        $note->save();

        return view('status.info',
            [
                'message' => "Note added",
            ]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        // Same content as edit(), but clientside jscript will lock the form fields
        return view('notes.form', [
            'note' => $note,
            'action' => route('notes.update', $note->id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        return view('notes.form', [
            'note' => $note,
            'action' => route('notes.update', $note->id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NoteRequest $request, Note $note)
    {
        $note->fill($request->validated());
        $note->save();
        return view('status.info',
            [
                'message' => "Note updated",
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        $note->delete();

        return view('status.warning',
            [
                'message' => "Note deleted",
            ]
        );
    }

}
