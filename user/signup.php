<?php
session_start();

$signupSuccess = false;

// Check if the signup form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Connect to the MySQL database
  $conn = new mysqli('localhost', 'admin', 'admin', 'db_bundatan');

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Check if the user already exists
  $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    // User already exists, show error message or handle it as per your requirement
    $signupError = "* User already exists.";
  } else {
    // User does not exist, proceed with registration
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();

    // Set the session and mark signup as successful
    $_SESSION['username'] = $username;
    $signupSuccess = true;
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
  <title>Signup</title>
  <link rel="stylesheet" href="../pages/style.css">
  <style>
  body {
  overflow: hidden;
}
</head>
<body>
  <style>
    p {
      color: red;
    }
  </style>
<nav class="navigation">

<h1>Bundatan</h1> 
<div id="boxTitle"></div>
</nav>

<div class="Main">
<div class="container">



<h1>Welcome!</h1>
<?php if (isset($signupError)): ?>
  <style>
    p.error {
      color: red;
    }
  </style>
  <p class="error"><?php echo $signupError; ?></p>
<?php endif; ?>


<form method="POST" action="">
  <label for="username">Username:</label>
  <input type="text" name="username" required><br>

  <label for="password">Password:</label>
  <input type="password" name="password" required><br>


  
  <button type="submit" value="Signup">Signup</button>
</form>
<h4>Already have an account? <a href="../index.php">Login</a></h4>
</div>  </div>

<?php if ($signupSuccess): ?>
  <!-- Modal for successful signup -->
  <div id="successModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <p>Signup successful!</p>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var successModal = document.getElementById('successModal');
      successModal.style.display = 'block';

      // Close the modal and redirect to login when the user clicks on the close button
      var closeBtn = successModal.getElementsByClassName('close')[0];
      closeBtn.onclick = function() {
        successModal.style.display = 'none';
        window.location.href = 'logout.php'; // Replace 'logout.php' with the actual logout URL
      }

      // Close the modal and redirect to login when the user clicks outside the modal
      window.onclick = function(event) {
        if (event.target === successModal) {
          successModal.style.display = 'none';
          window.location.href = 'logout.php'; // Replace 'logout.php' with the actual logout URL
        }
      }
    });
  </script>
<?php endif; ?>
</body>
</html>
