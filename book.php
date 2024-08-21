<?php
session_start();
$userInfo = $_SESSION['userInfo'];

echo "<pre>";
print_r($userInfo);
echo "</pre>";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';
$first_name = $userInfo['fName'];
$last_name = $userInfo['lName'];
$email = $userInfo['email'];
$mobile_country_code = $userInfo['countryCode'];
$mobile_number = $userInfo['mobileNo'];
$pickupLocation = $userInfo['pick'];
$returnLocation = $userInfo['drop'] ?? $userInfo['pick'];
$pickupDateTime =  $userInfo['pickDate'];
$returnDateTime = $userInfo['dropDate'];
$address = $userInfo['address'];
$city = $userInfo['city'];
$stateCode = $userInfo['state'];
$voucher = "12345678";
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

?>