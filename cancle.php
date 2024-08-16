<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hertz || Car Rental || Car Booking</title>
    <link
        href="style.css"
        rel="stylesheet">
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/74e6741759.js"
        crossorigin="anonymous"></script>
    <link rel="stylesheet"
        href="https://code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/ui/1.14.0/jquery-ui.js"></script>
    <link rel="stylesheet"
        href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
$can = false;
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['Can_btn'])) {
    $Can_confirmNo = $_POST['Can_confirmNo'];
    $Can_Lname = $_POST['Can_Lname'];

    $xmlString = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
        <OTA_VehCancelRQ xmlns=\"http://www.opentravel.org/OTA/2003/05\" Version=\"1.008\">
            <POS>
                <Source PseudoCityCode=\"XXXX\" ISOCountry=\"US\" AgentDutyCode=\"T17R16L5D11\">
                    <RequestorID Type=\"4\" ID=\"X975\">
                        <CompanyName Code=\"CP\" CodeContext=\"4PH5\"/>
                    </RequestorID>
                </Source>
                <Source>
                    <RequestorID Type=\"5\" ID=\"ota2007a\"/>
                </Source>
            </POS>
            <VehCancelRQCore CancelType=\"Book\">
                <UniqueID Type=\"14\" ID=\"$Can_confirmNo\"/>
                <PersonName>
                    <Surname>$Can_Lname</Surname>
                </PersonName>
            </VehCancelRQCore>
        </OTA_VehCancelRQ>";
        //  Initialize cURL session
            $ch = curl_init();

            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, "https://vv.xqual.hertz.com/DirectLinkWEB/handlers/DirectLinkHandler?id=ota2007a");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/xml',
                'Content-Length: ' . strlen($xmlString)
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlString);
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

            // Load the XML string into a SimpleXMLElement object
            $xml = simplexml_load_string($response);

            // Register the namespace for the XML
            $xml->registerXPathNamespace('ns', 'http://www.opentravel.org/OTA/2003/05');

            // Check if the CancelStatus is "Cancelled"
            $result = $xml->xpath("//ns:VehCancelRSCore[@CancelStatus='Cancelled']");

            if ($result) {
                $can = true;
            }
        }
?>
<script>
    let can = <?php echo json_encode($can); ?>; // Encode PHP boolean to JavaScript boolean
    if(can){
        Swal.fire({
            title: "Cancelled!",
            text: "Your reservation is cancelled!",
            icon: "success"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "index.php";
            }
        });
    }
</script>
<div class="container">
    <h1 class="text-center">Rent a car!</h1>
    <div id="Can-widget" class="api-widget p-3">
        <form action="" method="post">
            <div id="Can-div1" class="text-end mb-3">
                <a id="Can_to_search" href="#">Start your reservation</a>
            </div>
            <div id="Can-div2" class="row gap-2"
                style="margin-left: 12px;">
                <div class="input-div col-5 p-2 d-grid">
                    <label class="internal-label"
                        for="Can_confirmNo">Confirmation Number</label>
                    <input type="text" class="input text-uppercase" id="Can_confirmNo"
                        name="Can_confirmNo" required>
                </div>
                <div class="input-div col-5 p-2 d-grid">
                    <label class="internal-label" for="Can_Lname">Last
                        Name:</label>
                    <input type="text" class="input text-uppercase" id="Can_Lname" 
                        name="Can_Lname" required>
                </div>
                <div class="col-1 d-flex">
                    <button name="Can_btn" id="Can-btn" class="btn bg-secondary"
                        disabled>Cancle Reservation</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    function CanTooglebtn(){
        var Can_confirmNo = document.getElementById('Can_confirmNo').value;
        var Can_Lname = document.getElementById('Can_Lname').value;
        var Can_btn = document.getElementById('Can-btn');
        if(Can_confirmNo !== "" && Can_Lname !== ""){
            Can_btn.classList.remove('bg-secondary');
            Can_btn.classList.add('bg-warning');
            Can_btn.disabled = false;
        }else{
            Can_btn.classList.remove('bg-warning');
            Can_btn.classList.add('bg-secondary');
            Can_btn.disabled = true;
        }
    }
    document.getElementById('Can_to_search').addEventListener('click',function(){
        window.location.href = "index.php";
    });
    document.getElementById('Can_confirmNo').addEventListener('input',CanTooglebtn);
    document.getElementById('Can_Lname').addEventListener('input',CanTooglebtn);
</script>
</body>
</html>
