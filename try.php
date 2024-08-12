<?php
session_start();
$json = $_SESSION['results'];

// Decode the JSON string into a PHP array
$data = json_decode($json, true);

var_dump($data);

foreach($data['']['Info']['LocationDetails']['@attributes'] as $name){
    echo "Location Name: " . $name['name'];
}
// Output the name

?>
{Root}.VehAvailRSCore.VehVendorAvails.VehVendorAvail[1].VehAvails.VehAvail