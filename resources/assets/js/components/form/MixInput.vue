<template>
    <div class="mix-container-input">
        <label>
            {{ label }}<br>
            <input :type="type" ref="input" :value="value" @input="updateValue" :step="step" :min="min" :max="max">
        </label>
    </div>
</template>

<script>
    export default {
      props: {
        value: {
          default: null
        },
        type: {
          type: String,
          default: "text"
        },
        label: {
          type: String
        },
        step: {
          default: null,
          type: Number
        },
        min: {
          default: null,
          type: Number
        },
        max: {
          default: null,
          type: Number
        },

      },
      methods: {
        updateValue() {
          let val = this.$refs.input.value;

          if (this.type === "number") {
            val = +val;
          }

          this.$emit('input', val);
        }
      }
    }
</script>
