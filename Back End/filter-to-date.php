<?php

 $xml = file_get_contents('data_228.xml');
 $array = simplexml_load_string($xml);
 $array = json_decode(json_encode($array), true);

 # unix time code for 1/1/15
 $unixDateFilterFrom = 1420070400;
 # unix time code for 31/12/15
 $unixDateFilterTo = 1577750400;

 for ($i = 0; $i < count($array['rec']) ; $i++) {
    $date =  $array['rec'][$i]['@attributes']['ts'];
    $n0 = $array['rec'][$i]['@attributes']['n0'];
    $currentDate['date'] = date('d/m/y',$date);
    $currentTime = date('H:i:s',$date);

    $current =  $currentDate;
    $current += ['time' => $currentTime];
    $current += ['n0' => $n0];
    print_r($current);


 }

?>
