const initAdmin = () => {
  require('./bootstrap');
  const dt = require('datatables.net');
  require('d3');
  const c3 = require('c3');

  !function () {
    $('.datatable').DataTable({
      "lengthMenu": [[10, 25, 100, -1], [10, 25, 100, 'All']],
      stateSave: true
    });

    $(document).on('click', '#mobile-menu-toggle', function (e) {
      e.preventDefault();

      $(this).toggleClass("open");
    });

    const liquidChart = $("#liquids-per-day");
    if (liquidChart.length) {
      axios.get('/admin/statistics/liquids-per-day')
      .then(res => {
        let chart = c3.generate({
          bindto: "#liquids-per-day",
          data: {
            json: res.data,
            keys: {
              x: 'date',
              value: ['count']
            },
            names: {
              count: 'Count'
            }
          },
          axis: {
            x: {
              type: 'timeseries',
              tick: {
                format: '%Y-%m-%d',
                centered: true
              }
            }
          }
        })
      });
    }

    const flavourChart = $("#flavour-counts");
    if (flavourChart.length) {
      axios.get('/admin/statistics/flavours')
      .then(res => {
        console.log(res.data);
        let chart = c3.generate({
          bindto: "#flavour-counts",
          data: {
            json: res.data,
            keys: {
              x: 'name',
              value: ['count']
            },
            type: 'bar',
            names: {
              count: 'Count'
            }
          },
          axis: {
            x: {
              type: 'category',
              tick: {
                centered: true
              }
            }
          }
        })
      });
    }
  }();
};

if (window.Raven) {
  Raven.config('https://4f2d6206ed4e4e4a90205805a07072b8@sentry.io/304736').install();
  Raven.context(() => {
    initAdmin();
  });
} else {
  initAdmin();
}
