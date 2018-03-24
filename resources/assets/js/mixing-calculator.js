class MixingCaluclator {
  constructor(liquid) {
    this.liquid = liquid;
  }

  getRows() {
    return [
      this.getPgZeroRow(this.liquid),
      this.getVgZeroRow(this.liquid),
      this.getNicRow(this.liquid)
    ].concat(this.calculateFlavourRows(this.liquid.batch_size, this.liquid.flavours)).filter(x => !!x.ml);
  }

  getPgZeroRow(liquid) {
    let vol = this.calculateBaseVol(
      liquid.batch_size,
      liquid.target_pg_percentage,
      this.calculateNicotineVol(liquid.base_nic_strength, liquid.target_nic_strength, liquid.batch_size) * (liquid.base_pg_percentage / 100),
      liquid.flavours.filter(x => !x.is_vg)
    );

    return {
      what: 'PG (0mg/ml)',
      ml: vol,
      weight: this.calculatePgWeight(vol),
      percent: vol / liquid.batch_size * 100,
      isFlavour: false
    };
  }

  getVgZeroRow(liquid) {
    let vol = this.calculateBaseVol(
      liquid.batch_size,
      liquid.target_vg_percentage,
      this.calculateNicotineVol(liquid.base_nic_strength, liquid.target_nic_strength, liquid.batch_size) * (liquid.base_vg_percentage / 100),
      liquid.flavours.filter(x => x.is_vg)
    );

    return {
      what: 'VG (0mg/ml)',
      ml: vol,
      weight: this.calculateVgWeight(vol),
      percent: vol / liquid.batch_size * 100,
      isFlavour: false
    };
  }

  getNicRow(liquid) {
    let what = "Base nicotine";
    let percent = 0;
    let ml = 0;
    let isFlavour = false;

    if (liquid.base_nic_strength > 0) {
      ml = this.calculateNicotineVol(liquid.base_nic_strength, liquid.target_nic_strength, liquid.batch_size);
      percent = ml / liquid.batch_size * 100;
    }

    let weight = this.calculateNicotineWeight(ml, liquid.base_nic_strength, liquid.base_pg_percentage);

    return {
      what,
      ml,
      weight,
      percent,
      isFlavour
    };
  }

  calculateNicotineVol(baseStrength, targetStrength, batchSize) {
    if (+baseStrength === 0 || +targetStrength === 0) {
      return 0;
    }

    return targetStrength / baseStrength * batchSize;
  }

  calculateBaseVol(batchSize, targetPercent, nicVol, flavours) {
    const percent = flavours.map(f => {
      return +f.percent;
    })
    .reduce((acc, val) => {
      return acc - val;
    }, targetPercent);

    return (batchSize * (percent / 100)) - nicVol;
  }

  calculateFlavourRows(batchSize, flavours) {
    return flavours.map(f => {
      let name = f.name;
      if (f.vendor) {
        name += " " + f.vendor.abbr;
      }

      let vol = batchSize * f.percent / 100;
      return {
        what: name,
        ml: vol,
        weight: this.calculateFlavourWeight(vol),
        percent: +f.percent,
        isFlavour: true
      };
    });
  }

  calculatePgWeight(pgVol) {
    return +pgVol * 1.038;
  }

  calculateVgWeight(vgVol) {
    return +vgVol * 1.26;
  }

  calculateNicotineWeightPerMl(nicStrength, pgPercent) {
    const nicPerc = (+nicStrength / 10);
    const nicWeight = nicPerc * 1.01;
    const pureBase = 100 - nicPerc;
    const pgBaseWeight = this.calculatePgWeight(pureBase * (+pgPercent / 100));
    const vgBaseWeight = this.calculateVgWeight(pureBase * ((100 - +pgPercent) / 100));

    const nicAndBaseWeight = nicWeight + pgBaseWeight + vgBaseWeight;

    return nicAndBaseWeight / 100;
  }

  calculateNicotineWeight(nicVol, nicStrength, pgPercent) {
    return this.calculateNicotineWeightPerMl(nicStrength, pgPercent) * nicVol;
  }

  calculateFlavourWeight(flavourVol, weightPerMl=1) {
    return +flavourVol * weightPerMl;
  }
}

module.exports = MixingCaluclator;
