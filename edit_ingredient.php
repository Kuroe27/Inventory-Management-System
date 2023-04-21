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

// Insert data into the database
$ingredientName = $_POST['ingredientName'];
$quantity = $_POST['quantity'];
$measurementID = $_POST['measurementID'];
$sql = "INSERT INTO ingredients (IngredientName, Quantity, MeasurementID) VALUES ('$ingredientName', '$quantity', '$measurementID')";
if ($conn->query($sql) === true) {
    echo "Data inserted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection
mysqli_close($conn);
