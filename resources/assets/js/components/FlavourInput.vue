<template>
    <div class="mix-container">
        <div class="mix-container-header">
            <h2>Flavours</h2>
        </div>

        <div class="flavour-input-list">
            <div class="flavour-input-item" v-for="(flavour, idx) in liquid.flavours">
                <label>
                    <span v-if="flavour.vendor">{{ flavour.name }} {{ flavour.vendor.abbr }}</span>
                    <span v-else>{{ flavour.name }}</span>
                    <span class="remove" @click.prevent="removeFlavour(idx)">X</span>
                    <input type="number" v-model="flavour.percent" :step="0.25" :min="0" :max="100">
                </label>
            </div>
        </div>
    </div>
</template>

<script>
    const bus = require('../bus');

    export default {
      props: ["liquid"],
      methods: {
        removeFlavour(idx) {
          bus.$emit('flavour:removed', idx);
        }
      }
    }
</script>