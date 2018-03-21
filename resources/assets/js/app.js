const initApp = () => {
  require('./bootstrap');
  window.Vue = require('vue');

  const bus = require('./bus');
  const utils = require('./utils');
  const mixingCalculator = require('./mixing-calculator');

  const app = new Vue({
    el: '#app',
    data() {
      return {
        appLoading: false,
        showConcentrateModal: false,
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
        app.liquid.flavours.push(flavour);
      });

      bus.$on('flavour:removed', idx => {
        app.liquid.flavours.splice(idx, 1);
      });

      bus.$on('liquid:archive', liquid => {
        this.deleteLiquid(liquid);
      });

      bus.$on('concentrate-modal:open', () => {
        this.showConcentrateModal = true;
      });

      bus.$on('concentrate-modal:close', () => {
        this.showConcentrateModal = false;
      });

      bus.$on('liquid:new-version', liquid => {
        this.newVersion(liquid);
      })
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
        this.appLoading = true;
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
            bus.$emit('liquid:created');
            this.appLoading = false;
          }
        })
        .catch(err => {
          this.appLoading = false;
          const res = err.response;

          if (res && res.status === 400) {
            toastr.error(utils.extractErrors(res.data).join("<br>"));
          } else {
            toastr.error("Failed to save liquid");
          }
          console.error(new Error(err));
        })
      },
      deleteLiquid(liquid) {
        axios.delete(`/ajax/liquids/${liquid.id}`)
        .then(res => {
          if (res.status === 204) {
            toastr.success('Liquid archived');
            this.liquid = this.getEmptyLiquid();
            bus.$emit('liquid:archived');
          }
        })
        .catch(err => {
          console.log(err);
          toastr.error("Failed to archive liquid");
        });
      },
      newVersion(liquid) {
        axios.post(`/ajax/liquids/${liquid.id}/new-version`)
        .then(res => {
          if (res.status === 200) {
            this.liquid = _.assign(this.getEmptyLiquid(), res.data);
            bus.$emit('liquid:created');
          }
        })
        .catch(err => {
          let res = err.response;
          if (res.status === 400) {
            toastr.error(utils.extractErrors(res.data));
          } else {
            toastr.error("Failed to create new version");
          }
        })
      },
      removeFlavour(idx) {
        bus.$emit('flavour:removed', idx);
      }
    },
    watch: {
      'liquid.base_pg_percentage'(val) {
        this.liquid.base_vg_percentage = 100 - (+val);
        localStorage.setItem('preferred-base-pg', val);
      },
      'liquid.base_vg_percentage'(val) {
        this.liquid.base_pg_percentage = 100 - (+val);
      },
      'liquid.target_pg_percentage'(val) {
        this.liquid.target_vg_percentage = 100 - (+val);
        localStorage.setItem('preferred-target-pg', val);
      },
      'liquid.target_vg_percentage'(val) {
        this.liquid.target_pg_percentage = 100 - (+val);
      },
      'liquid.base_nic_strength'(val) {
        localStorage.setItem('preferred-base-nic', val);
      },
      'liquid.batch_size'(val) {
        localStorage.setItem('preferred-batch-size', val);
      },
      'liquid.target_nic_strength'(val) {
        localStorage.setItem('preferred-nic-strength', val);
      }
    },
    components: {
      'liquid-list': require('./components/LiquidList'),
      'flavour-list': require('./components/FlavourList'),
      'base-input': require('./components/BaseInput'),
      'target-input': require('./components/TargetInput'),
      'flavour-input': require('./components/FlavourInput'),
      'mix-table': require('./components/MixTable'),
      'action-row': require('./components/ActionRow'),
      'comments': require('./components/Comments'),
      'concentrate-modal': require('./components/ConcentrateModal'),
      'version-select': require('./components/VersionSelect'),
    }
  });
};

if (window.Raven) {
  Raven.config('https://4f2d6206ed4e4e4a90205805a07072b8@sentry.io/304736').install();
  Raven.context(() => {
    initApp();
  });
} else {
  initApp();
}
