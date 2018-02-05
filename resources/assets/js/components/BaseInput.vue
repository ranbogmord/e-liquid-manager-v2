<template>
    <div class="mix-container">
        <div class="mix-container-header">
            <h2>Base</h2>
        </div>

        <mix-input
            label="Nicotine strength (mg/ml)"
            v-model="liquid.base_nic_strength"
            type="number"
            :min="0"
            :step="1"
        ></mix-input>

        <mix-input
            label="PG (%)"
            v-model="liquid.base_pg_percentage"
            type="number"
            :min="0"
            :max="100"
            :step="1"
        ></mix-input>

        <mix-input
            label="VG (%)"
            v-model="liquid.base_vg_percentage"
            type="number"
            :min="0"
            :max="100"
            :step="1"
        ></mix-input>
    </div>
</template>

<script>
    export default {
      props: ['liquid'],
      components: {
        'mix-input': require('./form/MixInput')
      }
    }
</script>