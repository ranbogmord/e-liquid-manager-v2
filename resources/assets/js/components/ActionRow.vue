<template>
    <div id="action-row">
        <a href="#" class="btn primary" @click.prevent="saveLiquid(liquid)">Save</a>
        <a href="#" class="btn primary" @click.prevent="cancelEdit()">Cancel</a>
    </div>
</template>

<script>
    const bus = require('../bus');

    export default {
      props: ['liquid'],
      methods: {
        saveLiquid(liquid) {
          bus.$emit('liquid:save', liquid);
        },
        cancelEdit() {
          bus.$emit('liquid:reset');
        }
      }
    }
</script>
