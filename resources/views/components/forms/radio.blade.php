<div class="form-group @required($required)">
    <fieldset>
    <legend class="col-sm-4 form-legend">{{ $label }}</legend>
    <div id="{{ $name }}" class="col-sm-8">
        @php($i=1)
        @foreach($options as $option)
            <label class="radio-inline">
            <input id="{{ $name }}-{{ $i }}" name="{{ $name }}" type="radio"
                   value="{{ $option }}" @checked($option == $value)>
                {{ $option }}
            </label>
            @php($i+=1)
        @endforeach
    </div>
    </fieldset>
</div>
