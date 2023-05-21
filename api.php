<?php
include("php/config.php");

$method = $_SERVER['REQUEST_METHOD']; // Prendi il metodo della richiesta
$request = explode('/', trim($_SERVER['PATH_INFO'],'/')); // Prendi il contenuto della richiesta
$function = preg_replace('/[^a-z0-9_]+/i','',array_shift($request)); // Prendi il nome della tabella

switch ($function){
    // Operazioni per la tabella history
    case 'history':
        switch ($method) {
            case 'GET':
                // Visualizzazione della cronologia delle ricerche
                if ($request[0] == 'view_weather'){
                        $query = $connex_db->prepare(
                            "SELECT history.historyID, history.datetime, history.city, history.description,
                                            history.temperature, history.humidity, history.wind
                            FROM history INNER JOIN Users ON history.UserID = Users.UserID
                            WHERE Users.UserID = ?");
                        $query->bind_param("i", $_SESSION['id']);
                        $query->execute();
                        $result = $query->get_result();
                        $hist = [];
                        while($row = $result->fetch_assoc()){
                            $hist['hist'][]=$row;
                        }
                        echo json_encode($hist);
                }
                else
                    echo "Invalid request";

                break;


            case 'POST':
                // Ogni ricerca andata a buon fine verrà inserita nella cronologia
                if($request[0] == 'insert_weather'){
                        $city = $connex_db->real_escape_string($_POST['city']);
                        $description = $connex_db->real_escape_string($_POST['description']);
                        $temperature = $connex_db->real_escape_string($_POST['temperature']);
                        $humidity = $connex_db->real_escape_string($_POST['humidity']);
                        $wind = $connex_db->real_escape_string($_POST['wind']);
                        date_default_timezone_set('Europe/Berlin');
                        $datetime = date('Y-m-d H:i:s');

                        if(strlen(trim($city)) != 0){
                                $query = $connex_db->prepare(
                                    "INSERT INTO history (UserID, datetime, city, description, temperature, humidity, wind)
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
                                $query->bind_param("isssiii", $_SESSION['id'], $datetime,$city, $description,
                                    $temperature, $humidity, $wind);
                                $query->execute();
                                echo("Weather inserted");
                        }
                }
                else
                    echo "Invalid request";

                break;


            case 'DELETE':
                // L'utente potrà eliminare una ricerca dalla cronologia
                if($request[0] == 'delete_weather'){
                        parse_str(file_get_contents('php://input'), $_DELETE); // Prendi il contenuto della richiesta delete
                        $id = $connex_db->real_escape_string($_DELETE['id']);

                        $query = $connex_db->prepare(
                            "DELETE FROM history 
                                    WHERE history.historyID = ?");
                        $query->bind_param("i", $id);
                        $query->execute();
                        echo "Weather deleted";
                }
                else
                    echo "Invalid request";

        }
        break;


    // Operazioni per la tabella Users
    case 'Users':
       switch ($method) {
           case 'GET':
               // Nella pagina dell'utente vengono mostrati il suo nome, cognome e mail
               if($request[0] == 'credentials'){
                         $query = $connex_db->prepare(
                              "SELECT name, surname, mail
                              FROM Users
                              WHERE UserID = ?");
                         $query->bind_param("i", $_SESSION['id']);
                         $query->execute();
                         $result = $query->get_result();
                         $json = $result->fetch_assoc();
                         echo json_encode($json);
               }
               else
                   echo "Invalid request";

            break;


           case 'POST':
                // Operazione per effettuare il login
               if ($request[0] == 'login') {
                       $mail = $connex_db->real_escape_string($_POST['mail']);
                       $mail = filter_var($mail, FILTER_SANITIZE_EMAIL);
                       $password = $connex_db->real_escape_string($_POST['password']);

                       $query = $connex_db->prepare(
                           "SELECT *
                           FROM Users
                           WHERE mail = ?");
                       $query->bind_param("s", $mail);
                       $query->execute();
                       $result = $query->get_result();

                       $arr = mysqli_fetch_array($result, MYSQLI_ASSOC); // Ottieni un array associativo dei risultati

                       if (password_verify($password, $arr["password"])) {
                           session_start();
                           echo "Welcome to WeatherReport!";
                           $_SESSION['id'] = $arr['UserID'];
                       }
                       else
                           echo "Wrong credentials!";
               }

               // Operazione per effettuare il lougout
               elseif ($request[0] == 'logout') {
                       session_start();

                       // Elimina tutte le variabili di sessione
                       $_SESSION = array();
                       session_destroy();

                       // Imposta le direttive di controllo della cache
                       header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
                       header("Pragma: no-cache"); // HTTP 1.0.
                       header("Expires: 0"); // Proxies.

                       echo "Logout successful";
               }

                // Operazione per effettuare la registrazione
               elseif ($request[0] == 'register') {
                       $name = $connex_db->real_escape_string($_POST['name']);
                       $surname = $connex_db->real_escape_string($_POST['surname']);
                       $mail = $connex_db->real_escape_string($_POST['mail']);
                       $mail = filter_var($mail, FILTER_SANITIZE_EMAIL);
                       $password = $connex_db->real_escape_string($_POST['password']);
                       $confirm_password = $connex_db->real_escape_string($_POST['confirm_password']);
                       $pwdLenght = mb_strlen($password);
                       $cpwdLenght = mb_strlen($confirm_password);

                       if ((!preg_match("/^[a-zA-Z]*$/", $name)) || (empty($name))) {
                           echo "Registration failed: Use a correct name";
                       }
                       elseif ((!preg_match("/^[a-zA-Z]*$/", $surname)) || (empty($surname))) {
                           echo "Registration failed: Use a correct surname";
                       }
                       elseif ((!filter_var($mail, FILTER_VALIDATE_EMAIL)) || (empty($mail))) {
                           echo "Registration failed: Use a correct mail";
                       }
                       elseif ($pwdLenght < 4 || $pwdLenght >= 20) {
                           echo "Registration failed: The length of the password must be between 5 and 20 characters";
                       }
                       elseif ($cpwdLenght < 4 || $cpwdLenght >= 20) {
                           echo "Registration failed: The length of the password must be between 5 and 20 characters";
                       }
                       elseif ($password != $confirm_password) {
                           echo "Registration failed: Passwords do not match";
                       }
                       else {
                           $password_hash = password_hash($password, PASSWORD_BCRYPT);// hashing password
                           $query = $connex_db->prepare(
                               "INSERT INTO Users (name, surname, mail, password)
                                VALUES (?, ?, ?, ?)");
                           $query->bind_param("ssss", $name, $surname, $mail, $password_hash);

                           if ($query->execute())
                               echo "Registration successful";
                           else
                               echo "Mail already used";
                       }
               }
               else
                   echo "Invalid request";

               break;


           case 'PUT':
               // L'utente può modificare la propria password
               if($request[0] == 'update'){
                       parse_str(file_get_contents('php://input'), $_PUT); // Prendi il contenuto della richiesta put

                       $password = $connex_db->real_escape_string($_PUT['password']);
                       $new_password = $connex_db->real_escape_string($_PUT['new_password']);
                       $pwdLenght = mb_strlen($new_password);

                       $query = $connex_db->prepare(
                           "SELECT password
                           FROM Users
                           WHERE UserID = ?");
                       $query->bind_param("i", $_SESSION['id']);
                       $query->execute();
                       $result = $query->get_result();

                       $arr = mysqli_fetch_array($result, MYSQLI_ASSOC); // Ottieni un array associativo dei risultati

                       if( (strlen(trim($password)) == 0) || (strlen(trim($new_password)) == 0) )
                           echo "Fill in all fields";

                       if(password_verify($password, $arr["password"])){
                           if ( ($new_password == $password) || ($pwdLenght < 4 || $pwdLenght >= 20) )
                               echo "Problem with new password";
                           else{
                               $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);// hashing password
                               $query = $connex_db->prepare(
                                   "UPDATE Users
                               SET password = ?
                               WHERE UserID = ?");
                               $query->bind_param("si", $new_password_hash, $_SESSION['id']);
                               $query->execute();
                               echo "Password changed";
                           }
                       }
                       else
                           echo "Wrong password";
               }
               else
                   echo "Invalid request";

               break;


           case 'DELETE':
                // L'utente può eliminare il proprio account inserendo la propria mail e password
               if($request[0] == 'delete'){
                       parse_str(file_get_contents('php://input'), $_DELETE); // Prendi il contenuto della richiesta delete

                       $password = $connex_db->real_escape_string($_DELETE['password']);
                       $mail = $connex_db->real_escape_string($_DELETE['mail']);
                       $mail = filter_var($mail, FILTER_SANITIZE_EMAIL);

                       $query = $connex_db->prepare(
                              "SELECT password, mail
                              FROM Users
                              WHERE UserID = ?");
                       $query->bind_param("i", $_SESSION['id']);
                       $query->execute();
                       $result = $query->get_result();
                       $arr = mysqli_fetch_array($result, MYSQLI_ASSOC);

                       if( (strlen($password) == 0) || (strlen($mail) == 0) )
                           echo "Fill in all fields";

                         elseif ( (password_verify($password, $arr["password"])) && ($mail == $arr["mail"])){
                             $query2 = $connex_db->prepare(
                                 "DELETE FROM Users
                                 WHERE UserID = ? and mail = ?");
                             $query2->bind_param("is", $_SESSION['id'], $mail);
                             $query2->execute();
                             echo "Account deleted";
                         }
                         else
                             echo "Wrong credentials";

               }
               else
                   echo "Invalid request";

               break;
       }
}