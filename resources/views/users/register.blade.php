<form method="POST" action="{{ route('users.store') }}" accept-charset="UTF-8" class="form-horizontal"
      onsubmit="return false;">
    @csrf

    <input name="id" type="hidden" value="">

    <div class="row">
        <div class="col-md-6">
            <x-forms.text name="name" label="User Name" required autocomplete="off"/>
            <x-forms.email name="email" label="Email" required autocomplete="off"/>
            <x-forms.password name="password" label="Temporary Password" required autocomplete="new-password"/>
            <x-forms.password name="password_confirmation" label="Confirm Password" required autocomplete="new-password"/>

        @foreach($roles as $role)
                <x-forms.boolean :label="$role" :name="$role" :value="0"/>
            @endforeach


        </div><!-- column -->

        <div class="col-md-6">
        </div><!-- column -->
    </div><!-- row -->
</form>
