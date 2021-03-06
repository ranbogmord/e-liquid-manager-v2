<template>
    <div id="mixing-table">
        <h2>Mixing time</h2>

        <a href="#" v-if="liquid.flavours.length > 0" @click.prevent="openConcentrateModal">Make concentrate</a>

        <table>
            <colgroup>
                <col style="width: 60%;">
                <col style="width: 20%;">
                <col style="width: 20%;">
            </colgroup>
            <tr class="header">
                <th>What</th>
                <th>ml</th>
                <th>grams</th>
                <th>%</th>
            </tr>

            <tr v-for="row in rows">
                <td>{{ row.what }}</td>
                <td>{{ row.ml | rounded }} ml</td>
                <td>{{ row.weight | rounded }} g</td>
                <td>{{ row.percent | rounded }} %</td>
            </tr>

            <tr class="footer">
                <th>
                    Total
                </th>
                <th>
                    {{ total_ml | rounded }} / {{ total_flavour_ml | rounded }}
                </th>
                <th>
                    {{ total_weight | rounded }} / {{ total_flavour_weight | rounded }}
                </th>
                <th>
                    {{ total_percent | rounded }} / {{ total_flavour_percent | rounded }}
                </th>
            </tr>
        </table>
    </div>
</template>

<script>
    const MixingCalculator = require('../mixing-calculator');
    const bus = require('../bus');

    export default {
      props: ["liquid"],
      data() {
        return {
          mixCalc: new MixingCalculator(this.liquid)
        };
      },
      methods: {
        openConcentrateModal() {
          bus.$emit('concentrate-modal:open');
        }
      },
      watch: {
        liquid(newLiquid) {
          this.mixCalc = new MixingCalculator(newLiquid);
        }
      },
      computed: {
        rows() {
          return this.mixCalc.getRows();
        },
        total_ml() {
          return this.rows.map(r => {
            return +r.ml;
          })
          .reduce((acc, val) => {
            return acc + val;
          }, 0);
        },
        total_flavour_ml() {
          return this.rows.filter(r => {
            return r.isFlavour;
          })
          .map(r => {
            return +r.ml;
          })
          .reduce((acc, val) => {
            return acc + val;
          }, 0);
        },
        total_percent() {
          return this.rows.map(r => {
            return +r.percent;
          })
          .reduce((acc, val) => {
            return acc + val;
          }, 0);
        },
        total_flavour_percent() {
          return this.rows.filter(r => {
            return r.isFlavour;
          })
          .map(r => {
            return +r.percent;
          })
          .reduce((acc, val) => {
            return acc + val;
          }, 0);
        },
        total_weight() {
          return this.rows.map(r => {
            return +r.weight;
          })
          .reduce((acc, val) => {
            return acc + val;
          }, 0);
        },
        total_flavour_weight() {
          return this.rows.filter(r => {
            return r.isFlavour;
          })
          .map(r => {
            return +r.weight;
          })
          .reduce((acc, val) => {
            return acc + val;
          }, 0);
        },
      },
      filters: {
        rounded(val) {
          return Math.round(val * 100) / 100;
        }
      }
    }
</script>