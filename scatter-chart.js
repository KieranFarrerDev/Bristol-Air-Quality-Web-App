
//call drawChart when page loads
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
  $(document).ready(function(){
  $.ajax({ url: "fetch-scatter.php",
          method: "GET",
          dataType:"JSON",
          success: function(data){
            var chartData = google.visualization.arrayToDataTable([
              ['month', '(nO) average reading at 18:00:'],
              ['January',data.JanuaryAverage],
              ['February',data.FebruaryAverage],
              ['March',data.MarchAverage],
              ['April',data.AprilAverage],
              ['May', data.MayAverage],
              ['June',data.JuneAverage],
              ['July',data.JulyAverage],
              ['August',data.AugustAverage],
              ['September',data.JuneAverage],
              ['October',data.JulyAverage],
              ['November',data.AugustAverage],
              ['Decemeber',data.AugustAverage]
            ]);
            var options = {
              title: 'Wells Road, Station: 270',
              colors: ['#8B0000'],
              hAxis: {title: 'Month', slantedText:true, slantedTextAngle:45},
              vAxis: {title: 'Average nO Value (µg/m³)'},
              legend: 'none'
            };
            var chart = new google.visualization.ScatterChart(document.getElementById('scatter_chart_area'));
            chart.draw(chartData, options);
          }});
  });
}
