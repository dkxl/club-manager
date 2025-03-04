<div class="form-group @required($required)">
    <label for="{{ $name }}" class="col-sm-4">{{ $label }}</label>
    <div class="col-sm-8">
        <input type="hidden" name="{{ $name }}" value="0">
        <input id="{{ $name }}" name="{{ $name }}" type="checkbox" value="1" @checked($value)>
    </div>
</div>
