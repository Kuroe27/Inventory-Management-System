<?php
session_start();

// Check if the user is already logged in, redirect to homepage
if (isset($_SESSION['username'])) {
  header("Location: ./pages/dashboard.php");
  exit();
}

// Check if the login form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Connect to the MySQL database
  $conn = new mysqli('localhost', 'root', '', 'db_bundatan');

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the query
  $stmt = $conn->prepare("SELECT username FROM users WHERE username = ? AND password = ?");
  $stmt->bind_param("ss", $username, $password);
  $stmt->execute();
  $stmt->store_result();

  // If a row is found, set the session and redirect to homepage
  if ($stmt->num_rows > 0) {
    $_SESSION['username'] = $username;
    header("Location: ./pages/dashboard.php");
    exit();
  } else {
    // Invalid username or wrong password
    $error = '<p id="p1">Invalid username or wrong password.</p>';
  }
  

  // Close statement and database connection
  $stmt->close();
  $conn->close();
}
?>

<!DOCTYPE html>
<html>


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>
    <link rel="stylesheet" href="./pages/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
  body {
  overflow: hidden;
}

  </style>
  </head>


<body>
  
  <nav class="navigation">
    <a href="./index.php"><h1>Bundatan</h1></a>
    <div id="boxTitle"></div>
  </nav>
  <div class="Main">
    <div class="container">
      <h1>Welcome Back!</h1>
      <?php if (isset($error)) { ?>
        <p><?php echo $error; ?></p>
      <?php } ?>
      <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <button type="submit" value="Login">Login</button>
      </form>
      <h4>Don't have an account? <a href="./user/signup.php">Signup</a></h4>
    </div>
  </div>
</body>
</html>
