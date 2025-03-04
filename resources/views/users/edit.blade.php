<form method="POST" action="{{ route('users.store') }}" accept-charset="UTF-8" class="form-horizontal"
      onsubmit="return false;">
    @csrf

    <input name="id" type="hidden" value="{{ $user->id }}">

    <div class="row">
        <div class="col-md-6">
            <x-forms.text name="name" label="User Name" :value="$user->name" required autocomplete="off"/>
            <x-forms.email name="email" label="Email" :value="$user->email" required autocomplete="off"/>

            @foreach($roles as $role)
                <x-forms.boolean :label="$role" :name="$role" :value="$user->hasRole($role)"/>
            @endforeach

        </div><!-- column -->

        <div class="col-md-6">
        </div><!-- column -->
    </div><!-- row -->
</form>
