<?php
include("php/config.php");

// Se non è presente una sessione, reindirizza alla pagina di login
if(!isset($_SESSION['id'])){
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/7c8801c017.js" crossorigin="anonymous"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    <!-- Customized Style -->
    <link rel="stylesheet" href="style.css">

    <!-- Customized Favicon -->
    <link rel="icon" href="favicon.png">

    <title>WeatherReport Home</title>
</head>
<body>

    <div class="icon-container">
        <a class="icon-link" id="userbutton" href="user.php">
            <i class="fa-solid fa-user"></i>
        </a>
        <a class="icon-link" id="historybutton" href="history.php">
            <i class="fa-solid fa-clock-rotate-left"></i>
        </a>
        <a class="icon-link" id="logoutbutton" href="#">
            <i class="fa-solid fa-right-from-bracket"></i>
        </a>
        <a class="icon-link" href="https://github.com/mastronardo/WeatherReport" target="_blank">
            <i class="fa-brands fa-github"></i>
        </a>
    </div>

    <div class="container" id="container">
        <div class="search-box" id="search-box">
            <i class="fa-solid fa-location-dot"></i>
            <input id="input" type="text" placeholder="Enter location">
            <button id="button" class="fa-solid fa-magnifying-glass"></button>
        </div>

        <div class="not-found" id="not-found">
            <img src="images/404.png">
            <p>Location not found :/</p>
        </div>

        <div class="weather-box" id="weather-box">
            <img src="" id="img">
            <p class="temperature" id="temperature"></p>
            <p class="description" id="description"></p>
        </div>

        <div class="weather-details" id="weather-details">
            <div class="humidity" id="humidity">
                <i class="fa-solid fa-water"></i>
                <div class="text" id="textH">
                    <span id="spanH"></span>
                    <p>Humidity (%)</p>
                </div>
            </div>
            <div class="wind" id="wind">
                <i class="fa-solid fa-wind"></i>
                <div class="text" id="textW">
                    <span id="spanW"></span>
                    <p>Wind (m/s)</p>
                </div>
            </div>
        </div>
    </div>
</body>
<!-- JavaScript per le previsioni meteo -->
<script src="index.js"></script>

<!-- jQuery per il logout -->
<script>
    $('#logoutbutton').click(function(){
        $.ajax({
            url: "api.php/Users/logout",
            type: "POST",
            success: function(data){
                if (data == "Logout successful")
                    window.location.href = "index.php";
                else
                    alert(data);
            }
        });
    })
</script>

<!-- jQuery per salvare nella cronologia la ricerca appenna effettuata  -->
<script>
    $('#button').click(function(){
        var city = $('#city').val(); // Prende il valore dell'elemento con id city
        var description = $('#description').val(); // Prende il valore dell'elemento con id description
        var temperature = $('#temperature').val(); // Prende il valore dell'elemento con id temperature
        var humidity = $('#humidity').val(); // Prende il valore dell'elemento con id humidity
        var wind = $('#wind').val(); // Prende il valore dell'elemento con id wind

        $.ajax({
            url: "api.php/history/insert_weather",
            type: "POST",
            data: {
                city: city,
                description: description,
                temperature: temperature,
                humidity: humidity,
                wind: wind,
            }
        });
    })
</script>
</html>