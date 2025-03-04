<div class="form-group @required($required)">
    <label for="{{ $name }}" class="col-sm-4">{{ $label }}</label>
    <div class="col-sm-8">
        <input class="form-control datepicker" data-provide="timepicker"
               name="{{ $name }}" type="text" id="{{ $name }}"
               value="{{ $value }}">
    </div>
</div>
