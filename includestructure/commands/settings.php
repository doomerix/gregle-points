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
                if (isset($_POST["passwd1"], $_POST["passwd2"])) {
                    //  failsafe check (and in case something goes wrong, display message)
                    if ($_POST["passwd1"] == $_POST["passwd2"]) {
                        //  hash the password
                        $passwd = password_hash($_POST["passwd1"], PASSWORD_BCRYPT);

                        //  update password query
                        $updatePasswd = $connection->prepare("UPDATE user SET passwordhash = ? WHERE user_id = ?;");
                        $updatePasswd->bind_param("ss", $passwd, $_SESSION["user_id"]);
                        //  if the execution goes wrong, display an error instead
                        if ($updatePasswd->execute()) {
                            ?>
                            <!-- Password was successfully editted. -->
                            <div class="alert alert-success" role="alert">
                                Wachtwoord met success gewijzigd.
                            </div>
                            <?php
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

            <!-- if the account is an admin or a docent (or just simply, not a student), display this button -->
            <?php if (!$role->isStudent()) { ?>
                <!-- Open account overview page button -->
                <div class="paragraphMarginTop">
                    <a href="?command=accountOptions"><button type="button" class="btn btn-info">Accounts</button></a>
                </div>
            <?php } ?>

            <div class="paragraphMarginTop">
                <!-- Account logout button -->
                <a href="?command=logout"><button type="button" class="btn btn-danger">Uitloggen</button></a>
            </div>
        </div>
    </div>
</div>