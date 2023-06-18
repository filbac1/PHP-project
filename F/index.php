<?php
include "connect.php";
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
    <div class="wrapper"> <!-- Centered container -->
      <?php
        $query='SELECT DISTINCT kategorija FROM clanci';
        $result = mysqli_query($dbc, $query) or die('Error querying database.');
        while($row = mysqli_fetch_array($result)){
          $query='SELECT * FROM clanci WHERE kategorija="'.$row["kategorija"].'"';
          $result2 = mysqli_query($dbc, $query) or die('Error querying database.');
          echo '<section>
                  <h2 id="'.$row["kategorija"].'">'.$row["kategorija"].'</h2>
                  <div>';
          $i=0;
          while($col = mysqli_fetch_array($result2) and $i<3){
            if($col["arhiv"]){
              continue;
            }
            $i= $i + 1;
            echo '<article>
                    <a href="clanak.php?id='.$col["id"].'">
                      <div class="article-wrapper">
                      <img class="article-image" src="' . $col["slika"] . '" alt="Article Image"></a>
                        <div class="article-content">
                          <a href="clanak.php?id='.$col["id"].'">
                            <h4>'.$col["naslov"].'</h4>
                          </a>
                          <p>'.$col["ksadrzaj"].'</p> <!-- Moved ksadrzaj below naslov -->
                        </div>
                      </div>
                  </article>
                  <br>
                  <hr class="spec">';
          }
          echo '</div></section><br><br>';
          
          // Add a blank white space between categories
          echo '<div class="blank-space"></div>';
        }
        mysqli_close($dbc);
      ?>
    </div>
  </div>

  <footer>
    &copy; 2023 RP Online. All rights reserved.
  </footer>
</body>
</html>
