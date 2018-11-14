<?php enforceAdminOnly($role);
$createdAccount = isset($_POST["firstName"], $_POST["prefixName"], $_POST["surname"], $_POST["studentID"], $_POST["studentClass"]);
if ($createdAccount) {
$response = new Student($_POST["firstName"], $_POST["prefixName"], $_POST["surname"], $_POST["studentID"], $_POST["studentClass"]);
}
?>
<div class="bodyWrap">
    <div class="container">
        <div>
            <h2 class="paragraphMarginSmall">Student Toevoegen</h2>
            <form method="post">
                <?php
                if ($createdAccount) {
                    if (!$response->isValid()) {
                        ?>
                        <!-- Missing forms warning -->
                        <div class="alert alert-danger" role="alert">
                            Niet alle velden waren ingevuld!
                        </div>
                        <?php
                    } else {
                        if ($response->create($connection)) {
                            ?>
                            <div class="alert alert-success" role="alert">
                                Gebruiker <?php echo $response->getFirstName() . " " . $response->getPrefix() . " " . $response->getSurName() . " (" . $response->getStudentId() . ") is aangemaakt." ?>
                                <br>
                                Het standaard wachtwoord voor dit account is <?php echo "welkom" . date("Y") ?>.
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="alert alert-danger" role="alert">
                                Er is wat fout gegaan met het aanmaken van het account.<br>
                                Is het studentnummer wel uniek?<br>
                            </div>
                            <?php
                        }
                    }
                }
                ?>
                <div class="form-group">
                <label for="voornaam">Voornaam</label>
                    <input formmethod="post" class="form-control" name="firstName" placeholder="Voornaam" required>
                </div>
                <div class="form-group">
                <label for="prefix">Tussenvoegsel</label>
                    <input formmethod="post" class="form-control" name="prefixName" placeholder="Tussenvoegsel">
                </div>
                <div class="form-group">
                <label for="achternaam">Achternaam</label>
                    <input formmethod="post" class="form-control" name="surname" placeholder="Achternaam" required>
                </div>
                <div class="form-group">
                <label for="stamnummer">Stamnummer</label>
                    <input formmethod="post" class="form-control" name="studentID" placeholder="Stamnummer" required>
                </div>
                <div class="form-group">
                    <label for="Selecteer klassen">Selecteer Klas</label>
                    <select class="form-control" name="studentClass" required>
                        <?php
                        $classes = $connection->query("SELECT class FROM class ;");
                        while($row = $classes->fetch_assoc()) {
                            ?>
                            <option><?php echo $row["class"];?></option>
                            <?php
                        }
                        $classes->close();
                        ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-light">Student Toevoegen</button>
            </form>
        </div>
    </div>
</div>
