<?php


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_bundatan";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);





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
    <link rel="stylesheet" href="../pages/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
</head>
<body>

    <div class="insertion">
        <div class="container">


   

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
        <th>Ingredients</th>
        <th>Menu Table</th>
        <th></th>
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
          <p> <?php echo $row['Ingredients']; ?></p> 
    </td>
        </tr>
    <?php endwhile; ?>
</table>
            </div>
            

        
        </div>
        
      </div>
      <script>
    window.onload = function() {
        window.print();
        window.onafterprint = function() {
            window.location.href = "../pages/report.php";
        };
    };
</script>

</body>
</html>