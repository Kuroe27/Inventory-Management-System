<?php include 'sidebar.html'; ?>
<?php
// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbbundatan";
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
    
<div class="Tableheader">
            <h1>Sales</h1>
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
                <h2 class="formTitle">Add new sale</h2>
                <img src="../icons/cross.png"  class="close" onclick="hideForm()">
             
                </div>

  </form>
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

  <div class="secondForm">
  <div class="inputs">
  <label for="menuItemID">Select a menu item:</label>
  <select name="menuItemID" id="menuItemID">
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <option value="<?php echo $row["MenuItemID"]; ?>"><?php echo $row["MenuItemName"]; ?></option>
      <?php endwhile; ?>
  </select><br>

  
  </div>
  <div class="inputs">
  <label for="quantitySold">Quantity sold:</label>
  <input type="number" name="quantitySold" id="quantitySold" required><br>
  </div>

  <button type="submit" value="Record Sale" name="insert" class="insert">Insert</button>
  </div>
  </div>
  

 
  <div class="tableContainer">
  <table>
    <tr>
        <th>Sale ID</th>
        <th>Menu Item</th>
        <th>Quantity Sold</th>
        <th>Sales Table</th>
        <th>Sale Date</th>
    </tr>
    <?php
    // Get sales data from database
    $sql = "SELECT s.SaleID, m.MenuItemName, s.QuantitySold, s.SaleDate
            FROM sales s
            INNER JOIN menuitems m ON s.MenuItemID = m.MenuItemID
            ORDER BY s.SaleDate ASC";
    $result = mysqli_query($conn, $sql);

    // Display sales data in table rows
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row["SaleID"] . "</td>";
            echo "<td>" . $row["MenuItemName"] . "</td>";
            echo "<td>" . $row["QuantitySold"] . "</td>";
            echo "<td>" . $row["SaleDate"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No sales to display.</td></tr>";
    }
    ?>
</table>

  </div>
    </div>
  </div>


<script src="script.js"></script>
</body>
</html>