<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbbundatan";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

$result = $conn->query("SELECT * FROM ingredients");
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body onload="window.print()">
<div class="greyscale">
    <div class="insertion">
            <table>
            <thead>
    <tr>
        <th>IngredientID</th>
        <th>IngredientName</th>
        <th>Quantity</th>
        <th></th>

        <th>Measurement</th>
    </tr>
    </thead>
    <?php while ($row = $result->fetch_assoc()) : ?>
        <tr>
        <td>
        <span>Ingredients Id: </span>
            <?php echo $row["IngredientID"]; ?></td>
                <form method='POST'>
                    <input type='text' name='IngredientID' class='id' value='<?php echo $row["IngredientID"]; ?>'>
            </td>
            <td>
                    <span>Ingredients Name: </span>
                    <input  id='ingredientsInput<?php echo $row["IngredientID"]; ?>' class='all<?php echo $row["IngredientID"]; ?>' REQUIRED
                        IngredientID='IngredientName_<?php echo $row["IngredientID"]; ?>' type='text' name='IngredientName' value='<?php echo $row["IngredientName"]; ?> ' disabled>
            </td>
            <td>
                <span>Quantity:</span>
                    <input id='quantityInput<?php echo $row["IngredientID"]; ?>' REQUIRED class='all<?php echo $row["IngredientID"]; ?>' type='number' name='Quantity' value='<?php echo $row["Quantity"]; ?>' step='any' disabled>
            </td>
            <td>
                <span>Mesurement:</span>
                    <select id='measurementInput<?php echo $row["IngredientID"]; ?>' class='all<?php echo $row["IngredientID"]; ?>' name='MeasurementID' disabled>
                        <?php
                            $measurements = $conn->query("SELECT * FROM measurements");
                            while ($measurement = $measurements->fetch_assoc()) {
                                $selected = ($measurement["MeasurementID"] == $row["MeasurementID"]) ? "selected" : "";
                                echo "<option value='" . $measurement["MeasurementID"] . "' " . $selected . ">" . $measurement["MeasurementName"] . "</option>";
                            }
                        ?>
                    </select>
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
