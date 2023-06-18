<?php
include "connect.php";

// Define the error message variable
$errorMsg = "";

// Check if the registration form is submitted
if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirm_password'];
  $admin = 1; // Assuming all registered users have admin rights

  // Check if password and confirm password match
  if ($password !== $confirmPassword) {
    $errorMsg = "Passwords do not match.";
  } else {
    // Insert user into the database using prepared statement
    $insertQuery = "INSERT INTO korisnik (username, password, admin) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($dbc, $insertQuery);
    mysqli_stmt_bind_param($stmt, "ssi", $username, $password, $admin);
    mysqli_stmt_execute($stmt);

    // Check if the insertion was successful
    if (mysqli_stmt_affected_rows($stmt) > 0) {
      // Redirect to a success page or display a success message
      // You can customize this based on your requirements
      header("Location: administration.php");
      exit();
    } else {
      die('Error inserting user into database.');
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Registration</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<header>
  <div class="header-content">
    <div class="logo">RP ONLINE</div>
    <nav>
      <ul>
        <li><a href="index.php">HOME</a></li>
        <?php
        $query='SELECT DISTINCT kategorija FROM clanci';
        $result = mysqli_query($dbc, $query) or die('Error querying database.');
        while($row = mysqli_fetch_array($result)){
          echo '<li><a href="kategorija.php?kat='.$row["kategorija"].'">'.strtoupper($row["kategorija"]).'</a></li>';
        }
        ?>
        <li><a href="login.php">ADMINISTRATION</a></li>
        <li><a href="registracija.php">REGISTRATION</a></li>
        <li><a href="login.php">LOGIN</a></li>
      </ul>
    </nav>
  </div>
  <hr>
</header>

<div class="centered-form">
  <div class="wrapper">
    <h2>Registration</h2>
    <form method="POST" action="registracija.php">
      <div class="form-item">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
      </div>

      <div class="form-item">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
      </div>

      <div class="form-item">
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" required>
      </div>

      <div class="form-item">
        <button type="submit" name="submit">Register</button>
      </div>

      <div class="error-container">
        <?php echo $errorMsg; ?>
      </div>
    </form>
  </div>
</div>

<footer>
  &copy; <?php echo date('Y'); ?> RP Online. All rights reserved.
</footer>
</body>
</html>
