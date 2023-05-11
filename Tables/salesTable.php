<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbbundatan";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

$result = $conn->query("SELECT * FROM sales");
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

    <div class="insertion">
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
