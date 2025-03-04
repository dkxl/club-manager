<?php
/**
 * MemberController.php
 * @author davidh
 * @package dk-appt
 */
namespace App\Http\Controllers;

use App\Http\Requests\MemberRequest;
use App\Models\Member;
use App\Models\Contract;


class MemberController extends BaseController
{

    /**
     * Listing all members not implemented. Should only get here if the Members tab has not selected
     * a member record yet.
     * GET /members/
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('status.info',
            [
                'message' => "Use the Search Box to select a Member, or click New to create a new Member.",
            ]
        );
    }


    /**
     * Show the form for creating a new resource.
     * GET /member/create
     **
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('members.form', [
            'member' => new Member(),  // loads default data for a new model
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *  POST /member/
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MemberRequest $request)
    {

        $member = Member::create($request->validated());

        return view('members.form', [
            'member' => $member,
        ]);
    }



    /**
     * Display the specified resource.
     * GET /member/{$id}
     *
     * @param  Member $member
     * @return \Illuminate\Http\Response
     */
    public function show(Member $member){
        return view('members.form', [
            'member' => $member,
        ]);
    }


    /**
     * Get the form for editing the specified resource.
     * GET /member/{$id}/edit
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    //
    public function edit(Member $member){
        return view('members.form', [
            'member' => $member,
        ]);
    }


    /**
     * Update the specified resource in storage.
     * PUT/PATCH /member/{$id}
     *
     * Does not check whether the resource was marked as soft deleted,
     * we may be trying to undelete it
     *
     * @param  MemberRequest  $request
     * @param Member $member
     * @return \Illuminate\Http\Response
     */
    public function update(MemberRequest $request, Member $member)
    {

        $member->fill($request->validated());
        $member->save();

        return view('members.form', [
            'member' => $member,
        ]);

    }


    /**
     * Remove the specified resource from storage.
     * DELETE /member/{$id}
     *
     * @param Member $member
     * @return \Illuminate\Http\Response
     */
    public function destroy(Member $member){

        $member->delete();

        return view('status.warning',
            [
                'message' => "Member deleted",
            ]
        );
    }

}
