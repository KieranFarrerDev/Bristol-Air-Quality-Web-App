<?php
# set timezone
@date_default_timezone_set("GMT");

# set resource requirements
ini_set('memory_limit', '512M');
ini_set('max_execution_time', '120');
ini_set('auto_detect_line_endings', true);

# location constant array
define('location', array(
    188 => 'AURN Bristol Centre',
    203 => 'Brislington Depot',
    206 => 'Rupert Street',
    209 => 'IKEA M32',
    213 => 'Old Market',
    215 => 'Parson Street School',
    228 => 'Temple Meads Station',
    270 => 'Wells Road',
    271 => 'Trailer Portway P&R',
    375 => 'Newfoundland Road Police Station',
    395 => "Shiner's Garage",
    452 => 'AURN St Pauls',
    447 => 'Bath Road',
    459 => 'Cheltenham Road',
    463 => 'Fishponds Road',
    481 => 'Create Centre Roof',
    500 => 'Temple Way',
    501 => 'Colston Avenue'
));

# start the timer
$st = strtotime("now");

# initialise the header
$header = 'siteId,ts,nox,no2,no,pm10,nvpm10,vpm10,nvpm2.5,pm2.5,vpm2.5,co,o3,so2,loc,lat,long' . "\r\n";
$siteId = array_keys(location);

foreach ($siteId as $key => $val)
{
    #name of each file being ammended data_$key.csv
    $fo[$key] = fopen('data_' . $siteId[$key] . '.csv', 'a+');
    #fputs to add header and will eventually add the other bits of data
    fputs($fo[$key], $header);
}

# open the input CSV file
$handle = fopen("air-quality-data-2004-2019.csv", "r") or die("failed to open file");

# initialise line counter & error-lines counter
$ln = 0;
$er = 0;

if ($handle)
{

    # ignores headers
    fgets($handle, 500);

    while (($line = fgets($handle, 500)) !== false)
    {

        $csvSplit = explode(";", $line);

        foreach ($siteId as $key => $val)
        {

            # switch checks siteId array against location array keys
            switch ($csvSplit[4])
            {
                case $siteId[$key]:
                    $fo2 = $fo[$key];
                break;
            }
        }

        # ensure CO2 or CO exist and write out record
        if (!empty($csvSplit[1]) or !empty($csvSplit[11]))
        {
            $r = $csvSplit[4].','.strtotime($csvSplit[0]).','.$csvSplit[1].','.$csvSplit[2].','.
                 $csvSplit[3].','.$csvSplit[4].','.$csvSplit[5].','.$csvSplit[6].','.$csvSplit[7].','.
                 $csvSplit[8] . ',' . $csvSplit[9] . ',' . $csvSplit[10].','.$csvSplit[11].','.
                 $csvSplit[12].','.$csvSplit[17].','.$csvSplit[18]."\r\n";

            fwrite($fo2, $r);
        }
        # else count as rejected line
        else
        {
            $er++;
        }

        # count total lines processed
        $ln++;
    }
}

# stop the counter
$et = strtotime("now");

# report
echo '<p>' . $ln . ' lines processed</p>';
echo '<p>' . $er . ' empty records filtered out</p>';
echo '<p>It took ';
echo $et - $st;
echo ' seconds to process';
?>
