const $ = require('jquery');
const dt = require('datatables.net');

$('.datatable').DataTable();

$(document).on('click', '#mobile-menu-toggle', function (e) {
  e.preventDefault();

  $(this).toggleClass("open");
});
