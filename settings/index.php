<?php
session_start();
require_once '../db/connection.php';
require_once "../app/classes/Role.php";
require_once "../app/interfaces/CRUD.php";

if (!isset($_SESSION["user_id"]))
{
    header('Location: ../login.php');
    exit;
}

//  get the role name by id, use the result as constructor parameter for the Role object
$selectRole = $connection->prepare("SELECT role FROM role WHERE id = ? ;");
$selectRole->bind_param("i", $_SESSION["role_id"]);
$selectRole->execute();
$selectRole->bind_result($role_name);
$selectRole->fetch();
$selectRole->free_result();

//  the Role object has some nice functions to check if the created role is a student, administrator or teacher
$role = new Role($role_name);
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--Bootstrap CSS-->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/custom.css">

    <title>MVT-Points</title>
</head>

<body class="text-center basicBody">
    <div class="bodyWrap">
        <div class="container">
            <div>
                <p class="paragraphMarginSmall">Wachtwoord wijzigen</p>
                <form method="post">
                    <div class="form-group">
                        <input formmethod="post" type="password" name="passwd1" class="form-control" id="passwd1"
                               placeholder="Nieuw wachtwoord" required>
                    </div>
                    <div class="form-group">
                        <input formmethod="post" type="password" name="passwd2" class="form-control" id="passwd2"
                               placeholder="Herhaal wachtwoord" required>
                    </div>
                    <?php
                    //  if passwd1 and passwd2 are set, the password of the user has been changed.
                    if (isset($_POST["passwd1"], $_POST["passwd2"]))
                    {
                        //  failsafe check (and in case something goes wrong, display message)
                        if ($_POST["passwd1"] == $_POST["passwd2"])
                        {
                            $PwdFlaws = "";
                            //Controleer password lengte
                            //in this strlen if-statement we do a server side check if the password passes the requierment
                            if (strlen($_POST["passwd1"]) <= 8)
                            {
                                $PwdFlaws = "Minstens 8 karakters te bevaten.";
                            }

                            //in these 4 preg_match if-statements below we do a server side check if the password passes the 4 requierments
                            if (preg_match("([!,%,&,@,#,$,^,*,?,_,~])", $_POST["passwd1"]) === 0)
                            {
                                $PwdFlaws = $PwdFlaws . "<br>" . "Uw invoer dient minimaal 1 speciaal teken te bevaten.";
                            }

                            if (preg_match("([a-z])", $_POST["passwd1"]) === 0)
                            {
                                $PwdFlaws = $PwdFlaws . "<br>" . "Uw invoer dient minimaal 1 kleine letter te bevaten.";
                            }

                            if (preg_match("([A-Z])", $_POST["passwd1"]) === 0)
                            {
                                $PwdFlaws = $PwdFlaws . "<br>" . "Uw invoer dient minimaal 1 hoofdletter te bevaten.";
                            }

                            if (preg_match("([0-9])", $_POST["passwd1"]) === 0)
                            {
                                $PwdFlaws = $PwdFlaws . "<br>" . "Uw invoer dient minimaal 1 getal te bevaten.";
                            }


                            //check if there are no error by checking the length of the error massage
                            if (strlen($PwdFlaws) == 0)
                            {
                                //  hash the password
                                $passwd = password_hash($_POST["passwd1"], PASSWORD_BCRYPT);

                                //  update password query
                                $updatePasswd = $connection->prepare("UPDATE user SET passwordhash = ? WHERE user_id = ?;");
                                $updatePasswd->bind_param("ss", $passwd, $_SESSION["user_id"]);
                                //  if the execution goes wrong, display an error instead
                                if ($updatePasswd->execute())
                                {
                                    ?>
                                    <!-- Password was successfully editted. -->
                                    <div class="alert alert-success" role="alert">
                                        Wachtwoord met success gewijzigd.
                                    </div>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <!-- Password should change, but could not be changed -->
                                    <div class="alert alert-danger" role="alert">
                                        Wachtwoord kon niet worden gewijzigd, probeer het nog een keer!
                                    </div>
                                    <?php
                                }
                                $updatePasswd->free_result();
                                $updatePasswd->close();
                            }
                            else
                            {
                                print '<!-- De wachtwoorden komen niet overeen. -->';
                                print '<div class="alert alert-danger" role="alert">' . $PwdFlaws . '</div>';
                            }
                        }
                        else
                        {
                            ?>
                            <!-- Unequal password warning -->
                            <div class="alert alert-danger" role="alert">
                                De wachtwoorden komen niet overeen.
                            </div>
                            <?php
                        }
                    }
                    ?>
                    <button type="submit" class="btn btn-light">Wijzigen</button>
                </form>

                <!-- if the account is an admin or a docent (or just simply, not a student), display this button -->
                <?php
                if (!$role->isStudent())
                {
                    ?>
                    <!-- Open account overview page button -->
                    <div class="paragraphMarginTop">
                        <a href="accountOverview.php"><button type="button" class="btn btn-info">Accounts</button></a>
                    </div>
                <?php } ?>

                <div class="paragraphMarginTop">
                    <!-- Account logout button -->
                    <a href="../logout.php"><button type="button" class="btn btn-danger">Uitloggen</button></a>
                </div>
            </div>
        </div>

        <footer class="footerBar boxShadowFooter justify-content-center">
            <nav class="nav justify-content-center">
                <a class="nav-link footerIcon"><img src="../img/person.svg"></a>
                <a class="nav-link footerIcon"><img src="../img/achievement.svg"></a>
                <a class="nav-link footerIcon"><img src="../img/cog.svg"></a>
            </nav>
        </footer>
    </div>


    <!--Required Scripts-->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
            integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
    crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
            integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
    crossorigin="anonymous"></script>
</body>
