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

    <!-- Bootstrap v5 core CSS -->
    <link rel="stylesheet" href="bootstrap.css">

    <!-- AdminLTE v3 core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">

    <!-- Customized Style -->
    <link rel="stylesheet" href="user.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rock+Salt&display=swap" rel="stylesheet">

    <!-- Customized Favicon -->
    <link rel="icon" href="favicon.png">

    <title>WeatherReport User</title>
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

    <div class="container mt-5 mb-5 settingStyle">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4" style="font-family: 'Rock Salt', cursive;">Credentials</h1>
                <div class="row g-3">

                    <div class="col-md-4">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Name" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="surname" class="form-label">Surname</label>
                        <input type="text" class="form-control" id="surname" placeholder="Surname" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="mail" class="form-label">Mail</label>
                        <input type="email" class="form-control" id="mail" placeholder="mail" readonly>
                    </div>

                    <h4 class="mb-1">If you want to update your password:</h4>
                    <div class="col-md-6">
                        <label for="password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Current password">
                    </div>
                    <div class="col-md-6">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" placeholder="Confirm Password">
                    </div>
                    <div class="col-md-6">
                        <button id="update" type="submit" name="update" class="btn btn-primary">Update Password</button>
                    </div>

                    <h4 class="mb-1">If you want to delete your account:</h4>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Mail</label>
                        <input type="email" class="form-control" id="email" placeholder="Mail">
                    </div>
                    <div class="col-md-6">
                        <label for="confirmPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="confirmPassword" placeholder="Password">
                    </div>
                    <div class="col-md-6">
                        <button id="deleteUser" type="submit" class="btn btn-danger mb-2">Delete User Account</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<!-- jQuery per mostrare nome, cognome e mail dell'utente -->
<script>
    $.ajax({
            url: "api.php/Users/credentials",
            type: "GET",
            success: function (data){
                var json = JSON.parse(data); // Converte la stringa in JSON
                $('#name').val(json.name); // Inserisce il nome dell'utente nel campo
                $('#surname').val(json.surname); // Inserisce il cognome dell'utente nel campo
                $('#mail').val(json.mail); // Inserisce la mail dell'utente nel campo
            }
        });
</script>

<!-- jQuery per modificare la password dell'utente  -->
<script>
    $('#update').click(function (){
        var password = $('#password').val(); // Prende il valore del campo password
        var new_password = $('#new_password').val(); // Prende il valore del campo new_password

        $.ajax({
            url: 'api.php/Users/update',
            type: 'PUT',
            data: {
                password: password,
                new_password: new_password,
            },
            success: function (data) { // Se la chiamata ha successo viene effettuato anche il logout
                if (data == "Password changed") {
                    $.ajax({
                        url: "api.php/Users/logout",
                        type: "POST",
                        success: function (data) {
                            if (data == "Logout successful")
                                window.location.href = "index.php";
                            else
                                alert(data);
                        }
                    });
                }
                else
                    alert(data);
            }
        });
    })
</script>

<!-- jQuery per eliminare l'account dell'utente  -->
<script>
    $('#deleteUser').click(function (){
        var mail = $('#email').val(); // Prende il valore del campo mail
        var password = $('#confirmPassword').val(); // Prende il valore del campo password

        $.ajax({
            url: 'api.php/Users/delete',
            type: 'DELETE',
            data: {
                password: password,
                mail: mail,
            },
            success: function (data) { // Se la chiamata ha successo viene effettuato anche il logout
                if (data == "Account deleted") {
                    $.ajax({
                        url: "api.php/Users/logout",
                        type: "POST",
                        success: function (data) {
                            if (data == "Logout successful")
                                window.location.href = "index.php";
                            else
                                alert(data);
                        }
                    });
                }
                else
                    alert(data);
            }
        });
    })
</script>
</html>