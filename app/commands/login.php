<div class="bodyWrap">
    <div class="container col-sm-7">
        <div>
            <img class="logoLogin" src="../img/mvtlogo.svg">
            <form method="post">
                <?php
                if (isset($_POST["user_id"]) && isset($_POST["password"])) {
                    $user_id = $_POST['user_id'];
                    $password = $_POST['password'];
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

                    $stmt->bind_result($user_id, $passwordhash, $role_id);
                    $stmt->fetch();

                    //when the query has succeeded and a record has been found we will compare the password with the passwordhash from the database
                    if (password_verify($password, $passwordhash))
                    {
                        ?>
                        <div class="alert alert-success" role="alert">
                            Je word doorgestuurd naar de hoofdpagina..
                        </div>
                        <?php

                        $_SESSION['user_id'] = $user_id;
                        $_SESSION['role_id'] = $role_id;

                        $_SESSION['IPaddress'] = $_SERVER['REMOTE_ADDR'];
                        $_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];

                        /* Redirect to Index page */
                        header('Location: ../app/');
                    }
                    else
                    {
                        ?>
                        <div class="alert alert-danger" role="alert">
                            De combinatie van stamnummer en wachtwoord is onjuist.
                        </div>
                        <?php
                    }
                    $stmt->close();
                    $connection->close();
                }
                ?>
                <div class="form-group">
                    <label for="loginID">Stamnummer</label>
                    <input formmethod="post" name="user_id" class="form-control" id="loginID" placeholder="Stamnummer" required>
                </div>
                <div class="form-group">
                    <label for="loginPass">Wachtwoord</label>
                    <input formmethod="post" name="password" type="password" class="form-control" id="loginPass" placeholder="Wachtwoord" required>
                </div>
                <button type="submit" class="btn btn-light">Inloggen</button>
            </form>
        </div>
    </div>
</div>