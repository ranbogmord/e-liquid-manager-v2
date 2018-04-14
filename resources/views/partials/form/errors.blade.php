<div class="form-errors">
    <ul>
        @foreach ($errors as $error)
            <li>
                {{ $error }}
            </li>
        @endforeach
    </ul>
</div>
