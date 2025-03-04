<header>
    <h2>{{ __('Update Password') }}</h2>
    <p>{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
</header>

<form method="post" action="{{ route('password.update') }}" accept-charset="UTF-8" class="form-horizontal">
    @csrf
    @method('put')


    <x-forms.password name="current_password" label="Current Password" required autocomplete="current-password"/>
    <x-forms.password name="password" label="New Password" required autocomplete="new-password"/>
    <x-forms.password name="password_confirmation" label="Confirm Password" required autocomplete="new-password"/>

    <button id="btnPasswordSave" type="submit" class="btn btn-success" >Save</button>
    @if (session('status') === 'password-updated')
        <div class="alert alert-warning">{{ __('Saved.') }}</div>
    @endif

</form>
