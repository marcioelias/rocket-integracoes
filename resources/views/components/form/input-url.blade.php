<div>
    <label for="{{ $field }}">{{ $label }}</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="{{ $field }}">{{ $url }}</span>
            </div>
        <input type="text" class="form-control" id="{{ $field }}" name="{{ $field }}" {{ isset($disabled) ? 'disabled' : '' }} value="{{ isset($value)?$value:old($field) }}" aria-describedby="basic-addon3" placeholder="">
        @if ($errors->has($field))
            <span class="invalid-feedback" style="display: block">
                <strong>{{ $errors->first($field) }}</strong>
            </span>
        @endif
    </div>
</div>
