<?php
session_start();
?>
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
    </head>
    <body>
        <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
            $code = "Add a Discount Code";
            if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['search_btn'])) {
                // Capture form data
                $Drop_off = $_POST['Drop_off'];
                $dropType = $_POST['drop-Type'];
                $Pickup = $_POST['Pick-up'];
                $PickDate = $_POST['Pick-Date'];
                $PickTime = $_POST['Pick-Time'];
                $DropDate = $_POST['Drop-Date'];
                $DropTime = $_POST['Drop-Time'];

                // Function to convert 12-hour time to 24-hour format
                function convertTo24HourFormat($time12Hour) {
                    $date = DateTime::createFromFormat('h:i A', $time12Hour);
                    return $date->format('H:i:s');
                }

                // Function to convert date format
                function convertDateFormat($date, $fromFormat, $toFormat) {
                    $dateTime = DateTime::createFromFormat($fromFormat, $date);
                    return $dateTime->format($toFormat);
                }

                // Convert the date formats
                $fromFormat = 'm/d/Y'; // Original format
                $toFormat = 'Y-m-d';   // Desired format

                $convertedPickDate = convertDateFormat($PickDate, $fromFormat, $toFormat);
                $convertedDropDate = convertDateFormat($DropDate, $fromFormat, $toFormat);

                // Convert the time formats
                $formatPickTime = convertTo24HourFormat($PickTime);
                $formatDropTime = convertTo24HourFormat($DropTime);

                // Combine date and time for XML
                $pickUpDateTime = $convertedPickDate . 'T' . $formatPickTime;
                $dropOffDateTime = $convertedDropDate . 'T' . $formatDropTime;

                //convert xml to json
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

                if(($dropType == "Same Drop-off Location")){
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
                                    "PickUpDateTime"=> $pickUpDateTime,
                                    "ReturnDateTime"=> $dropOffDateTime,
                                    "PickUpLocation"=> [
                                        "LocationCode"=> $Pickup,
                                        "Type"=> "IATA"
                                    ]
                                ]
                            ]
                        ]
                    ];
                }elseif(($dropType == "Different Drop-off Location")){
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
                                    "PickUpDateTime"=> $pickUpDateTime,
                                    "ReturnDateTime"=> $dropOffDateTime,
                                    "PickUpLocation"=> [
                                        "LocationCode"=> $Pickup,
                                        "Type"=> "IATA"
                                    ],
                                    "ReturnLocation"=> [
                                        "LocationCode"=> $Drop_off,
                                        "Type"=> "IATA"
                                    ]
                                ]
                            ]
                        ]
                    ];
                }
                // Initialize cURL session
                
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

                // Handle the response (for now, let's just var_dump it)
                if(strlen($response)>500){
                    $_SESSION['results'] = $response;
                    echo "<script>window.location.href = 'result.php';</script>";
                }else{
                    var_dump($response);
                }
                
            }
            if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['dicountBtn'])) {
                $Club_Code = $_POST['Club_Code'];
                $Promotional_Coupon = $_POST['Promotional_Coupon'];
                $Rate_Code = $_POST['Rate_Code'];
                $code = $Club_Code . " " . "applied" ;
                // Echo statements for the cancel widget
                // echo "<h2>Discount Widget</h2>";
                // echo "<p>Club_Code: $Club_Code</p>";
                // echo "<p>Promotional_Coupon: $Promotional_Coupon</p>";
                // echo "<p>Rate_Code: $Rate_Code</p>";
            }
        ?>
        <div class="container">
            <h1 class="text-center">Rent a car!</h1>
            <div id="search-widget" class="api-widget p-3">
                <form action="" method="post">
                    <div id="search-div1"
                        class="d-flex justify-content-between mb-3">
                        <div class="d-flex gap-3 align-items-center">
                            <select class="input" name="drop-Type" id="drop-Type">
                                <option value selected hidden>Select option</option>
                                <option value="Different Drop-off Location">
                                    Different Drop-off Location
                                </option>
                                <option value="Same Drop-off Location">
                                    Same Drop-off Location
                                </option>
                            </select>
                            <a id="Discount_code" href="#"><?php echo $code;  ?></a><i
                                class="fa-solid fa-circle-info fa-lg"
                                style="color: #858585;"></i>
                        </div>
                        <div>
                            <a id="view" href="#">View /</a>
                            <a id="Edit" href="#">Edit /</a>
                            <a id="Cancel" href="#">Cancel</a>
                        </div>
                    </div>
                    <div id="search-div2" class="row px-2 d-flex gap-2">
                        <div id="location" class="col-4">
                            <div class="input-div d-grid p-2">
                                <label class="internal-label" for="Pick-up">Pick-up
                                Location</label>
                                <input type="text" class="input" id="Pick-up"
                                    name="Pick-up" required>
                            </div>
                            <div id="drop-div" class="input-div d-grid p-2 d-none">
                                <label class="internal-label" for="Drop_off">Drop off
                                Location</label>
                                <input type="text" class="input" id="Drop_off"
                                    name="Drop_off">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="row">
                                <div class="input-div col-6 d-grid p-2">
                                    <label class="internal-label"
                                        for="Pick-Date">Pick-up-Date</label>
                                    <input class="input" type="text" id="Pick-Date"
                                        name="Pick-Date">
                                </div>
                                <div class="input-div col-6 d-grid p-2">
                                    <label class="internal-label"
                                        for="Pick-Time">Pick-up-Time</label>
                                    <input class="input" type="text" id="Pick-Time"
                                        name="Pick-Time">
                                </div>
                            </div>
                        </div>
                        <div class="col-3 d-flex">
                            <div class="row">
                                <div class="input-div col-6 d-grid p-2">
                                    <label class="internal-label"
                                        for="Drop-Date">Drop-off-Date</label>
                                    <input class="input" type="text" id="Drop-Date"
                                        name="Drop-Date">
                                </div>
                                <div class="input-div col-6 d-grid p-2">
                                    <label class="internal-label"
                                        for="Drop-Time">Drop-off-Time</label>
                                    <input class="input" type="text" id="Drop-Time"
                                        name="Drop-Time">
                                </div>
                            </div>
                        </div>
                        <div class="col-1 d-flex">
                            <button name="search_btn" class="btn bg-warning">View Vechicles</button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="popup" class="bg-white d-none">
                <div class="d-flex align-items-center" style="background-color: rgba(134 134 134 / 23%);justify-content: space-between;padding: 20px;">
                    <h2>Discount Code</h2>
                    <i id="cross" class="fa-solid fa-xmark fa-rotate-180 fa-2xl" style="color: #000000;"></i>
                </div>
                <form action="" method="post">
                    <div class="input-div d-grid m-3 p-2">
                        <label class="internal-label" for="Club_Code">Discount/CDP/Club Code:</label>
                        <input type="text" class="input" id="Club_Code" name="Club_Code" required>
                    </div>
                    <div class="input-div d-grid m-3 p-2">
                        <label class="internal-label" for="Promotional_Coupon">Promotional Coupon (PC):</label>
                        <input type="text" class="input" id="Promotional_Coupon" name="Promotional_Coupon" required>
                    </div>
                    <div class="input-div d-grid m-3 p-2">
                        <label class="internal-label" for="Rate_Code">Rate Code (RQ):</label>
                        <input type="text" class="input" id="Rate_Code" name="Rate_Code" required>
                    </div>
                    <div class="row m-3 p-2 gap-3 offset-1 justify-content-center">
                        <button id="closeBtn" class="btn btn-hover text-center col-5" style="border: 2px solid black; padding: 10px;">Cancel</button>
                        <button name="dicountBtn" id="dicountBtn" class="bg-warning btn col-5" style="padding: 10px;">Apply</button>
                    </div>
                    <div class="">
                    </div>
                </form>
            </div>
        </div>
    </body>
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <script>
        document.getElementById('view').addEventListener('click',function(){
            window.location.href = "view.php"
        })
        document.getElementById('Edit').addEventListener('click',function(){
            window.location.href = "edit.php"
        })
        document.getElementById('Cancel').addEventListener('click',function(){
            window.location.href = "cancle.php"
        })
        document.getElementById('drop-Type').addEventListener('input',function(){
            if(document.getElementById('drop-Type').value == "Different Drop-off Location"){
                document.getElementById('location').classList.add('d-flex');
                document.getElementById('drop-div').classList.remove('d-none');
            }else{
                document.getElementById('drop-div').classList.add('d-none');
                document.getElementById('location').classList.remove('d-flex');
            }
        })
        // pop up location 
        document.getElementById('Discount_code').addEventListener('click',function(){
            document.getElementById('popup').classList.remove('d-none');
        })
        document.getElementById('cross').addEventListener('click',function(){
            document.getElementById('popup').classList.add('d-none');
        })
        document.getElementById('closeBtn').addEventListener('click',function(){
            document.getElementById('popup').classList.add('d-none');
        })
        $( function() {
        $( "#Pick-Date" ).datepicker();
        } );
        $(document).ready(function(){
            $('#Pick-Time').timepicker({});
        });
        $( function() {
        $( "#Drop-Date" ).datepicker();
        } );
        $(document).ready(function(){
            $('#Drop-Time').timepicker({});
        });
    </script>
</html>
