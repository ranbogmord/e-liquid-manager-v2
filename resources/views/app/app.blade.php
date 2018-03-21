@extends("layouts.app")

@section("styles")
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
@append

@section("content")
    <div id="app">
        <liquid-list></liquid-list>
        <div id="main-area">
            <div class="name-row">
                <input type="text" class="liquid-name" v-model="liquid.name" placeholder="e-liquid name">
            </div>
            <div class="input-cols">
                <div class="input-col">
                    <base-input :liquid.sync="liquid"></base-input>
                </div>
                <div class="input-col">
                    <target-input :liquid.sync="liquid"></target-input>
                </div>
                <div class="input-col">
                    <div class="mix-container">
                        <div class="mix-container-header">
                            <h2>Flavours</h2>
                        </div>

                        <div class="flavour-input-list">
                            <div class="flavour-input-item" v-for="(flavour, idx) in liquid.flavours">
                                <label>
                                    <span v-if="flavour.vendor">@{{ flavour.name }} @{{ flavour.vendor.abbr }}</span>
                                    <span v-else>@{{ flavour.name }}</span>
                                    <span class="remove" @click.prevent="removeFlavour(idx)">X</span>
                                    <input type="number" v-model="flavour.percent" :step="0.25" :min="0" :max="100">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <mix-table :liquid="liquid"></mix-table>

            <action-row :liquid.sync="liquid"></action-row>

            <version-select v-if="liquid.id" :liquid.sync="liquid"></version-select>

            <comments v-if="liquid.id" :liquid.sync="liquid"></comments>

            <concentrate-modal :is-open.sync="showConcentrateModal" :liquid="liquid"></concentrate-modal>

            <div class="loading" v-if="appLoading">
                <img src="{{ asset("img/loader.svg") }}" alt="">
            </div>
        </div>

        <flavour-list></flavour-list>
    </div>
@endsection

@section("scripts")
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/ui.js') }}"></script>
@append

