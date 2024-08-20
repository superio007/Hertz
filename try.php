<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;

// Define your XML request
$xml = "
<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
<OTA_VehAvailRateRQ xmlns=\"http://www.opentravel.org/OTA/2003/05\" Version=\"1.008\" MaxResponses=\"5\">
    <POS>
        <Source ISOCountry=\"AU\" AgentDutyCode=\"T17R16L5D11\">
            <RequestorID Type=\"4\" ID=\"X975\">
                <CompanyName Code=\"CP\" CodeContext=\"4PH5\"></CompanyName>
            </RequestorID>
        </Source>
    </POS>
    <VehAvailRQCore>
        <VehRentalCore PickUpDateTime=\"2024-08-20T15:45:00\" ReturnDateTime=\"2024-08-22T13:30:00\">
            <PickUpLocation LocationCode=\"SYD\" Type=\"IATA\"></PickUpLocation>
        </VehRentalCore>
    </VehAvailRQCore>
</OTA_VehAvailRateRQ>";

// Retry handler function (max 3 retries for server errors)
$retryHandler = Middleware::retry(function ($retries, $request, $response, $exception) {
    // Retry on server errors or network issues (500 or higher, or request exceptions)
    return $retries < 3 && ($exception || ($response && $response->getStatusCode() >= 500));
});

// Create a handler stack and push the retry middleware
$stack = HandlerStack::create();
$stack->push($retryHandler);

// Create a new Guzzle client with the handler stack
$client = new Client(['handler' => $stack]);

try {
    // Send a POST request with Guzzle
    $response = $client->request('POST', 'https://vv.xqual.hertz.com/DirectLinkWEB/handlers/DirectLinkHandler?id=ota2007a', [
        'body' => $xml,
        'headers' => [
            'Content-Type' => 'application/xml',
            'Connection' => 'keep-alive',
        ],
        'timeout' => 60, // Set a longer timeout (60 seconds)
        'connect_timeout' => 20, // Set a longer connection timeout (20 seconds)
    ]);

    // Get the response body and print it
    $body = $response->getBody()->getContents();
    echo 'API Response: ' . htmlspecialchars($body);

} catch (RequestException $e) {
    // Handle request exception (network or server error)
    if ($e->hasResponse()) {
        // If a response is available, output the status and error details
        $errorResponse = $e->getResponse()->getBody()->getContents();
        echo 'Error Response: ' . htmlspecialchars($errorResponse);
    } else {
        // If no response, output the error message
        echo 'Error: ' . $e->getMessage();
    }
}
