<template>
    <div id="liquid-list" class="sidebar-list">
        <h3>Liquids</h3>
        <div class="search-field">
            <input type="text" placeholder="Search" v-model="searchString">
        </div>
        <div class="params">
            <label>
                Sort by: <br>
                <select v-model="params.sort">
                    <option value="name">Name</option>
                    <option value="latest">Latest</option>
                    <option value="oldest">Oldest</option>
                </select>
            </label>
        </div>
        <ul>
            <li v-for="item in filtered" @click.prevent="setLiquid(item)">
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
          params: {
            sort: localStorage.getItem('default-liquid-sort') || 'name',
            order: 'asc',
            showArchived: false
          }
        };
      },
      mounted() {
        this.getLiquids();

        bus.$on('liquid:archived', () => {
          this.getLiquids();
        });

        bus.$on('liquid:created', () => {
          this.getLiquids();
        });
      },
      methods: {
        setLiquid(liquid) {
          bus.$emit('liquid:set', liquid);
        },
        getLiquids() {
          this.loading = true;

          let params = {};
          if (this.params.sort === "latest") {
            params.sort = "created_at";
            params.order = "desc";
          } else if (this.params.sort === "oldest") {
            params.sort = "created_at";
            params.order = "asc";
          } else {
            params.sort = "name";
            params.order = "asc";
          }

          axios.get('/ajax/liquids', {
            params: params
          })
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
        }
      },
      watch: {
        params: {
          handler() {
            this.getLiquids();
          },
          deep: true
        },
        'params.sort'(val) {
          localStorage.setItem('default-liquid-sort', val);
        }
      }
    }
</script>