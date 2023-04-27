<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bundatan_db";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);


if (isset($_POST["insert"])) {
    $CategoryName = $_POST["CategoryName"];
    $conn->query("INSERT INTO categories (CategoryName) VALUES ('$CategoryName')");
    header("Location: category.php");
    exit();
}

// check if the form has been submitted for editing an measurement
if (isset($_POST["save"])) {
    $CategoryID = $_POST["CategoryID"];
    $CategoryName = $_POST["CategoryNamex"];
    // update the order in the database
    $conn->query("UPDATE categories SET CategoryName='$CategoryName' WHERE CategoryID=$CategoryID");
}

// check if the form has been submitted for deleting an measurement
if (isset($_POST["delete"])) {
    $CategoryID = $_POST["CategoryID"];
    // delete the measurement from the database
    $conn->query("DELETE FROM categories WHERE CategoryID=$CategoryID");
}


$result = $conn->query("SELECT * FROM categories");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="wMeasurementIDth=device-wMeasurementIDth, initial-scale=1.0">
  <title>Measurement</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>

<form method="POST">
  <label for="CategoryName">CategoryName:</label>
  <input type="text"
   name="CategoryName" required>
  <br>
  <button type="submit" name="insert">Insert</button>
</form>

<table>
        <tr>
            <th>CategoryID</th>
            <th>CategoryName</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td>
                    <form method='POST'>
                        <input type='text' 
                        name='CategoryID' 
                        class='id'
                        value='<?php echo $row["CategoryID"]; ?>'>

          

                        <input  id='name<?php echo $row["CategoryID"]; ?>' 
                        REQUIRED
                        class='all<?php echo $row["CategoryID"]; ?>' 
                        CategoryID='CategoryName<?php echo $row["CategoryID"]; ?>' 
                        type='text' name='CategoryNamex' 
                        value='<?php echo $row["CategoryName"]; ?> ' disabled>
                 </td>
                <td>
              

                    <button CategoryID='editbtn<?php echo $row["CategoryID"]; ?>' 
                    type='button' name='editbtn<?php echo $row["CategoryID"]; ?>' 
                    id='editbtn<?php echo $row["CategoryID"]; ?>' 
                    onclick='enableInputFields(<?php echo intval($row["CategoryID"]); ?>)'>Edit</button>


                    <button
                    class='cancelbtn'
                     id="cancel<?php echo $row["CategoryID"]; ?>"
                     onclick='myFunction(<?php echo intval($row["CategoryID"]); ?>)'>Cancel
                    </button>
                    
                    <button
                    class='savebtn'
                    name='save'
                     id="save<?php echo $row["CategoryID"]; ?>"
                     onclick='myFunction(<?php echo intval($row["CategoryID"]); ?>)'>Save
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
