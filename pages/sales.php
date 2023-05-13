<?php
ob_start(); // Start output buffering
?>

<?php
session_start();
// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    // Redirect the user to the login page
    header("Location: ../index.php");
    exit(); // Terminate the script to prevent further execution
}
?>

<?php include 'sidebar.html'; ?>

<?php
// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_bundatan";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission for inserting new sales
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $menuItemID = $_POST["menuItemID"];
    $quantitySold = $_POST["quantitySold"];

    // Check if the sale will cause ingredient quantities to go negative
    $sql = "SELECT i.IngredientName, i.Quantity, mi.Quantity AS MenuItemQuantity
            FROM ingredients i
            INNER JOIN menuitemingredients mi ON i.IngredientID = mi.IngredientID
            WHERE mi.MenuItemID = '$menuItemID'";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $availableQuantity = $row["Quantity"] - ($row["MenuItemQuantity"] * $quantitySold);
        if ($availableQuantity < 0) {
            die('<span style="margin-left: 350px; color: red;">Error: The sale cannot be recorded because the quantity of ' . $row["IngredientName"] . ' will go negative.</span>');
        }
    }

    // Record the sale and update ingredient quantities
    $saleDate = date("Y-m-d H:i:s");
    $sql = "INSERT INTO sales (MenuItemID, QuantitySold, SaleDate) VALUES ('$menuItemID', '$quantitySold', '$saleDate')";
    header("Location: sales.php");
    if (mysqli_query($conn, $sql)) {
        $sql = "UPDATE ingredients
                SET Quantity = Quantity - (SELECT Quantity * '$quantitySold' FROM menuitemingredients WHERE MenuItemID = '$menuItemID' AND IngredientID = ingredients.IngredientID)
                WHERE EXISTS (SELECT 1 FROM menuitemingredients WHERE MenuItemID = '$menuItemID' AND IngredientID = ingredients.IngredientID)";
        if (mysqli_query($conn, $sql)) {
            echo "Sale recorded successfully!";
        } else {
            echo "Error updating ingredient quantities: " . mysqli_error($conn);
        }
    } else {
        echo "Error recording sale: " . mysqli_error($conn);
    }
}

// Display form for inserting new sales
$sql = "SELECT * FROM menuitems";
$result = mysqli_query($conn, $sql);

$searchQuery = "";
if (isset($_GET["search"])) {
    $searchQuery = $_GET["search"];
}
$results = $conn->query("SELECT sales.*, menuitems.MenuItemName FROM sales INNER JOIN menuitems ON sales.MenuItemID = menuitems.MenuItemID WHERE menuitems.MenuItemName LIKE '%" . $searchQuery . "%'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="Tableheader">
        <h1>Sales</h1>
        <form method="GET" class="searhForm">
            <input type="text" name="search" class="search" placeholder="Search...">
            
        </form>
        <button class="create" onclick="showForm()">New</button>
    </div>

    <div class="insertion">
        <div class="container">
            <div class="form"  id="insert-form">
                <div class="formHeader">    
                    <h2 class="formTitle">Add new sale</h2>
                    <img src="../icons/cross.png"  class="close" onclick="hideForm()">
                </div>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="secondForm">
                        <div class="inputs">
                            <label for="menuItemID">Select a menu item:</label>
                            <select name="menuItemID" id="menuItemID">
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <option value="<?php echo $row["MenuItemID"]; ?>"><?php echo $row["MenuItemName"]; ?></option>
                                <?php endwhile; ?>
                            </select><br>
                        </div>
                        <div class="inputs">
                            <label for="quantitySold">Quantity sold:</label>
                            <input type="number" name="quantitySold" id="quantitySold" required><br>
                        </div>
                        <button type="submit" value="Record Sale" name="insert" class="insert">Insert</button>
                    </div>
                </form>
                <div class="tableContainer">
                    <table>
                        <tr>
                            <th>Sale ID</th>
                            <th>Menu Item</th>
                            <th>Quantity Sold</th>
                            <th>Sales Table</th>
                            <th>Sale Date</th>
                        </tr>
                        <?php
                        // Display sales data in table rows
                        if ($results->num_rows > 0) {
                            while ($row = $results->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td><span>Sale ID:</span> " . $row["SaleID"] . "</td>";
                                echo "<td><span>Menu Name:</span> " . $row["MenuItemName"] . "</td>";
                                echo "<td><span>Quantity Sold:</span> " . $row["QuantitySold"] . "</td>";
                                echo "<td><span>Sale Date:</span> " . $row["SaleDate"] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No sales found for the search query.</td></tr>";
                        }
                        ?>
                    </table>
                    </div>
                 </div>
                </div>
            <script src="script.js"></script>
        </body>
    </html>
<?php
ob_end_flush(); // Flush and output the buffer
?>