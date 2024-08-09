<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hertz || Car Rental || Car Booking</title>
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
    <style>
        a {
            text-decoration: none;
            font-weight: 600;
            font-size: large;
        }
        .input-div {
            border: #858585 solid 1px;
            position: relative;
        }
        .input {
            background-color: #ffffff00;
            border: none;
        }
        .input:focus {
            background-color: transparent !important;
            border: none;
            outline: none;
        }
        #search-div2 .col-1, #view-div2 .col-1, #Edit-div2 .col-1, #Can-div2 .col-1 {
            width: 14.333333%;
        }
        .api-widget {
            position: relative;
            background-color: white;
            border-radius: 25px;
            height: 170px;
            border-bottom: yellow 4px solid; 
        }
        #popup{
            position: absolute;
            top: 7%;
            left: 25%;
            width: 500px;
            height: 440px;
        }
        #cross{
            cursor: pointer;
        }
    </style>
    <body style="background:url(https://images.hertz.com/content/Intl/Carousel/Asia/asia_ww_generic_en.jpg)">
        <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
            $code = "Add a Discount Code";
            if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['search_btn'])) {
                $dropType = $_POST['drop-Type'];
                $Pickup = $_POST['Pick-up'];
                $PickDate = $_POST['Pick-Date'];
                $PickTime = $_POST['Pick-Time'];
                $DropDate = $_POST['Drop-Date'];
                $DropTime = $_POST['Drop-Time'];

                function convertTo24HourFormat($time12Hour) {
                    $date = DateTime::createFromFormat('h:i A', $time12Hour);
                    return $date->format('H:i:s');
                }

                function convertDateFormat($date, $fromFormat, $toFormat) {
                    $dateTime = DateTime::createFromFormat($fromFormat, $date);
                    return $dateTime->format($toFormat);
                }

                $fromFormat = 'm/d/Y'; // Original format
                $toFormat = 'Y-m-d';   // Desired format

                $convertedPickDate = convertDateFormat($PickDate, $fromFormat, $toFormat);
                $convertedDropDate = convertDateFormat($DropDate, $fromFormat, $toFormat);

                $formatPickTime = convertTo24HourFormat($PickTime);
                $formatDropTime = convertTo24HourFormat($DropTime);

                // Combine date and time for XML
                $pickUpDateTime = $convertedPickDate . 'T' . $formatPickTime;
                $dropOffDateTime = $convertedDropDate . 'T' . $formatDropTime;

                // Define the XML request body with dynamic dates and times
                $xmlRequest = <<<XML
                <?xml version="1.0" encoding="UTF-8"?>
                <OTA_VehAvailRateRQ xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_VehAvailRateRQ.xsd" Version="1.008">
                    <POS>
                        <Source ISOCountry="US" AgentDutyCode="T17R16L5D11">
                            <RequestorID Type="4" ID="X975">
                                <CompanyName Code="CP" CodeContext="4PH5"/>
                            </RequestorID>
                        </Source>
                    </POS>
                    <VehAvailRQCore Status="All">
                        <VehRentalCore PickUpDateTime="$pickUpDateTime" ReturnDateTime="$dropOffDateTime">
                            <PickUpLocation LocationCode="MEL" Type="IATA" />
                            <ReturnLocation LocationCode="SYD" Type="IATA" />
                        </VehRentalCore>
                    </VehAvailRQCore>
                </OTA_VehAvailRateRQ>
                XML;

                // Initialize cURL session
                $ch = curl_init();

                // Set cURL options
                curl_setopt($ch, CURLOPT_URL, "https://vv.xqual.hertz.com/DirectLinkWEB/handlers/DirectLinkHandler?id=ota2007a");
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/xml',
                    'Content-Length: ' . strlen($xmlRequest)
                ]);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest);
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
                echo $response;
            }

            
            if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['view_btn'])) {
                $view_confirmNo = $_POST['view_confirmNo'];
                $view_Lname = $_POST['view_Lname'];
            
                // // Echo statements for the view widget
                // echo "<h2>View Widget</h2>";
                // echo "<p>Confirmation Number: $view_confirmNo</p>";
                // echo "<p>Last Name: $view_Lname</p>";
            }
            
            if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['Edit_btn'])) {
                $Edit_confirmNo = $_POST['Edit_confirmNo'];
                $Edit_Lname = $_POST['Edit_Lname'];
            
                // // Echo statements for the edit widget
                // echo "<h2>Edit Widget</h2>";
                // echo "<p>Confirmation Number: $Edit_confirmNo</p>";
                // echo "<p>Last Name: $Edit_Lname</p>";
            }
            
            if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['Can_btn'])) {
                $Can_confirmNo = $_POST['Can_confirmNo'];
                $Can_Lname = $_POST['Can_Lname'];
            
                // // Echo statements for the cancel widget
                // echo "<h2>Cancel Widget</h2>";
                // echo "<p>Confirmation Number: $Can_confirmNo</p>";
                // echo "<p>Last Name: $Can_Lname</p>";
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
            <!-- search widget -->
            <div id="search-widget" class="api-widget p-3 d-none">
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
                        <div class="input-div d-grid p-2 col-4">
                            <label class="internal-label" for="Pick-up">Pick-up
                                Location</label>
                            <input type="text" class="input" id="Pick-up"
                                name="Pick-up" required>
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
                                        for="Pick-Time">Pick-up-Date</label>
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
                                        for="Drop-Time">Drop-off-Date</label>
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
            <!-- view section -->
            <div id="view-widget" class="api-widget p-3 d-none">
                <form action="" method="post">
                    <div id="view-div1" class="text-end mb-3">
                        <a id="view_to_search" href="#">Start your reservation</a>
                    </div>
                    <div id="view-div2" class="row gap-2"
                        style="margin-left: 12px;">
                        <div class="input-div col-5 p-2 d-grid">
                            <label class="internal-label"
                                for="view_confirmNo">Confirmation Number</label>
                            <input type="text" class="input" id="view_confirmNo"
                                value name="view_confirmNo" required>
                        </div>
                        <div class="input-div col-5 p-2 d-grid">
                            <label class="internal-label" for="view_Lname">Last
                                Name:</label>
                            <input type="text" class="input" id="view_Lname" value
                                name="view_Lname" required>
                        </div>
                        <div class="col-1 d-flex">
                            <button name="view_btn" id="view-btn" class="btn bg-secondary"
                                disabled>View Vechicles</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Edit widget -->
            <div id="Edit-widget" class="api-widget p-3 d-none">
                <form action="" method="post">
                    <div id="Edit-div1" class="text-end mb-3">
                        <a id="Edit_to_search" href="#">Start your reservation</a>
                    </div>
                    <div id="Edit-div2" class="row gap-2"
                        style="margin-left: 12px;">
                        <div class="input-div col-5 p-2 d-grid">
                            <label class="internal-label"
                                for="Edit_confirmNo">Confirmation Number</label>
                            <input type="text" class="input" id="Edit_confirmNo"
                                value name="Edit_confirmNo" required>
                        </div>
                        <div class="input-div col-5 p-2 d-grid">
                            <label class="internal-label" for="Edit_Lname">Last
                                Name:</label>
                            <input type="text" class="input" id="Edit_Lname" value
                                name="Edit_Lname" required>
                        </div>
                        <div class="col-1 d-flex">
                            <button name="Edit_btn" id="Edit-btn" class="btn bg-secondary"
                                disabled>View Vechicles</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Cancle widget -->
            <div id="Can-widget" class="api-widget p-3 d-none">
                <form action="" method="post">
                    <div id="Can-div1" class="text-end mb-3">
                        <a id="Can_to_search" href="#">Start your reservation</a>
                    </div>
                    <div id="Can-div2" class="row gap-2"
                        style="margin-left: 12px;">
                        <div class="input-div col-5 p-2 d-grid">
                            <label class="internal-label"
                                for="Can_confirmNo">Confirmation Number</label>
                            <input type="text" class="input" id="Can_confirmNo"
                                value name="Can_confirmNo" required>
                        </div>
                        <div class="input-div col-5 p-2 d-grid">
                            <label class="internal-label" for="Can_Lname">Last
                                Name:</label>
                            <input type="text" class="input" id="Can_Lname" value
                                name="Can_Lname" required>
                        </div>
                        <div class="col-1 d-flex">
                            <button name="Can_btn" id="Can-btn" class="btn bg-secondary"
                                disabled>View Vechicles</button>
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
                </form>
            </div>
        </div>
    </body>
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <script>
        console.log(window.location.pathname);
        document.addEventListener('DOMContentLoaded',function(){
            document.getElementById('search-widget').classList.remove('d-none');
        })
        document.getElementById('view').addEventListener('click',function(){
            document.getElementById('search-widget').classList.add('d-none');
            document.getElementById('view-widget').classList.remove('d-none');
        })
        document.getElementById('Edit').addEventListener('click',function(){
            document.getElementById('search-widget').classList.add('d-none');
            document.getElementById('Edit-widget').classList.remove('d-none');
        })
        document.getElementById('Cancel').addEventListener('click',function(){
            document.getElementById('search-widget').classList.add('d-none');
            document.getElementById('Can-widget').classList.remove('d-none');
        })
        document.getElementById('Discount_code').addEventListener('click',function(){
            document.getElementById('popup').classList.remove('d-none');
        })
        document.getElementById('cross').addEventListener('click',function(){
            document.getElementById('popup').classList.add('d-none');
        })
        document.getElementById('closeBtn').addEventListener('click',function(){
            document.getElementById('popup').classList.add('d-none');
        })
        // document.getElementById('dicountBtn').addEventListener('click', function(event) {
        //     event.preventDefault(); // Prevent the form from submitting
        //     let Club_Code = document.getElementById('Club_Code').value;
        //     document.getElementById('Discount_code').innerHTML = Club_Code;
        //     document.getElementById('popup').classList.add('d-none'); // Hide the popup after applying the discount code
        // });
        // js for button enable and disable button for view 
        function ViewTooglebtn(){
            var view_confirmNo = document.getElementById('view_confirmNo').value;
            var view_Lname = document.getElementById('view_Lname').value;
            var view_btn = document.getElementById('view-btn');
            if(view_confirmNo !== "" && view_Lname){
                view_btn.classList.remove('bg-secondary');
                view_btn.classList.add('bg-warning');
                view_btn.disabled = false;
            }else{
                view_btn.classList.remove('bg-warning');
                view_btn.classList.add('bg-secondary');
                view_btn.disabled = true;
            }
        }
        document.getElementById('view_to_search').addEventListener('click',function(){
            document.getElementById('search-widget').classList.remove('d-none');
            document.getElementById('view-widget').classList.add('d-none');
        })
        document.getElementById('view_confirmNo').addEventListener('input',ViewTooglebtn);
        document.getElementById('view_Lname').addEventListener('input',ViewTooglebtn);
        // js for button enable and disable button for Edit
        function EditTooglebtn(){
            var Edit_confirmNo = document.getElementById('Edit_confirmNo').value;
            var Edit_Lname = document.getElementById('Edit_Lname').value;
            var Edit_btn = document.getElementById('Edit-btn');
            if(Edit_confirmNo !== "" && Edit_Lname){
                Edit_btn.classList.remove('bg-secondary');
                Edit_btn.classList.add('bg-warning');
                Edit_btn.disabled = false;
            }else{
                Edit_btn.classList.remove('bg-warning');
                Edit_btn.classList.add('bg-secondary');
                Edit_btn.disabled = true;
            }
        }
        document.getElementById('Edit_to_search').addEventListener('click',function(){
            document.getElementById('search-widget').classList.remove('d-none');
            document.getElementById('Edit-widget').classList.add('d-none');
        })
        document.getElementById('Edit_confirmNo').addEventListener('input',EditTooglebtn);
        document.getElementById('Edit_Lname').addEventListener('input',EditTooglebtn);

        // js for button enable and disable button for Cancle

        function CanTooglebtn(){
            var Can_confirmNo = document.getElementById('Can_confirmNo').value;
            var Can_Lname = document.getElementById('Can_Lname').value;
            var Can_btn = document.getElementById('Can-btn');
            if(Can_confirmNo !== "" && Can_Lname){
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
            document.getElementById('search-widget').classList.remove('d-none');
            document.getElementById('Can-widget').classList.add('d-none');
        })
        document.getElementById('Can_confirmNo').addEventListener('input',CanTooglebtn);
        document.getElementById('Can_Lname').addEventListener('input',CanTooglebtn);
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
