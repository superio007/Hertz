<?php
session_start();
$results = $_SESSION['results'];

function xmlToJson($xmlString)
{
    // Load the XML string into a SimpleXMLElement object
    $xml = simplexml_load_string($xmlString, "SimpleXMLElement", LIBXML_NOCDATA);

    // Convert the SimpleXMLElement object to a JSON string
    $json = json_encode($xml);

    // Decode the JSON string into an associative array
    $array = json_decode($json, true);

    // Return the associative array
    return $array;
}

// Convert XML to JSON
$xmlString = $results; // Use your XML string from the session
$data = xmlToJson($xmlString);
$vehAvails = $data['VehAvailRSCore']['VehVendorAvails']['VehVendorAvail']['VehAvails']['VehAvail'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/74e6741759.js" crossorigin="anonymous"></script>
</head>
<style>
    #search_result p{
        margin: 0;
    }
    #search_result a{
        text-decoration: none;
    }
    .instruct-p{
        font-size: 12px;
        font-weight:700;
    }
    .card img{
        object-fit: contain;
        image-rendering: inherit;
        width: min-content;
    }
    #vechical_spec{
        border-top: 1px solid rgba(0, 0, 0, 0.301);
        border-bottom: 1px solid rgba(0, 0, 0, 0.301);
    }
    #standard_rate{
        font-weight: 400;
    }
    #standard_rate:hover{
        background-color: black;
        color: #ffffff;
    }
    .card{
        width: auto;
        max-width: 400px;
        border-bottom: yellow 4px solid; 
        border-radius: 5px;
        box-shadow: rgba(128, 128, 128, 0.432) 2px 4px 2px 1px;
    }
    #search_box{
        background-color: #5c5f65;
        margin: auto;
        border-bottom: yellow 3px solid;
        margin-bottom: 2rem;
        padding: 0.8rem;
    }
    #car_result{
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px; /* Adjust the gap between cards as needed */
        padding: 20px;
    }
    @media screen and (max-width:769px) {
        #car_result{
            grid-template-columns: repeat(1, 1fr);
        }
    }
