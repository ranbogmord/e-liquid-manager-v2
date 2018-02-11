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
        this.getLiquids();

        bus.$on('liquid:archived', () => {
          this.getLiquids();
        })
      },
      methods: {
        setLiquid(liquid) {
          bus.$emit('liquid:set', liquid);
        },
        getLiquids() {
          this.loading = true;
          axios.get('/ajax/liquids')
          .then(res => {
            this.loading = false;
            if (res.status === 200) {
              this.items = res.data;
            }
          })
          .catch(err => {
            this.loading = false;
            toastr.error('Failed to load liquids');
            console.error(new Error(err));
          });
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