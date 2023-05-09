<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbbundatan";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from ingredients table
$sql = "SELECT * FROM ingredients";
$result = $conn->query($sql);

// Print the data in a table format
if ($result->num_rows > 0) {
    echo "<table><tr><th>ID</th><th>Name</th><th>Quantity</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["id"]. "</td><td>" . $row["name"]. "</td><td>" . $row["quantity"]. "</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Print Ingredients Table</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
	<button onclick="printTable()">Print Ingredients Table</button>

	<script>
	function printTable() {
		$.ajax({
			url: "print_table.php",
			type: "GET",
			success: function(data) {
				$("body").append(data);
			}
		});
	}
	</script>
</body>
</html>
