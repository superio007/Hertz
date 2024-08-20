<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Reservation Details</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
        .section {
            margin-bottom: 20px;
        }
        .section p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <?php
    $xmlString = <<<XML
    <?xml version="1.0" encoding="UTF-8"?>
    <OTA_VehRetResRS xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.opentravel.org/OTA/2003/05" Target="Test" Version="1.008">
        <Success/>
        <Warnings>
            <Warning Type="1" ShortText="TOLL CHARGE APPLIES" RecordID="510"/>
            <Warning Type="1" ShortText="CREDIT CARD SURCHARGE OF 1.35% APPLIES TO QUALIFYING RENTALS" RecordID="766"/>
            <Warning Type="1" ShortText="A BOND MAY APPLY." RecordID="767"/>
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
                    <ConfID Type="14" ID="K952B2623E9"/>
                    <Vendor Code="ZE"/>
                    <VehRentalCore PickUpDateTime="2024-08-18T16:30:00-06:00" ReturnDateTime="2024-08-19T21:30:00-06:00">
                        <PickUpLocation ExtendedLocationCode="MELT50" LocationCode="MEL" CodeContext="IATA"/>
                        <ReturnLocation ExtendedLocationCode="MELT50" LocationCode="MEL" CodeContext="IATA"/>
                    </VehRentalCore>
                    <Vehicle PassengerQuantity="4" BaggageQuantity="2" AirConditionInd="true" TransmissionType="Automatic" FuelType="Unspecified" DriveType="Unspecified" Code="CDAR" CodeContext="SIPP">
                        <VehType VehicleCategory="1" DoorCount="4"/>
                        <VehClass Size="4"/>
                        <VehMakeModel Name="H HYUNDAI I30 OR SIMILAR" Code="CDAR"/>
                        <PictureURL>ZEAUCDAR999.jpg</PictureURL>
                    </Vehicle>
                    <RentalRate>
                        <RateDistance Unlimited="true" DistUnitName="Km" VehiclePeriodUnitName="RentalPeriod"/>
                        <VehicleCharges>
                            <VehicleCharge Purpose="1" TaxInclusive="false" GuaranteedInd="true" Amount="111.60" CurrencyCode="AUD" IncludedInRate="false">
                                <TaxAmounts>
                                    <TaxAmount Total="17.37" CurrencyCode="AUD" Percentage="10.00" Description="Tax"/>
                                </TaxAmounts>
                                <Calculation UnitCharge="55.80" UnitName="Day" Quantity="2"/>
                            </VehicleCharge>
                        </VehicleCharges>
                        <RateQualifier ArriveByFlight="false" RateCategory="3" RateQualifier="E02NSG"/>
                    </RentalRate>
                    <Fees>
                        <Fee Purpose="5" TaxInclusive="false" Description="LOCATION FEE:" Amount="41.15" CurrencyCode="AUD"/>
                        <Fee Purpose="5" TaxInclusive="false" Description="ADMN RECOVERY:" Amount="3.91" CurrencyCode="AUD"/>
                        <Fee Purpose="5" TaxInclusive="false" Description="VEHICLE REGISTRATION RECOVERY:" Amount="17.00" CurrencyCode="AUD"/>
                    </Fees>
                    <TotalCharge RateTotalAmount="111.60" EstimatedTotalAmount="191.03" CurrencyCode="AUD"/>
                </VehSegmentCore>
                <VehSegmentInfo>
                    <PricedCoverages>
                        <PricedCoverage Required="false">
                            <Coverage CoverageType="24"/>
                            <Charge TaxInclusive="false" IncludedInRate="false" CurrencyCode="AUD">
                                <Calculation UnitCharge="40.91" UnitName="Day" Quantity="1"/>
                            </Charge>
                        </PricedCoverage>
                        <PricedCoverage Required="false">
                            <Coverage CoverageType="56"/>
                            <Charge TaxInclusive="false" IncludedInRate="false" CurrencyCode="AUD">
                                <Calculation UnitCharge="40.91" UnitName="Day" Quantity="1"/>
                            </Charge>
                        </PricedCoverage>
                    </PricedCoverages>
                </VehSegmentInfo>
            </VehReservation>
        </VehRetResRSCore>
    </OTA_VehRetResRS>
    XML;
    $xml = simplexml_load_string($xmlString);
    ?>
    <div class="container">
        <h1 class="text-center m-4">Vehicle Reservation Details</h1>

        <div class="section">
            <h2 class="bg-warning text-primary p-2">Reservation Information</h2>
            <p><strong>Confirmation ID:</strong> <?php echo $xml->VehRetResRSCore->VehReservation->VehSegmentCore->ConfID['ID'];?></p>
            <p><strong>Vendor Code:</strong> <?php echo $xml->VehRetResRSCore->VehReservation->VehSegmentCore->Vendor['Code']; ?></p>
            <p><strong>Pick-Up Date and Time:</strong> <?php echo $xml->VehRetResRSCore->VehReservation->VehSegmentCore->VehRentalCore['PickUpDateTime'];?></p>
            <p><strong>Return Date and Time:</strong> <?php echo $xml->VehRetResRSCore->VehReservation->VehSegmentCore->VehRentalCore['ReturnDateTime'];?></p>
            <p><strong>Pick-Up Location:</strong> <?php echo $xml->VehRetResRSCore->VehReservation->VehSegmentCore->VehRentalCore->PickUpLocation['LocationCode'];?></p>
            <p><strong>Return Location:</strong> <?php echo $xml->VehRetResRSCore->VehReservation->VehSegmentCore->VehRentalCore->ReturnLocation['LocationCode'];?></p>
        </div>

        <div class="section">
            <h2 class="bg-warning text-primary p-2">Customer Information</h2>
            <p><strong>Customer Name:</strong> <?php echo $xml->VehRetResRSCore->VehReservation->Customer->Primary->PersonName->Surname;?></p>
        </div>

        <div class="section">
            <h2 class="bg-warning text-primary p-2">Vehicle Details</h2>
            <img src="<?php echo 'https://images.hertz.com/vehicles/220x128/' . $xml->VehRetResRSCore->VehReservation->VehSegmentCore->Vehicle->PictureURL;?>" alt="Vehicle Image" class="vehicle-img">
            <p><strong>Vehicle Code:</strong> <?php echo $xml->VehRetResRSCore->VehReservation->VehSegmentCore->Vehicle->VehMakeModel['Code'] ;?></p>
            <p><strong>Passenger Quantity:</strong> <?php echo $xml->VehRetResRSCore->VehReservation->VehSegmentCore->Vehicle['PassengerQuantity'] ;?></p>  
            <p><strong>Baggage Quantity:</strong> <?php echo $xml->VehRetResRSCore->VehReservation->VehSegmentCore->Vehicle['BaggageQuantity'] ;?></p>
            <p><strong>Air Conditioning:</strong> <?php echo $xml->VehRetResRSCore->VehReservation->VehSegmentCore->Vehicle['AirConditionInd'] ;?></p>
            <p><strong>Transmission Type:</strong> <?php echo $xml->VehRetResRSCore->VehReservation->VehSegmentCore->Vehicle['TransmissionType'] ;?></p>
            <p><strong>Fuel Type:</strong> <?php echo $xml->VehRetResRSCore->VehReservation->VehSegmentCore->Vehicle['FuelType'] ;?></p>
            <p><strong>Drive Type:</strong> <?php echo $xml->VehRetResRSCore->VehReservation->VehSegmentCore->Vehicle['DriveType'] ;?></p>
            <p><strong>Make and Model:</strong> <?php echo $xml->VehRetResRSCore->VehReservation->VehSegmentCore->Vehicle->VehMakeModel['Name'] ;?></p>
        </div>

        <div class="section">
            <h2 class="bg-warning text-primary p-2 mb-3">Terms & Conditions</h2>
            <?php foreach ($xml->Warnings->Warning as $warning): ?>
                <div class="alert alert-danger">
                    <ul class="m-0">
                        <li> <?php echo htmlspecialchars((string)$warning['ShortText']); ?></li>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
