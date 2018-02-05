<template>
    <div id="liquid-list" class="sidebar-list">
        <h3>Liquids</h3>
        <div class="search-field">
            <input type="text" placeholder="Search" v-model="searchString">
        </div>
        <ul>
            <li v-for="item in sorted" @click.prevent="setLiquid(item)">
                {{ item.name }}
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
        axios.get('/ajax/liquids')
        .then(res => {
          if (res.status === 200) {
            this.items = res.data;
          }
        })
        .catch(err => {
          toastr.error('Failed to load liquids');
          console.error(new Error(err));
        });
      },
      methods: {
        setLiquid(liquid) {
          bus.$emit('liquid:set', liquid);
        }
      },
      computed: {
        filtered() {
          if (!this.searchString) {
            return this.items || [];
          }

          return (this.items || []).filter(item => {
            return item.name.toLowerCase().indexOf(this.searchString.toLowerCase()) !== -1;
          });
        },
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
        }
      }
    }
</script>