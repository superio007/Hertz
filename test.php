<?php
require 'dbconn.php';
$sql = "SELECT * FROM `airport_list`";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Airport Details</title>
</head>
<body>
    <h2>Airport Details</h2>
    <input type="text" list="airport_name" id>
    <datalist id="airport_name">
        <?php while($row = $result->fetch_assoc()):?>
            <option value="<?php echo $row['citycode'];?>"><?php echo $row['city'] .' ' .  $row['airpotname'];?></option>
        <?php endwhile;?>
    </datalist>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>