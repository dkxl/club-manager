<form method="POST" action="{{ route('venues.store') }}" accept-charset="UTF-8" class="form-horizontal"
      onsubmit="return false;">
    @csrf

    <input name="id" type="hidden" value="{{ $venue->id }}">

    <div class="row">
        <div class="col-md-6">
            <x-forms.text name="name" label="Venue Name" :value="$venue->name" required autocomplete="off"/>
            <x-forms.text name="capacity" label="Capacity" :value="$venue->capacity" required autocomplete="off"/>
            <x-forms.text name="description" label="Description" :value="$venue->description" autocomplete="off"/>

        </div><!-- column -->

        <div class="col-md-6">
        </div><!-- column -->
    </div><!-- row -->
</form>
