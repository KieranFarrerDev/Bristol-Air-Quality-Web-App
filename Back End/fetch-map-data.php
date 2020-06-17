<?php

date_default_timezone_set("GMT");

ini_set('memory_limit', '512M');

define('location', array(
    203 => 'Brislington Depot',
    206 => 'Rupert Street',
    215 => 'Parson Street School',
    270 => 'Wells Road',
    375 => 'Newfoundland Road Police Station',
    452 => 'AURN St Pauls',
    463 => 'Fishponds Road',
    500 => 'Temple Way',
    501 => 'Colston Avenue'
));

$siteId = array_keys(location);


foreach ($siteId as $key => $val)
{


    $xml=simplexml_load_file('data_'.$siteId[$key].'.xml');
    $array = $xml->xpath("/station/rec");



    # creating variables for the sum of the pollutant readings and number of records each month has
    # and also creating sum and counter varables for certain times within each month: 00:00, 07:00, 14:00, 21:00
    for($i = 1; $i <= 12; $i++) {
      ${"n0Sum$i"} = 0;
      ${"n02Sum$i"} = 0;
      ${"n0xSum$i"} = 0;
      ${"counter$i"} = 0;
    for($j = 0; $j < 24; $j+=7){
      ${"n0Sum$i"."At$j"} = 0;
      ${"n0xSum$i"."At$j"} = 0;
      ${"n02Sum$i"."At$j"} = 0;
      ${"counter$i"."At$j"} = 0;
    }
  }

# $count used to count number of records for the year
$count = 0;

    # for loop to read each record in the xml file
    for ($i = 0;$i < count($array);$i++)
    {

        # pointing to readings timestamp
        $date = intval($array[$i]['ts']);

        # timestamp is converted into date format
        $year = date('y', $date);
        $month = [];
        $time =[];

        # if ($year == $_POST["yearSelect"])
      if ($year == $_POST["yearSelect"])
      {
            $month = date('m', $date);
            $time = date('H', $date);
            #pointing to each readings n0,n02,n0x value


            $n0 = $array[$i]['n0'];
            $n0x = $array[$i]['n0x'];
            $n02 = $array[$i]['n02'];
            $count++;
        }

  # creating if conditions for each month to get total for n0 and counter for number of readings
  for($j = 1; $j <= 12; $j++) {
      if ($month == $j){
         ${"n0Sum$j"} += $n0;
         ${"n0xSum$j"} += $n0x;
         ${"n02Sum$j"} += $n02;
         ${"counter$j"}++;
    for($k = 0; $k < 24; $k+=7){
      if ($time == $k){
        ${"n0Sum$j"."At$k"} += $n0;
        ${"n0xSum$j"."At$k"} += $n0x;
        ${"n02Sum$j"."At$k"} += $n02;
        ${"counter$j"."At$k"}++;
        }

      }

    }

  }



}


$n0YearTotal = 0;
$n02YearTotal = 0;
$n0xYearTotal = 0;

# creating variables for year totals at certain times, e.g. n0YearTotalAt0, n0YearTotalAt7
for ($i = 0; $i < 24; $i+=7){
  ${"n0YearTotalAt$i"} = 0;
  ${"n0xYearTotalAt$i"} = 0;
  ${"n02YearTotalAt$i"} = 0;
  ${"n0YearAverageAt$i"} = 0;
  ${"n0xYearAverageAt$i"} = 0;
  ${"n02YearAverageAt$i"} = 0;
}

    for($i = 1; $i <= 12; $i++) {
      if(${"n0Sum$i"} != 0){
      ${"month$i"} = (${"n0Sum$i"}/${"counter$i"});
      $n0YearTotal += ${"month$i"};
      
      # works out the average reading at each month for each time
      for($j=0; $j <24; $j+=7){
        ${"time$i"} = (${"n0Sum$i"."At$j"}/${"counter$i"."At$j"});
        ${"n0YearTotalAt$j"} += ${"time$i"};
    }
   }
 }


 for($i = 1; $i <= 12; $i++) {
   if(${"n02Sum$i"} != 0){
   ${"month$i"} = (${"n02Sum$i"}/${"counter$i"});
   $n02YearTotal += ${"month$i"};
   
   # works out the average reading at each month for each time
   for($j=0; $j <24; $j+=7){
     ${"time$i"} = (${"n02Sum$i"."At$j"}/${"counter$i"."At$j"});
     ${"n02YearTotalAt$j"} += ${"time$i"};
 }
}
}



for($i = 1; $i <= 12; $i++) {
  if(${"n0xSum$i"} != 0){
  ${"month$i"} = (${"n0xSum$i"}/${"counter$i"});
  $n0xYearTotal += ${"month$i"};
  
  # works out the average reading at each month for each time
  for($j=0; $j <24; $j+=7){
    ${"time$i"} = (${"n0xSum$i"."At$j"}/${"counter$i"."At$j"});
    ${"n0xYearTotalAt$j"} += ${"time$i"};
}
}
}

$n0Year = $n0YearTotal/12;
$n02Year = $n02YearTotal/12;
$n0xYear = $n0xYearTotal/12;

for ($i=0; $i < 24; $i+=7){
  ${"n0YearAverageAt$i"} = ${"n0YearTotalAt$i"}/12;
    ${"n02YearAverageAt$i"} = ${"n02YearTotalAt$i"}/12;
      ${"n0xYearAverageAt$i"} = ${"n0xYearTotalAt$i"}/12;
}


$name = implode($xml->xpath("/station/@name"));

# seperate latitude and longitude values
$loc = implode($xml->xpath("/station/@geocode"));

$latLng = explode(',', $loc);
$lat=$latLng['0'];
$lng=$latLng['1'];

$current = ['lat' => (float) $lat];
$current += ['lng' => (float) $lng];
$current2 = ['YearAverageN0' => $n0Year];
$current2 += ['YearAverageN02' => $n02Year];
$current2 += ['YearAverageN0x' => $n0xYear];

for ($i=0; $i < 24; $i+=7){
  $current2 += ['YearAverageN0At'.$i => ${"n0YearAverageAt$i"}];
    $current2 += ['YearAverageN02At'.$i => ${"n02YearAverageAt$i"}];
      $current2 += ['YearAverageN0xAt'.$i => ${"n0xYearAverageAt$i"}];
}

$output[$name] = array('center' => $current) + $current2;



}

echo json_encode($output);

?>
