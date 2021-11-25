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
        <ul class="nav navbar-nav me-auto mb-2 mb-lg-0">
            <a class="nav-item nav-link active" href="index.php">Grafik <span class="sr-only"></span></a>
            <a class="nav-item nav-link" href="tabel.php">Tabel</a>
        </ul>
        <ul class="nav navbar-nav mb-2 mb-lg-0">
            <li><a class="nav-item nav-link" href="/logout/index.php">Logout</a></li>
        </ul>
  </div>
</nav>
    <div class="d-flex justify-content-center">
        <h3 class="mx-auto">Grafik</h3>
    </div>
    <div class="d-flex justify-content-center">
        <div class="chart-container" style="position: relative; width:80vw">
            <canvas id="myChart" data-json="<?php echo htmlspecialchars(json_encode($result)) ?>"></canvas>
        </div>
    </div>
    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        const backgroundColor = [
                        'rgba(255, 0, 0, 0.2)',
                        'rgba(128, 128, 128, 0.2)',
                        'rgba(0, 0, 255, 0.2)',
                        'rgba(0, 255, 0, 0.2)'
                    ];
        const borderColor = [
                        'rgba(255, 0, 0, 1)',
                        'rgba(128, 128, 128, 1)',
                        'rgba(0, 0, 255, 1)',
                        'rgba(0, 255, 0, 1)'
                    ];

        const datas = JSON.parse(document.getElementById('myChart').dataset["json"])
        const chartBackground = []
        const chartBorder = []
        const chartLabels = []
        const chartValues = []
        datas.forEach(data => {
            chartBackground.push(backgroundColor[data.id % backgroundColor.length])
            chartBorder.push(borderColor[data.id % borderColor.length])
            chartLabels.push(data.period)
            chartValues.push(data.value)
        })
        // console.log()

        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Penjualan Home Console Generasi ke-8',
                    data: chartValues,
                    backgroundColor: chartBackground,
                    borderColor: chartBorder,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
