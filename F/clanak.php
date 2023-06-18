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


<?php
    include "connect.php";
    if(isset($_GET["id"])){
        $ajdi=$_GET["id"];
    }
    else{
        $ajdi=$idizzle["0"];
    }
    $query = 'SELECT * FROM clanci WHERE id='.$ajdi;
    $result = mysqli_query($dbc, $query) or die('Error querying database 4.');
    $row=mysqli_fetch_array($result);
    mysqli_close($dbc);
?>

<section class="centered-form">
    <div class="wrapper">
        <h2><?php echo $row["kategorija"]; ?></h2>
        <article>
            <div class="article-content">
              <div class= "centered">
                    <h1><?php echo $row["naslov"]; ?></h1>
                    <p><?php echo $row["datum"]; ?></p>
              </div>
              <div class= "centered">
                <?php 
                echo '<img class="clanakslika" src="' . $row["slika"] . '" alt="Article Image">';
                ?>
              </div>
              <div class= "centered">
                  <h4><?php echo $row["ksadrzaj"]; ?></h4>
                  <p><?php echo $row["sadrzaj"]; ?></p>
              </div>
            </div>
        </article>
    </div>
</section>

<footer>
    &copy; 2023 RP Online. All rights reserved.
</footer>
</body>
</html>
