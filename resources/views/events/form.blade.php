<form method="POST" action="{{ $controller }}" accept-charset="UTF-8" class="form-horizontal">
    @csrf

    <input name="id" type="hidden" value="{{ $event->id }}">
    <div class="row">
        <div class="col-md-6">
            <x-forms.keyed-selector name="venue_id" label="Venue" :options="$venues" :value="$event->venue_id" required/>
            <x-forms.text name="name" label="Event Name" :value="$event->name" required/>
            <x-forms.keyed-selector name="instructor_id" label="Instructor" :options="$instructors" :value="$event->instructor_id" required/>
            <x-forms.text name="capacity" label="Event Capacity" :value="$event->metadata['capacity']" required/>
            <x-forms.text name="description" label="Description" :value="$event->description"/>
        </div><!-- column -->

        <div class="col-md-6">

            <div class="form-group required">
                <label for="start_date" class="col-sm-4">Starts</label>
                <div class="col-sm-4">
                    <input class="form-control" data-provide="datepicker" data-date-format="dd-mm-yyyy"
                           data-date-today-highlight="true" required name="start_date" type="text"
                           value="{{ $event->starts_at->format('d-m-Y') }}" id="start_date">
                </div>
                <div class="col-sm-4">
                    <input class="form-control" data-provide="timepicker" required name="start_time" type="text"
                           value="{{ $event->starts_at->format('H:i') }}">
                </div>
            </div>
            <div class="form-group required">
                <label for="end_date" class="col-sm-4">Ends</label>
                <div class="col-sm-4">
                    <input class="form-control" data-provide="datepicker" data-date-format="dd-mm-yyyy"
                           data-date-today-highlight="true" required name="end_date" type="text"
                           value="{{ $event->ends_at->format('d-m-Y') }}" id="end_date">
                </div>
                <div class="col-sm-4">
                    <input class="form-control" data-provide="timepicker" required name="end_time" type="text"
                           value="{{ $event->ends_at->format('H:i') }}">
                </div>
            </div>

            {{-- only show the repeat selectors for new appointments or existing series parents --}}
            @if (is_null($event->id) || $event->isSeriesParent() )
                <x-forms.selector name="repeat_type" label="Repeat Type" :options="$event->repeatTypes" :value="$event->repeat_type"/>
                <x-forms.datepicker name="repeat_until" label="Repeat Until" :value="($event->repeat_until) ? $event->repeat_until->format('d-m-Y') : ''"/>
            @else
                <input name="repeat_type" type="hidden" value="None">
                <input name="repeat_until" type="hidden" value="">
            @endif

            <x-forms.text name="report_ref" label="Reporting Ref." :value="$event->metadata['report_ref']"/>
            <x-forms.selector name="css" label="CSS Style" :options="$event->cssStyles" :value="$event->metadata['css']"/>
        </div><!-- column -->
    </div><!-- row -->
</form>
