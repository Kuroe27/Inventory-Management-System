
<?php
ob_start(); // Start output buffering
?>
<?php include 'sidebar.html'; ?>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbbundatan";
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
    try {
        // delete the measurement from the database
        $conn->query("DELETE FROM ingredients WHERE IngredientID=$IngredientID");
        header("Location: ingredients.php");
        exit();
    } catch (mysqli_sql_exception $e) {
        echo "<script>alert('Error deleting the ingredient: " . $e->getMessage() . "');</script>";
    }
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

$searchQuery = "";
if (isset($_GET["search"])) {
    $searchQuery = $_GET["searchQuery"];
}

$result = $conn->query("SELECT * FROM ingredients WHERE IngredientName LIKE '%$searchQuery%'");


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
</head>

<body>

    <div class="Tableheader">
        <h1>Ingredients</h1>
        <form method="GET" class="searhForm">
            <input type="text" name="searchQuery" class="search" placeholder="Search...">
            <button type="submit" name="search">Search</button>
        </form>

        <button class="create" onclick="showForm()">New</button>
    </div>
    <div class="insertion">
        <div class="container">


            <div class="form"  id="insert-form">
                <div class="formHeader">    
                <h2 class="formTitle">Insert New Ingredient</h2>
                <img src="../icons/cross.png"  class="close" onclick="hideForm()">
             
                </div>
 

            <form method="POST" >
    <div class="secondForm">
  <div class="inputs">
  <label for="IngredientName">Ingredient Name:</label>
  <input type="text" name="IngredientName" required>
   </div>
   
   <div class="inputs">
   <label for="Quantity"  >Quantity:</label>
  <input type="number" min="1"
   name="Quantity" required>
   </div>
   <div class="inputs">
   <label for="Measurement"  >Measurement:</label>
   <select name="MeasurementID">
    <?php
      $measurements = $conn->query("SELECT * FROM measurements");
while ($measurement = $measurements->fetch_assoc()) {
    echo "<option value='" . $measurement["MeasurementID"] . "'>" . $measurement["MeasurementName"] . "</option>";
}
?>
  </select>
   </div>
  <button type="submit" name="insert" class="insert">Insert</button>
  </div>
</form>
            </div>
            

            <div class="tableContainer">
            <table>
            <thead>
    <tr>
        <th>IngredientID</th>
        <th>IngredientName</th>
        <th>Quantity</th>
        <th>Measurement</th>
        <th>Ingredients Table</th>
        <th>Actions</th>
    </tr>
    </thead>
    <?php while ($row = $result->fetch_assoc()) : ?>
        <tr>
        <td>
        <span>Ingredients Id: </span>
            <?php echo $row["IngredientID"]; ?></td>
                <form method='POST'>
                    <input type='text' name='IngredientID' class='id' value='<?php echo $row["IngredientID"]; ?>'>
            </td>
            <td>
                    <span>Ingredients Name: </span>
                    <input  id='ingredientsInput<?php echo $row["IngredientID"]; ?>' class='all<?php echo $row["IngredientID"]; ?>' REQUIRED
                        IngredientID='IngredientName_<?php echo $row["IngredientID"]; ?>' type='text' name='IngredientName' value='<?php echo $row["IngredientName"]; ?> ' disabled>
            </td>
            <td>
                <span>Quantity:</span>
                    <input id='quantityInput<?php echo $row["IngredientID"]; ?>' REQUIRED class='all<?php echo $row["IngredientID"]; ?>' type='number' name='Quantity' value='<?php echo $row["Quantity"]; ?>' step='any' disabled>
            </td>
            <td>
                <span>Mesurement:</span>
                    <select id='measurementInput<?php echo $row["IngredientID"]; ?>' class='all<?php echo $row["IngredientID"]; ?>' name='MeasurementID' disabled>
                        <?php
                            $measurements = $conn->query("SELECT * FROM measurements");
                            while ($measurement = $measurements->fetch_assoc()) {
                                $selected = ($measurement["MeasurementID"] == $row["MeasurementID"]) ? "selected" : "";
                                echo "<option value='" . $measurement["MeasurementID"] . "' " . $selected . ">" . $measurement["MeasurementName"] . "</option>";
                            }
                        ?>
                    </select>
            </td>
            <td>
                    <button IngredientID='editbtn<?php echo $row["IngredientID"]; ?>' type='button' name='editbtn<?php echo $row["IngredientID"]; ?>' id='editbtn<?php echo $row["IngredientID"]; ?>' onclick='enableInputFields(<?php echo intval($row["IngredientID"]); ?>)'>Edit</button>
                    <button class='cancelbtn' id="cancel<?php echo $row["IngredientID"]; ?>" onclick='cancelFunction(<?php echo intval($row["IngredientID"]); ?>)'>Cancel</button>
                    <button class='savebtn' name='save' id="save<?php echo $row["IngredientID"]; ?>" onclick='saveFunction(<?php echo intval($row["IngredientID"]); ?>)'>Save</button>
                    <button type='submit' name='delete' class="deletebtn" onclick='deleteFunction(<?php echo intval($row["IngredientID"]); ?>)'>Delete</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>


            </div>
        </div>
    </div>
    <script src="script.js"></script>
</body>

</html>
<?php
ob_end_flush(); // Flush and output the buffer
?>