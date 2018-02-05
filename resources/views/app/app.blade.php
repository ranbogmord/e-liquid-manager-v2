@extends("layouts.app")

@section("styles")
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
@append

@section("content")
    <div id="app">
        <div class="input-cols">
            <div class="input-col">
                <base-input :liquid="liquid"></base-input>
            </div>
            <div class="input-col">
                <target-input :liquid="liquid"></target-input>
            </div>
            <div class="input-col">
                <flavour-input :liquid="liquid"></flavour-input>
            </div>
        </div>

        <mix-table :liquid="liquid"></mix-table>

        <action-row :liquid="liquid"></action-row>

        <liquid-list></liquid-list>
        <flavour-list></flavour-list>
    </div>
@endsection

@section("scripts")
    <script src="{{ asset('js/app.js') }}"></script>
@append

