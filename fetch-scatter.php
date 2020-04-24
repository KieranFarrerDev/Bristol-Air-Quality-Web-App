<?php
//fetch-scatter.php uses AJAX to grab xml file for Wells Road Station: 270
//passing each months average reading for n0 as Json data
ini_set('memory_limit', '512M');

$reader = new XMLReader();
$var = array();

$xml = file_get_contents("data_270.xml");

$array = simplexml_load_string($xml);
$array = json_decode(json_encode($array) , true);

#creating variables for the sum and counter of each month
for($i = 1; $i <= 12; $i++) {
         ${"sum$i"} = 0;
         ${"counter$i"} = 0;
        }

$count = 0;

#for loop to read each record in the xml file
for ($i = 0;$i < count($array['rec']);$i++)
{

    #pointing to each readings date
    $date = $array['rec'][$i]['@attributes']['ts'];
    #pointing to each readings n0 value
    $n0 = $array['rec'][$i]['@attributes']['n0'];




    $time = date('H', $date);
    $year = date('y', $date);
    $month = [];

    #filters results to those in 2018 & at 18:00 hours
    if ($year == '18' && $time == "18")
    {
        $count++;
        $month = date('m', $date);
    }

    #creating if conditions for each month to get total for n0 and counter for number of readings
    for($j = 1; $j <= 12; $j++) {
             if ($month == $j){
               ${"sum$j"} += $n0;
               ${"counter$j"}++;
             }
            }

}

#sum of n0 readings per month/number of readings for the month = average for each month
$monthAverageJan = $sum1 / $counter1;
$monthAverageFeb = $sum2 / $counter2;
$monthAverageMar = $sum3 / $counter3;
$monthAverageApr = $sum4 / $counter4;
$monthAverageMay = $sum5 / $counter5;
$monthAverageJun = $sum6 / $counter6;
$monthAverageJul = $sum7 / $counter7;
$monthAverageAug = $sum8 / $counter8;
$monthAverageSep = $sum9 / $counter9;
$monthAverageOct = $sum10 / $counter10;
$monthAverageNov = $sum11 / $counter11;
$monthAverageDec = $sum12 / $counter12;

#declaring output array and adding each month
$output = [];
$output += ['JanuaryAverage' => $monthAverageJan];
$output += ['FebruaryAverage' => $monthAverageFeb];
$output += ['MarchAverage' => $monthAverageMar];
$output += ['AprilAverage' => $monthAverageApr];
$output += ['MayAverage' => $monthAverageMay];
$output += ['JuneAverage' => $monthAverageJun];
$output += ['JulyAverage' => $monthAverageJul];
$output += ['AugustAverage' => $monthAverageAug];
$output += ['SeptemberAverage' => $monthAverageSep];
$output += ['OctoberAverage' => $monthAverageOct];
$output += ['NovemberAverage' => $monthAverageNov];
$output += ['DecemberAverage' => $monthAverageDec];

#value passed back
echo json_encode($output);

?>
