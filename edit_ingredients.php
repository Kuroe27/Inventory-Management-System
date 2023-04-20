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
    $ingredientID = mysqli_real_escape_string($conn, $_POST['ingredientID']);
    $ingredientName = mysqli_real_escape_string($conn, $_POST['ingredientName']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $measurementID = mysqli_real_escape_string($conn, $_POST['measurementID']);

    // Update the ingredient in the database
    $sql = "UPDATE Ingredients SET IngredientName='$ingredientName', Quantity='$quantity', MeasurementID='$measurementID' WHERE IngredientID='$ingredientID'";
    if (mysqli_query($conn, $sql)) {
        // Redirect the user to a different page to avoid duplicate insert
        header("Location: ingredients.php?success=2");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Retrieve the ingredient details from the database
if (isset($_GET['ingredientID'])) {
    $ingredientID = mysqli_real_escape_string($conn, $_GET['ingredientID']);

    $sql = "SELECT * FROM Ingredients WHERE IngredientID='$ingredientID'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $ingredientName = $row['IngredientName'];
        $quantity = $row['Quantity'];
        $measurementID = $row['MeasurementID'];
    } else {
        echo "Error: Ingredient not found.";
        exit();
    }
} else {
    echo "Error: Invalid request.";
    exit();
}

// Retrieve all measurements from the database
$sql_measurements = "SELECT * FROM Measurements";
$result_measurements = mysqli_query($conn, $sql_measurements);

// Close the database connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit Ingredient</title>
</head>
<body>
	<h1>Edit Ingredient</h1>
	<form method="post">
		<label for="ingredientName">Ingredient Name:</label>
		<input type="text" name="ingredientName" value="Tomato" required>
		<br>
		<label for="quantity">Quantity:</label>
		<input type="number" name="quantity" value="2" min="1" required>
		<br>
		<label for="measurementID">Measurement:</label>
		<select name="measurementID" required>
			<option value="1" selected>grams</option>
			<option value="2">cups</option>
			<option value="3">pieces</option>
		</select>
		<br>
		<input type="submit" value="Save Changes">
	</form>
	<br>
	<a href="ingredients.php">Back to Ingredients</a>
</body>
</html>
