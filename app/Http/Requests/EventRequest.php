<?php

namespace App\Http\Requests;

use App\Models\Event;
use App\Services\DiaryServices;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;


class EventRequest extends FormRequest
{


    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    /**
     * The validation rules
     */
    public function rules() : array
    {
        return [
            'id' => 'ulid|nullable',
            'venue_id' => [
                'required',
                'ulid',
                Rule::exists('venues', 'id'),
            ],
            'instructor_id' => [
                'required',
                'ulid',
                Rule::exists('instructors', 'id'),
            ],
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'name' => 'required|string',
            'description' => 'string|nullable',
            'repeat_type' => 'string|nullable',
            'repeat_until' => [
                'date',
                'after:start_date',
                'nullable',
                'required_unless:repeat_type,None',
                ],
            'series_id' => 'ulid|nullable',
            'metadata' => 'array:capacity,report_ref,css',
            'metadata.capacity' => 'required|integer',
            'metadata.report_ref' => 'string|nullable',
            'metadata.css'   => 'string|nullable',
        ];

    }

    /**
     * Hook to invoke additional validation
     * @param Validator $validator
     * @return void
     */
    public function after(Validator $validator): array
    {
        return [
            function (Validator $validator) {
                if ($this->input('repeat_type') !== 'None') {
                     $this->validateSeriesConflicts($validator);
                     $this->validateSeriesChangesPermitted($validator);
                } else {
                    $this->validateEventConflicts($validator);
                    $this->validateEventChangesPermitted($validator);
                }
            },
        ];
    }

    /**
     * Validate that this event does not conflict with other existing events.
     * @param Validator $validator
     * @return void
     */
    public function validateEventConflicts(Validator $validator) : void
    {
        if (Event::venueConflicts($this->input('starts_at'), $this->input('ends_at'), $this->input('venue_id'))
                 ->excludeEventId($this->input('id'))->exists())
        {
            $validator->errors()->add("starts_venue", "Conflicts with an existing event for that venue");
        };

        if (Event::instructorConflicts($this->input('starts_at'), $this->input('ends_at'), $this->input('instructor_id'))
            ->excludeEventId($this->input('id'))->exists())
        {
            $validator->errors()->add("starts_instructor", "Conflicts with an existing event for that instructor");
        };
    }

    /**
     * Validate that none of the child events for this series conflict with existing events
     * @param Validator $validator
     * @return void
     */
    public function validateSeriesConflicts(Validator $validator) : void
    {
        $timeslots = DiaryServices::seriesTimeslots($this->input('starts_at'), $this->input('ends_at'),
            $this->input('repeat_until'), $this->input('repeat_type'));

        if (count($timeslots) > config('club.max_repeats')) {
            $validator->errors()->add("repeat_until", "Too many events - max is " . config('club.max_repeats'));
            return;
        }

        // Now check each timeslot for conflicts. Ignore events with this series_id
        foreach ($timeslots as $idx => $timeslot) {

            if (Event::venueConflicts($timeslot['starts_at'], $timeslot['ends_at'], $this->input('venue_id'))
                ->excludeSeriesId($this->input('id'))->exists())
            {
                $conflict_date = $timeslot['starts_at']->format('d-m-Y');
                $validator->errors()->add("venue_$idx", "The repeat on $conflict_date conflicts with an existing event for that venue. ");
            };

            if (Event::instructorConflicts($timeslot['starts_at'], $timeslot['ends_at'], $this->input('instructor_id'))
                ->excludeSeriesId($this->input('id'))->exists())
            {
                $conflict_date = $timeslot['starts_at']->format('d-m-Y');
                $validator->errors()->add("instructor_$idx", "The repeat on $conflict_date conflicts with an existing event for that instructor. ");
            };

        }
    }


    /**
     * Are changes permitted for this series and all child events?
     * @param Validator $validator
     * @return void
     */
    public function validateSeriesChangesPermitted(Validator $validator) : void
    {
        if (!$this->input('id')) {
            return;
        }

        $children = Event::where('series_id', $this->input('id'))->get();

        foreach ($children as $idx => $child) {
            if ($child->hasBookings()) {
                $conflict_date = $child->starts_at->format('d-m-Y');
                $validator->errors()->add("child_$idx", "Changes not permitted: there are existing bookings for the repeat on $conflict_date");
            }
        }
    }


    /**
     * Are changes permitted for this event?
     * @param Validator $validator
     * @return void
     */
    public function validateEventChangesPermitted(Validator $validator) : void
    {
        if (!$this->input('id')) {
            return;
        }

        $event = Event::find($this->input('id'));

        if ($event && $event->hasBookings()) {
            $conflict_date = $event->starts_at->format('d-m-Y');
            $validator->errors()->add("bookings", "Changes not permitted: there are existing bookings for this event");
        }

    }

    /**
     * Custom validation messages for this form
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'The event name is required',
            'repeat_until.required' => 'The repeat_until date is required for repeated events',
            'metadata.capacity.required' => 'The capacity of the event is required',
        ] ;
    }


    /*
     * Prepare input for validation
     */
    protected function prepareForValidation(): void
    {

        $uk_datetime_format = 'd-m-Y H:i';
        $uk_date = 'd-m-Y';

        $starts_at = implode(' ',[$this->input('start_date'), $this->input('start_time')]);
        if (Carbon::canBeCreatedFromFormat($starts_at, $uk_datetime_format)) {
            $this->merge([
                'starts_at' => Carbon::createFromFormat($uk_datetime_format, $starts_at),
            ]);
        }

        $ends_at = implode(' ',[$this->input('end_date'), $this->input('end_time')]);
        if (Carbon::canBeCreatedFromFormat($ends_at, $uk_datetime_format)) {
            $this->merge([
                'ends_at' => Carbon::createFromFormat($uk_datetime_format, $ends_at),
            ]);
        }

        if (Carbon::canBeCreatedFromFormat($this->input('repeat_until'), $uk_date)) {
            $this->merge([
                'repeat_until' => Carbon::createFromFormat($uk_date, $this->input('repeat_until'))->endOfDay(),
            ]);
        }

        $this->merge([
            'metadata' => [
                'capacity' => $this->input('capacity'),
                'report_ref' => $this->input('report_ref'),
                'css' => $this->input('css'),
            ],
        ]);
    }

}
