<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ims_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $menuItemName = mysqli_real_escape_string($conn, $_POST['menuItemName']);
    $menuItemPrice = mysqli_real_escape_string($conn, $_POST['menuItemPrice']);
    $ingredients = $_POST['ingredients'];
    $quantities = $_POST['quantities'];

    $sql = "INSERT INTO MenuItems (MenuItemName, MenuItemPrice) VALUES ('$menuItemName', '$menuItemPrice')";
    if (mysqli_query($conn, $sql)) {
        $menuItemID = mysqli_insert_id($conn);

        foreach ($ingredients as $key => $ingredientID) {
            $quantity = (int)$quantities[$key];
            if ($quantity > 0) {
                $sql = "INSERT INTO MenuItemIngredients (MenuItemID, IngredientID, Quantity) VALUES ('$menuItemID', '$ingredientID', '$quantity')";
                if (!mysqli_query($conn, $sql)) {
                    echo "Error: " . mysqli_error($conn);
                }
            }
        }

        header("Location: menu.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

$sql = "SELECT * FROM Ingredients";
$result = mysqli_query($conn, $sql);
$ingredients = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);

$sql = "SELECT * FROM MenuItems";
$result = mysqli_query($conn, $sql);
$menuItems = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);

?>
<!DOCTYPE html>
<html>
<head>
  <title>Add Menu Item</title>
</head>
<body>
  <?php if (isset($_GET['success'])) { ?>
    <p>Menu item added successfully.</p>
  <?php } else { ?>
    <h1>Add Menu Item</h1>
    <form method="post">
      <label for="menuItemName">Menu Item Name:</label>
      <input type="text" name="menuItemName" required>
      <br>
      <label for="menuItemPrice">Menu Item Price:</label>
      <input type="number" name="menuItemPrice" step="0.01" min="0" required>
      <br>
      <p>Ingredients:</p>
      <?php foreach ($ingredients as $key => $ingredient): ?>
          <input type="checkbox" name="ingredients[]" value="<?php echo $ingredient['IngredientID']; ?>">
          <label><?php echo $ingredient['IngredientName']; ?></label>
          <input type="number" name="quantities[]" value="0" min="0">
          <br>
      <?php endforeach; ?>
      <br>
      <input type="submit" value="Add Menu Item">
    </form>
  <?php } ?>
  <br>
  <h2>Menu Items</h2>
  <table>
    <thead>
      <tr>
        <th>Menu Item Name</th>
        <th>Menu Item Price</th>
        <th>Ingredients</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($menuItems as $menuItem): ?>
        <tr>
          <td><?php echo $menuItem['MenuItemName']; ?></td>
          <td><?php echo $menuItem['MenuItemPrice']; ?></td>
          <td>
            <?php
           $sql = "SELECT Ingredients.IngredientName, MenuItemIngredients.Quantity FROM MenuItemIngredients INNER JOIN Ingredients ON MenuItemIngredients.IngredientID = Ingredients.IngredientID WHERE MenuItemIngredients.MenuItemID = " . $menuItem['MenuItemID'];

          $result = mysqli_query($conn, $sql);
          $ingredients = mysqli_fetch_all($result, MYSQLI_ASSOC);
          mysqli_free_result($result);
          foreach ($ingredients as $ingredient) {
              echo $ingredient['IngredientName'] . " (" . $ingredient['Quantity'] . "), ";
          }
          ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <a href="ingredients.php">Back to ingredient</a>
</body>
</html>

