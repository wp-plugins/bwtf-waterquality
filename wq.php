<?php
# For CLI Testing- commented out for production
#
#parse_str(implode('&', array_slice($argv, 1)), $_GET);

function objectToArray($d) {
	if (is_object($d)) {
		// Gets the properties of the given object
		// with get_object_vars function
		$d = get_object_vars($d);
	}

	if (is_array($d)) {
		/*
		* Return array converted to object
		* Using __FUNCTION__ (Magic constant)
		* for recursive call
		*/
		return array_map(__FUNCTION__, $d);
	}
	else {
		// Return array
		return $d;
	}
}


$site = $_GET['site'];
$jsonURL = "http://www.surfrider.org/bwtf/site/getByID/" . $site;
$jsonData = file_get_contents($jsonURL);
$jsonDecoded = json_decode($jsonData);

#For Testing JSON output - commented out for production
#
#var_dump($jsonDecoded);

$jsonDecoded = objectToArray($jsonDecoded);

if ((string)$jsonDecoded["lab"]["primaryTestType"] == 'Entero') {

	if ((string)$jsonDecoded["samples"][0]["enterobacteria"] < '36') {
		$baclevel = '<div style="margin-left:90px; margin-right:auto; padding:10px; background-color:#6DCA21">Low Bacteria</div>';
	} elseif ((string)$jsonDecoded["samples"][0]["enterobacteria"] < '105') {
		$baclevel = '<div style="margin-left:90px; margin-right:auto; padding:10px; background-color:#F7CA1F">Medium Bacteria</div>';
	} else {
		$baclevel = '<div style="margin-left:10px; margin-right:auto; padding:10px; background-color:#CB391C">High Bacteria</div>';
	}
	$bacnumber = (string)$jsonDecoded["samples"][0]["enterobacteria"];

} elseif ((string)$jsonDecoded["lab"]["primaryTestType"] == 'Ecoli') {
	
	if ((string)$jsonDecoded["samples"][0]["ecolbacteria"] < '127') {
                $baclevel = '<div style="margin-left:90px; margin-right:auto; padding:10px; background-color:#6DCA21">Low Bacteria</div>';
        } elseif ((string)$jsonDecoded["samples"][0]["ecolbacteria"] < '236') {
                $baclevel = '<div style="margin-left:90px; margin-right:auto; padding:10px; background-color:#F7CA1F">Medium Bacteria</div>';
        } else { 
                $baclevel = '<div style="margin-left:90px; margin-right:auto; padding:10px; background-color:#CB391C">High Bacteria</div>';
        }
	$bacnumber = (string)$jsonDecoded["samples"][0]["ecolbacteria"];
}

$labID = (string)$jsonDecoded["lab"]["labID"];

echo "Bacteria level for </big><b>" . (string)$jsonDecoded["info"]["siteName"] . ":<br /> " . $baclevel . "</b></big><br /><br />";
echo "<small>Testing provided by ";
if ((string)$jsonDecoded["lab"]["labURL"] != '') {
	echo '<a href="' . (string)$jsonDecoded["lab"]["labURL"] . '" target="_blank">';
}
echo 'Surfrider\'s ' . (string)$jsonDecoded["lab"]["labName"] . ' Chapter';
if ((string)$jsonDecoded["lab"]["labURL"] != '') {
	echo '</a>';
}
echo " on " . (string)$jsonDecoded["samples"][0]["date"] . "<br /><br />";
echo '&#8226; <a href="http://www.surfrider.org/blue-water-task-force/beach/' . $site . '" target="_blank">Detailed info about this site (including testing history)</a><br />';
echo '&#8226; <a href="http://www.surfrider.org/blue-water-task-force/chapter/' . $labID . '" target="_blank">Results for all beaches tested by this chapter</a></small>';
# echo "Chapter Website: " . (string)$jsonDecoded["lab"]["labURL"] . "<br />";
# echo "Test Date: " . (string)$jsonDecoded["samples"][0]["date"] . "<br />";
# echo "Bacteria Level: " . $bacnumber . "<br />";
# echo "This test result indicates " . $baclevel . " levels of indicator bacteria in the water.\n";

?>
