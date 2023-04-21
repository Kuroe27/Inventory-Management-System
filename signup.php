<?php
session_start();
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password_hash')";

    if (mysqli_query($conn, $sql)) {
        echo "User created successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Sign up</title>
</head>
<body>
  <h1>Sign up</h1>
  <form method="post">
    <label>Username:</label>
    <input type="text" name="username" required>
    <br>
    <label>Email:</label>
    <input type="email" name="email" required>
    <br>
    <label>Password:</label>
    <input type="password" name="password" required>
    <br>
    <button type="submit">Sign up</button>
  </form>
</body>
</html>
