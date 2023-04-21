<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ims_db";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// check if the form has been submitted for editing a user
if (isset($_POST["save"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $contact = $_POST["contact"];
    $sex = $_POST["sex"];
    // update the user in the database
    $conn->query("UPDATE user1 SET name='$name', sex='$sex', contact='$contact' WHERE id=$id");
}

// check if the form has been submitted for deleting a user
if (isset($_POST["delete"])) {
    $id = $_POST["id"];
    // delete the user from the database
    $conn->query("DELETE FROM user1 WHERE id=$id");
}

// retrieve the data
$result = $conn->query("SELECT * FROM user1");

// retrieve sex values from sex table
$sql_sex = "SELECT * FROM sex";
$result_sex = mysqli_query($conn, $sql_sex);

// display the data in a table
echo "<table>";
echo "<tr><th>ID</th><th>Actions</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row["id"] . "</td>";
    echo "<td>";
    echo "<form method='POST'>";
    echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
    echo "<input id='name_" . $row["id"] . "' type='text' name='name' value='" . $row["name"] . "'disabled>";

    // display sex values as options in the select tag
    echo "<select id='sex_" . $row["id"] . "' name='sex'>";
    while ($sex_row = mysqli_fetch_assoc($result_sex)) {
        if ($row["sex"] == $sex_row["sex_value"]) {
            echo "<option value='" . $sex_row['sex_value'] . "' selected>" . $sex_row['sex_label'] . "</option>";
        } else {
            echo "<option value='" . $sex_row['sex_value'] . "'>" . $sex_row['sex_label'] . "</option>";
        }
    }
    echo "</select>";

    echo "<input id='contact_" . $row["id"] . "' type='text' name='contact' value='" . $row["contact"] . "'disabled>";

    echo "<button id='editbtn" . $row["id"] . "' type='button' name='editbtn' onclick='enableInputFields(" . $row["id"] . ")'>Edit</button>";


    echo "<button id='cancel" . $row["id"] . "' type='button' name='cancel' onclick='disableinput(" . $row["id"] . ")' style='display: none;'>cancel</button>";




    echo "<button type='submit' name='save'>Save</button>";
    echo "<button type='submit' name='delete'>Delete</button>";
    echo "</form>";
    echo "</td>";
    echo "</tr>";
}
echo "</table>";

?>

<!DOCTYPE html>
<html>
<head>
  <title>Add Ingredient</title>
  
</head>
<body>
  <?php if (isset($_GET['success'])) { ?>
    <p>Ingredient added successfully.</p>
  <?php
  } ?>
  
  <h1>Add Ingredient</h1>
  <form method="post">
    <label for="ingredientName">Ingredient Name:</label>
    <input type="text" name="ingredientName" required>
    <br>
    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" min="1" required>
    <br>
    <label for="measurementID">Measurement:</label>
    
    <select name="measurementID" required>
      <?php
while ($row_measurement = mysqli_fetch_assoc($result_measurements)) {
    echo "<option value='" . $row_measurement['MeasurementID'] . "'>" . $row_measurement['MeasurementName'] . "</option>";
} ?>
    </select>
    
    <br>
   

    <input type="submit" value="Add Ingredient">
  </form>
  <br>
    <br>
  <form method="POST">
    <input type="hidden" name="update" value="true">
    <label for="ingredientID">Ingredient ID:</label>
    <input type="text" name="update_id" id="ingredientID" required>
    <br>
    <label for="ingredientName">Ingredient Name:</label>
    <input type="text" name="update_name" id="ingredientName" required>
    <br>
    <label for="quantity">Quantity:</label>
    <input type="number" name="update_quantity" id="quantity" required>
    <br>
    <label for="measurementID">Measurement ID:</label>
    <select name="update_measurement" required>
      <?php
while ($row_measurement = mysqli_fetch_assoc($result_measurements)) {
    echo "<option value='" . $row_measurement['MeasurementID'] . "'>" . $row_measurement['MeasurementName'] . "</option>";
} ?>
    </select>


    <br>
    <input type="submit" value="Update">
</form>
  
  <?php
if (mysqli_num_rows($result) > 0) {
    // Display the ingredients table

    ?>
  <h2>Ingredients</h2>
  <table>
      <tr>
          <th>Ingredient ID</th>
          <th>Ingredient Name</th>
          <th>Quantity</th>
          <th>Measurement</th>
          <th>Actions</th>
      </tr>
<?php
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
      <tr>
          <td><?php echo $row["IngredientID"]; ?></td>
          <td><?php echo $row["IngredientName"]; ?></td>
          <td><?php echo $row["Quantity"]; ?></td>
          <td><?php echo $row["MeasurementName"]; ?></td>
          <td>
              <button onclick="editIngredient('<?php echo $row['IngredientID']; ?>', '<?php echo $row['IngredientName']; ?>', '<?php echo $row['Quantity']; ?>', '<?php echo $row['MeasurementName']; ?>')">edit</button>
          </td>
      </tr>
<?php
        }
    ?>
  </table>
<?php
}
?>
  
  <br>
  <a href="menu.php">Back to menu</a>


  <script src="script.js"></script>
</body>
</html>
