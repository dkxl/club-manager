<form method="POST" action="{{ route('plans.store') }}" accept-charset="UTF-8" class="form-horizontal"
      onsubmit="return false;">
    @csrf

    <input name="id" type="hidden" value="{{ $plan->id }}">

    <div class="row">
        <div class="col-md-6">
            <x-forms.text name="name" label="Plan Name" :value="$plan->name" required autocomplete="off"/>
            <x-forms.boolean name="free_classes" label="Includes free classes?" :value="$plan->free_classes" required/>
            <x-forms.boolean name="available" label="Available for new contracts?" :value="$plan->available" required/>
            <x-forms.text name="start_time" label="Earliest Check In Time" :value="$plan->start_time->format('H:i')" required/>
            <x-forms.text name="end_time" label="Latest Check In Time" :value="$plan->end_time->format('H:i')" required/>
        </div><!-- column -->

        <div class="col-md-6">
            <x-forms.text name="jf_amount" label="Joining Fee" :value="$plan->jf_amount" required/>
            <x-forms.text name="puf_amount" label="First Month" :value="$plan->puf_amount" required/>
            <x-forms.text name="dd_amount" label="Monthly Fee" :value="$plan->dd_amount" required/>
            <x-forms.text name="term_months" label="Contract Term (Months)" :value="$plan->term_months" required/>
        </div><!-- column -->
    </div><!-- row -->
</form>
