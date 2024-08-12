<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .checkout-container {
            margin: 50px auto;
            max-width: 1200px;
        }
        .driver-info, .total-box, .additional-info {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .driver-info .header,
        .additional-info .header {
            background-color: #ffd207;
            padding: 10px;
            font-weight: bold;
            border-radius: 3px 3px 0 0;
            margin-bottom: 15px;
        }
        .driver-info .form-group label,
        .additional-info .form-group label {
            font-weight: bold;
        }
        .total-box {
            position: sticky;
            top: 20px;
        }
        .total-box h2 {
            font-size: 2rem;
            font-weight: bold;
        }
        .total-box .price-details,
        .total-box .your-car {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .total-box .your-car img {
            width: 50px;
            margin-right: 10px;
        }
        .total-box .price-details a,
        .total-box .your-car a {
            text-decoration: none;
            color: #000;
        }
        .total-box .price-details a:hover,
        .total-box .your-car a:hover {
            text-decoration: underline;
        }
        .total-box .car-info {
            color: green;
            font-style: italic;
            font-weight: bold;
        }
        .subscribe-section {
            margin-top: 10px;
        }
        .additional-info .form-group textarea {
            resize: none;
            height: 100px;
        }
        .submit-btn {
            text-align: center;
            margin-top: 20px;
        }
        .submit-btn button {
            padding: 10px 30px;
            font-size: 1.2rem;
        }
        .terms {
            font-size: 0.9rem;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container checkout-container">
    <h2>Checkout</h2>
    <div class="row">
        <div class="col-md-8">
            <!-- Form starts here -->
            <form action="process_checkout.php" method="POST">
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
                                <option value="+91" selected>India (+91)</option>
                                <option value="+1">United States (+1)</option>
                                <option value="+61">Australia (+61)</option>
                                <!-- Add more options as needed -->
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="mobileNumber">Mobile Number:</label>
                            <input type="tel" class="form-control" id="mobileNumber" name="mobile_number" placeholder="Mobile Number" required>
                        </div>
                    </div>
                    <div class="form-group subscribe-section">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="subscribeCheckbox" name="subscribe">
                            <label class="form-check-label" for="subscribeCheckbox">
                                By selecting this checkbox you are indicating that you would like to periodically receive marketing and promotional materials via email from Hertz.
                            </label>
                        </div>
                    </div>
                </div>

                <div class="additional-info">
                    <div class="header">
                        <a href="#collapseAdditionalInfo" data-toggle="collapse" aria-expanded="false" aria-controls="collapseAdditionalInfo" class="d-block text-dark">
                            Additional Information
                        </a>
                    </div>
                    <div id="collapseAdditionalInfo" class="collapse">
                        <div class="form-group">
                            <label for="companyOrder">Company order or billing reference</label>
                            <input type="text" class="form-control" id="companyOrder" name="company_order" placeholder="Company order or billing reference">
                        </div>
                        <div class="form-group">
                            <label for="arrivalInfo">Arrival/Flight Information <small>(Optional Details)</small></label>
                            <textarea class="form-control" id="arrivalInfo" name="arrival_info" placeholder="Arrival/Flight Information"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="travelInfo">Travel information</label>
                            <textarea class="form-control" id="travelInfo" name="travel_info" placeholder="Travel information"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="rewardsInfo">Rewards program information</label>
                            <textarea class="form-control" id="rewardsInfo" name="rewards_info" placeholder="Rewards program information"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="customerRemarks"><i class="fas fa-question-circle"></i> Customer Remarks</label>
                            <textarea class="form-control" id="customerRemarks" name="customer_remarks" placeholder="Customer Remarks"></textarea>
                        </div>
                    </div>
                    <div class="terms">
                        'Total' does not include any additional items you may select at the location or any costs arising from the rental (such as damage, fuel or road traffic charges, & local insurance). For renters under the age of 25, additional charges may apply, and are payable at the location.
                        <br><br>
                        By clicking on the "Submit" button, you confirm that you understand and accept our <a href="#">Rental Qualification and Requirements</a>, <a href="#">Terms and Conditions</a>.
                    </div>
                </div>

                <div class="submit-btn">
                    <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                </div>
            </form>
            <!-- Form ends here -->
        </div>
        <div class="col-md-4">
            <div class="total-box">
                <h5>Est. Total</h5>
                <h2 class="text-right">4162.66 AUD</h2>
                <p class="text-right">Charged At Pickup</p>
                <div class="price-details">
                    <a href="#" data-toggle="collapse" data-target="#priceDetails">Price Details <span class="float-right">-</span></a>
                    <div id="priceDetails" class="collapse show">
                        <p><strong>Payment Method:</strong> Pay Later</p>
                        <p><strong>Base Rate</strong><br>1 month at 1461.80 AUD = 1461.80 AUD<br>9 extra days at 52.18 AUD = 469.62 AUD</p>
                        <p><strong>Included:</strong><br>&#9679; Total Sales Tax<br>&#9679; Location Fee<br>&#9679; Vehicle Registration Fee<br>&#9679; Admin Recovery<br>&#9679; Drivers Age Surcharge (Paid at Location)<br>3700 Kilometers Included</p>
                        <p><strong>Not Included:</strong><br>0.28 AUD Each Extra Kilometer</p>
                        <p><strong>Amount To Be Paid At Time Of Rent:</strong><br>4162.66 AUD</p>
                        <p><strong>Rate Code:</strong> M28NSG</p>
                    </div>
                </div>
                <div class="your-car">
                    <h6>Your Car</h6>
                    <a href="#">Edit</a>
                    <p>Sports EV (Group D5) RSAC (D5) Polestar 2 or similar</p>
                    <p class="car-info">Green Collection</p>
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
