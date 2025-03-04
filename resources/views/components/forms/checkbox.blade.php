<div class="form-group @required($required)">
    <fieldset>
    <legend class="col-sm-4 form-legend">{{ $label }}</legend>
    <div id="{{ $name }}" class="col-sm-8">
        @php($i=1)
        @foreach($options as $option)
            <label class="checkbox-inline">
                <input id="{{ $name }}-{{ $i }}" name="{{ $name }}[]" type="checkbox"
                       value="{{ $option }}" @checked($option == $value)>
                {{ ucfirst($option) }}
            </label>
            @php($i+=1)
        @endforeach
    </div>
    </fieldset>
</div>
