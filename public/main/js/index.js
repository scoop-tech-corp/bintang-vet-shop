$(document).ready(function() {

  function loadWidget1() {
    Highcharts.chart('container', {
      chart: {
        type: 'column'
      },
      title: { text: '' },
      xAxis: { 
        categories: ['Apples', 'Bananas', 'Oranges'] 
      },
      yAxis: {
        title: { 
          text: '' 
        }
      },
      series: [{
        name: 'Jane',
        data: [1, 2, 4]
      }, {
        name: 'John',
        data: [5, 7, 3]
      }],
    }); 
  }

});