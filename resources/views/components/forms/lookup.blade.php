<div class="form-group @required($required)">
    <label for="{{ $name }}" class="col-sm-4">{{ $label }}</label>
    <div class="col-sm-8">
        <input class="form-control" name="{{ $name }}" type="text" id="{{ $name }}" @required($required) value="{{ $value }}">
    </div>
    <div class="col-sm-4">
        <button type="button" class="btn btn-primary" id="{{ $name }}-btn">{{ $buttonLabel }}</button>
        <p class="form-control-static text-warning" hidden>Searching...</p>
    </div>
</div>
