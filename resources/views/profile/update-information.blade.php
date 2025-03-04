<header>
    <h2>{{ __('Profile Information') }}</h2>
    <p>{{ __("Update your account's profile information and email address.") }}</p>
</header>

<form method="POST" action="{{ route('profile.update') }}" accept-charset="UTF-8" class="form-horizontal">
    @csrf
    @method('patch')

    <x-forms.text name="name" label="User Name" :value="$user->name" autocomplete="off"/>
    <x-forms.email name="email" label="Email" :value="$user->email" autocomplete="off"/>

    <button id="btnProfileSave" type="submit" class="btn btn-primary" >Save</button>

    @if (session('status') === 'profile-updated')
        <div class="alert alert-warning">{{ __('Saved.') }}</div>
    @endif

</form>
