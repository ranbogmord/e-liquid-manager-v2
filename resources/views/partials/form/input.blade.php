<div class="input-field">
    <label>
        {{ $label }}: <br>
        <input type="{{ $type or "text" }}" name="{{ $name or str_slug($label) }}" value="{{ $value or "" }}">
    </label>
</div>
