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
        <div class="loading" v-if="loading">
            <img src="/img/loader.svg" alt="">
        </div>
    </div>
</template>

<script>
  const bus = require('../bus');

  export default {
    data() {
      return {
        searchString: "",
        loading: false,
        items: [],
      };
    },
    mounted() {
      axios.get('/ajax/flavours')
      .then(res => {
        if (res.status === 200) {
          this.items = res.data;
        }
      })
      .catch(err => {
        toastr.error('Failed to load flavours');
        console.error(new Error(err));
      });
    },
    methods: {
      addFlavour(flavour) {
        flavour.percent = flavour.base_percent;
        bus.$emit('flavour:added', flavour);
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
    }
  }
</script>
