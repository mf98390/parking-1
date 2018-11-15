<?php
require("phpsqlajax_dbinfo.php");

function parseToXML($htmlStr)
{
$xmlStr=str_replace('<','&lt;',$htmlStr);
$xmlStr=str_replace('>','&gt;',$xmlStr);
$xmlStr=str_replace('"','&quot;',$xmlStr);
$xmlStr=str_replace("'",'&#39;',$xmlStr);
$xmlStr=str_replace("&",'&amp;',$xmlStr);
return $xmlStr;
}

// Opens a connection to a MySQL server
$connection=mysqli_connect ('localhost', $username, $password);

if (!$connection) {
  die('Not connected : ' . mysqli_error($connection));
}

// Set the active MySQL database
mysqli_query($connection,"SET NAMES 'UTF8'");
$db_selected = mysqli_select_db($connection, $database);
if (!$db_selected) {
  die ('Can\'t use db : ' . mysqli_error($connection));
}

// Select all the rows in the markers table
$query = "SELECT * FROM test WHERE 1";
$result = mysqli_query($connection ,$query);
if (!$result) {
  die('Invalid query: ' . mysqli_error($connection));
}

header("Content-type: text/xml");

// Start XML file, echo parent node
echo "<?xml version='1.0' encoding='UTF-8'?>";
echo '<markers>';
$ind=0;
// Iterate through the rows, printing XML nodes for each
while ($row = @mysqli_fetch_assoc($result)){
  // Add to XML document node
  echo '<marker ';
  echo '_id="' . $row['_id'] . '" ';
  echo 'parkName="' . parseToXML($row['parkName']) . '" ';
  echo 'totalSpace="' . parseToXML($row['totalSpace']) . '" ';
  echo 'payGuide="' . parseToXML($row['payGuide']) . '" ';
  echo 'address="' . parseToXML($row['address']) . '" ';
  echo 'lat="' . $row['lat'] . '" ';
  echo 'lng="' . $row['lng'] . '" ';
  echo 'type="' . $row['type'] . '" ';
  echo 'surplusSpace="' . parseToXML($row['surplusSpace']) . '" ';
  echo '/>';
  $ind = $ind + 1;
}

// End XML file
echo '</markers>';

?>