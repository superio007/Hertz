<?php
session_start();
$results = $_SESSION['results'] ?? ''; // Safely get session data

if ($results) {
    // Load the XML string into a SimpleXMLElement object
    $xml = simplexml_load_string($results);

    if ($xml !== false) {
        // Register namespaces for XPath queries
        $xml->registerXPathNamespace('ota', 'http://www.opentravel.org/OTA/2003/05');

        // Find and remove all VehAvail elements that don't have Code="CCMR"
        foreach ($xml->xpath('//ota:VehAvail[ota:VehAvailCore/ota:Vehicle/@Code != "CCMR"]') as $remove) {
            unset($remove[0]);
        }

        // Convert the filtered SimpleXMLElement object to JSON
        $json = json_encode($xml);

        if ($json !== false) {
            echo "<pre>";
            var_dump($json);
            echo "</pre>";
        } else {
            echo "Failed to convert XML to JSON.";
        }
    } else {
        echo "Failed to parse XML.";
    }
} else {
    echo "No XML data found in session.";
}
?>
