<?php
/**
 * ContractController.php
 * @author davidh
 * @package dk-appt
 */
namespace App\Http\Controllers;

use App\Http\Requests\ContractRequest;
use App\Models\Contract;
use App\Models\MembershipPlan;

class ContractController extends BaseController
{

    /**
     * Show the list of contracts for the current member, newest first.
     * GET /member/{$member_id}/contract
     *
     * @param  string $member_id
     * @return string | \Illuminate\Http\Response
     */
    public function index(string $member_id)
    {
        return view('contracts.index',[
            'contracts' => Contract::where('member_id', $member_id)
                ->orderBy('created_at','desc')
                ->get(),
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(string $member_id)
    {
        return view('contracts.form', [
            'contract' => new Contract([
                'member_id' => $member_id,
                ]),
            'plans' => MembershipPlan::all(),
            'action' => route('members.contracts.store', $member_id),

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContractRequest $request)
    {
        $contract = Contract::create($request->validated());

        return view('status.info',
            [
                'message' => "Contract created: " . $contract->membership_plan->name,
            ]
        );

    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
    {
        // Same content as edit(), but clientside jscript will lock the form fields
        return view('contracts.form', [
            'contract' => $contract,
            'plans' => MembershipPlan::all(),
            'action' => route('contracts.update', $contract->id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contract $contract)
    {
        return view('contracts.form', [
            'contract' => $contract,
            'plans' => MembershipPlan::all(),
            'action' => route('contracts.update', $contract->id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContractRequest $request, Contract $contract)
    {
        $contract->fill($request->validated());
        $contract->save();
        return view('status.info',
            [
                'message' => "Contract updated: " . $contract->membership_plan->name,
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        $contract->delete();

        return view('status.warning',
            [
                'message' => "Contract deleted",
            ]
        );
    }

}
