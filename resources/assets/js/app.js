require('./bootstrap');
window.Vue = require('vue');

const bus = require('./bus');

const app = new Vue({
  el: '#app',
  data() {
    return {
      liquid: this.getEmptyLiquid()
    };
  },
  mounted() {
    bus.$on('liquid:set', liquid => {
      this.liquid = this.mergeLiquidWithDefault(liquid);
    });

    bus.$on('liquid:reset', () => {
      this.liquid = this.getEmptyLiquid();
    });

    bus.$on('liquid:save', liquid => {
      this.saveLiquid(liquid);
    });

    bus.$on('flavour:added', flavour => {
      this.liquid.flavours.push(flavour);
    });

    bus.$on('flavour:removed', idx => {
      this.liquid.flavours.splice(idx, 1);
    });
  },
  methods: {
    getEmptyLiquid() {
      return {
        id: null,
        name: null,

        base_nic_strength: this.getStoredValue('preferred-base-nic', 30),
        base_pg_percentage: this.getStoredValue('preferred-base-pg', 100) || 100,
        base_vg_percentage: 100 - this.getStoredValue('preferred-base-pg', 100),

        batch_size: this.getStoredValue('preferred-batch-size', 30),
        target_pg_percentage: this.getStoredValue('preferred-target-pg', 50),
        target_vg_percentage: 100 - this.getStoredValue('preferred-target-pg', 50),
        target_nic_strength: this.getStoredValue('preferred-nic-strength', 3),

        next_version_id: null,
        flavours: []
      };
    },
    mergeLiquidWithDefault(liquid) {
      return _.assign(this.getEmptyLiquid(), liquid);
    },
    getStoredValue(key, defaultVal=null) {
      return localStorage.getItem(key) || defaultVal;
    },
    saveLiquid(liquid) {
      liquid = JSON.parse(JSON.stringify(liquid));
      liquid.flavours = liquid.flavours.map(f => {
        return {
          flavour_id: f.id,
          percent: f.percent
        };
      });

      let prom;
      if (liquid.id) {
        prom = axios.put('/ajax/liquids/' + liquid.id, liquid);
      } else {
        prom = axios.post('/ajax/liquids', liquid);
      }

      prom.then(res => {
        if (res.status === 200) {
          toastr.success("Liquid saved");
          bus.$emit('liquid:reset');
        }
      })
      .catch(err => {
        toastr.error("Failed to save liquid");
        console.error(new Error(err));
      })
    }
  },
  watch: {
    'liquid.base_pg_percentage'(val) {
      this.liquid.base_vg_percentage = 100 - (+val);
    },
    'liquid.base_vg_percentage'(val) {
      this.liquid.base_pg_percentage = 100 - (+val);
    },
    'liquid.target_pg_percentage'(val) {
      this.liquid.target_vg_percentage = 100 - (+val);
    },
    'liquid.target_vg_percentage'(val) {
      this.liquid.target_pg_percentage = 100 - (+val);
    },
  },
  components: {
    'liquid-list': require('./components/LiquidList'),
    'flavour-list': require('./components/FlavourList'),
    'base-input': require('./components/BaseInput'),
    'target-input': require('./components/TargetInput'),
    'flavour-input': require('./components/FlavourInput'),
    'mix-table': require('./components/MixTable'),
    'action-row': require('./components/ActionRow'),
  }
});
