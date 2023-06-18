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
            include "connect.php";
            $query='SELECT DISTINCT kategorija FROM clanci';
            $result = mysqli_query($dbc, $query) or die('Error querying database.');
            while($row = mysqli_fetch_array($result)){
              echo '<li><a href="kategorija.php?kat='.$row["kategorija"].'">'.strtoupper($row["kategorija"]).'</a></li>';
            }
          ?>
          <li><a href="administration.php">ADMINISTRATION</a></li>
        </ul>
      </nav>
    </div>
    <hr>
  </header>

  
  <footer>
    &copy; 2023 RP Online. All rights reserved.
  </footer>
</body>
</html>
