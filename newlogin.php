<?php session_start();
require_once 'db/connection.php';

if (isset($_SESSION["user_id"])) {
    header('Location: index.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--Bootstrap CSS-->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/custom.css">

    <title>MVT-Points</title>
</head>

<body class="text-center loginBody">
<div class="bodyWrap">
    <div class="container">
        <div>
            <img class="logoLogin" src="img/mvtlogo.svg">
            <form method="post">
                <?php
                if (isset($_POST["user_id"]) && isset($_POST["password"])) {
                    $user_id = $_POST['user_id'];
                    $password = $_POST['password'];
                    echo $_POST["user_id"] . "<br>";
                    echo $_POST["password"] . "<br>";
                    $sql = "SELECT user_id, passwordhash, role_id FROM user WHERE user_id = ?;";

                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param("s", $user_id);

                    //has the $sql query been propery executed or not?
                    if ($stmt->execute() == false)
                    {
                        //Query failed
                        ?>
                        <div class="alert alert-danger" role="alert">
                            Je kon momenteel niet worden ingelogd. Probeer het later nog eens!
                        </div>
                        <?php
                    }

                    $result = $stmt->get_result();
                    $user = $result->fetch_assoc();

                    //when the query has succeeded and a record has been found we will compare the password with the passwordhash from the database
                    if (password_verify($password, $user['passwordhash']))
                    {
                        ?>
                        <div class="alert alert-success" role="alert">
                            Je word doorgestuurd naar de hoofdpagina..
                        </div>
                        <?php

                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['role_id'] = $user['role_id'];
                        /* Redirect to Index page */
                        header('Location: index.php');
                    }
                    else
                    {
                        ?>
                        <div class="alert alert-danger" role="alert">
                            De combinatie van studentnummer en wachtwoord is onjuist.
                        </div>
                        <?php
                    }
                    $result->free();
                    $connection->close();
                }
                ?>
                <div class="form-group">
                    <label for="loginID">Studentnummer</label>
                    <input formmethod="post" name="user_id" type="name" class="form-control" id="loginID" placeholder="Student ID" required>
                </div>
                <div class="form-group">
                    <label for="loginPass">Wachtwoord</label>
                    <input formmethod="post" name="password" type="password" class="form-control" id="loginPass" placeholder="Wachtwoord" required>
                </div>
                <button type="submit" class="btn btn-light">Inloggen</button>
            </form>
        </div>
    </div>

    <footer class="loginFooterBar boxShadowFooter justify-content-center">
            <p>&copy; Team Gregle, 2018-2019</p>
    </footer>
</div>

<!--Required Scripts-->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
