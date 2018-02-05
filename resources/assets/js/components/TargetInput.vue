<template>
    <div class="mix-container">
        <div class="mix-container-header">
            <h2>Target</h2>
        </div>

        <mix-input
            label="Batch size (ml)"
            v-model="liquid.batch_size"
            type="number"
            :step="1"
            :min="0"
        ></mix-input>

        <mix-input
            label="Nicotine strength (mg/ml)"
            v-model="liquid.target_nic_strength"
            type="number"
            :step="0.25"
            :min="0"
        ></mix-input>

        <mix-input
            label="PG (%)"
            v-model="liquid.target_pg_percentage"
            type="number"
            :step="1"
            :min="0"
            :max="100"
        ></mix-input>

        <mix-input
            label="VG (%)"
            v-model="liquid.target_vg_percentage"
            type="number"
            :step="1"
            :min="0"
            :max="100"
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
