<div>
    <label for="{{ $field }}">{{ $label }}</label>
    <select class="form-control basic" {{ isset($disabled) ? 'disabled' : '' }} id="{{ $field }}" name="{{ $field }}">
        @foreach ($options as $option)
            <option value="{{ $option->$keyField }}" {{ (isset($value) ? $value : null || old($field) == $option->$keyField) ? 'selected' : '' }} >{{ isset($displayField) ? $option->$displayField : eval($displayExpression) }}</option>
        @endforeach
    </select>
    @if ($errors->has($field))
        <span class="invalid-feedback" style="display: block">
            <strong>{{ $errors->first($field) }}</strong>
        </span>
    @endif
</div>
