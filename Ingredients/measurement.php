<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lomitrack";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);


if (isset($_POST["insert"])) {
    $MeasurementName = $_POST["MeasurementName"];
    $conn->query("INSERT INTO measurements (MeasurementName) VALUES ('$MeasurementName')");
    header("Location: measurement.php");
    exit();
}

// check if the form has been submitted for editing an measurement
if (isset($_POST["save"])) {
    $MeasurementID = $_POST["MeasurementID"];
    $MeasurementName = $_POST["MeasurementNamex"];
    // update the order in the database
    $conn->query("UPDATE measurements SET MeasurementName='$MeasurementName' WHERE MeasurementID=$MeasurementID");
}

// check if the form has been submitted for deleting an measurement
if (isset($_POST["delete"])) {
    $MeasurementID = $_POST["MeasurementID"];
    // delete the measurement from the database
    $conn->query("DELETE FROM measurements WHERE MeasurementID=$MeasurementID");
}


$result = $conn->query("SELECT * FROM measurements");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="wMeasurementIDth=device-wMeasurementIDth, initial-scale=1.0">
  <title>Measurement</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>

<form method="POST">
  <label for="MeasurementName">MeasurementName:</label>
  <input type="text"
   name="MeasurementName" required>
  <br>
  <button type="submit" name="insert">Insert</button>
</form>

<table>
        <tr>
            <th>MeasurementID</th>
            <th>MeasurementName</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td>
                    <form method='POST'>
                        <input type='text' 
                        name='MeasurementID' 
                        class='id'
                        value='<?php echo $row["MeasurementID"]; ?>'>

          

                        <input  id='name<?php echo $row["MeasurementID"]; ?>' 
                        REQUIRED
                        class='all<?php echo $row["MeasurementID"]; ?>' 
                        MeasurementID='MeasurementName<?php echo $row["MeasurementID"]; ?>' 
                        type='text' name='MeasurementNamex' 
                        value='<?php echo $row["MeasurementName"]; ?> ' disabled>
                 </td>
                <td>
              

                    <button MeasurementID='editbtn<?php echo $row["MeasurementID"]; ?>' 
                    type='button' name='editbtn<?php echo $row["MeasurementID"]; ?>' 
                    id='editbtn<?php echo $row["MeasurementID"]; ?>' 
                    onclick='enableInputFields(<?php echo intval($row["MeasurementID"]); ?>)'>Edit</button>


                    <button
                    class='cancelbtn'
                     id="cancel<?php echo $row["MeasurementID"]; ?>"
                     onclick='myFunction(<?php echo intval($row["MeasurementID"]); ?>)'>Cancel
                    </button>
                    
                    <button
                    class='savebtn'
                    name='save'
                     id="save<?php echo $row["MeasurementID"]; ?>"
                     onclick='myFunction(<?php echo intval($row["MeasurementID"]); ?>)'>Save
                    </button>

                    <button type='submit' name='delete'   >Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
<script src="../script.js"></script>
</body>
</html>
