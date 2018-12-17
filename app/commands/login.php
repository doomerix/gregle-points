<div class="bodyWrap">
    <div class="container col-sm-7">
        <div>
            <img class="logoLogin" src="../img/mvtlogo.svg">
            <form method="post">
                <?php
                function Login($user_id, $password)
                {
                    $sql = "SELECT user_id, passwordhash, role_id FROM user WHERE user_id = ?;";

                    $stmt = $GLOBALS['connection']->prepare($sql);
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
                        setcookie("user_id", $user_id, time() + 60 * 60 * 24 * 7);
                        setcookie("password", openssl_encrypt($password, "AES-256-ECB", '7Cs7+KgS2uETaVJF#K*@z766e-a^XSBS'), time() + 60 * 60 * 24 * 7); //encript password and put THAT in the cookie - also, 32-encription generated online
                        ?>
                        <div class="alert alert-success" role="alert">
                            Je wordt doorgestuurd naar de hoofdpagina..
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
                            De combinatie van loginnummer en wachtwoord is onjuist.
                        </div>
                        <?php
                    }
                    $stmt->close();
                    $GLOBALS['connection']->close();
                }

                if (isset($_POST["user_id"]) && isset($_POST["password"]))
                {
                    Login($_POST['user_id'], $_POST['password']);
                }
                else if (isset($_COOKIE["user_id"]) && isset($_COOKIE["password"]))
                {
                    Login($_COOKIE["user_id"], openssl_decrypt($_COOKIE["password"], "AES-256-ECB", '7Cs7+KgS2uETaVJF#K*@z766e-a^XSBS')); //decript password
                }
                ?>
                <div class="form-group">
                    <label for="loginID">Loginnummer</label>
                    <input formmethod="post" name="user_id" class="form-control" id="loginID" placeholder="Loginnummer" required>
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