<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous">
</head>
<style>
    #spinner {
        width: 100%;
        height: 100%;
        color: #154782;
        margin-top: 12px;
    }
</style>
<body>
    <?php
    session_start();
    unset($_SESSION['dataarray']);
    unset($_SESSION['results']);
    session_destroy();
    ?>
    <div class="d-flex justify-content-center align-items-center" style="height:100vh">
        <div class="text-center">
            <h1>Thanks for with us!</h1>
            <p class="m-0">you will get confirmation on mail.</p>
            <p class="m-0">We will redirecting you please wait or <a class="text-decoration-none text-capitalize" href="index.php">Click here</a></p>
            <div id="spinner">
                <div class="spinner-border" role="status" >
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    function redirect(){
        window.location.href = "index.php";
    }
    setTimeout(redirect,2000);
</script>
</html>