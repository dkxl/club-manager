<div class="form-group @required($required)">
    <label for="{{ $name }}" class="col-sm-4">{{ $label }}</label>
    <div class="col-sm-8">
        <select class="form-control" name="{{ $name }}" id="{{ $name }}" @required($required)>
            <option value="">-- select --</option>
            @foreach($options as $key => $option)
                <option value="{{ $key }}" @selected($key == $value)>
                {{ $option }}
                </option>
            @endforeach
        </select>
    </div>
</div>
