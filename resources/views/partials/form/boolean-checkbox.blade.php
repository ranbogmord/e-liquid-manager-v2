<div class="boolean-checkbox-field">
    <input type="hidden" name="{{ $name }}" value="0">
    <label>
        <input type="checkbox" name="{{ $name }}" @if($value) checked @endif value="1"> {{ $label }}
    </label>
</div>