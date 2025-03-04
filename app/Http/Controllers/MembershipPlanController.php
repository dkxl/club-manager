<?php
/**
 * MembershipPlanController.php
 * Membership plans
 * @author davidh
 * @package dk-appt
 */
namespace App\Http\Controllers;

use App\Http\Requests\MembershipPlanRequest;
use App\Models\MembershipPlan;


class MembershipPlanController extends BaseController
{

    /**
     * Display a listing of all resources
     * GET plan/
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('membership_plans.index',[
            'plans' => MembershipPlan::orderBy('available', 'desc')  // active plans first
                                     ->orderBy('name', 'asc')
                                     ->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * GET plan/create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('membership_plans.form', [
            'plan' => new MembershipPlan(),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     * Return the list of all available resources
     *  POST plan/
     *
     * @param  StoreMembershipPlan  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MembershipPlanRequest $request)
    {

        $plan = MembershipPlan::create($request->validated());

        return view('status.info',
            [
                'message' => "Membership plan created: $plan->name",
            ]
        );

    }

    // Note: Must use the variable name ($plan) defined by the Route::Resource or a new, empty, Model will be used!
    // - Laravel will not send a 404, as the model does exist (that validation happens before we get here)

    /**
     * Display the specified resource
     * GET membership_plans/{$plan}
     *
     * @param MembershipPlan $plan
     * @return \Illuminate\Http\Response
     */
    public function show(MembershipPlan $plan)
    {
        // Same content as edit(), but clientside jscript will lock the form fields
        return view('membership_plans.form', [
            'plan' => $plan,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     * GET membership_plans/{$plan}/edit
     *
     * @param MembershipPlan $plan
     * @return \Illuminate\Http\Response
     */
    //
    public function edit(MembershipPlan $plan)
    {
        return view('membership_plans.form', [
            'plan' => $plan,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * PUT/PATCH plan/{$id}
     *
     * Does not check whether the resource was marked as soft deleted,
     * we may be trying to undelete it
     *
     * @param  MembershipPlanRequest  $request
     * @param MembershipPlan $plan
     * @return \Illuminate\Http\Response
     */
    public function update(MembershipPlanRequest $request, MembershipPlan $plan){

        $plan->fill($request->validated());
        $plan->save();

        return view('status.info',
            [
                'message' => "Membership plan updated: $plan->name",
            ]
        );
    }

    /**
     * Mark the specified resource as deleted.
     * DELETE plan/{$id}
     *
     * @param MembershipPlan $plan
     * @return \Illuminate\Http\Response
     */
    public function destroy(MembershipPlan $plan)
    {

        $plan->delete();

        return view('status.warning',
            [
                'message' => "Membership plan deleted: $plan->name",
            ]
        );
    }

}
