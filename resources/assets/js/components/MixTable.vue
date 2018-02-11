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
                <th>%</th>
            </tr>

            <tr v-for="row in rows">
                <td>{{ row.what }}</td>
                <td>{{ row.ml | rounded }}</td>
                <td>{{ row.percent | rounded }}</td>
            </tr>

            <tr class="footer">
                <th>
                    Total
                </th>
                <th>
                    {{ total_ml }} / {{ total_flavour_ml }}
                </th>
                <th>
                    {{ total_percent }} / {{ total_flavour_percent }}
                </th>
            </tr>
        </table>
    </div>
</template>

<script>
    const mixCalc = require('../mixing-calculator');
    const bus = require('../bus');

    export default {
      props: ["liquid"],
      methods: {
        openConcentrateModal() {
          bus.$emit('concentrate-modal:open');
        }
      },
      computed: {
        rows: mixCalc.getRows,
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
        }
      },
      filters: {
        rounded(val) {
          return Math.round(val * 100) / 100;
        }
      }
    }
</script>