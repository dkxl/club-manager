<div class="form-group @required($required)">
    <label for="{{ $name }}" class="col-sm-4">{{ $label }}</label>
    <div class="col-sm-8">
        <textarea {{ $attributes }} class="form-control" name="{{ $name }}" id="{{ $name }}"
                  rows="{{ $rows }}" cols="{{ $cols }}" @required($required)>
            {{ $value }}
        </textarea>
    </div>
</div>
