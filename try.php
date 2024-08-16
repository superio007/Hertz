<?php
// Load the XML file
$xml = "

";

// Convert XML to JSON and then to PHP array
$json = json_encode($xml);
$data = json_decode($json, true);

// Extract data from the array
$warnings = $data['Warnings']['Warning'] ?? [];
$reservation = $data['VehRetResRSCore']['VehReservation'];
$customer = $reservation['Customer']['Primary']['PersonName']['Surname'] ?? 'N/A';
$vehSegment = $reservation['VehSegmentCore'];
$confID = $vehSegment['ConfID']['ID'] ?? 'N/A';
$vendor = $vehSegment['Vendor']['Code'] ?? 'N/A';
$pickUpDateTime = $vehSegment['VehRentalCore']['PickUpDateTime'] ?? 'N/A';
$returnDateTime = $vehSegment['VehRentalCore']['ReturnDateTime'] ?? 'N/A';
$pickUpLocation = $vehSegment['VehRentalCore']['PickUpLocation']['LocationCode'] ?? 'N/A';
$returnLocation = $vehSegment['VehRentalCore']['ReturnLocation']['LocationCode'] ?? 'N/A';
$vehicle = $vehSegment['Vehicle'];
$vehicleMakeModel = $vehicle['VehMakeModel']['Name'] ?? 'N/A';
$pictureURL = $vehicle['PictureURL'] ?? '';

// Render the HTML
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Reservation Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            background: #333;
            color: #fff;
            padding: 10px;
            border-radius: 4px;
        }
        .section p {
            margin: 10px 0;
        }
        .warning {
            background: #ffdddd;
            padding: 10px;
            border-left: 4px solid #f44336;
        }
        .vehicle-img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Vehicle Reservation Details</h1>

        <div id="warnings" class="section">
            <h2>Warnings</h2>
            <?php if (!empty($warnings)): ?>
                <?php foreach ($warnings as $warning): ?>
                    <div class="warning">
                        <p><strong>Warning:</strong> <?php echo htmlspecialchars($warning['ShortText']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No warnings available.</p>
            <?php endif; ?>
        </div>

        <div id="reservation-info" class="section">
            <h2>Reservation Information</h2>
            <p><strong>Customer:</strong> <?php echo htmlspecialchars($customer); ?></p>
            <p><strong>Confirmation ID:</strong> <?php echo htmlspecialchars($confID); ?></p>
            <p><strong>Vendor Code:</strong> <?php echo htmlspecialchars($vendor); ?></p>
            <p><strong>Pick-Up Date and Time:</strong> <?php echo htmlspecialchars($pickUpDateTime); ?></p>
            <p><strong>Return Date and Time:</strong> <?php echo htmlspecialchars($returnDateTime); ?></p>
            <p><strong>Pick-Up Location Code:</strong> <?php echo htmlspecialchars($pickUpLocation); ?></p>
            <p><strong>Return Location Code:</strong> <?php echo htmlspecialchars($returnLocation); ?></p>
        </div>

        <div id="vehicle-info" class="section">
            <h2>Vehicle Information</h2>
            <p><strong>Make and Model:</strong> <?php echo htmlspecialchars($vehicleMakeModel); ?></p>
            <?php if (!empty($pictureURL)): ?>
                <img src="<?php echo htmlspecialchars($pictureURL); ?>" alt="Vehicle Image" class="vehicle-img">
            <?php else: ?>
                <p>No vehicle image available.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
