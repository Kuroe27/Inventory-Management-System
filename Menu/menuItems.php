<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lomi_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST["delete"])) {
  $menuItemName = htmlspecialchars($_POST['menuItemName']);
  $menuItemPrice = htmlspecialchars($_POST['menuItemPrice']);
  $ingredientIDs = $_POST['ingredientIDs'];

  // Prepare statement for inserting menu item
  $stmt = $conn->prepare("INSERT INTO MenuItems (MenuItemName, MenuItemPrice) VALUES (?, ?)");
  $stmt->bind_param("sd", $menuItemName, $menuItemPrice);
  $stmt->execute();

  $menuItemID = $stmt->insert_id;

  // Prepare statement for inserting menu item ingredients
  $stmt = $conn->prepare("INSERT INTO MenuItemIngredients (MenuItemID, IngredientID, Quantity) VALUES (?, ?, ?)");
  $stmt->bind_param("ddd", $menuItemID, $ingredientID, $quantity);

  foreach ($ingredientIDs as $ingredientID) {
    $quantity = htmlspecialchars($_POST['quantities'][$ingredientID]);
    $stmt->execute();
  }

  header("Location: menuItems.php");
  exit();
}

if (isset($_POST["delete"])) {
    $MenuItemID = $_POST["MenuItemID"];
    // Prepare statement for deleting menu item
    $stmt = $conn->prepare("DELETE FROM menuItems WHERE MenuItemID=?");
    $stmt->bind_param("d", $MenuItemID);
    $stmt->execute();
}

// Prepare statement for selecting all menu items
$result = $conn->query("SELECT * FROM MenuItems");

// Close the database connection
?>

<html>
<head>
  <title>Add Menu Item</title>
</head>
<body>

  <h1>Add Menu Item</h1>
  <form method="post">
    <label for="menuItemName">Menu Item Name:</label>
    <input type="text" name="menuItemName" required>
    <br>
    <label for="menuItemPrice">Menu Item Price:</label>
    <input type="number" name="menuItemPrice" step="0.01" min="0" required>
    <br>
    <p>Ingredients:</p>

    <?php
      $ingredients = $conn->query("SELECT * FROM ingredients");
      while ($ingredient = $ingredients->fetch_assoc()) {
        echo "<label>";
        echo "<input type='checkbox' name='ingredientIDs[]' value='" . $ingredient['IngredientID'] . "'>";
        echo $ingredient['IngredientName'];
        echo "</label>";
        echo "<input type='number' name='quantities[" . $ingredient['IngredientID'] . "]'  min='0.1' step='0.1'>";
        echo "<br>";
      }
    ?>

    <input type="submit" value="Add Menu Item">
  </form>
  <br>
  <h2>Menu Items</h2>
  <table>
  <thead>
    <tr>
      <th>Menu Item Name</th>
      <th>Ingredients</th>
    </tr>
  </thead>
  <tbody>
    <?php
      // Loop through menu items
      $menuItems = $conn->query("SELECT * FROM menuitems");
      while ($menuItem = $menuItems->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $menuItem['MenuItemName'] . "</td>";
        echo "<td>" . $menuItem['MenuItemName'] . "</td>";
        echo "<td>";
        // Retrieve and display ingredients and quantities for current menu item
        $sql = "SELECT Ingredients.IngredientName, MenuItemIngredients.Quantity FROM MenuItemIngredients INNER JOIN Ingredients ON MenuItemIngredients.IngredientID = Ingredients.IngredientID WHERE MenuItemIngredients.MenuItemID = " . $menuItem['MenuItemID'];
        $result = mysqli_query($conn, $sql);
        $ingredients = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
        foreach ($ingredients as $ingredient) {
          echo $ingredient['IngredientName'] . " (" . $ingredient['Quantity'] . "), ";
        }
        echo "</td>";
        echo "</tr>";
      }
    ?>
  </tbody>
</table>
  <a href="ingredients.php">Back to ingredient</a>
</body>
</html>