<?php
include("php/config.php");

// Se è presente una sessione, reindirizza alla pagina di home
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

    <!-- AdminLTE v3 core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rock+Salt&display=swap" rel="stylesheet">

    <!-- Customized Favicon -->
    <link rel="icon" href="favicon.png">

    <title>WeatherReport Registration</title>
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <p style="font-family: 'Rock Salt', cursive;">WeatherReport</p>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Create a new account</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Name" name="name">
                        </div>

                        <div class="col-md-6">
                            <label for="surname" class="form-label">Surname</label>
                            <input type="text" class="form-control" id="surname" placeholder="Surname" name="surname">
                        </div>

                        <br>

                        <div class="col-12">
                            <label for="mail" class="form-label">Mail</label>
                            <input type="email" class="form-control" id="mail" placeholder="Insert your mail" name="mail" maxlength="50">
                        </div>

                        <div class="col-12">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Insert a password" name="password" maxlenght="21">
                        </div>

                        <div class="col-12">
                            <label for="password" class="form-label">Confirm password</label>
                            <input type="password" class="form-control"  id="confirm_password" placeholder="Enter your password again" name="confirm_password" maxlenght="20">
                        </div>
                        <br>
                        <button  id="registrationbutton" type="submit" name="register" class="btn btn-primary"> Register </button>
                    </div>
                <div class="col-12">
                    <br>
                    <h6>Are you already registered? <a href="index.php">Click here!</a></h6>
                </div>
            </div>
        </div>
    </div>

</body>
<!-- jQuery per registrarsi -->
<script>
    $('#registrationbutton').click(
        function(){
            var name = $('#name').val(); // Prende il valore dell'input con id "name"
            var surname = $('#surname').val(); // Prende il valore dell'input con id "surname"
            var mail = $('#mail').val(); // Prende il valore dell'input con id "mail"
            var password = $('#password').val(); // Prende il valore dell'input con id "password"
            var confirm_password = $('#confirm_password').val(); // Prende il valore dell'input con id "confirm_password"

            $.ajax({
                url: 'api.php/Users/register',
                type: 'POST',
                data: {
                    name: name,
                    surname: surname,
                    mail: mail,
                    password: password,
                    confirm_password: confirm_password,
                },
                success: function (data) {
                    if(data == "Registration successful")
                        window.location.href = "index.php"; // Reindirizza alla pagina di login
                    else
                        alert(data);
                }
            });
        }
    )
</script>
</html>