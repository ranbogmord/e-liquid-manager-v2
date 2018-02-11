<template>
    <div class="modal-overlay" id="concentrate-modal" v-if="isOpen">
        <div class="modal-container">
            <div class="modal-header"><h2>Concentrate Calculator</h2></div>
            <div class="modal-content">
                <div>
                    <div class="form-field">
                        <label>Amount (ml):<br>
                            <input placeholder="Amount (ml)" class="concentrate-amount" v-model="amount">
                        </label>
                    </div>
                    <table class="concentrate-rows">
                        <tbody>
                        <tr>
                            <th>Flavour</th>
                            <th>ml</th>
                            <th>%</th>
                            <th>Original %</th>
                        </tr>
                        <tr v-for="row in rows">
                            <td>{{ row.name }}</td>
                            <td>{{ row.ml | rounded }}</td>
                            <td>{{ row.newPercent | rounded }}</td>
                            <td>{{ row.percent | rounded }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn primary" @click.prevent="closeModal">Close</button>
            </div>
        </div>
    </div>
</template>

<script>
    const bus = require('../bus');

  export default {
    props: ["liquid", "isOpen"],
    data() {
      return {
        amount: 100
      };
    },
    methods: {
      closeModal() {
        bus.$emit('concentrate-modal:close');
      }
    },
    computed: {
      rows() {
        if (!this.liquid.flavours.length) {
          return [];
        }

        let totalPerc = this.liquid.flavours.map((item) => {
          return +item.percent;
        }).reduce((tot, item) => {
          return tot + item;
        }, 0);

        return this.liquid.flavours.map((item) => {
          let newPerc = totalPerc ? item.percent / totalPerc : 0;
          let name = item.name;
          if (item.vendor) {
            name += " " + item.vendor.abbr;
          }

          return {
            name: name,
            newPercent: newPerc * 100,
            percent: item.percent,
            ml: newPerc * this.amount
          };
        });
      }
    },
    filters: {
      rounded(val) {
        return Math.round(val * 100) / 100;
      }
    }
  }
</script>