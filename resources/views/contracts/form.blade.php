<form method="POST" action="{{ $action }}" accept-charset="UTF-8" class="form-horizontal" onsubmit="return false;">
    @csrf

    <input name="id" type="hidden" value="{{ $contract->id }}">
    <input name="member_id" type="hidden" value="{{ $contract->member_id }}">

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-sm-4" for="plan_id">Membership Plan</label>
                <div class="col-sm-8">
                    <select class="form-control" name="plan_id">
                        <option value="">-- select --</option>
                        @foreach($plans as $plan)
                        <?php
                        $plan_data = json_encode([
                            'jfAmount' => $plan->jf_amount,
                            'pufAmount' => $plan->puf_amount,
                            'ddAmount' => $plan->dd_amount,
                            'termMonths' => $plan->term_months,
                        ]);
                        ?>
                        <option value="{{ $plan->id }}" data-opt="{{ $plan_data  }}" @selected($plan->id === $contract->plan_id)>
                            {{ $plan->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <x-forms.datepicker name="start_date" label="Start Date" :value="$contract->start_date" required/>
            <x-forms.text name="end_date" label="End Date" :value="$contract->end_date"/>
            <x-forms.text name="jf_amount" label="Joining Fee" :value="$contract->jf_amount" required/>
            <x-forms.text name="puf_amount" label="First Month" :value="$contract->puf_amount" required/>
            <x-forms.text name="dd_amount" label="Monthly Fee" :value="$contract->dd_amount" required/>
        </div><!-- column -->

        <div class="col-md-6">
            <x-forms.keyed-selector name="state" label="Contract State" :options="$contract->contractStates" :value="$contract->state"/>
            <x-forms.text name="dd_day" label="Preferred Payment Day" :value="$contract->dd_day"/>
            <x-forms.datepicker name="dd_first" label="First Payment" :value="$contract->dd_first"/>
            <x-forms.datepicker name="dd_last" label="Last Payment" :value="$contract->dd_last"/>
            <x-forms.datepicker name="canx_date" label="Cancellation Date" :value="$contract->dd_last"/>
        </div><!-- column -->
    </div><!-- row -->
</form>
