<template>
    <div id="version-row">
        <h2>This is an older version of:</h2>
        <select v-model="liquid.next_version_id">
            <option :value="null">Select newer liquid</option>
            <option :value="l.id" v-for="l in options">{{ l.name }}</option>
        </select>
    </div>
</template>

<script>
  export default {
    props: ["liquid"],
    data() {
      return {
        liquids: []
      };
    },
    mounted() {
      this.fetchLiquids();
    },
    methods: {
      fetchLiquids() {
        axios.get('/ajax/liquids')
        .then(res => {
          if (res.status === 200) {
            this.liquids = res.data;
          }
        });
      }
    },
    computed: {
      options() {
        return this.liquids.filter(l => {
          return l.id !== this.liquid.id;
        });
      }
    }
  }
</script>
