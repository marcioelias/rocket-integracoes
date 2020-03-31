<div>
    <label for="{{ $field }}">{{ $label }}</label>
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="{{ isset($rows)?$rows:'3' }}" id="{{ $field }}" name="{{ $field }}" {{ isset($disabled) ? 'disabled' : '' }}>{{ isset($value)?$value:old($field) }}</textarea>
    @if ($errors->has($field))
        <span class="invalid-feedback" style="display: block">
            <strong>{{ $errors->first($field) }}</strong>
        </span>
    @endif
</div>
