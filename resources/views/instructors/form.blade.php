<form method="POST" action="{{ route('instructors.store') }}" accept-charset="UTF-8" class="form-horizontal"
      onsubmit="return false;">
    @csrf

    <input name="id" type="hidden" value="{{ $instructor->id }}">

    <div class="row">
        <div class="col-md-6">
            <x-forms.text name="name" label="Name" :value="$instructor->name" required autocomplete="off"/>
            <x-forms.boolean name="available" label="Available for work?" :value="$instructor->available" required/>
            <x-forms.text name="phone" label="Phone" :value="$instructor->phone" required autocomplete="off"/>
            <x-forms.email name="email" label="Email" :value="$instructor->email" autocomplete="off"/>

        </div><!-- column -->

        <div class="col-md-6">
            <x-forms.textArea name="skills" label="Skills" :value="$instructor->skills" autocomplete="off"/>
        </div><!-- column -->
    </div><!-- row -->
</form>
