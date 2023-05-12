<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
  // Redirect the user to the login page
  header("Location: ../user/login.php");
  exit(); // Terminate the script to prevent further execution
}
?>
<?php include 'sidebar.html'; ?>
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
<h1>Report</h1>
</div>
<div class="insertion">
        <div class="container">
			<div class="reports boxes">
				<div class="box" ><img src="../icons/ingredient.png" class="icons"><h3>Ingredients</h3>
                        <a href="../Tables/ingredientsTable.php"><button>Print Report</button></a></div>

				<div class="box"><img
                        src="../icons/restaurant.png" class="icons"><h3>Menu</h3>
                        <a href="../Tables/menuTable.php">
                        <button>Print Report</button></a></div>
				<div class="box"><img
                        src="../icons/sales.png" class="icons"><h3>Sales</h3>
                           <a href="../Tables/salesTable.php"><button>Print Report</button></a></div>
			</div>



              
		</div>
		</div>
       
</body>
</html>
