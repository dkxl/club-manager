<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Http\Requests\InstructorRequest;


class InstructorController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('instructors.index', [
            'instructors' => Instructor::orderBy('name', 'asc')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('instructors.form', [
            'instructor' => new Instructor(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InstructorRequest $request)
    {
        $instructor = Instructor::create($request->validated());

        return view('status.info',
            [
                'message' => "Instructor created: $instructor->name ",
            ]
        );

    }

    /**
     * Display the specified resource.
     */
    public function show(Instructor $instructor)
    {
        // Same content as edit(), but clientside jscript will lock the form fields
        return view('instructors.form', [
            'instructor' => $instructor,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Instructor $instructor)
    {
        return view('instructors.form', [
            'instructor' => $instructor,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InstructorRequest $request, Instructor $instructor)
    {
        $instructor->fill($request->validated());
        $instructor->save();
        return view('status.info',
            [
                'message' => "Instructor updated: $instructor->name",
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Instructor $instructor)
    {
        $instructor->delete();

        return view('status.warning',
            [
                'message' => "Instructor deleted: $instructor->name",
            ]
        );
    }
}
