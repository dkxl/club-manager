<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venue;
use App\Http\Requests\VenueRequest;


class VenueController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('venues.index', [
            'venues' => Venue::orderBy('name', 'asc')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('venues.form', [
            'venue' => new Venue(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VenueRequest $request)
    {
        $venue = Venue::create($request->validated());

        return view('status.info',
            [
                'message' => "Venue created: $venue->name ",
            ]
        );

    }

    /**
     * Display the specified resource.
     */
    public function show(Venue $venue)
    {
        // Same content as edit(), but clientside jscript will lock the form fields
        return view('venues.form', [
            'venue' => $venue,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venue $venue)
    {
        return view('venues.form', [
            'venue' => $venue,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VenueRequest $request, Venue $venue)
    {
        $venue->fill($request->validated());
        $venue->save();
        return view('status.info',
            [
                'message' => "Venue updated: $venue->name",
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venue $venue)
    {
        $venue->delete();

        return view('status.warning',
            [
                'message' => "Venue deleted: $venue->name",
            ]
        );
    }
}
