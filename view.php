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
        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['view_btn'])) {
            $view_confirmNo = $_POST['view_confirmNo'];
            $view_Lname = $_POST['view_Lname'];
        
            echo "<script>window.location.href=\"index.html?cnfNo=$view_confirmNo&lName=$view_Lname\"</script>";
        }
    ?>
<div class="container">
    <h1 class="text-center">View your car!</h1>
    <div id="view-widget" class="api-widget p-3">
        <form action="" method="post">
            <div id="view-div1" class="text-end mb-3">
                <a id="view_to_search" href="#">Start your reservation</a>
            </div>
            <div id="view-div2" class="row gap-2"
                style="margin-left: 12px;">
                <div class="input-div col-5 p-2 d-grid">
                    <label class="internal-label"
                        for="view_confirmNo">Confirmation Number</label>
                    <input type="text" class="input text-uppercase" id="view_confirmNo"
                        value name="view_confirmNo" required>
                </div>
                <div class="input-div col-5 p-2 d-grid">
                    <label class="internal-label" for="view_Lname">Last
                        Name:</label>
                    <input type="text" class="input text-uppercase" id="view_Lname" value
                        name="view_Lname" required>
                </div>
                <div class="col-1 d-flex">
                    <button name="view_btn" id="view-btn" class="btn bg-secondary"
                        disabled>View Details</button>
                </div>
            </div>
        </form>
    </div>
</div>
    <script>
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
            window.location.href = "index.php";
        })
        document.getElementById('view_confirmNo').addEventListener('input',ViewTooglebtn);
        document.getElementById('view_Lname').addEventListener('input',ViewTooglebtn);
    </script>
</body>
</html>