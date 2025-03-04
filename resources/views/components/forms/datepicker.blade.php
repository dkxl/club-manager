<div class="form-group @required($required)">
    <label for="{{ $name }}" class="col-sm-4">{{ $label }}</label>
    <div class="col-sm-8">
        <input class="form-control datepicker" data-provide="datepicker"
               data-date-format="dd-mm-yyyy" data-date-today-highlight="true"
               name="{{ $name }}" type="text" id="{{ $name }}"
               value="{{ $value }}">
    </div>
</div>
