<?php
// API endpoint URL
$apiUrl = 'https://vv.xqual.hertz.com/DirectLinkWEB/handlers/DirectLinkHandler?id=ota2007a'; // Replace with your actual API endpoint
$confId = $_GET['confId'];
$surname = $_GET['surname'];
// XML request data
$xmlRequest = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<OTA_VehRetResRQ xmlns="http://www.opentravel.org/OTA/2003/05" Version="2.007">
    <POS>
        <Source ISOCountry="IN" AgentDutyCode="T17R16L5D11">
            <RequestorID Type="4" ID="X975">
                <CompanyName Code="CP" CodeContext="4PH5"/>
            </RequestorID>
        </Source>
    </POS>
    <VehRetResRQCore>
        <UniqueID Type="14" ID="$confId"/> <!-- Confirmation ID -->
        <PersonName>
            <Surname>$surname</Surname> <!-- Customer's Last Name -->
        </PersonName>
    </VehRetResRQCore>
</OTA_VehRetResRQ>
XML;

// Initialize cURL
$ch = curl_init($apiUrl);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/xml',  // Set the request content type to XML
    'Content-Length: ' . strlen($xmlRequest)
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest);

// Execute the cURL request and capture the response
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
} else {
    // Print the response
    echo 'Response: ' . htmlentities($response);
}

// Close cURL resource
curl_close($ch);
?>
