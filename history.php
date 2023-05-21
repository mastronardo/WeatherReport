<?php
include("php/config.php");

// Se non è presente una sessione, reindirizza alla pagina di login
if(!isset($_SESSION['id'])){
    header("Location: index.php");
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/7c8801c017.js" crossorigin="anonymous"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    <!-- Style -->
    <link rel="stylesheet" href="bootstrap.css">

    <!-- Customized Style -->
    <link rel="stylesheet" href="history.css">

    <!-- AdminLTE v3 core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rock+Salt&display=swap" rel="stylesheet">

    <!-- Customized Favicon -->
    <link rel="icon" href="favicon.png">

    <title>WeatherReport History</title>
</head>
<body>

    <div class="icon-container">
        <a class="icon-link" href="home.php">
            <i class="fa-solid fa-house-tsunami"></i>
        </a>
        <a class="icon-link" href="https://github.com/mastronardo/WeatherReport" target="_blank">
            <i class="fa-brands fa-github"></i>
        </a>
    </div>

    <div class="container mt-4 mb-5">
        <div class="row">
            <div class="col-12">
                <h3 style="font-family: 'Rock Salt', cursive;">History</h3>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table">
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">City</th>
                                <th scope="col">Description</th>
                                <th scope="col">Temperature (°C)</th>
                                <th scope="col">Humidity (%)</th>
                                <th scope="col">Wind (m/s)</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody class="table-group-divider" id="historytable"></tbody>
                        </table>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</body>

<!-- jQuery per la visualizzazione di tutte le ricerche -->
<script>
    var hist = []; // Array che conterrà tutte le ricerche
    $.ajax({
        url: "api.php/history/view_weather",
        type: "GET",
        success: function (data) {
            var json = JSON.parse(data); // Converte la stringa in JSON
            hist = json.hist; // Assegna l'array di ricerche a hist
            var html = ""; // Stringa che conterrà l'HTML
            $('#historytable').empty(); // Svuota la tabella
            for (var i = 0; i < hist.length; i++) { // Cicla tutte le ricerche
                html += "<tr id="+ hist[i].historyID + ">";
                html += "<td>" + hist[i].datetime + "</td>";
                html += "<td>" + hist[i].city + "</td>";
                html += "<td>" + hist[i].description + "</td>";
                html += "<td>" + hist[i].temperature + "</td>";
                html += "<td>" + hist[i].humidity + "</td>";
                html += "<td>" + hist[i].wind + "</td>";
                html += "<td>";
                html += "<button type='submit' class='btn btn-danger m-2' onclick='deleteHist(" + hist[i].historyID + ")'><i class=\"bi bi-x-square\"></i></button></td>";
                html += "</tr>";
            }
            $('#historytable').append(html); // Aggiunge la variabile html alla tabella
        }
    });
</script>

<!-- jQuery per cancellare una riga dalla cronologia -->
<script>
function deleteHist(id) {
        $.ajax({
            url: "api.php/history/delete_weather",
            type: "DELETE",
            data: {
                id: id,
            },
            success: function (data) {
                if (data == "Weather deleted") {
                    location.reload(); // Aggiorna la tabella
                }
                else {
                    alert(data);
                }
            }
        });
    }
</script>
</html>