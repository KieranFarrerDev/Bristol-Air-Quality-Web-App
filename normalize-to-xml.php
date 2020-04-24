<?php
# set timezone
@date_default_timezone_set("GMT");

# set resource requirements
ini_set('memory_limit', '512M');
ini_set('max_execution_time', '120');
ini_set('auto_detect_line_endings', true);

#location constant array
define('location', array(
    188 => array('AURN Bristol Centre' => '51.4572041156,-2.58564914143'),
    203 => array('Brislington Depot' => '51.4417471802,-2.55995583224'),
    206 => array('Rupert Street' => '51.4554331987,-2.59626237324'),
    209 => array('IKEA M32' => '51.4752847609,-2.56207998299'),
    213 => array('Old Market' => '51.4560189999,-2.58348949026'),
    215 => array('Parson Street School' => '51.432675707,-2.60495665673'),
    228 => array('Temple Meads Station' => '51.4488837041,-2.58447776241'),
    270 => array('Wells Road' => '51.4278638883,-2.56374153315'),
    271 => array("Trailer Portway P&R" => '51.4899934596,-2.68877856929'),
    375 => array('Newfoundland Road Police Station' => '51.4606738207,-2.58225341824'),
    395 => array("Shiner's Garage" => '51.4577930324,-2.56271419977'),
    452 => array('AURN St Pauls' => '51.4628294172,-2.58454081635'),
    447 => array('Bath Road' => '51.4425372726,-2.57137536073'),
    459 => array('Cheltenham Road' => '51.4689385901,-2.5927241667'),
    463 => array('Fishponds Road' => '51.4780449714,-2.53523027459'),
    481 => array('Create Centre Roof' => 'N/A'),
    500 => array('Temple Way' => '51.4579497129,-2.58398909033'),
    501 => array('Colston Avenue' => '51.4552693825,-2.59664882861')
));

# start the timer
$st = strtotime("now");
# Get site ID's
$siteId = array_keys(location);

# initialise line counter & error-lines counter
$ln = 0;
$er = 0;

foreach ($siteId as $key => $val)
{
    $siteArray = array_keys(location[$siteId[$key]]);
    $site = location[$siteId[$key]];
    $siteName = $siteArray[0];
    $latLong = ($site[$siteName]);

    #opens each file using location constant array
    $fo[$key] = fopen('data_' . $siteId[$key] . '.csv', 'a+');

    $writer = new XMLWriter();
    $writer->openURI('data_' . $siteId[$key] . '.xml');
    $writer->startDocument("1.0", "UTF-8");
    $writer->startElement('station');
    $writer->writeAttribute('id', $siteId[$key]);
    $writer->writeAttribute('name', $siteName);
    $writer->writeAttribute('geocode', $latLong);

    # ignores headers
    fgets($fo[$key], 500);
    while (($line = fgets($fo[$key], 500)) !== false)
    {
        $csvSplit = explode(",", $line);

        if (empty($csvSplit[2]) && empty($csvSplit[3]) && empty($csvSplit[4]))
        {


          $er++;

        }
        else
        {
          $writer->startElement('rec');
          $writer->writeAttribute('ts', $csvSplit[1]);
          $writer->writeAttribute('n0x', $csvSplit[2]);
          $writer->writeAttribute('n0', $csvSplit[4]);
          $writer->writeAttribute('n02', $csvSplit[3]);
          $writer->endElement();

          $ln++;


        }
    }
    $writer->endElement();
}
$writer->endDocument();

# stop the counter
$et = strtotime("now");

# report
echo '<p>' . $ln . ' lines processed</p>';
echo '<p>' . $er . ' empty records filtered out</p>';
echo '<p>It took ';
echo $et - $st;
echo ' seconds to process';
?>
