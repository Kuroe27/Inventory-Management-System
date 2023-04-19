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
    if (mysqli_query($conn, $sql)) {
        // Redirect the user to a different page to avoid duplicate delete
        header("Location: ingredients.php?deleted=1");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
