<?php include 'sidebar.html'; ?>
<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbbundatan";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);


if (isset($_POST["insert"])) {
    $MeasurementName = $_POST["MeasurementName"];
    $conn->query("INSERT INTO measurements (MeasurementName) VALUES ('$MeasurementName')");
    header("Location: measurement.php");
    exit();
}

// check if the form has been submitted for editing an measurement
if (isset($_POST["save"])) {
    $MeasurementID = $_POST["MeasurementID"];
    $MeasurementName = $_POST["MeasurementNamex"];
    // update the order in the database
    $conn->query("UPDATE measurements SET MeasurementName='$MeasurementName' WHERE MeasurementID=$MeasurementID");
}


// check if the form has been submitted for deleting an measurement
if (isset($_POST["delete"])) {
    $MeasurementID = $_POST["MeasurementID"];
    try {
        // delete the measurement from the database
        $conn->query("DELETE FROM measurements WHERE MeasurementID=$MeasurementID");
        header("Location: measurement.php");
        exit();
    } catch (mysqli_sql_exception $e) {
        echo "<script>alert('Error deleting the measurement: " . $e->getMessage() . "');</script>";
    }
}

$searchQuery = "";
if (isset($_GET["search"])) {
    $searchQuery = $_GET["searchQuery"];
}

$result = $conn->query("SELECT * FROM measurements WHERE MeasurementName LIKE '%$searchQuery%'");

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
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
    
<div class="Tableheader">
            <h1>Measurement</h1>
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
                <h2 class="formTitle">Insert New Title</h2>
                <img src="../icons/cross.png"  class="close" onclick="hideForm()">
             
                </div>
 

            <form method="POST" >
    <div class="secondForm">
  <div class="inputs">
  <label for="MeasurementName">Measurement Name:</label>

  <input type="text"
   name="MeasurementName" required>
  <button type="submit" name="insert" class="insert">Insert</button>
  </div>
  </div>
</form>


            </div>

            <div class="tableContainer">
<table>
<thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th class="no-print">Actions</th>
    </tr>
  </thead>
  <tbody>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
            <td>
            <span>Measurement Id: </span>    
            <?php echo $row["MeasurementID"]; ?></td>
                <td>
                    <form method='POST'>
                    <span>Measurement Name: </span>
                        <input type='text' 
                        name='MeasurementID' 
                        class='id'
                        value='<?php echo $row["MeasurementID"]; ?>'>

          

                        <input  id='name<?php echo $row["MeasurementID"]; ?>' 
                        REQUIRED
                        class='all<?php echo $row["MeasurementID"]; ?>' 
                        MeasurementID='MeasurementName<?php echo $row["MeasurementID"]; ?>' 
                        type='text' name='MeasurementNamex' 
                        value='<?php echo $row["MeasurementName"]; ?> ' disabled>
                        
                 </td>
                 <td class="no-print">
              

                    <button MeasurementID='editbtn<?php echo $row["MeasurementID"]; ?>' 
                    type='button' name='editbtn<?php echo $row["MeasurementID"]; ?>' 
                    id='editbtn<?php echo $row["MeasurementID"]; ?>' 
                    onclick='enableInputFields(<?php echo intval($row["MeasurementID"]); ?>)'>Edit</button>


                    <button
                    class='cancelbtn'
                     id="cancel<?php echo $row["MeasurementID"]; ?>"
                     onclick='myFunction(<?php echo intval($row["MeasurementID"]); ?>)'>Cancel
                    </button>
                    
                    <button
                    class='savebtn'
                    name='save'
                     id="save<?php echo $row["MeasurementID"]; ?>"
                     onclick='myFunction(<?php echo intval($row["MeasurementID"]); ?>)'>Save
                    </button>

                    <button type='submit' name='delete'  class="deletebtn" >Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
        <button onclick="printDivName()">Print Div Name</button>
    </table>
    </div>
    </div>


</div>
<script>
    function printDivName() {
      // Get the table container element
      var tableContainer = document.querySelector(".tableContainer");

      // Clone the table container to preserve the original
      var clonedContainer = tableContainer.cloneNode(true);

      // Remove the "Actions" column by index
      var headerRow = clonedContainer.querySelector("tr");
      headerRow.deleteCell(3);

      // Loop through the data rows and remove the corresponding cell
      var dataRows = clonedContainer.querySelectorAll("tr:not(:first-child)");
      for (var i = 0; i < dataRows.length; i++) {
        dataRows[i].deleteCell(3);
      }

      // Get the HTML contents of the cloned container
      var printContents = clonedContainer.innerHTML;

      // Create a new window to display the print dialog
      var printWindow = window.open("", "", "");


      // Write the HTML contents to the new window and print
      printWindow.document.write(printContents);
      printWindow.document.close();
      printWindow.focus();
      printWindow.print();
      printWindow.close();
    }
  </script>
</body>
</html>