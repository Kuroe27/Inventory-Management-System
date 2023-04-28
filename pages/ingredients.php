
<?php include 'sidebar.html'; ?>
<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bundatan_db";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if (isset($_POST["insert"])) {
    $IngredientName = $_POST["IngredientName"];
    $Quantity = $_POST["Quantity"];
    $MeasurementID = $_POST["MeasurementID"];
    $conn->query("INSERT INTO ingredients (IngredientName, Quantity, MeasurementID) VALUES ('$IngredientName', '$Quantity', '$MeasurementID')");
    header("Location: ingredients.php");
    exit();
}



// check if the form has been submitted for deleting an measurement
if (isset($_POST["delete"])) {
    $IngredientID = $_POST["IngredientID"];
    // delete the measurement from the database
    $conn->query("DELETE FROM ingredients WHERE IngredientID=$IngredientID");
}

// check if the form has been submitted for editing an measurement
    if (isset($_POST["save"])) {
    $IngredientID = $_POST["IngredientID"];
    $IngredientName = $_POST["IngredientName"];
    $Quantity = $_POST["Quantity"];
    $MeasurementID = $_POST["MeasurementID"];

    // update the order in the database
    $conn->query("UPDATE ingredients SET IngredientName='$IngredientName', Quantity='$Quantity', MeasurementID='$MeasurementID' WHERE IngredientID='$IngredientID'");

}

$result = $conn->query("SELECT * FROM ingredients");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="wInIngredientIDth=device-wInIngredientIDth, initial-scale=1.0">
  <title>Measurement</title>
    <link rel="stylesheet" href="../style.css">

</head>
<body>

<form method="POST">
  <label for="IngredientName">IngredientName:</label>
  <input type="text"
   name="IngredientName" required>
  <br>

  <label for="Quantity"  >Quantity:</label>
  <input type="number" min="1"
   name="Quantity" required>
  <br>

  <select name="MeasurementID">
    <?php
      $measurements = $conn->query("SELECT * FROM measurements");
while ($measurement = $measurements->fetch_assoc()) {
    echo "<option value='" . $measurement["MeasurementID"] . "'>" . $measurement["MeasurementName"] . "</option>";
}
?>
  </select>

 
  <br>
  <button type="submit" name="insert">Insert</button>
</form>

<table>
        <tr>
            <th>IngredientID</th>
            <th>IngredientName</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td>
                    
                    <form method='POST'>

                        <!-- inputs -->
                        <!-- id -->
                        <input type='text' 
                        name='IngredientID' 
                        class='id'
                        value='<?php echo $row["IngredientID"]; ?>'>

                        <!-- name -->
                        <input  id='ingredientsInput<?php echo $row["IngredientID"]; ?>'
                        class='all<?php echo $row["IngredientID"]; ?>'
                        REQUIRED
                        IngredientID='IngredientName_<?php echo $row["IngredientID"]; ?>' 
                        type='text' name='IngredientName' 
                        value='<?php echo $row["IngredientName"]; ?> ' disabled>
                        
                        <select name="MeasurementID" disabled                class='all<?php echo $row["IngredientID"]; ?>'>
    <?php
    $measurements = $conn->query("SELECT * FROM measurements");
            while ($measurement = $measurements->fetch_assoc()) {
                $selected = ($measurement["MeasurementID"] == $row["MeasurementID"]) ? "selected" : "";
                echo "<option value='" . $measurement["MeasurementID"] . "' " . $selected . ">" . $measurement["MeasurementName"] . "</option>";
            }
            ?>
    </select>
                        <!-- quantity -->
                        <input id='name<?php echo $row["IngredientID"]; ?>' 
    REQUIRED
    IngredientID='IngredientName_<?php echo $row["IngredientID"]; ?>' 
    type='number' name='Quantity' 
    class='all<?php echo $row["IngredientID"]; ?>'
    value='<?php echo preg_replace("/[^0-9.]/", "", $row["Quantity"]); ?>' 
    step='any' disabled>


                        <!-- measurement -->
                        <input  class='savebtn' value='<?php echo $row["MeasurementID"]; ?> ' >

                     

                        
                 </td>
                <td>
                
                <!-- buttons -->

                    <button IngredientID='editbtn<?php echo $row["IngredientID"]; ?>' 
                    type='button' name='editbtn<?php echo $row["IngredientID"]; ?>' 
                    id='editbtn<?php echo $row["IngredientID"]; ?>' 
                    onclick='enableInputFields(<?php echo intval($row["IngredientID"]); ?>)'>Edit</button>


                    <button
                    class='cancelbtn'
                     id="cancel<?php echo $row["IngredientID"]; ?>"
                     onclick='myFunction(<?php echo intval($row["IngredientID"]); ?>)'>Cancel
                    </button>
                    
                    <button
                    class='savebtn'
                    name='save'
                     id="save<?php echo $row["IngredientID"]; ?>"
                     onclick='myFunction(<?php echo intval($row["IngredientID"]); ?>)'>Save
                    </button>

                    <button type='submit' name='delete'    onclick='saveFunction(<?php echo intval($row["IngredientID"]); ?>)'>Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="measurement.php">Measurement</a>
    <a href="../Menu/menuItems.php">s</a>
    <script src="../script.js"></script>
</body>
</html>
