<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bundatan_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query the database to get the top 2 sold menu items
$sql = "SELECT 
            mi.MenuItemName,
            mi.MenuItemPrice,
            mi.MenuItemImage
        FROM 
            menuitems mi
        INNER JOIN 
            sales s ON mi.MenuItemID = s.MenuItemID
        GROUP BY 
            s.MenuItemID
        ORDER BY 
            SUM(s.QuantitySold) DESC
        LIMIT 2";

$result = $conn->query($sql);

// Display the results
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<h2>" . $row["MenuItemName"] . "</h2>";
        echo "<p>Price: " . $row["MenuItemPrice"] . "</p>";
        if (!empty($row["MenuItemImage"])) {
            echo '<img src="images/' . $row["MenuItemImage"] . '" alt="' . $row["MenuItemName"] . '">';
        }
        echo "</div>";
    }
} else {
    echo "No menu items found.";
}

// Close the database connection
$conn->close();

?>
 