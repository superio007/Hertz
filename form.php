<?php
session_start();
$results = $_SESSION['results'] ?? ''; // Safely get session data
$dataarray = $_SESSION['dataarray'] ?? '';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';
if (isset($_GET['veh_Code'])) {
    $veh_Code = $_GET['veh_Code'];
}
if (isset($_GET['venCode'])) {
    $venCode = $_GET['venCode'];
}
if (isset($_GET['vehcontext'])) {
    $vehcontext = $_GET['vehcontext'];
}
if ($results) {
    // Load the XML string into a SimpleXMLElement object
    $xml = simplexml_load_string($results);

    if ($xml !== false) {
        // Register namespaces for XPath queries
        $xml->registerXPathNamespace('ota', 'http://www.opentravel.org/OTA/2003/05');

        // Safely escape the vehicle code for use in XPath
        $escaped_veh_Code = htmlspecialchars($veh_Code, ENT_QUOTES);

        // Find and remove all VehAvail elements that don't have the dynamic Code
        foreach ($xml->xpath("//ota:VehAvail[ota:VehAvailCore/ota:Vehicle/@Code != '{$escaped_veh_Code}']") as $remove) {
            unset($remove[0]);
        }

        // Convert the filtered SimpleXMLElement object to JSON
        $json = json_encode($xml);

        if ($json !== false) {
            $data = json_decode($json, true);

            // Extract relevant data from JSON
            $vehicle = $data['VehAvailRSCore']['VehVendorAvails']['VehVendorAvail']['VehAvails']['VehAvail']['VehAvailCore']['Vehicle'];
            $totalCharge = $data['VehAvailRSCore']['VehVendorAvails']['VehVendorAvail']['VehAvails']['VehAvail']['VehAvailCore']['TotalCharge'];
            $fees = $data['VehAvailRSCore']['VehVendorAvails']['VehVendorAvail']['VehAvails']['VehAvail']['VehAvailCore']['Fees']['Fee'];
            $vendorInfo = $data['VehAvailRSCore']['VehVendorAvails']['VehVendorAvail']['Info']['LocationDetails'];

            // Extract vehicle details
            $vehicleName = $vehicle['VehMakeModel']['@attributes']['Name'];
            $passengerQuantity = $vehicle['@attributes']['PassengerQuantity'];
            $baggageQuantity = $vehicle['@attributes']['BaggageQuantity'];
            $transmissionType = $vehicle['@attributes']['TransmissionType'];
            $pictureUrl = $vehicle['PictureURL'];

            // Extract total charge and fees
            $estimatedTotalAmount = $totalCharge['@attributes']['EstimatedTotalAmount'];
            $currencyCode = $totalCharge['@attributes']['CurrencyCode'];

            // Extract vendor info
            $vendorName = $vendorInfo['@attributes']['Name'];
            $address = implode(', ', $vendorInfo['Address']['AddressLine']) . ', ' . $vendorInfo['Address']['CityName'] . ', ' . $vendorInfo['Address']['StateProv'] . ', ' . $vendorInfo['Address']['CountryName'];
            $phone = $vendorInfo['Telephone']['@attributes']['PhoneNumber'];

        } else {
            echo "Failed to convert XML to JSON.";
        }
    } else {
        echo "Failed to parse XML.";
    }
} else {
    echo "No XML data found in session.";
}
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
if($_SERVER['REQUEST_METHOD']=="POST"){
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $mobile_country_code = $_POST['mobile_country_code'];
    $mobile_number = $_POST['mobile_number'];
    $pickupLocation = $dataarray['pickLocation'];
    $returnLocation = $dataarray['dropLocation'] ?? $dataarray['pickLocation'];
    $pickupDateTime =  $dataarray['pickUpDateTime'];
    $returnDateTime = $dataarray['dropOffDateTime'];
    $address = $_POST['Address'];
    $city = $_POST['City'];
    $stateCode = $_POST['State'];
    $voucher = "12345678";
    $usersInfo = [
        'fName' => $first_name,
        'lName' => $last_name,
        'email' => $email,
        'countryCode' => $mobile_country_code,
        'mobileNo' => $mobile_number,
        'address' => $address,
        'city' => $city,
        'state' => $stateCode,
        'pickDate' => $pickupDateTime,
        'dropDate' => $returnDateTime,
        'pick'=> $pickupLocation,
        'drop' => $returnLocation,
    ];
    $_SESSION['userInfo'] = $usersInfo;
    $xml = "
        <OTA_VehResRQ xmlns=\"http://www.opentravel.org/OTA/2003/05\" Version=\"1.008\">
            <POS>
                <Source ISOCountry=\"IN\" AgentDutyCode=\"T17R16L5D11\">
                    <RequestorID Type=\"4\" ID=\"X975\">
                        <CompanyName Code=\"CP\" CodeContext=\"4PH5\"/>
                    </RequestorID>
                </Source>
            </POS>
            <VehResRQCore>
                <VehRentalCore PickUpDateTime=\"$pickupDateTime\" ReturnDateTime=\"$returnDateTime\">
                    <PickUpLocation LocationCode=\"$pickupLocation\" CodeContext=\"IATA\"/>
                    <ReturnLocation LocationCode=\"$returnLocation\" CodeContext=\"IATA\"/>
                </VehRentalCore>
                <Customer>
                    <Primary>
                        <PersonName>
                            <GivenName>$first_name</GivenName>
                            <Surname>$last_name</Surname>
                        </PersonName>
                        <Email>$email</Email>
                        <Address>
                            <AddressLine>$address</AddressLine>
                            <CityName>$city</CityName>
                            <StateProv StateCode=\"$stateCode\"/>
                            <CountryName Code=\"$mobile_country_code\"/>
                        </Address>
                    </Primary>
                </Customer>
                <VendorPref Code=\"ZE\"/>
                <VehPref Code=\"CDAR\" CodeContext=\"SIPP\"/>
                <RentalPaymentPref>
                    <Voucher Identifier=\"$voucher\" IdentifierContext=\"TestVoucher\"/>
                </RentalPaymentPref>
            </VehResRQCore>
        </OTA_VehResRQ>";
        // var_dump($jsonReq);
       
        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, "https://vv.xqual.hertz.com/DirectLinkWEB/handlers/DirectLinkHandler?id=ota2007a");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/xml',
            'Content-Length: ' . strlen($xml)
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        // Execute cURL request and get the response
        $response = curl_exec($ch);
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            die('cURL Error: ' . $error);
        }else{
            $xmlres = new SimpleXMLElement($response);

            // Check if the <Success> tag exists
            if (isset($xmlres->Success)) {
                // If <Success> tag is present, print a success message
                echo "Success! The vehicle reservation was processed successfully.";
                // Retrieve and print the name
                $givenName = $xmlres->VehResRSCore->VehReservation->Customer->Primary->PersonName->GivenName;
                $surname = $xmlres->VehResRSCore->VehReservation->Customer->Primary->PersonName->Surname;

                // Retrieve and print the ConfID
                $confID = $xmlres->VehResRSCore->VehReservation->VehSegmentCore->ConfID['ID'];

                // Retrieve and print the car name
                $carName = $xmlres->VehResRSCore->VehReservation->VehSegmentCore->Vehicle->VehMakeModel['Name'];

                // Create an instance of PHPMailer
                $mail = new PHPMailer(true);

                try {
                    // Server settings
                    $mail->isSMTP();                                 // Set mailer to use SMTP
                    $mail->Host       = 'smtp.gmail.com';          // Specify main and backup SMTP servers
                    $mail->SMTPAuth   = true;                        // Enable SMTP authentication
                    $mail->Username   = 'dhokekiran98@gmail.com';    // SMTP username
                    $mail->Password   = 'fzepmsgxliiticxs';       // SMTP password
                    $mail->SMTPSecure = 'tls';                        // Enable TLS encryption, `ssl` also accepted
                    $mail->Port       = 587;                         // TCP port to connect to

                    // Recipients
                    $mail->setFrom("dhokekiran98@gmail.com", "Hertz_Support");
                    $mail->addAddress($email, $first_name . " " .  $last_name);

                    // Content
                    $mail->isHTML(true);                            // Set email format to HTML
                    $mail->Subject = "Confirmation from hertz : $confID";
                    $mail->Body    = "Passengers given name : $givenName <br> Passengers surname : $surname <br> Car booked : $carName <br> Check details : 
                    <a href='detail.php?confId=$confID&surname=$surname' 
                      style='background-color: #ffd207; color:#0d7fa6; padding: 5px; text-decoration: none; border-radius: 5px;'>Click Here</a>
                    ";
                    $mail->AltBody = '';

                    if($mail->send()){
                        echo "<script>window.location.href='sucess.php?cnfNo=$confID&lName=$surname'</script>";
                    }
                    // echo 'Message has been sent';
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                // If <Success> tag is not present, print a failure message
                echo "Failed to process the vehicle reservation.";
            }
            // Check for cURL errors
            

            // Close cURL session
            curl_close($ch);     
            var_dump($response);
        }
        
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .checkout-container { margin: 50px auto; max-width: 1200px; }
        .driver-info, .total-box, .additional-info { border: 1px solid #ddd; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .driver-info .header, .additional-info .header { background-color: #ffd207; padding: 10px; font-weight: bold; border-radius: 3px 3px 0 0; margin-bottom: 15px; }
        .total-box { position: sticky; top: 20px; }
        .total-box h2 { font-size: 2rem; font-weight: bold; }
        .total-box .price-details, .total-box .your-car { margin-top: 20px; border-top: 1px solid #ddd; padding-top: 20px; }
        .total-box .your-car img { width: 50px; margin-right: 10px; }
        .total-box .car-info { color: green; font-style: italic; font-weight: bold; }
    </style>
</head>
<body>
<div class="container checkout-container">
    <h2>Checkout</h2>
    <div class="row">
        <div class="col-md-8">
            <!-- Form starts here -->
            <form action="" method="POST">
                <div class="driver-info">
                    <div class="header">Driver Information</div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="firstName">First Name:</label>
                            <input type="text" class="form-control" id="firstName" name="first_name" placeholder="First Name" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lastName">Last Name:</label>
                            <input type="text" class="form-control" id="lastName" name="last_name" placeholder="Last Name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail:</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="countryCode">Mobile Country Code:</label>
                            <select id="countryCode" class="form-control" name="mobile_country_code">
                                <option value="IN" selected>India (+91)</option>
                                <option value="US">United States (+1)</option>
                                <option value="AU">Australia (+61)</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="mobileNumber">Mobile Number:</label>
                            <input type="tel" class="form-control" id="mobileNumber" name="mobile_number" placeholder="Mobile Number" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="Address">Address:</label>
                            <input type="text" class="form-control" id="Address" name="Address" placeholder="Address" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="City">City:</label>
                            <input type="text" class="form-control" id="City" name="City" placeholder="City" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="State">State:</label>
                            <input type="text" class="form-control" id="State" name="State" placeholder="State" required>
                        </div>
                    </div>
                </div>

                <div class="submit-btn">
                    <button type="submit" class="btn btn-warning btn-lg">Submit</button>
                </div>
            </form>
            <!-- Form ends here -->
        </div>
        <div class="col-md-4">
            <div class="total-box">
                <h5>Est. Total</h5>
                <h2 class="text-right"><?= $estimatedTotalAmount ?? 'N/A'; ?> <?= $currencyCode ?? ''; ?></h2>
                <p class="text-right">Charged At Pickup</p>
                <div class="price-details">
                    <p><strong>Base Rate</strong></p>
                    <?php foreach ($fees as $fee) : ?>
                        <p><?php echo $fee['@attributes']['Description']; ?>: <?php echo $fee['@attributes']['Amount']; ?> <?php echo $currencyCode; ?></p>
                    <?php endforeach; ?>
                    <p><strong>Amount To Be Paid At Time Of Rent:</strong><br><?= $estimatedTotalAmount ?? 'N/A'; ?> <?= $currencyCode ?? ''; ?></p>
                </div>
                <div class="your-car">
                    <a href="result.php" class="link text-primary">Edit</a>
                    <h6>Your Car</h6>
                    <img src="<?php echo "https://images.hertz.com/vehicles/220x128/" . $pictureUrl ; ?>" alt="">
                    <p><?= $vehicleName ?? 'N/A'; ?></p>
                    <p class="car-info">Passenger: <?= $passengerQuantity ?? 'N/A'; ?>, Baggage: <?= $baggageQuantity ?? 'N/A'; ?>, Transmission: <?= $transmissionType ?? 'N/A'; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
