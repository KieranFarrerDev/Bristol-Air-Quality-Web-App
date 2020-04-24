
<?php
date_default_timezone_set("GMT");
#fetch-stations.php grabs and outputs dates with records for calendar
ini_set('memory_limit', '512M');

if(isset($_POST["stationSelect"])){


 $reader = new XMLReader();


 $xml = file_get_contents("data_".$_POST["stationSelect"].".xml");
//$xml = file_get_contents("data_501.xml");

$array = simplexml_load_string($xml);
$array = json_decode(json_encode($array), true);

#unix time code for 1/1/15 - https://www.epochconverter.com
$unixDateFilterFrom = 1420070400;
#unix time code for 31/12/19 - https://www.epochconverter.com
$unixDateFilterTo = 1577750400;



for ($i = 0; $i < count($array['rec']) ; $i++) {

  $date =  $array['rec'][$i]['@attributes']['ts'];

   #filters results to those between 01/01/15 & 31/12/19
   if ($date > $unixDateFilterFrom && $date < $unixDateFilterTo) {
     $currentDate = date('j/n/Y',$date);
     $current = $currentDate;
     #output to be converted into json
     $output[] = $current;
     }
   }
 }

echo json_encode($output);

?>
