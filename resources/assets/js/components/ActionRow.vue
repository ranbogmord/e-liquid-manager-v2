<template>
    <div id="action-row">
        <a v-if="liquid.id" @click.prevent="archiveLiquid(liquid)" href="#" class="btn danger archive">Archive</a>
        <a href="#" class="btn primary" @click.prevent="saveLiquid(liquid)">Save</a>
        <a href="#" class="btn danger" @click.prevent="cancelEdit()">Cancel</a>
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
        },
        archiveLiquid(liquid) {
          bus.$emit('liquid:archive', liquid);
        }
      }
    }
</script>
