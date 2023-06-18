<?php
include "connect.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Get the form data
  $articleId = $_POST["article_id"];
  $naslov = $_POST["title"];
  $ksadrzaj = $_POST["about"];
  $sadrzaj = $_POST["content"];
  $kategorija = $_POST["category"];
  $arhiv = isset($_POST["archive"]) ? 1 : 0;

  // Prepare the SQL statement
  $updateQuery = "UPDATE clanci SET naslov = ?, ksadrzaj = ?, sadrzaj = ?, kategorija = ?, arhiv = ? WHERE id = ?";
  $stmt = mysqli_prepare($dbc, $updateQuery);
  mysqli_stmt_bind_param($stmt, "ssssii", $naslov, $ksadrzaj, $sadrzaj, $kategorija, $arhiv, $articleId);

  // Execute the statement
  mysqli_stmt_execute($stmt);

  // Handle the uploaded photo
  $photoName = $_FILES["pphoto"]["name"];
  $photoTmpName = $_FILES["pphoto"]["tmp_name"];
  $photoError = $_FILES["pphoto"]["error"];

  if ($photoError === UPLOAD_ERR_OK) {
    $uploadPath = "uploads/" . $photoName;
    move_uploaded_file($photoTmpName, $uploadPath);

    // Update the article record with the photo path
    $updatePhotoQuery = "UPDATE clanci SET slika = ? WHERE id = ?";
    $stmt = mysqli_prepare($dbc, $updatePhotoQuery);
    mysqli_stmt_bind_param($stmt, "si", $uploadPath, $articleId);
    mysqli_stmt_execute($stmt);
  }

  // Redirect to a success page or display a success message
  header("Location: administration.php");
  exit();
}

// Check if the article ID is provided
if (isset($_GET["id"])) {
  $articleId = $_GET["id"];

  // Retrieve the article from the database
  $selectQuery = "SELECT * FROM clanci WHERE id = ?";
  $stmt = mysqli_prepare($dbc, $selectQuery);
  mysqli_stmt_bind_param($stmt, "i", $articleId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $article = mysqli_fetch_assoc($result);

  // Check if the article exists
  if (!$article) {
    // Article not found, redirect to an error page or display an error message
    header("Location: error.php");
    exit();
  }
} else {
  // Article ID not provided, redirect to an error page or display an error message
  header("Location: error.php");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>RP Online - Edit</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <style>
    /* Paste the CSS code here */
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
  <form action="update.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="article_id" value="<?php echo $article["id"]; ?>">
    <div class="form-item">
      <label for="title">Naslov vijesti</label>
      <div class="form-field">
        <input type="text" name="title" class="form-field-textual" value="<?php echo $article["naslov"]; ?>">
      </div>
    </div>
    <div class="form-item">
      <label for="about">Kratki sadržaj vijesti (do 50 znakova)</label>
      <div class="form-field">
        <textarea name="about" cols="30" rows="10" class="form-field-textual"><?php echo $article["ksadrzaj"]; ?></textarea>
      </div>
    </div>
    <div class="form-item">
      <label for="content">Sadržaj vijesti</label>
      <div class="form-field">
        <textarea name="content" cols="30" rows="10" class="form-field-textual"><?php echo $article["sadrzaj"]; ?></textarea>
      </div>
    </div>
    <div class="form-item">
      <label for="pphoto">Slika:</label>
      <div class="form-field">
        <input type="file" accept="image/jpg,image/gif" class="input-text" name="pphoto">
      </div>
    </div>
    <div class="form-item">
      <label for="category">Kategorija vijesti</label>
      <div class="form-field">
        <select name="category" class="form-field-textual">
          <option value="sport" <?php if ($article["kategorija"] === "sport") echo "selected"; ?>>Sport</option>
          <option value="kultura" <?php if ($article["kategorija"] === "kultura") echo "selected"; ?>>Kultura</option>
        </select>
      </div>
    </div>
    <div class="form-item">
      <label>Spremiti u arhivu:</label>
      <div class="form-field">
        <input type="checkbox" name="archive" <?php if ($article["arhiv"] === 1) echo "checked"; ?>>
      </div>
    </div>
    <div class="form-item">
      <button type="reset" value="Poništi">Poništi</button>
      <button type="submit" value="Prihvati">Prihvati</button>
    </div>
  </form>
</div>

<footer>
  &copy; <?php echo date('Y'); ?> RP Online. All rights reserved.
</footer>

</body>
</html>
