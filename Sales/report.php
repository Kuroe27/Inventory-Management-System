<!DOCTYPE html>
<html>
<head>
	<title>Sales Report</title>
</head>
<body>
	<h1>Sales Report</h1>
	<table>
		<thead>
			<tr>
				<th>Item ID</th>
				<th>Sale Date</th>
				<th>Quantity Sold</th>
			</tr>
		</thead>
		<tbody>
			<?php
			// Connect to database
			$conn = mysqli_connect('localhost', 'root', '', 'bundatan_db');
			if (!$conn) {
			    die("Connection failed: " . mysqli_connect_error());
			}

			// Fetch sales data
			$sql = "SELECT MenuItemID, SaleDate, QuantitySold FROM sales";
			$result = mysqli_query($conn, $sql);

			// Display sales data in table
			if (mysqli_num_rows($result) > 0) {
			    while($row = mysqli_fetch_assoc($result)) {
			        echo "<tr>";
			        echo "<td>" . $row["MenuItemID"] . "</td>";
			        echo "<td>" . $row["SaleDate"] . "</td>";
			        echo "<td>" . $row["QuantitySold"] . "</td>";
			        echo "</tr>";
			    }
			} else {
			    echo "<tr><td colspan='3'>No sales data found</td></tr>";
			}

			mysqli_close($conn);
			?>
		</tbody>
	</table>

	<button onclick="window.print()">Print Report</button>
</body>
</html>
