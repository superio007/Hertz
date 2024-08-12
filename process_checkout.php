<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $mobileCountryCode = $_POST['mobile_country_code'] ?? '';
    $mobileNumber = $_POST['mobile_number'] ?? '';
    $subscribe = isset($_POST['subscribe']) ? 'Yes' : 'No';

    $companyOrder = $_POST['company_order'] ?? '';
    $arrivalInfo = $_POST['arrival_info'] ?? '';
    $travelInfo = $_POST['travel_info'] ?? '';
    $rewardsInfo = $_POST['rewards_info'] ?? '';
    $customerRemarks = $_POST['customer_remarks'] ?? '';

    // Example: Output the data (or process/store as needed)
    echo "<h1>Checkout Information</h1>";
    echo "<p><strong>First Name:</strong> $firstName</p>";
    echo "<p><strong>Last Name:</strong> $lastName</p>";
    echo "<p><strong>Email:</strong> $email</p>";
    echo "<p><strong>Mobile Country Code:</strong> $mobileCountryCode</p>";
    echo "<p><strong>Mobile Number:</strong> $mobileNumber</p>";
    echo "<p><strong>Subscribe to Newsletter:</strong> $subscribe</p>";
    echo "<hr>";
    echo "<p><strong>Company Order:</strong> $companyOrder</p>";
    echo "<p><strong>Arrival Info:</strong> $arrivalInfo</p>";
    echo "<p><strong>Travel Info:</strong> $travelInfo</p>";
    echo "<p><strong>Rewards Info:</strong> $rewardsInfo</p>";
    echo "<p><strong>Customer Remarks:</strong> $customerRemarks</p>";
}
?>
