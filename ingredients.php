<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ims_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the values from the form
    $ingredientName = mysqli_real_escape_string($conn, $_POST['ingredientName']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $measurementID = mysqli_real_escape_string($conn, $_POST['measurementID']);

    // Insert the new ingredient into the database
    $sql = "INSERT INTO Ingredients (IngredientName, Quantity, MeasurementID) VALUES ('$ingredientName', '$quantity', '$measurementID')";
    if (mysqli_query($conn, $sql)) {
        // Redirect the user to a different page to avoid duplicate insert
        header("Location: ingredients.php?success=1");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Check if the delete button has been pressed
// Check if the delete button has been pressed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_ingredient'])) {
    $ingredientID = mysqli_real_escape_string($conn, $_POST['ingredientID']);

    // Start a transaction
    mysqli_begin_transaction($conn);

    try {
        // Delete the related rows in the menuitemingredients table
        $sql_delete_related = "DELETE FROM menuitemingredients WHERE IngredientID = $ingredientID";
        mysqli_query($conn, $sql_delete_related);

        // Delete the row from the Ingredients table
        $sql_delete_ingredient = "DELETE FROM Ingredients WHERE IngredientID = $ingredientID";
        mysqli_query($conn, $sql_delete_ingredient);

        // Commit the transaction
        mysqli_commit($conn);

        header("Location: ingredients.php?delete_success=1");
        exit();
    } catch (mysqli_sql_exception $exception) {
        // Rollback the transaction on exception
        mysqli_rollback($conn);

        echo "Error: " . $exception->getMessage();
    }
}


// Retrieve all ingredients from the database
$sql = "SELECT Ingredients.IngredientID, Ingredients.IngredientName, Ingredients.Quantity, Measurements.MeasurementName
        FROM Ingredients
        JOIN Measurements ON Ingredients.MeasurementID = Measurements.MeasurementID";

$result = mysqli_query($conn, $sql);

// Retrieve all measurements from the database
$sql_measurements = "SELECT * FROM Measurements";
$result_measurements = mysqli_query($conn, $sql_measurements);

// Close the database connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Add Ingredient</title>
</head>
<body>
  <?php if (isset($_GET['success'])) { ?>
    <p>Ingredient added successfully.</p>
  <?php } ?>
  <h1>Add Ingredient</h1>
  <form method="post">
    <label for="ingredientName">Ingredient Name:</label>
    <input type="text" name="ingredientName" required>
    <br>
    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" min="1" required>
    <br>
    <label for="measurementID">Measurement:</label>
    <select name="measurementID" required>
      <?php
        // Display the measurements dropdown
      // Display the measurements dropdown
while ($row_measurement = mysqli_fetch_assoc($result_measurements)) {
    echo "<option value='" . $row_measurement['MeasurementID'] . "'>" . $row_measurement['MeasurementName'] . "</option>";

}

?>
    </select>
    <br>
    <input type="submit" value="Add Ingredient">
  </form>
  
  <?php
if (mysqli_num_rows($result) > 0) {
    // Display the ingredients table
    echo "<h2>Ingredients</h2>";
    echo "<table>";
    echo "<tr><th>Ingredient Name</th><th>Quantity</th><th>Measurement</th><th>Actions</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>" . $row["IngredientName"] . "</td><td>" . $row["Quantity"] . "</td><td>" . $row["MeasurementName"] . "</td>";
        echo "<td>";
        // Edit button
        echo "<form method='post' action='edit_ingredient.php' style='display: inline-block;'>";
        echo "<input type='hidden' name='ingredientID' value='" . $row["IngredientID"] . "'>";
        echo "<input type='submit' value='Edit'>";
        echo "</form>";
        // Delete button
        echo "<form method='post' action='delete_ingredient.php' style='display: inline-block;'>";
        echo "<input type='hidden' name='ingredientID' value='" . $row["IngredientID"] . "'>";
        echo "<input type='submit' value='Delete'>";
        echo "</form>";
        echo "</td></tr>";
    }
    echo "</table>";
}


?>
  
  <br>
  <a href="menu.php">Back to menu</a>
</body>
</html>
