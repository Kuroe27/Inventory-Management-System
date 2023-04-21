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
    // Get the ingredient ID from the form
    $ingredientID = mysqli_real_escape_string($conn, $_POST['ingredientID']);

    // Delete the ingredient from the database
    $sql = "DELETE FROM Ingredients WHERE IngredientID = '$ingredientID'";
    try {
        if (mysqli_query($conn, $sql)) {
            // Redirect the user to a different page to avoid duplicate delete
            header("Location: ingredients.php");
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        // Display an error message to the user using JavaScript alert
        echo '<script>alert("Before deleting the ingredients, please ensure that you delete the corresponding menu item first.");</script>';
        // Use JavaScript to navigate back to the previous page
        echo '<script>history.go(-1);</script>';
        exit();
    }

}


// Close the database connection
mysqli_close($conn);
