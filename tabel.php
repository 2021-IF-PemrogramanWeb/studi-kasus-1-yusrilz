<?php

session_start();

if(!($_SESSION["loggedin"] ?? false)) {
    header('location: /login');
    exit;
}

require_once "./config.php";
$sql = "SELECT * FROM JML_MHS WHERE 1";
// $statement = mysqli_prepare($link, $sql);
if(($statement = mysqli_prepare($link, $sql)) === false) {
    header("location: /");
    $_SESSION["warning"] = "Internal error";
    exit;
}
if (!mysqli_stmt_execute($statement)) {
    header("location: /");
    $_SESSION["warning"] = "Internal error";
    mysqli_stmt_close($statement);
    exit;
}
mysqli_stmt_store_result($statement);
mysqli_stmt_bind_result($statement, $id, $period, $val);
$result = [];
while (mysqli_stmt_fetch($statement)) {
    array_push($result, ['id' => $id, 'period' => $period, 'value' => $val]);
}
// var_dump(json_encode($result));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js" integrity="sha256-bC3LCZCwKeehY6T4fFi9VfOU0gztUa+S4cnkIhVPZ5E=" crossorigin="anonymous"></script>
    <title>Home</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Pemrograman Web</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
    <div class="navbar-nav">
      <a class="nav-item nav-link" href="index.php">Grafik </a>
      <a class="nav-item nav-link active" href="tabel.php">Tabel<span class="sr-only"></span></a>
    </div>
  </div>
</nav>
    <div class="d-flex justify-content-center mt-3">
        <h3 class="mx-auto">Tabel</h3>
    </div>
    <div class="d-flex justify-content-center">
        <table class="table" style="position: relative; width:80vw">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Home Console</th>
                    <th scope="col">Jumlah Penjualan</th>
                </tr>
            </thead>
            <tbody>
            <?php
                foreach($result as $row) { ?>
                <tr>
            <?php
                echo '<th scope="row">' . $row['id'] . '</th>';
                echo '<td>' . $row['period'] . '</td>';
                echo '<td>' . $row['value'] . '</td>';
            ?>
                </tr>
            <?php
                }
            ?>
            </tbody>
        </table>
    </div>
</body>
</html>
