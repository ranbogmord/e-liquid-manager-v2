const assert = require('assert');
const MixingCalculator = require('../mixing-calculator');
const mc = new MixingCalculator();

describe('MixingCalculator', function () {
  describe('Weight', function () {
    describe('Bases', function () {
      it('1ml of pure PG should weigh 1.038g/ml', function () {
        let w = mc.calculatePgWeight(1);
        assert.equal(w, 1.038);
      });
      it('1ml of pure VG should weigh 1.26g/ml', function () {
        let w = mc.calculateVgWeight(1);
        assert.equal(w, 1.26);
      });
      it('1ml of flavour should weigh 1g/ml by default', function () {
        let w = mc.calculateFlavourWeight(1);
        assert.equal(w, 1);
      });
      it('1ml of flavour with specified gravity of 0.5g/ml should weigh 0.5g/ml', function () {
        let w = mc.calculateFlavourWeight(1, 0.5);
        assert.equal(w, 0.5);
      });
    });
    describe('PG Nicotine', function () {
      /*
        Weight of 100mg in PG: 1.035 grams per ml
        Weight of 60mg in PG: 1.03632 grams per ml
        Weight of 50mg in PG: 1.0366 grams per ml
        Weight of 48mg in PG: 1.036656 grams per ml
        Weight of 36mg in PG: 1.036992 grams per ml
        Weight of 24mg in PG: 1.037328 grams per ml
      */

      it('100mg/ml in PG should weigh 1.035g/ml', function () {
        let weight = mc.calculateNicotineWeightPerMl(100, 100);
        assert.equal(weight.toFixed(3), 1.035);
      });
      it('60mg/ml in PG should weigh 1.03632g/ml', function () {
        let weight = mc.calculateNicotineWeightPerMl(60, 100);
        assert.equal(weight.toFixed(6), 1.03632);
      });
      it('50mg/ml in PG should weigh 1.0366g/ml', function () {
        let weight = mc.calculateNicotineWeightPerMl(50, 100);
        assert.equal(weight.toFixed(4), 1.0366);
      });
      it('48mg/ml in PG should weigh 1.036656', function () {
        let weight = mc.calculateNicotineWeightPerMl(48, 100);
        assert.equal(weight.toFixed(6), 1.036656);
      });
      it('36mg/ml in PG should weigh 1.036992g/ml', function () {
        let weight = mc.calculateNicotineWeightPerMl(36, 100);
        assert.equal(weight.toFixed(6), 1.036992);
      });
      it('24mg/ml in PG should weigh 1.037328g/ml', function () {
        let weight = mc.calculateNicotineWeightPerMl(24, 100);
        assert.equal(weight.toFixed(6), 1.037328);
      });
    });

    describe('VG Nicotine', function () {
      /*
        Weight of 100mg in VG: 1.235 grams per ml
        Weight of 60mg in VG: 1.245 grams per ml
        Weight of 50mg in VG: 1.2475 grams per ml
        Weight of 48mg in VG: 1.248 grams per ml
        Weight of 36mg in VG: 1.251 grams per ml
        Weight of 24mg in VG: 1.254 grams per ml
      */
      it('100mg/ml in VG should weigh 1.235g/ml', function () {
        let weight = mc.calculateNicotineWeightPerMl(100, 0);
        assert.equal(weight.toFixed(3), 1.235);
      });
      it('60mg/ml in VG should weigh 1.245g/ml', function () {
        let weight = mc.calculateNicotineWeightPerMl(60, 0);
        assert.equal(weight.toFixed(3), 1.245);
      });
      it('50mg/ml in VG should weigh 1.2475g/ml', function () {
        let w = mc.calculateNicotineWeightPerMl(50, 0);
        assert.equal(w.toFixed(4), 1.2475);
      });
      it('48mg/ml in VG should weigh 1.248g/ml', function () {
        let w = mc.calculateNicotineWeightPerMl(48, 0);
        assert.equal(w.toFixed(3), 1.248);
      });
      it('36mg/ml in VG should weigh 1.251g/ml', function () {
        let w = mc.calculateNicotineWeightPerMl(36, 0);
        assert.equal(w.toFixed(3), 1.251);
      });
      it('24mg/ml in VG should weigh 1.254g/ml', function () {
        let w = mc.calculateNicotineWeightPerMl(24, 0);
        assert.equal(w.toFixed(3), 1.254);
      });
    });
  });
});