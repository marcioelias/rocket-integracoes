<div>
    <label for="{{ $field }}">{{ $label }}</label>
    <select class="form-control basic" id="{{ $field }}" name="{{ $field }}" {{ isset($disabled) ? 'disabled' : '' }}>
        @foreach ($options as $key => $option)
            <option value="{{ $key }}" {{ (isset($value) ? $value : null || old($field) == $key) ? 'selected' : '' }} >{{ $option }}</option>
        @endforeach
    </select>
    @if ($errors->has($field))
        <span class="invalid-feedback" style="display: block">
            <strong>{{ $errors->first($field) }}</strong>
        </span>
    @endif
</div>
