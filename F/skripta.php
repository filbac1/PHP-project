<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Detaljni prikaz</title>
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

    <main>
        <div class="centered-form">
            <div class="details">
            <?php
                if (isset($_GET["id"])) {
                $ajdi = $_GET["id"];
                } else {
                $ajdi = 0;
                }

                $query = 'SELECT * FROM clanci WHERE id=' . $ajdi;
                $result = mysqli_query($dbc, $query) or die('Error querying database.');

                if ($row = mysqli_fetch_array($result)) {
                echo '<h2>Detaljni prikaz</h2>';
                echo '<p><strong>Naslov vijesti:</strong> ' . $row["naslov"] . '</p>';
                echo '<p><strong>Kratki sadržaj:</strong> ' . $row["ksadrzaj"] . '</p>';
                echo '<p><strong>Sadržaj vijesti:</strong> ' . $row["sadrzaj"] . '</p>';
                echo '<p><strong>Kategorija vijesti:</strong> ' . $row["kategorija"] . '</p>';
                echo '<p><strong>Spremiti u arhivu:</strong> ' . ($row["arhiv"] ? "Yes" : "No") . '</p>';
                } else {
                echo 'No article found.';
                }

                mysqli_close($dbc);
            ?>
            </div>
        </div>
    </main>

    <footer>
        &copy; 2023 RP Online. All rights reserved.
    </footer>
</body>
</html>
