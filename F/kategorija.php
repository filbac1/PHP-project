<?php
include "connect.php";
?>

<!DOCTYPE html>
<html>
<head>
  <title>RP Online - Kategorija</title>
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
  <div class="wrapper"> <!-- Centered container -->
    <section>
      <h2><?php echo $_GET["kat"]; ?></h2>
      <div>
        <?php
          $query = 'SELECT * FROM clanci WHERE kategorija="'.$_GET["kat"].'"';
          $result = mysqli_query($dbc, $query) or die('Error querying database.');
          while($row = mysqli_fetch_array($result)){
            if($row["arhiv"]){
              continue;
            }
            echo '<article>
                    <a href="clanak.php?id='.$row["id"].'">
                      <div class="article-wrapper">
                      <img class="article-image" src="' . $row["slika"] . '" alt="Article Image"></a>
                        <div class="article-content">
                          <a href="clanak.php?id='.$row["id"].'">
                            <h4>'.$row["naslov"].'</h4>
                          </a>
                          <p>'.$row["ksadrzaj"].'</p>
                        </div>
                      </div>
                  </article>
                  <hr class="spec">';
          }
        ?>
      </div>
    </section>
    <br><br>
  </div>
</div>

<footer>
  &copy; <?php echo date('Y'); ?> RP Online. All rights reserved.
</footer>
</body>
</html>
