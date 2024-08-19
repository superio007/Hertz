<?php
// Load the XML file
$xml = "
<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<OTA_VehRetResRS xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns=\"http://www.opentravel.org/OTA/2003/05\" Target=\"Test\" Version=\"1.008\">
    <Success/>
    <Warnings>
        <Warning Type=\"1\" ShortText=\"TOLL CHARGE APPLIES\" RecordID=\"510\"/>
        <Warning Type=\"1\" ShortText=\"CREDIT CARD SURCHARGE OF 1.35% APPLIES TO QUALIFYING RENTALS\" RecordID=\"766\"/>
        <Warning Type=\"1\" ShortText=\"A BOND MAY APPLY.\" RecordID=\"767\"/>
    </Warnings>
    <VehRetResRSCore>
        <VehReservation>
            <Customer>
                <Primary>
                    <PersonName>
                        <Surname>DHOKE KIRAN</Surname>
                    </PersonName>
                </Primary>
            </Customer>
            <VehSegmentCore>
                <ConfID Type=\"14\" ID=\"K952B2623E9\"/>
                <Vendor Code=\"ZE\"/>
                <VehRentalCore PickUpDateTime=\"2024-08-18T16:30:00-06:00\" ReturnDateTime=\"2024-08-19T21:30:00-06:00\">
                    <PickUpLocation ExtendedLocationCode=\"MELT50\" LocationCode=\"MEL\" CodeContext=\"IATA\"/>
                    <ReturnLocation ExtendedLocationCode=\"MELT50\" LocationCode=\"MEL\" CodeContext=\"IATA\"/>
                </VehRentalCore>
                <Vehicle PassengerQuantity=\"4\" BaggageQuantity=\"2\" AirConditionInd=\"true\" TransmissionType=\"Automatic\" FuelType=\"Unspecified\" DriveType=\"Unspecified\" Code=\"CDAR\" CodeContext=\"SIPP\">
                    <VehType VehicleCategory=\"1\" DoorCount=\"4\"/>
                    <VehClass Size=\"4\"/>
                    <VehMakeModel Name=\"H HYUNDAI I30 OR SIMILAR\" Code=\"CDAR\"/>
                    <PictureURL>ZEAUCDAR999.jpg</PictureURL>
                </Vehicle>
                <RentalRate>
                    <RateDistance Unlimited=\"true\" DistUnitName=\"Km\" VehiclePeriodUnitName=\"RentalPeriod\"/>
                    <VehicleCharges>
                        <VehicleCharge Purpose=\"1\" TaxInclusive=\"false\" GuaranteedInd=\"true\" Amount=\"111.60\" CurrencyCode=\"AUD\" IncludedInRate=\"false\">
                            <TaxAmounts>
                                <TaxAmount Total=\"17.37\" CurrencyCode=\"AUD\" Percentage=\"10.00\" Description=\"Tax\"/>
                            </TaxAmounts>
                            <Calculation UnitCharge=\"55.80\" UnitName=\"Day\" Quantity=\"2\"/>
                        </VehicleCharge>
                    </VehicleCharges>
                    <RateQualifier ArriveByFlight=\"false\" RateCategory=\"3\" RateQualifier=\"E02NSG\"/>
                </RentalRate>
                <Fees>
                    <Fee Purpose=\"5\" TaxInclusive=\"false\" Description=\"LOCATION FEE:\" Amount=\"41.15\" CurrencyCode=\"AUD\"/>
                    <Fee Purpose=\"5\" TaxInclusive=\"false\" Description=\"ADMN RECOVERY:\" Amount=\"3.91\" CurrencyCode=\"AUD\"/>
                    <Fee Purpose=\"5\" TaxInclusive=\"false\" Description=\"VEHICLE REGISTRATION RECOVERY:\" Amount=\"17.00\" CurrencyCode=\"AUD\"/>
                </Fees>
                <TotalCharge RateTotalAmount=\"111.60\" EstimatedTotalAmount=\"191.03\" CurrencyCode=\"AUD\"/>
            </VehSegmentCore>
            <VehSegmentInfo>
                <PricedCoverages>
                    <PricedCoverage Required=\"false\">
                        <Coverage CoverageType=\"24\"/>
                        <Charge TaxInclusive=\"false\" IncludedInRate=\"false\" CurrencyCode=\"AUD\">
                            <Calculation UnitCharge=\"40.91\" UnitName=\"Day\" Quantity=\"1\"/>
                        </Charge>
                    </PricedCoverage>
                    <PricedCoverage Required=\"false\">
                        <Coverage CoverageType=\"56\"/>
                        <Charge TaxInclusive=\"false\" IncludedInRate=\"false\" CurrencyCode=\"AUD\">
                            <Calculation UnitCharge=\"40.91\" UnitName=\"Day\" Quantity=\"1\"/>
                        </Charge>
                    </PricedCoverage>
                </PricedCoverages>
            </VehSegmentInfo>
        </VehReservation>
    </VehRetResRSCore>
</OTA_VehRetResRS>
";



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
