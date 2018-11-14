<?php enforceAdminOnly($role);
$createdAccount = isset($_POST["firstName"], $_POST["prefixName"], $_POST["surname"], $_POST["teacherID"], $_POST["adminCheck"]);
if ($createdAccount) {
    $isAdmin = $_POST["adminCheck"] == "true";
    $response = new Teacher($_POST["firstName"], $_POST["prefixName"], $_POST["surname"], $_POST["teacherID"], $_POST["teacherClasses"], $isAdmin);
}
?>
<div class="bodyWrap">
    <div class="container col-sm-7">
        <div>
            <h2 class="paragraphMarginSmall">Docent Toevoegen</h2>
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
                            //  this is done afterwards because we need to assign the classes to the teachers
                            foreach ($response->getClasses() as $class) {
                                $insertIntoTeacherClasses = $connection->prepare("INSERT INTO docent_classes (docentnumber, class_id) VALUES (?, (SELECT id FROM class WHERE class = ?)) ;");
                                $teacherId = $response->getTeacherId();
                                $insertIntoTeacherClasses->bind_param("ss", $teacherId, $class);
                                $insertIntoTeacherClasses->execute();
                            }
                            ?>
                            <div class="alert alert-success" role="alert">
                                Gebruiker <?php echo $response->getFirstName() . " " . $response->getPrefix() . " " . $response->getSurName() . " (" . $response->getTeacherId() . ") is aangemaakt." ?>
                                <br>
                                Het standaard wachtwoord voor dit account is <?php echo "welkom" . date("Y") ?>.
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="alert alert-danger" role="alert">
                                Er is wat fout gegaan met het aanmaken van het account.<br>
                                Is het docentnummer wel uniek?<br>
                            </div>
                            <?php
                        }
                    }
                }
                ?>
                <div class="form-group">
                    <label for="voornaam">Voornaam</label>
                    <input formmethod="post" name="firstName" class="form-control" id="firstName" placeholder="Voornaam"
                           required>
                </div>
                <div class="form-group">
                    <label for="prefix">Tussenvoegsel</label>
                    <input formmethod="post" name="prefixName" class="form-control" id="prefixName"
                           placeholder="Tussenvoegsel">
                </div>
                <div class="form-group">
                    <label for="achternaam">Achternaam</label>
                    <input formmethod="post" name="surname" class="form-control" id="surname" placeholder="Achternaam"
                           required>
                </div>
                <div class="form-group">
                    <label for="ID">Docentnummer</label>
                    <input formmethod="post" name="teacherID" class="form-control" id="teacherID"
                           placeholder="Docentnummer" required>
                </div>
                <div class="form-group">
                    <label for="Selecteer klassen">Selecteer Klassen</label>
                    <select name="teacherClasses[]" class="form-control" id="teacherClasses" multiple required>
                        <?php
                        $classes = $connection->query("SELECT class FROM class ;");
                        while ($row = $classes->fetch_assoc()) {
                            ?>
                            <option><?php echo $row["class"]; ?></option>
                            <?php
                        }
                        $classes->close();
                        ?>
                    </select>
                </div>
                <div class="form-check">
                    <label for="admin">Administrator</label>
                    <div class="form-row justify-content-center" style="margin-bottom:10px;">
                        <div class="form-check form-check-inline">
                            <input formmethod="post" name="adminCheck" class="form-check-input" type="radio"
                                   value="true" id="adminCheckX">
                            <label class="form-check-label" for="adminRadio1">
                                Ja
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input formmethod="post" name="adminCheck" class="form-check-input" type="radio"
                                   value="false" id="adminCheckY">
                            <label class="form-check-label" for="adminRadio2">
                                Nee
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-light">Docent Toevoegen</button>
            </form>
        </div>
    </div>
</div>
