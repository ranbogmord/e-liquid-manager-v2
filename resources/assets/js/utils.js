const utils = module.exports = {
  extractErrors(data) {
    const errors = [];
    Object.keys(data).forEach(item => {
      errors.push(data[item]);
    });

    return errors;
  }
};