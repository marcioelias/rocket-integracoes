<div>
    <label for="{{ $field }}">{{ $label }}</label>
    <input id="{{ $field }}" name="{{ $field }}" type="password" class="form-control" placeholder="" value="{{ old($field) }}">
    @if ($errors->has($field))
        <span class="invalid-feedback" style="display: block">
            <strong>{{ $errors->first($field) }}</strong>
        </span>
    @endif
</div>
