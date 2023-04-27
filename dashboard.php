<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bundatan_db";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from the sales table
$sql = "SELECT SaleDate,  QuantitySold FROM sales";
$result = $conn->query($sql);

// Display the data in a chart using Chart.js
$data_labels = [];
$data_values = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data_labels[] = $row["SaleDate"];
        $data_values[] = $row["QuantitySold"];
    }
}

$sql = "SELECT SUM(s.QuantitySold * m.MenuItemPrice) as TotalSales FROM sales s JOIN menuitems m ON s.MenuItemID = m.MenuItemID";
$result = $conn->query($sql);

// Display the total sales value in the HTML code
$total_sales = 0;
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $total_sales = $row["TotalSales"];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="s.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;600&family=Kanit:wght@100&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <div class="container">
        <nav class="sidebar">
            <div class="container">
                <div class="navTitle">
                    <h1 class="title">Bundatan</h1>
                    <div id="boxTitle"></div>
                </div>


                <div class="navLinks">
                    <a href="index.php">Dashboard <img src="icons/dashboardIcon.png" alt="dashboardIcon"
                            class="icons"></a>
                    <a href="dashboard.php">Manage<img src="icons/manageIcon.png" alt="dashboardIcon" class="icons"></a>
                    <a href="login.php">Reports<img src="icons/report.png" alt="dashboardIcon" class="icons"></a>
                    <a href="register.php">Logout<img src="icons/logoutIcon.png" alt="dashboardIcon" class="icons"></a>

                </div>
            </div>
        </nav>



        <section class="dashboard">
            <div class="container">
                <h1 class="title">Discover</h1>
                <div class="boxes ">
                    <div class="box">
                        <div class="smBox"><img src="icons/user.png" alt="dashboardIcon" class="boxIcons"></div>
                        <h3>Total User</h3>
                        <p>100</p>
                    </div>
                    <div class="box">
                        <div class="smBox"><img src="icons/Ingredients.png" alt="dashboardIcon" class="boxIcons"></div>
                        <h3>Ingredients</h3>
                        <p>100</p>
                    </div>
                    <div class="box">
                        <div class="smBox"><img src="icons/menu.png" alt="dashboardIcon" class="boxIcons"></div>
                        <h3>Menu Items</h3>
                        <p>100</p>
                    </div>
                    <div class="box">
                        <div class="white"><img src="icons/restock.png" alt="dashboardIcon" class="boxIcons">
                        </div>
                        <h3>Require Restock</h3>
                        <p>100</p>
                    </div>
                </div>
                <div class="chart">
                <h1 class="title">Sales Data</h1>
                <h3>Total Sales</h1>
                <p>₱ <?php echo number_format($total_sales, 2); ?> ↑ </p>
                <canvas id="salesChart" ></canvas>
            </div>
            </div>
            <div>
      
</div>
        </section>
    </div>
    <script>
        // Create a line chart using Chart.js
        var ctx = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($data_labels); ?>,
                datasets: [{
                    label: 'Quantity Sold',
                    data: <?php echo json_encode($data_values); ?>,
                    borderColor: 'black',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                scales: {
       		x: {
          		ticks: {
              	display: false
              }
          }
       }
    }
});
    </script>
 
</body>

</html>