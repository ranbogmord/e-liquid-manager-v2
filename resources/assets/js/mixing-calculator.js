const mixingCalculator = module.exports = {
  getRows() {
    return [
      mixingCalculator.getPgZeroRow(this.liquid),
      mixingCalculator.getVgZeroRow(this.liquid),
      mixingCalculator.getNicRow(this.liquid)
    ].concat(mixingCalculator.calculateFlavourRows(this.liquid.batch_size, this.liquid.flavours))
  },
  getPgZeroRow(liquid) {

    let vol = mixingCalculator.calculateBaseVol(
      liquid.batch_size,
      liquid.target_pg_percentage,
      mixingCalculator.calculateNicotineVol(liquid.base_nic_strength, liquid.target_nic_strength, liquid.batch_size) * (liquid.base_pg_percentage / 100),
      liquid.flavours.filter(x => !x.is_vg)
    );

    return {
      what: 'PG (0mg/ml)',
      ml: vol,
      percent: vol / liquid.batch_size * 100,
      isFlavour: false
    };
  },
  getVgZeroRow(liquid) {
    let vol = mixingCalculator.calculateBaseVol(
      liquid.batch_size,
      liquid.target_vg_percentage,
      mixingCalculator.calculateNicotineVol(liquid.base_nic_strength, liquid.target_nic_strength, liquid.batch_size) * (liquid.base_vg_percentage / 100),
      liquid.flavours.filter(x => x.is_vg)
    );

    return {
      what: 'VG (0mg/ml)',
      ml: vol,
      percent: vol / liquid.batch_size * 100,
      isFlavour: false
    };
  },
  getNicRow(liquid) {
    let vol = mixingCalculator.calculateNicotineVol(liquid.base_nic_strength, liquid.target_nic_strength, liquid.batch_size);
    return {
      what: 'Base nicotine',
      ml: vol,
      percent: vol / liquid.batch_size * 100,
      isFlavour: false
    }
  },
  calculateNicotineVol(baseStrength, targetStrength, batchSize) {
    return targetStrength / baseStrength * batchSize;
  },
  calculateBaseVol(batchSize, targetPercent, nicVol, flavours) {
    const percent = flavours.map(f => {
      return +f.percent;
    })
    .reduce((acc, val) => {
      return acc - val;
    }, targetPercent);

    return (batchSize * (percent / 100)) - nicVol;
  },

  calculateFlavourRows(batchSize, flavours) {
    return flavours.map(f => {
      let name = f.name;
      if (f.vendor) {
        name += " " + f.vendor.abbr;
      }

      return {
        what: name,
        ml: batchSize * f.percent / 100,
        percent: +f.percent,
        isFlavour: true
      };
    });
  }
};