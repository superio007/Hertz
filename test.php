<?php

function arrayToXml($data, &$xmlData) {
    foreach($data as $key => $value) {
        if (is_array($value)) {
            if (is_numeric($key)) {
                $key = 'item' . $key; // dealing with <0/>..<n/> issues
            }
            $subnode = $xmlData->addChild($key);
            arrayToXml($value, $subnode);
        } else {
            $xmlData->addChild("$key", htmlspecialchars("$value"));
        }
    }
}
$pickUp = "Mel";
$json = [
    "OTA_VehAvailRateRQ"=> [
        "Version"=> "1.008",
        "POS"=> [
            "Source"=> [
                "ISOCountry"=> "AU",
                "AgentDutyCode"=> "T17R16L5D11",
                "RequestorID"=> [
                    "Type"=> "4",
                    "ID"=> "X975",
                    "CompanyName"=> [
                        "Code"=> "CP",
                        "CodeContext"=> "4PH5"
                    ]
                ]
            ]
        ],
        "VehAvailRQCore"=> [
            "Status"=> "All",
            "VehRentalCore"=> [
                "PickUpDateTime"=> "2024-08-16T15:45:00",
                "ReturnDateTime"=> "2024-08-18T13:30:00",
                "PickUpLocation"=> [
                    "LocationCode"=> $pickUp,
                    "Type"=> "IATA"
                ],
                "ReturnLocation"=> [
                    "LocationCode"=> "ADL",
                    "Type"=> "IATA"
                ]
            ]
        ]
    ]
];

// $arrayData = json_decode($json, true);

$xmlData = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><OTA_VehAvailRateRQ xmlns="http://www.opentravel.org/OTA/2003/05"></OTA_VehAvailRateRQ>');

arrayToXml($json['OTA_VehAvailRateRQ'], $xmlData);

$dom = dom_import_simplexml($xmlData)->ownerDocument;
$dom->formatOutput = true;

$myxml = $dom->saveXML();

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, "https://vv.xqual.hertz.com/DirectLinkWEB/handlers/DirectLinkHandler?id=ota2007a");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/xml',
    'Content-Length: ' . strlen($myxml)
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $myxml);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

// Execute cURL request and get the response
$response = curl_exec($ch);

// Check for cURL errors
if ($response === false) {
    $error = curl_error($ch);
    curl_close($ch);
    die('cURL Error: ' . $error);
}

// Close cURL session
curl_close($ch);
$jsonDcode = json_encode($response,true);
// Handle the response (for now, let's just var_dump it)


