<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lomi_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST["delete"])) {
  $menuItemName = htmlspecialchars($_POST['menuItemName']);
  $menuItemPrice = htmlspecialchars($_POST['menuItemPrice']);
  $ingredientIDs = $_POST['ingredientIDs'];

  // Prepare statement for inserting menu item
  $stmt = $conn->prepare("INSERT INTO MenuItems (MenuItemName, MenuItemPrice) VALUES (?, ?)");
  $stmt->bind_param("sd", $menuItemName, $menuItemPrice);
  $stmt->execute();

  $menuItemID = $stmt
  ->insert_id;

  // Prepare statement for inserting menu item ingredients
  $stmt = $conn->prepare("INSERT INTO MenuItemIngredients (MenuItemID, IngredientID, Quantity) VALUES (?, ?, ?)");
  $stmt->bind_param("ddd", $menuItemID, $ingredientID, $quantity);

  foreach ($ingredientIDs as $ingredientID) {
    $quantity = htmlspecialchars($_POST['quantities'][$ingredientID]);
    $stmt->execute();
  }

  header("Location: menuItems.php");
}
if (isset($_POST["delete"])) {
  $MenuItemID = $_POST["MenuItemID"];
  $conn->query("DELETE FROM menuitems WHERE MenuItemID='$MenuItemID'");
}

if (isset($_POST["save"])) {
  $MenuItemID = $_POST["MenuItemID"];
  $MenuItemName = $_POST["MenuItemName"];
  $MenuItemPrice = $_POST["MenuItemPrice"];
  // Disable foreign key check
  $conn->query("SET FOREIGN_KEY_CHECKS=0");
  
  // Update the menu item name in the database
  $conn->query("UPDATE menuitems SET MenuItemName='$MenuItemName', MenuItemPrice='$MenuItemPrice' WHERE MenuItemID=$MenuItemID");
  
  // Re-enable foreign key check
  $conn->query("SET FOREIGN_KEY_CHECKS=1");
}

// Select all menu items
$result = $conn->query("
SELECT 
  m.MenuItemID, 
  m.MenuItemName, 
  m.MenuItemPrice, 
  GROUP_CONCAT(CONCAT(mi.Quantity, ' ', i.IngredientName) SEPARATOR ', ') AS Ingredients 
FROM 
  menuitems m 
  JOIN MenuItemIngredients mi ON m.MenuItemID = mi.MenuItemID 
  JOIN ingredients i ON mi.IngredientID = i.IngredientID 
GROUP BY 
  m.MenuItemID, 
  m.MenuItemName, 
  m.MenuItemPrice

");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Measurement</title>
  <link rel="stylesheet" href="../style.css">
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

  <table>
    <tr>
      <th>Menu Item ID</th>
      <th>Menu Item Name</th>
      <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) : ?>
      <tr>
        <td>
        <form method='POST'>
                      
                      <input type='text' 
                      name='MenuItemID' 
                      class='id'
                      value='<?php echo $row["MenuItemID"]; ?>'>

                      <input  id='name<?php echo $row["MenuItemID"]; ?>' 
                      REQUIRED
                      class='all<?php echo $row["MenuItemID"]; ?>' 
                      MenuItemID='MenuItemName<?php echo $row["MenuItemID"]; ?>' 
                      type='text' name='MenuItemName' 
                      value='<?php echo $row["MenuItemName"]; ?> ' >
                
                      <input id='name<?php echo $row["MenuItemID"]; ?>' 
                      REQUIRED
                      IngredientID='IngredientName_<?php echo $row["MenuItemID"]; ?>' 
                      type='number' name='MenuItemPrice' 
                      class='all<?php echo $row["MenuItemID"]; ?>'
                      value='<?php echo preg_replace("/[^0-9.]/", "", $row["MenuItemPrice"]); ?>' 


                      step='any' disabled>
                   
                      
                      <input type="text" name="ingredients" value="<?php echo $row['Ingredients']; ?>">
        </td>
        <td>
  
           

              

              <button MenuItemID='editbtn<?php echo $row["MenuItemID"]; ?>' 
              type='button' name='editbtn<?php echo $row["MenuItemID"]; ?>' 
              id='editbtn<?php echo $row["MenuItemID"]; ?>' 
              onclick='enableInputFields(<?php echo intval($row["MenuItemID"]); ?>)'>Edit</button>


              <button
              class='cancelbtn'
               id="cancel<?php echo $row["MenuItemID"]; ?>"
               onclick='myFunction(<?php echo intval($row["MenuItemID"]); ?>)'>Cancel
              </button>
              
              <button
              class='savebtn'
              name='save'
               id="save<?php echo $row["MenuItemID"]; ?>"
               onclick='myFunction(<?php echo intval($row["MenuItemID"]); ?>)'>Save
              </button>

              <button type='submit' name='delete'   >Delete</button>
              </form>
          </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <script src="../script.js"></script>
</body>
</html>
