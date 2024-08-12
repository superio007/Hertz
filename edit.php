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
<?php 
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['Edit_btn'])) {
    $Edit_confirmNo = $_POST['Edit_confirmNo'];
    $Edit_Lname = $_POST['Edit_Lname'];

    // // Echo statements for the edit widget
    // echo "<h2>Edit Widget</h2>";
    // echo "<p>Confirmation Number: $Edit_confirmNo</p>";
    // echo "<p>Last Name: $Edit_Lname</p>";
}
?>
<body>
<div class="container">
    <h1 class="text-center">Rent a car!</h1>
    <div id="Edit-widget" class="api-widget p-3 ">
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
</div>
<script>
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
            window.location.href = "index.php";
        })
        document.getElementById('Edit_confirmNo').addEventListener('input',EditTooglebtn);
        document.getElementById('Edit_Lname').addEventListener('input',EditTooglebtn);
</script>
</body>
</html>