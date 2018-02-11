<div class="select-field">
    <label>
        {{ $label }}: <br>
        <select name="{{ $name or str_slug($label) }}">
            <option value="">{{ $default or "Select" }}</option>
            @foreach($options as $option)
                @if($value && $value == $option['value'])
                    <option value="{{ $option['value'] }}" selected>{{ $option['label'] }}</option>
                @else
                    <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                @endif
            @endforeach
        </select>
    </label>
</div>
