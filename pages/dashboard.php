<?php include 'sidebar.html'; ?>
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

// Query the database to get the top 2 sold menu items
$sql = "SELECT 
            mi.MenuItemName,
            mi.MenuItemPrice,
            mi.MenuItemImage
        FROM 
            menuitems mi
        INNER JOIN 
            sales s ON mi.MenuItemID = s.MenuItemID
        GROUP BY 
            s.MenuItemID
        ORDER BY 
            SUM(s.QuantitySold) DESC
        LIMIT 2";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../s.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;600&family=Kanit:wght@100&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <div class="container">
    



        <section class="dashboard">
            <div class="container">
                <section class="header">
                <h1 class="title">Discover</h1>
                <div class="boxes ">
                    <div class="box">
                        <div class="smBox"><img src="../icons/user.png" alt="dashboardIcon" class="box../icons"></div>
                        <h3>Total User</h3>
                        <p>100</p>
                    </div>
                    <div class="box">
                        <div class="smBox"><img src="../icons/Ingredients.png" alt="dashboardIcon" class="box../icons"></div>
                        <h3>Ingredients</h3>
                        <p>100</p>
                    </div>
                    <div class="box">
                        <div class="smBox"><img src="../icons/menu.png" alt="dashboardIcon" class="box../icons"></div>
                        <h3>Menu Items</h3>
                        <p>100</p>
                    </div>
                    <div class="box">
                        <div class="white"><img src="../icons/restock.png" alt="dashboardIcon" class="box../icons">
                        </div>
                        <h3>Require Restock</h3>
                        <p>100</p>
                    </div>
                </div>
                </section>

                <section class="main">


                <div class="chart">
                <h1 class="title">Sales Data</h1>
                <h3>Total Sales</h1>
                <p>₱ <?php echo number_format($total_sales, 2); ?> ↑ </p>
                <canvas id="salesChart" ></canvas>
                </div>


                <div class="topSales">
                    <h3 class="title">Best Sellers</h3>
                    <?php
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
?>
 <h3><?php echo $row["MenuItemName"]; ?></h3>
        <div class="topSalesItem">
           
            <?php if (!empty($row["MenuItemImage"])) { ?>
                <div class="topSalesItemImg">
                    <img src="../images/<?php echo $row["MenuItemImage"]; ?>" alt="<?php echo $row["MenuItemName"]; ?>">
                    <div class="gradient"></div>
               
                </div>
            <?php } ?>
            <div class="itemInfo">
                <h4>Price</h4>
                <p class="itemPrice">₱ <?php echo $row["MenuItemPrice"]; ?></p>
                <button class="viewBtn">View<img src="../icons/redirect.png" alt="dashboardIcon"></button>
            </div>
        </div>
<?php
    }
} else {
    echo "No menu items found.";
}
?>

                


</section>


</div>
            
            </section>
      
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

var boxes = document.querySelector('.boxes');
  var isDragging = false;
  var startPosition = {
    x: 0,
    scrollLeft: 0
  };
  
  boxes.addEventListener('mousedown', function(e) {
    isDragging = true;
    boxes.style.cursor = 'grabbing';
    startPosition = {
      x: e.clientX,
      scrollLeft: boxes.scrollLeft
    };
  });
  
  boxes.addEventListener('mousemove', function(e) {
    if (!isDragging) {
      return;
    }
    var dx = e.clientX - startPosition.x;
    boxes.scrollLeft = startPosition.scrollLeft - dx;
  });
  
  boxes.addEventListener('mouseup', function(e) {
    isDragging = false;
    boxes.style.cursor = 'grab';
  });
    </script>
 
</body>

</html>