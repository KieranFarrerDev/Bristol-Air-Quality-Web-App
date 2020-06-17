//jquery arguments
//hides date selection and pollutant selection elements
$('#txtstart_date').hide();
$('#pollutantDrop').hide();
$('#lineLoader').hide();
//when a station is selected reset date and show calendar
$('#stationDrop').change(function () {
$('#line_chart_area').hide();
$('#line_chart_area').empty();
$('#pollutantDrop').hide();
getAvailableDates();
$('#txtstart_date').datepicker('setDate',null);
$('#txtstart_date').show();
});

//when date selected from calendar show pollutants dropdown
$('#txtstart_date').change(function () {
  $('#pollutantDrop').val("");
  $('#pollutantDrop').show();
});

$('#pollutantDrop').change(function () {
  console.log($( "#pollutantDrop" ).val());
  getPollutantReadings();
  $('#line_chart_area').hide();
});





// finds the days that are passed back from the JSON of avilable dates for calanedar
function available(date) {
   dmy = date.getDate() + "/" + (date.getMonth()+1) + "/" + date.getFullYear();
   if ($.inArray(dmy, str) != -1) {
   return [true, "","Available"];
   } else {
    return [false,"","unAvailable"];
   }
}


function getAvailableDates(){
  //changes string value based on dropdown value str= selected string
   var station =$( "#stationDrop" ).val();
   $('#lineLoader').show();
   $.ajax({
       url:"fetch-stations.php",
       method:"POST",
       data:{stationSelect:station},
       dataType:"JSON",
       success:function(data)
       {
         $('#lineLoader').hide();
         $('#line_chart_area').show();
          console.log("successful AJAX call");
          console.log(station);
          //str gets used in available date function to filter dates with records from selected station
          str=data;
          $("#txtstart_date").datepicker
          ({
            changeMonth: true,
            changeYear: true,
            yearRange: "2015:2019",
            dateFormat: 'dd/mm/yy',
            defaultDate: '01/01/2015',
            onClose: function (selectedDate) {
                $("#txtend_date").datepicker("option", "minDate", selectedDate);
            },
            //beforeShowDay enables dates based on selection from dropdown
            beforeShowDay: available
        });
      }
  });
}


function getPollutantReadings(){
//changes string value based on dropdown value str= selected string
var pollutant =$( "#pollutantDrop" ).val();
var station =$( "#stationDrop" ).val();
var date =$( "#txtstart_date" ).val();
$('#lineLoader').show();

$.ajax({
    url:"action_page.php",
    method:"POST",
    data:{
      pollutantSelect:pollutant,
      stationSelect:station,
      dateSelect:date
    },
    dataType:"JSON",
    success:function(data)
    {
      $('#lineLoader').hide();
      $('#line_chart_area').show();
      drawLineChart(pollutant,station,date,data);
     }
   });
 }


function drawLineChart(pollutant,station,date,data){

         var chartData = new google.visualization.DataTable();
         var i= 0;
         chartData.addColumn('date', 'Day');

         if (pollutant == 'n0'){
           chartData.addColumn('number', 'nO reading');
         } else if (pollutant == 'n02') {
           chartData.addColumn('number', 'nO2 reading');
         } else if (pollutant == 'n0x') {
           chartData.addColumn('number', 'nOx reading');
         } else {

            chartData.addColumn('number', 'nO reading');
            chartData.addColumn('number', 'nO2 reading');
            chartData.addColumn('number', 'nOx reading');
         }




         //chartData.addColumn('number', 'The Avengers');
         //chartData.addColumn('number', 'Transformers: Age of Extinction');
        console.log(data);
         for (i = 0; i < data.length; i++){
           var year = parseInt(data[i].stationData.year);
           var month = parseInt(data[i].stationData.Month);
           var day = parseInt(data[i].stationData.Day);
           var hour = parseInt(data[i].stationData.Hour);
           var minute = parseInt(data[i].stationData.Minute);
           var seconds = parseInt(data[i].stationData.Seconds);




         if (pollutant == 'n0'){
         var n0 = parseFloat(data[i].stationData.n0);
         chartData.addRows([
               //mappedToArray[i]['0']
               [new Date (year,month-1,day,hour,minute,seconds), n0]

           ])

       } else if (pollutant == 'n02') {
         var n02 = parseFloat(data[i].stationData.n02);
         chartData.addRows([
               //mappedToArray[i]['0']
               [new Date (year,month-1,day,hour,minute,seconds), n02]

           ])

       } else if (pollutant == 'n0x') {
         var n0x = parseFloat(data[i].stationData.n0x);
         chartData.addRows([
               //mappedToArray[i]['0']
               [new Date (year,month-1,day,hour,minute,seconds), n0x]

           ])

       } else {
         var n0 = parseFloat(data[i].stationData.n0);
         var n02 = parseFloat(data[i].stationData.n02);
         var n0x = parseFloat(data[i].stationData.n0x);
         chartData.addRows([
               //mappedToArray[i]['0']
               [new Date (year,month-1,day,hour,minute,seconds),n0,n02,n0x]

           ])

       }
  }

    var startDay = data['0'].stationData.Day;
    var startMonth = data['0'].stationData.Month;
    var startYear = data['0'].stationData.year;
    var stationName = data['0'].stationData.StationName;
              var options = {
                  title: stationName + ',' + ' ' + 'Station:' + station + ' ' +'(' + '24hr period'+ ' ' + startDay + '/' + startMonth + '/' + startYear + ' ' + '-' + ' ' + day + '/' + month + '/' + year + ')',
                  hAxis: {title: 'Time of reading'},
                  vAxis: {title: 'Value (µg/m³)'}
              };





              var chart = new google.visualization.LineChart(document.getElementById('line_chart_area'));
              chart.draw(chartData, options);
}
