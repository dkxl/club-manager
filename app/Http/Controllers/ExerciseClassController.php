<?php
namespace App\Http\Controllers;

use App\Projections\ClassCredit;
use App\Projections\Member;
use App\Projections\MemberClasses;

class ExerciseClassController extends BaseController
{

    /**
     * Display all class activity for the member
     * And forms to book new classes or add credits
     * GET member/classes/{member_id}
     *
     * @param string $member_id
     * @return \Illuminate\Http\Response
     */
    public function index($member_id)
    {

        return view('tables.member_classes', [
            'events' => MemberClasses::find($member_id),
            'member' => Member::findOrFail($member_id),
            'balance' => ClassCredit::balance($member_id)
        ]);
    }



}
