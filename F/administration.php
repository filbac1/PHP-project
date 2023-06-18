<?php
include "connect.php";

// Check if the delete form is submitted
if (isset($_POST['delete'])) {
  $id = $_POST['article_id'];

  // Delete the article from the database
  $deleteQuery = "DELETE FROM clanci WHERE id = $id";
  mysqli_query($dbc, $deleteQuery) or die('Error deleting article.');

  // Redirect to the same page to update the displayed list after deletion
  header("Location: administration.php");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>RP Online</title>
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
    <form enctype="multipart/form-data" method="POST">
      <div class="form-item">
        <label for="article">Choose an article to update:</label>
        <div class="form-field">
          <?php
          // Fetch all articles from the database
          $articleQuery = "SELECT * FROM clanci";
          $articles = mysqli_query($dbc, $articleQuery);

          while ($article = mysqli_fetch_array($articles)) {
            $articleId = $article['id'];
            $articleTitle = $article['naslov'];
            echo '<input type="radio" name="article_id" value="'.$articleId.'">'.$articleTitle.'<br>';
          }
          ?>
        </div>
      </div>
      <div class="form-item">
        <a href="unos.php"><button type="button" class="button">Add</button></a>
        <a href="update.php?id=<?php echo $articleId; ?>"><button type="button" class="button">Update</button></a>
        <button type="submit" name="delete" value="Delete">Delete</button>
        <button type="reset" value="Reset">Reset</button>
      </div>
    </form>
  </div>
</div>
<footer>
  &copy; <?php echo date('Y'); ?> RP Online. All rights reserved.
</footer>
</body>
</html>
