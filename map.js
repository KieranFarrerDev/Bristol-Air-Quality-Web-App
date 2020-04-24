//call getMapData when page loads
google.charts.setOnLoadCallback(getMapData);

//jQuery arguments, hide divs
$('#map').hide();
$('#legend').hide();
$('#circleLegend').hide();
$('#timeDropMap').hide();
$('#loadDataForYear').hide();
$('#pollutantDropMap').hide();
$('#yearDrop').hide();

//jQuery argument for year drop down menu change
$('#yearDropMap').change(function () {
  $('#legend').hide();
  $('#circleLegend').hide();
  $('#map').hide();
  getMapData();
});

//re-draw the map with new elements when pollutant is changed
$('#pollutantDropMap').change(function () {
  drawMap();
});

//re-draw the map with new elements when time is changed
$('#timeDropMap').change(function () {
  drawMap();
});


 // map data object stored in global to sve results from JSON response and pass back into draw map
 //https://stackoverflow.com/questions/7032424/what-is-the-best-way-to-store-a-value-for-use-in-a-later-function-im-hearing
 var MapData = {};

 function getMapData(){
   var year =$( "#yearDropMap" ).val();
 $.ajax({
     url:"fetch-map-data.php",
     method:"POST",
     data:{
       yearSelect:year,
     },
     dataType:"JSON",
     beforeSend: function(){
    // Show image container
    $("#mapLoader").show();
  },
     success:function(data)
     {

    $("#mapLoader").hide();
    $('#timeDropMap').show();
    $('#loadDataForYear').show();
    $('#pollutantDropMap').show();
    MapData = data;
    drawMap();
 }
    });
 }



function drawMap(){
 $('#map').show();
 $('#yearDrop').show();
 $('#legend').show();
 $('#circleLegend').show();
  var map;
  var BRISTOL_BOUNDS = {
   north: 51.5200,
   south: 51.4000,
   west: -2.75,
   east: -2.45,
  };

  var BRISTOL = {lat:51.4545, lng:-2.5879};

    map = new google.maps.Map(document.getElementById('map'), {
      disableDefaultUI: false,
      mapTypeControl: false,
      center: BRISTOL,
      restriction: {
        latLngBounds: BRISTOL_BOUNDS,
        strictBounds: false,
      },
      zoom: 12,
      streetViewControl: false,
      mapTypeId: google.maps.MapTypeId.ROAD,
      styles: [{
           featureType: 'all',
           elementType: "",
           stylers: [{
               hue: ""
           }, {
               saturation: -90
           }, {
               lightness: ""
           }, {
               gamma: ""
           }]
       }, {
           featureType: 'poi.park',
           elementType: "labels",
           stylers: [{
               visibility: "off"
           }]
       }]
    });

drawMarkers(map);
}


function drawMarkers(map){

  var color;
  var poll = $( "#pollutantDropMap" ).val();
  var time = $( "#timeDropMap" ).val();

  if (time !== '24Hour'){
  var timePointerN0= 'YearAverageN0At'+time;
  var timePointerN02= 'YearAverageN02At'+time;
  var timePointerN0x= 'YearAverageN0xAt'+time;
  } else{
    var timePointerN0= 'YearAverageN0';
    var timePointerN02= 'YearAverageN02';
    var timePointerN0x= 'YearAverageN0x';
  }


      for (var station in MapData) {

        //colour of circle  will change dependig on value compared to DEFRA ozone Index https://uk-air.defra.gov.uk/air-pollution/daqi?view=more-info&pollutant=ozone#pollutant
        if (MapData[station][timePointerN0] + MapData[station][timePointerN02] + MapData[station][timePointerN0x] <= 33){
          color = '#7CFC00';
        }
        if (MapData[station][timePointerN0] + MapData[station][timePointerN02] + MapData[station][timePointerN0x] >= 34){
          color = '#228B22';
        }
        if (MapData[station][timePointerN0] + MapData[station][timePointerN02] + MapData[station][timePointerN0x] >= 67){
          color = '#006400';
        }
        if (MapData[station][timePointerN0] + MapData[station][timePointerN02] + MapData[station][timePointerN0x] >= 101){
          color = '#FFFF00';
        }
        if (MapData[station][timePointerN0] + MapData[station][timePointerN02] + MapData[station][timePointerN0x]  >= 121){
          color = '#999900';
        }
        if (MapData[station][timePointerN0] + MapData[station][timePointerN02] + MapData[station][timePointerN0x] >= 141){
          color = '#FFA500';
        }
        if (MapData[station][timePointerN0] + MapData[station][timePointerN02] + MapData[station][timePointerN0x] >= 161){
          color = '#F08080';
        }
        if (MapData[station][timePointerN0] + MapData[station][timePointerN02] + MapData[station][timePointerN0x] >= 188){
          color = '#FF0000';
        }
        if (MapData[station][timePointerN0] + MapData[station][timePointerN02] + MapData[station][timePointerN0x] >= 214){
          color = '#800000';
        }
        if (MapData[station][timePointerN0] + MapData[station][timePointerN02] + MapData[station][timePointerN0x]  >= 241){
          color = '#EE82EE';
        }


        var marker = new google.maps.Marker({
                      position: MapData[station].center,
                      map: map,
                      title: station
                      });




  if (poll == 'n0'){
                $('#check').hide();
                $('#legend').hide();
                var stationCircleN0 = new google.maps.Circle({
                  strokeColor: '#000000',
                  strokeOpacity: 0.5,
                  strokeWeight: 1,
                  fillColor: '#000000',
                  fillOpacity: 0.2,
                  map: map,
                  center: MapData[station].center,
                  radius: Math.sqrt(MapData[station][timePointerN0]) * 50
                });
  }

  if (poll == 'n02'){
                $('#check').hide();
                $('#legend').hide();
                var stationCircleN02 = new google.maps.Circle({
                  strokeColor: '#000000',
                  strokeOpacity: 0.5,
                  strokeWeight: 1,
                  fillColor: '#000000',
                  fillOpacity: 0.2,
                  map: map,
                  center: MapData[station].center,
                  radius: Math.sqrt(MapData[station][timePointerN02]) * 50
                });
  }
  if (poll == 'n0x'){
                $('#check').hide();
                $('#legend').hide();
                var stationCircleN0x = new google.maps.Circle({
                  strokeColor:'#000000',
                  strokeOpacity: 0.5,
                  strokeWeight: 1,
                  fillColor: '#000000',
                  fillOpacity: 0.2,
                  map: map,
                  center: MapData[station].center,
                  radius: Math.sqrt(MapData[station][timePointerN0x]) * 50
                });
  }

  if (poll == 'all'){
                $('#legend').show();
                var stationCircleN0x = new google.maps.Circle({
                  strokeColor: color,
                  strokeOpacity: 0.9,
                  strokeWeight: 1,
                  fillColor: color,
                  fillOpacity: 0.6,
                  map: map,
                  center: MapData[station].center,
                  radius: Math.sqrt(MapData[station][timePointerN0x]+MapData[station][timePointerN0]+MapData[station][timePointerN0x]) * 50
                });
  }

  }

}
