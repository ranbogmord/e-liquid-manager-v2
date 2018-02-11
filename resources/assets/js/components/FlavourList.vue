<template>
    <div id="flavour-list" class="sidebar-list">
        <h3>Flavours</h3>
        <div class="search-field">
            <input type="text" placeholder="Search" v-model="searchString">
        </div>
        <ul>
            <li v-for="item in sorted" @click.prevent="addFlavour(item)">
                {{ item.name }} {{ (item.vendor || {}).abbr }}
            </li>
        </ul>

        <a v-if="!showCreateFlavourForm" href="#" class="btn primary expanded" @click="showCreateFlavourForm = true">Add flavour</a>
        <div id="add-flavour" v-if="showCreateFlavourForm">
            <h3>Add flavour</h3>
            <mix-input
                label="Name"
                v-model="newFlavour.name"
            ></mix-input>

            <div class="mix-checkbox">
                <label>
                    Is VG based? <input type="checkbox" v-model="newFlavour.is_vg">
                </label>
            </div>

            <div class="mix-select">
                <label>
                    Vendor<br>
                    <select v-model="newFlavour.vendor">
                        <option :value="null">Other</option>
                        <option :value="vendor" v-for="vendor in vendors">{{ vendor.name }}</option>
                    </select>
                </label>
            </div>

            <a @click.prevent="createFlavour(newFlavour)" href="#" class="btn primary expanded">Add</a>
            <a @click.prevent="resetNewFlavour" href="#" class="btn danger expanded">Cancel</a>
        </div>

        <div class="loading" v-if="loading">
            <img src="/img/loader.svg" alt="">
        </div>
    </div>
</template>

<script>
  const bus = require('../bus');
  const utils = require('../utils');

  export default {
    data() {
      return {
        searchString: "",
        loading: false,
        items: [],
        vendors: [],
        showCreateFlavourForm: false,
        newFlavour: {
          name: null,
          is_vg: false,
          vendor: null
        }
      };
    },
    mounted() {
      this.loading = true;
      axios.get('/ajax/flavours')
      .then(res => {
        this.loading = false;
        if (res.status === 200) {
          this.items = res.data;
        }
      })
      .catch(err => {
        this.loading = false;
        toastr.error('Failed to load flavours');
        console.error(new Error(err));
      });

      axios.get('/ajax/vendors')
      .then(res => {
        if (res.status === 200) {
          this.vendors = res.data;
        }
      })
      .catch(err => {
        toastr.error('Failed to load vendors');
        console.error(new Error(err));
      })
    },
    methods: {
      addFlavour(flavour) {
        // flavour.percent = flavour.base_percent;
        bus.$emit('flavour:added', flavour);
      },
      createFlavour(flavour) {
        if (this.loading) {
          return;
        }

        this.loading = true;
        axios.post('/ajax/flavours', {
          name: flavour.name,
          is_vg: flavour.is_vg,
          vendor_id: (flavour.vendor || {}).id
        })
        .then(res => {
          this.loading = false;
          if (res.status === 200) {
            toastr.success("Flavour created");
            this.items.push(res.data);

            this.resetNewFlavour();
          }
        })
        .catch(err => {
          this.loading = false;
          const res = err.response;
          if (res && res.status === 400) {
            const errors = utils.extractErrors(res.data);

            toastr.error(errors.join('<br>'));
          }
        })
      },
      resetNewFlavour() {
        this.showCreateFlavourForm = false;
        this.newFlavour = {
          name: null,
          is_vg: false,
          vendor: null
        };
      }
    },
    computed: {
      sorted() {
        return this.filtered.sort((a, b) => {
          if (a > b) {
            return 1;
          } else if (a < b) {
            return -1;
          } else {
            return 0;
          }
        });
      },
      filtered() {
        if (!this.searchString) {
          return this.items || [];
        }

        return (this.items || []).filter(item => {
          return item.name.toLowerCase().indexOf(this.searchString.toLowerCase()) !== -1;
        });
      }
    },
    components: {
      'mix-input': require('./form/MixInput')
    }
  }
</script>
