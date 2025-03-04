<div class="form-group @required($required)">
    <label for="{{ $name }}" class="col-sm-4">{{ $label }}</label>
    <div class="col-sm-8">
        <input {{ $attributes }} class="form-control" name="{{ $name }}" type="password" id="{{ $name }}" @required($required)>
    </div>
</div>
