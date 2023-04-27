<?php
// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bundatan_db";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission for inserting new sales
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $menuItemID = $_POST["menuItemID"];
  $quantitySold = $_POST["quantitySold"];

  // Check if the sale will cause ingredient quantities to go negative
  $sql = "SELECT i.IngredientName, i.Quantity, mi.Quantity AS MenuItemQuantity
          FROM ingredients i
          INNER JOIN menuitemingredients mi ON i.IngredientID = mi.IngredientID
          WHERE mi.MenuItemID = '$menuItemID'";
  $result = mysqli_query($conn, $sql);
  while ($row = mysqli_fetch_assoc($result)) {
      $availableQuantity = $row["Quantity"] - ($row["MenuItemQuantity"] * $quantitySold);
      if ($availableQuantity < 0) {
          die("Error: The sale cannot be recorded because the quantity of " . $row["IngredientName"] . " will go negative.");
      }
  }

  // Record the sale and update ingredient quantities
  $saleDate = date("Y-m-d H:i:s");
  $sql = "INSERT INTO sales (MenuItemID, QuantitySold, SaleDate) VALUES ('$menuItemID', '$quantitySold', '$saleDate')";
  if (mysqli_query($conn, $sql)) {
      $sql = "UPDATE ingredients
              SET Quantity = Quantity - (SELECT Quantity * '$quantitySold' FROM menuitemingredients WHERE MenuItemID = '$menuItemID' AND IngredientID = ingredients.IngredientID)
              WHERE EXISTS (SELECT 1 FROM menuitemingredients WHERE MenuItemID = '$menuItemID' AND IngredientID = ingredients.IngredientID)";
      if (mysqli_query($conn, $sql)) {
          echo "Sale recorded successfully!";
      } else {
          echo "Error updating ingredient quantities: " . mysqli_error($conn);
      }
  } else {
      echo "Error recording sale: " . mysqli_error($conn);
  }
}

// Display form for inserting new sales
$sql = "SELECT * FROM menuitems";
$result = mysqli_query($conn, $sql);
?>
<h2>Record a New Sale</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <label for="menuItemID">Select a menu item:</label>
  <select name="menuItemID" id="menuItemID">
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <option value="<?php echo $row["MenuItemID"]; ?>"><?php echo $row["MenuItemName"]; ?></option>
      <?php endwhile; ?>
  </select><br>
  <label for="quantitySold">Quantity sold:</label>
  <input type="number" name="quantitySold" id="quantitySold" required><br>
  <input type="submit" value="Record Sale">
</form>

<hr>

<form method="get" action="display_sales.php">
  <label for="start_date">Start Date:</label>
  <input type="date" id="start_date" name="start_date" required>
  
  <label for="end_date">End Date:</label>
  <input type="date" id="end_date" name="end_date" required>
  
  <input type="submit" value="Display Sales">
</form>

<?php

// Display sales table
$sql = "SELECT s.SaleID, m.MenuItemName, s.QuantitySold, s.SaleDate
        FROM sales s
        INNER JOIN menuitems m ON s.MenuItemID = m.MenuItemID
        ORDER BY s.SaleDate ASC";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    echo "<h2>Sales</h2>";
    echo "<table>";
    echo "<tr><th>Sale ID</th><th>Menu Item</th><th>Quantity Sold</th><th>Sale Date</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>" . $row["SaleID"] . "</td><td>" . $row["MenuItemName"] . "</td><td>" . $row["QuantitySold"] . "</td><td>" . $row["SaleDate"] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p>No sales to display.</p>";
}
?>

<?php
mysqli_close($conn);
?>