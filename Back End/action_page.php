<?php

date_default_timezone_set("GMT");
# fetch.php uses AJAX to grab the xml file specified from dropdown menu
ini_set('memory_limit', '512M');

if(isset($_POST["stationSelect"])){


 $reader = new XMLReader();
 $var = array();

 $xml = file_get_contents("data_".$_POST["stationSelect"].".xml");

 $array = simplexml_load_string($xml);
 $array = json_decode(json_encode($array), true);


# adding day to the date passed in to create a 24hour Period
# https://stackoverflow.com/questions/42434985/add-days-in-format-d-m-y
$dateSelected = $_POST["dateSelect"];
$dateSelected = str_replace("/","-",$dateSelected);
$datePlus24Hour = date("d/m/Y", strtotime(date('d-m-Y', strtotime(' + 1 days', strtotime($dateSelected)))));

# timestamp conversions for data array comparison
$startTimestamp = strtotime(str_replace("/", "-", $dateSelected));
$endTimestamp = strtotime(str_replace("/", "-", $datePlus24Hour));



for ($i = 0; $i < count($array['rec']) ; $i++) {

  # need to sort into order to pass
  $date =  $array['rec'][$i]['@attributes']['ts'];
  $n0 = $array['rec'][$i]['@attributes']['n0'];
  $n0x = $array['rec'][$i]['@attributes']['n0x'];
  $n02 = $array['rec'][$i]['@attributes']['n02'];
  $stationName = $array['@attributes']['name'];

  # filters results to those in a 24hour period from $dateSelected
  if ($date >= $startTimestamp && $date <= $endTimestamp) {


     $recordsYear['year'] = date('Y',$date);
     $recordsMonth =  date('m',$date);
     $recordsDay = date('d',$date);
     $recordsHour =  date('H',$date);
     $recordsMinute = date('i',$date);
     $recordSeconds = date('s',$date);
     $current = $recordsYear;


    $current += ['Month' => $recordsMonth];
    $current += ['Day' => $recordsDay];
    $current += ['Hour' => $recordsHour];
    $current += ['Minute' => $recordsMinute];
    $current += ['Seconds' => $recordSeconds];
    $current += ['n0' => $n0];
    $current += ['n0x' => $n0x];
    $current += ['n02' => $n02];
    $current += ['StationName' => $stationName];

     # output to be converted into json
     $output[] = array('stationData' => $current);

     # puts array in ascending order based on date/Hour of record
     sort($output);


     }
   }


   echo json_encode($output);

 }

?>
