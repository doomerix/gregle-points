<div class="bodyWrap">
    <div class="container">
        <div>
            <p class="paragraphMarginSmall"><b>Voor de accountveiligheid is het nodig om uw wachtwoord te veranderen bij de eerste login.</b></p>
            <form method="post">
                <div class="form-group">
                    <input formmethod="post" name="passwd1" type="password" class="form-control" id="changePassPrim" placeholder="Nieuw wachtwoord">
                </div>
                <div class="form-group">
                    <input formmethod="post" name="passwd2" type="password" class="form-control" id="changePassSecond" placeholder="Herhaal wachtwoord">
                </div>

                <?php
                //  if passwd1 and passwd2 are set, the password of the user has been changed.
                if (isset($_POST["passwd1"], $_POST["passwd2"])) {
                    //  failsafe check (and in case something goes wrong, display message)
                    if ($_POST["passwd1"] == $_POST["passwd2"]) {
                        //  hash the password
                        $passwd = password_hash($_POST["passwd1"], PASSWORD_BCRYPT);
                        //  get user id
                        $userId = $_SESSION["user_id"];

                        //  update password query
                        $updatePasswd = $connection->prepare("UPDATE user SET passwordhash = ? WHERE user_id = ? ;");
                        $updatePasswd->bind_param("ss", $passwd, $userId);
                        //  if the execution goes wrong, display an error instead
                        if ($updatePasswd->execute()) {
                            $updateFirstLogin = $connection->prepare("UPDATE user SET firstlogin = 0 WHERE user_id = ? ;");
                            $updateFirstLogin->bind_param("s", $userId);
                            $updateFirstLogin->execute();
                            header("Location: ../app/?fls=true");
                            exit;
                        } else {
                            ?>
                            <!-- Password should change, but could not be changed -->
                            <div class="alert alert-danger" role="alert">
                                Wachtwoord kon niet worden gewijzigd, probeer het nog een keer!
                            </div>
                            <?php
                        }
                        $updatePasswd->free_result();
                        $updatePasswd->close();
                    } else {
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
        </div>
    </div>
</div>