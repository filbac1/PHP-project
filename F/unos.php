<?php
include "connect.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Validate form fields
  $errors = [];

  // Validate title
  if (empty($_POST["title"]) || strlen($_POST["title"]) < 5 || strlen($_POST["title"]) > 30) {
    $errors[] = "Naslov vijesti mora imati između 5 i 30 znakova.";
  }

  // Validate about
  if (empty($_POST["about"]) || strlen($_POST["about"]) < 10 || strlen($_POST["about"]) > 100) {
    $errors[] = "Kratki sadržaj vijesti mora imati između 10 i 100 znakova.";
  }

  // Validate content
  if (empty($_POST["content"])) {
    $errors[] = "Tekst vijesti ne smije biti prazan.";
  }

  // Validate photo
  if (empty($_FILES["pphoto"]["name"])) {
    $errors[] = "Morate odabrati sliku.";
  }

  // Validate category
  if (empty($_POST["category"])) {
    $errors[] = "Morate odabrati kategoriju vijesti.";
  }

  // If there are validation errors, display them and stop further processing
  if (!empty($errors)) {
    foreach ($errors as $error) {
      echo $error . "<br>";
    }
    exit();
  }

  // Form fields are valid, proceed with data processing

  // Get the current date
  $currentDate = date("Y-m-d");

  // Get the form data
  $naslov = $_POST["title"];
  $ksadrzaj = $_POST["about"];
  $sadrzaj = $_POST["content"];
  $kategorija = $_POST["category"];
  $arhiv = isset($_POST["archive"]) ? 1 : 0;

  // Prepare the SQL statement
  $insertQuery = "INSERT INTO clanci (naslov, ksadrzaj, sadrzaj, kategorija, arhiv, datum) VALUES (?, ?, ?, ?, ?, ?)";
  $stmt = mysqli_prepare($dbc, $insertQuery);
  mysqli_stmt_bind_param($stmt, "ssssis", $naslov, $ksadrzaj, $sadrzaj, $kategorija, $arhiv, $currentDate);

  // Execute the statement
  mysqli_stmt_execute($stmt);

  // Get the ID of the inserted article
  $articleId = mysqli_insert_id($dbc);

  // Handle the uploaded photo
  $photoName = $_FILES["pphoto"]["name"];
  $photoTmpName = $_FILES["pphoto"]["tmp_name"];
  $photoError = $_FILES["pphoto"]["error"];

  // Debug statement
  echo "Photo Error: " . $photoError;

  if ($photoError === UPLOAD_ERR_OK) {
    $uploadPath = "slike/" . $photoName;
    if (move_uploaded_file($photoTmpName, $uploadPath)) {
      // Update the article record with the photo path
      $updateQuery = "UPDATE clanci SET slika = ? WHERE id = ?";
      $stmt = mysqli_prepare($dbc, $updateQuery);
      mysqli_stmt_bind_param($stmt, "si", $uploadPath, $articleId);
      mysqli_stmt_execute($stmt);

      // Check if the update was successful
      if (mysqli_stmt_affected_rows($stmt) > 0) {
        // Success: photo uploaded and article updated
        echo "Photo uploaded and article updated successfully.";
      } else {
        // Error: article update failed
        echo "Error updating the article.";
      }
    } else {
      // Failed to move the uploaded file
      echo "Error moving the uploaded file.";
    }
  } else {
    // Error occurred during file upload
    echo "Error uploading the file. Error code: " . $photoError;
  }

  // Redirect to a success page or display a success message
  header("Location: administration.php");
  exit();
}
?>

<!-- Rest of your HTML code -->
<!DOCTYPE html>
<html>
<head>
  <title>RP Online - Unos</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <style>
    /* Paste the CSS code here */
    .error {
      color: red;
    }
  </style>
  <script>
    // JavaScript form validation
    function validateForm() {
      var title = document.forms["unosForm"]["title"].value;
      var about = document.forms["unosForm"]["about"].value;
      var content = document.forms["unosForm"]["content"].value;
      var photo = document.forms["unosForm"]["pphoto"].value;
      var category = document.forms["unosForm"]["category"].value;

      var errors = [];

      if (title.length < 5 || title.length > 30) {
        errors.push("Naslov vijesti mora imati između 5 i 30 znakova.");
        document.forms["unosForm"]["title"].classList.add("error");
      } else {
        document.forms["unosForm"]["title"].classList.remove("error");
      }

      if (about.length < 10 || about.length > 100) {
        errors.push("Kratki sadržaj vijesti mora imati između 10 i 100 znakova.");
        document.forms["unosForm"]["about"].classList.add("error");
      } else {
        document.forms["unosForm"]["about"].classList.remove("error");
      }

      if (content.length === 0) {
        errors.push("Tekst vijesti ne smije biti prazan.");
        document.forms["unosForm"]["content"].classList.add("error");
      } else {
        document.forms["unosForm"]["content"].classList.remove("error");
      }

      if (photo.length === 0) {
        errors.push("Morate odabrati sliku.");
        document.forms["unosForm"]["pphoto"].classList.add("error");
      } else {
        document.forms["unosForm"]["pphoto"].classList.remove("error");
      }

      if (category.length === 0) {
        errors.push("Morate odabrati kategoriju vijesti.");
        document.forms["unosForm"]["category"].classList.add("error");
      } else {
        document.forms["unosForm"]["category"].classList.remove("error");
      }

      if (errors.length > 0) {
        var errorContainer = document.getElementById("errorContainer");
        errorContainer.innerHTML = "";
        for (var i = 0; i < errors.length; i++) {
          errorContainer.innerHTML += errors[i] + "<br>";
        }
        return false;
      }

      return true;
    }
  </script>
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
<form name="unosForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
    <div class="form-item">
      <label for="title">Naslov vijesti</label>
      <div class="form-field">
        <input type="text" name="title" class="form-field-textual">
      </div>
    </div>
    <div class="form-item">
      <label for="about">Kratki sadržaj vijesti (do 50 znakova)</label>
      <div class="form-field">
        <textarea name="about" cols="30" rows="10" class="form-field-textual"></textarea>
      </div>
    </div>
    <div class="form-item">
      <label for="content">Sadržaj vijesti</label>
      <div class="form-field">
        <textarea name="content" cols="30" rows="10" class="form-field-textual"></textarea>
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
          <option value="">Odaberi kategoriju</option>
          <option value="Sport">Sport</option>
          <option value="Kultura">Kultura</option>
        </select>
      </div>
    </div>
    <div class="form-item">
      <label>Spremiti u arhivu:</label>
      <div class="form-field">
        <input type="checkbox" name="archive">
      </div>
    </div>
    <div class="form-item">
      <button type="reset" value="Poništi">Poništi</button>
      <button type="submit" value="Prihvati">Prihvati</button>
    </div>
  </form>
  <div id="errorContainer" class="error"></div>
</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> RP Online</p>
</footer>
</body>
</html>

