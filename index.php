<?php
include("php/config.php");

// Se è presente una sessione, reindirizza alla pagina home
if(isset($_SESSION['id'])){
    header("Location: home.php");
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

    <!-- AdminLTE v3 core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rock+Salt&display=swap" rel="stylesheet">

    <!-- Customized Favicon -->
    <link rel="icon" href="favicon.png">

    <title>WeatherReport Login</title>
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <p style="font-family: 'Rock Salt', cursive;">WeatherReport</p>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Login to your account</p>
                    <div class="col-12">
                        <label for="mail" class="form-label">Mail</label>
                        <input type="email" class="form-control" id="mail" placeholder="Insert your mail" name="mail" >
                    </div>
                    <div class="col-12">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Insert a password" name="password">
                    </div>
                    <br>
                    <button id="loginbutton" type="submit" name="login" class="btn btn-primary"> Login </button>
                <div class="col-12">
                    <br>
                    <h6> Not registered yet? <a href="registration.php">Click here!</a></h6>
                </div>
            </div>
        </div>
    </div>

</body>
<!-- jQuery per effettuare il login -->
<script>
    $('#loginbutton').click(function(){
            var mail = $('#mail').val(); // Prende il valore della mail inserita
            var password = $('#password').val(); // Prende il valore della password inserita

            $.ajax({
                url: 'api.php/Users/login',
                type: 'POST',
                data: {
                    mail: mail,
                    password: password,
                },
                success: function (data) {
                    if (data == "Welcome to WeatherReport!")
                        window.location.href = "home.php"; // Reindirizza alla pagina home se il login è andato a buon fine
                    else
                        alert(data);
                }
            });
        }
    )
</script>
</html>
