<?php
include "connect.php";

// Define the error message variable
$errorMsg = "";

// Check if the login form is submitted
if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Query the database to fetch the user with the given credentials using prepared statement
  $query = "SELECT * FROM korisnik WHERE username = ? AND password = ?";
  $stmt = mysqli_prepare($dbc, $query);
  mysqli_stmt_bind_param($stmt, "ss", $username, $password);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  // Check if a row is returned
  if ($row = mysqli_fetch_assoc($result)) {
    $isAdmin = $row['admin'];

    if ($isAdmin) {
      // Redirect to the administration page if the user is an administrator
      header("Location: administration.php");
      exit();
    } else {
      // Set the error message for non-administrator users
      $errorMsg = "Hello, " . $username . "! You don't have access to the administration page.";
    }
  } else {
    // Set the error message for invalid username or password
    $errorMsg = "Invalid username or password. Please register <a href='registracija.php'>here</a>.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <style>
    .error-message {
      margin-top: 5px;
      color: red;
    }

    .error-container {
      margin-top: 10px;
    }
  </style>
</head>
<body>
<header>
  <div class="header-content">
    <div class="logo">RP ONLINE</div>
    <nav>
      <ul>
        <li><a href="index.php">HOME</a></li>
        <?php
        $query = 'SELECT DISTINCT kategorija FROM clanci';
        $result = mysqli_query($dbc, $query) or die('Error querying database.');
        while ($row = mysqli_fetch_array($result)) {
          echo '<li><a href="kategorija.php?kat=' . $row["kategorija"] . '">' . strtoupper($row["kategorija"]) . '</a></li>';
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
    <h2>Login</h2>
    <form method="POST" action="login.php">
      <div class="form-item">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
      </div>

      <div class="form-item">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
      </div>

      <div class="form-item">
        <button type="submit" name="submit">Login</button>
      </div>
    </form>
    <div class="error-container">
      <?php echo $errorMsg; ?>
    </div>
  </div>
</div>

<footer>
  &copy; <?php echo date('Y'); ?> RP Online. All rights reserved.
</footer>
</body>
</html>