</style>
<body>
    <div id="search_result" class="bg-white">
        <div id="search_box" class="row align-items-center d-none d-md-flex">
            <div class="col-6 d-flex align-items-center">
                <div class="col-4">
                    <p class="text-white">Pick-up Location</p>
                    <p class="text-white">Melbroune Airport</p>
                </div>
                <div class="col-4">
                    <p class="text-white">Pick-up Time</p>
                    <p class="text-white">Mon, 19 Aug, 2024 at 01:00</p>
                </div>
                <div class="col-4">
                    <p class="text-white">Drop-off Time</p>
                    <p class="text-white">Tue, 20 Aug, 2024 at 01:00</p>
                </div>
            </div>
            <div class="col-6 d-flex align-items-center justify-content-end gap-2">
                <i class="fa-solid fa-cart-shopping fa-xl" style="color: #ffffff;"></i>
                <p class="text-white">425.02</p>
                <p class="text-white">AUD</p>
            </div>
        </div>
        <div id="search_box" class="row align-items-center d-md-none">
            <div class="d-flex justify-content-end">
                <button style="width: auto;" class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="fa-solid fa-list fa-lg" style="color: #ffffff;"></i></button>
            </div>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasRightLabel">Offcanvas right</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <div>
                        <div >
                            <p>Pick-up Location</p>
                            <p>Melbroune Airport</p>
                        </div>
                        <div >
                            <p>Pick-up Time</p>
                            <p>Mon, 19 Aug, 2024 at 01:00</p>
                        </div>
                        <div >
                            <p>Drop-off Time</p>
                            <p>Tue, 20 Aug, 2024 at 01:00</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div style="margin-bottom: 2rem;" class="row align-items-center">
                <div class="row">
                    <div class="col-6">
                        <h2 style="font-size: 28px;font-weight: 300;">Showing All Vehicle Results</h2>
                    </div>
                    <div class="col-6 align-items-baseline d-flex justify-content-end gap-2">
                        <p class="instruct-p">
                            Sort Vehicle By :
                        </p>
                        <p>
                            <a href="#" class="instruct-p">Price</a> | <a href="#" class="instruct-p">Size</a>
                        </p>
                    </div>
                </div>
                <div class="col-8">
                    <p><a href="#" class="instruct-p">Credit Card Surcharge</a>:</p>
                    <ul>
                        <li>
                            <p class="instruct-p"><strong> Pay Now price</strong> <span style="color: darkgray;">quoted includes Credit Card Surcharge.</span></p>
                        </li>
                        <li>
                            <p class="instruct-p"><strong>Pay Later price</strong> <span style="color: darkgray;">is quoted based on cash payment and does not include Credit Card Surcharge of 1.35% which will be applied at counter.</span></p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="">
                <div id="car_result">
                    <?php foreach($vehAvails as $vehAvail):?>
                        <?php
                            // Calculate the total price
                            $rateTotalAmount = (float) $vehAvail['VehAvailCore']['TotalCharge']['@attributes']['RateTotalAmount'];
                            $estimatedTotalAmount = (float) $vehAvail['VehAvailCore']['TotalCharge']['@attributes']['EstimatedTotalAmount'];
                            $finalPrice = $rateTotalAmount;

                            // Add up the fees
                            if (isset($vehAvail['VehAvailCore']['Fees']['Fee'])) {
                                foreach ($vehAvail['VehAvailCore']['Fees']['Fee'] as $fee) {
                                    $finalPrice += (float) $fee['@attributes']['Amount'];
                                }
                            }
                        ?>
                        <div class="card" >
                            <div style="height: 210px;position: relative;" class="d-flex justify-content-center align-items-center">
                                <img src="<?php echo 'https://images.hertz.com/vehicles/220x128/'. $vehAvail['VehAvailCore']['Vehicle']['PictureURL']?>" alt="image not available">
                                <div style="position: absolute; bottom: 0; left: 25px;">
                                    <h2>Economy</h2>
                                    <p><?php echo  $vehAvail['VehAvailCore']['Vehicle']['VehMakeModel']['@attributes']['Name']?></p>
                                </div>
                            </div>
                            <div  id="vechical_spec" class="my-2">
                                <div class="card-body row offset-1">
                                    <div class="col-2 d-flex align-items-center">
                                        <img src="https://images.hertz.com/content/dam/irac/Overlay/icons/person.png" alt="">
                                        <p><?php echo  $vehAvail['VehAvailCore']['Vehicle']['@attributes']['PassengerQuantity']?></p>
                                    </div>
                                    <div class="col-2 d-flex align-items-center">
                                        <img src="https://images.hertz.com/content/dam/irac/Overlay/icons/feature_suitcase.png" alt="">
                                        <p><?php echo  $vehAvail['VehAvailCore']['Vehicle']['@attributes']['BaggageQuantity']?></p>
                                    </div>
                                    <div class="col-2 d-flex align-items-center">
                                        <img src="https://images.hertz.com/content/dam/irac/Overlay/icons/feature_transmission.png" alt="">
                                        <p class="transmission"><?php 
                                        if($vehAvail['VehAvailCore']['Vehicle']['@attributes']['TransmissionType'] == "Automatic"){
                                            echo "A";
                                        }else{
                                            echo "M";
                                        }
                                          ?></p>
                                    </div>
                                    <div class="col-3 d-flex align-items-center">
                                        <img src="https://images.hertz.com/content/dam/irac/Overlay/icons/feature_fuel.png" alt="">
                                        <p class="d-flex">4.8</p>
                                        <p>l/</p>
                                        <p>100km</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row p-3">
                                <div class="d-flex justify-content-center align-content-center col-6">
                                    <h3><?php echo number_format($finalPrice, 2); ?></h3>
                                    <p class="pt-1">AUD</p>
                                </div>
                                <div class="col-6">
                                    <button class="btn" style="border: 1px solid black;" id="standard_rate">Standard Rate</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>