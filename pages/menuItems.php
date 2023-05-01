<?php include 'sidebar.html'; ?>
<?php


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbbundatan";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST["delete"])) {
  $menuItemName = isset($_POST['menuItemName']) ? htmlspecialchars($_POST['menuItemName']) : '';
  $menuItemPrice = isset($_POST['menuItemPrice']) ? htmlspecialchars($_POST['menuItemPrice']) : '';
  $menuItemFile = isset($_FILES['menuItemFile']['name']) ? $_FILES['menuItemFile']['name'] : '';
  $ingredientIDs = isset($_POST['ingredientIDs']) ? $_POST['ingredientIDs'] : array();

  // Upload image file to the server
  $target_dir = "../images/";
  $target_file = $target_dir . basename($_FILES["menuItemFile"]["name"]);
  move_uploaded_file($_FILES["menuItemFile"]["tmp_name"], $target_file);

  $stmt = $conn->prepare("INSERT INTO menuitems (MenuItemName, MenuItemPrice, MenuItemImage) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $menuItemName, $menuItemPrice, $target_file);
  $stmt->execute();
  
  $menuItemID = $stmt->insert_id;

  // Prepare statement for inserting menu item ingredients
  $stmt = $conn->prepare("INSERT INTO menuitemingredients (MenuItemID, IngredientID, Quantity) VALUES (?, ?, ?)");
  $stmt->bind_param("ddd", $menuItemID, $ingredientID, $quantity);

  foreach ($ingredientIDs as $ingredientID) {
    $quantity = isset($_POST['quantities'][$ingredientID]) ? htmlspecialchars($_POST['quantities'][$ingredientID]) : '';
    $stmt->execute();
  }

  
}

if (isset($_POST["delete"])) {
  $MenuItemID = isset($_POST["MenuItemID"]) ? $_POST["MenuItemID"] : '';
  $conn->query("DELETE FROM menuitems WHERE MenuItemID='$MenuItemID'");
}
if (isset($_POST["save"])) {
  $MenuItemID = isset($_POST["MenuItemID"]) ? $_POST["MenuItemID"] : '';
  $MenuItemName = isset($_POST["MenuItemName"]) ? $_POST["MenuItemName"] : '';
  $MenuItemPrice = isset($_POST["MenuItemPrice"]) ? $_POST["MenuItemPrice"] : '';
  // Disable foreign key check
  $conn->query("SET FOREIGN_KEY_CHECKS=0");

  // Update the menu item name and price in the database
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
  m.MenuItemImage,
  GROUP_CONCAT(CONCAT(mi.Quantity, ' ', i.IngredientName) SEPARATOR ', ') AS Ingredients 
FROM 
  menuitems m 
  JOIN menuitemingredients mi ON m.MenuItemID = mi.MenuItemID 
  JOIN ingredients i ON mi.IngredientID = i.IngredientID 
GROUP BY 
  m.MenuItemID, 
  m.MenuItemName, 
  m.MenuItemPrice, 
  m.MenuItemImage
");

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
<body>
<div class="Tableheader">
        <h1>Menu</h1>
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
                <h2 class="formTitle">Insert New Ingredient</h2>
                <img src="../icons/cross.png"  class="close" onclick="hideForm()">
                </div>

                
                <form method="post" enctype="multipart/form-data">



    <div class="secondForm">

  <div class="inputs">
  <label for="menuItemName">MenuItem Name:</label>
  <input type="text" name="menuItemName" required>
      </div>

      <div class="inputs">
  <label for="menuItemPrice">Price:</label>
  <input type="number" name="menuItemPrice" step="0.01" min="0" required>
      </div>

    
   

      <div class="inputs" id="imageContainer">
  <label for="menuItemFile">Image:</label>
  <input type="file" name="menuItemFile" accept=".jpg,.jpeg,.png" required>
      </div>
      
      <label class>Ingredients:</label>
      
      <div class="ingredient-grid">

  <?php
    $ingredients = $conn->query("SELECT * FROM ingredients");
    while ($ingredient = $ingredients->fetch_assoc()) {
      echo "<div class='cont'>";
      echo "<label >";
      echo "<input type='checkbox' name='ingredientIDs[]' value='" . $ingredient['IngredientID'] . "'>";
      echo $ingredient['IngredientName'];
      echo "</label >";
      echo "<input type='number' name='quantities[" . $ingredient['IngredientID'] . "]'  min='0.1' step='0.1' class='quan'>";
      echo "</div>";
    }
?>
</div>
   
      
<input type="submit" value="Add Menu Item">
  </div>
</form>


        </div>

        <div class="tableContainer">
        <table>
            <thead>
    <tr>
        <th>MenuId</th>
        <th>Image</th>
        <th>Item Name</th>
        <th>Item Price</th>
        <th>Actions</th>
    </tr>
    </thead>
    <?php while ($row = $result->fetch_assoc()) : ?>
        <tr>
        <td>
        <span>Menu Id: </span>
            <?php echo $row["MenuItemID"]; ?></td>
                <form method='POST'>
                 <input type='text' 
                      name='MenuItemID' 
                      class='id'
                      value='<?php echo $row["MenuItemID"]; ?>'>
            </td>
            </td>
               <td><img src="<?php echo $row["MenuItemImage"]; ?>" width="100" height="100"></td>
            <td>
                    <span>Ingredients Name: </span>
                    <input  id='name<?php echo $row["MenuItemID"]; ?>' 
                      REQUIRED
                      class='all<?php echo $row["MenuItemID"]; ?>' 
                      MenuItemID='MenuItemName<?php echo $row["MenuItemID"]; ?>' 
                      type='text' name='MenuItemName' 
                      value='<?php echo $row["MenuItemName"]; ?> ' >
            </td>
            <td>
                <span>Item Price:</span>
                <input id='name<?php echo $row["MenuItemID"]; ?>' 
                      REQUIRED
                      IngredientID='IngredientName_<?php echo $row["MenuItemID"]; ?>' 
                      type='number' name='MenuItemPrice' 
                      class='all<?php echo $row["MenuItemID"]; ?>'
                      value='<?php echo preg_replace("/[^0-9.]/", "", $row["MenuItemPrice"]); ?>' 
                      step='any' disabled>
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
                    <button>Details</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
            </div>
            

        
        </div>
        
      </div>
<script src="script.js"></script>
</body>
</html>