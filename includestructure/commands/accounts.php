<?php
//  the user is a student, handle changes
if (isset($_POST["studentClass"])) {
    //  handle student update
    $response = new Student($_POST["firstName"], $_POST["prefixName"], $_POST["surname"], $_POST["studentID"], $_POST["studentClass"]);
    if ($response->update($connection)) {
        //  everything went well, yaaay!
        ?>
        <div class="alert alert-success" role="alert">
            Gebruiker <?php echo $response->getFirstName() . " " . $response->getPrefix() . " " . $response->getSurName() . " (" . $response->getStudentId() . ") is geüpdatet." ?>
        </div>
        <?php
    } else {
        //  something went wrong......
        ?>
        <div class="alert alert-danger" role="alert">
            Gebruiker <?php echo $response->getFirstName() . " " . $response->getPrefix() . " " . $response->getSurName() . " (" . $response->getStudentId() . ") kon niet worden geüpdatet." ?>
        </div>
        <?php
    }
}

//  a student will be deleted
if (isset($_POST["deleteStudent"])) {
    //  handle student delete, we only need to give the ID
    $response = new Student("", "", "", $_POST["deleteStudent"], "");
    if ($response->delete($connection)) {
        //  everything went well, yaaay!
        ?>
        <div class="alert alert-success" role="alert">
            Gebruiker <?php echo $response->getStudentId() . " is verwijdert." ?>
        </div>
        <?php
    } else {
        //  something went wrong......
        ?>
        <div class="alert alert-danger" role="alert">
            Gebruiker <?php echo $response->getStudentId() . " kon niet worden verwijdert." ?>
        </div>
        <?php
    }
}

//  the user is a teacher, handle changes
if (isset($_POST["teacherClasses"])) {
    $response = new Teacher($_POST["firstName"], $_POST["prefixName"], $_POST["surname"], $_POST["teacherID"], $_POST["teacherClasses"], isset($_POST["adminCheck"]));
    if ($response->update($connection)) {
        //  everything went well, yaaay!
        ?>
        <div class="alert alert-success" role="alert">
            Gebruiker <?php echo $response->getFirstName() . " " . $response->getPrefix() . " " . $response->getSurName() . " (" . $response->getTeacherId() . ") is geüpdatet." ?>
        </div>
        <?php
    } else {
        //  something went wrong......
        ?>
        <div class="alert alert-danger" role="alert">
            Gebruiker <?php echo $response->getFirstName() . " " . $response->getPrefix() . " " . $response->getSurName() . " (" . $response->getTeacherId() . ") kon niet worden geüpdatet." ?>
        </div>
        <?php
    }
}

//  a docent will be deleted
if (isset($_POST["deleteTeacher"])) {
    //  handle teacher delete, we only need to give the ID
    $response = new Teacher("", "", "", $_POST["deleteTeacher"], "", "");
    if ($response->delete($connection)) {
        //  everything went well, yaaay!
        ?>
        <div class="alert alert-success" role="alert">
            Gebruiker <?php echo $response->getTeacherId() . " is verwijdert." ?>
        </div>
        <?php
    } else {
        //  something went wrong......
        ?>
        <div class="alert alert-danger" role="alert">
            Gebruiker <?php echo $response->getTeacherId() . " kon niet worden verwijdert." ?>
        </div>
        <?php
    }
}

//  display edit student form
if (isset($_POST["editStudent"])) {
    $studentId = $_POST["editStudent"];
    $statement = $connection->prepare("SELECT firstname, surname_prefix, surname FROM student WHERE studentnumber = ? ;");
    $statement->bind_param("s", $studentId);
    $statement->execute();
    $result = $statement->get_result();
    $student = $result->fetch_assoc();
    ?>
    <div class="bodyWrap">
        <div class="container">
            <div>
                <h2 class="paragraphMarginSmall"><?php echo $student["firstname"] . " " . $student["surname_prefix"] . " " . $student["surname"]; ?>
                    wijzigen</h2>
                <form method="post">
                    <div class="form-group">
                        <input formmethod="post" class="form-control" name="firstName" placeholder="Voornaam"
                               value="<?php echo $student["firstname"]; ?>" required>
                    </div>
                    <div class="form-group">
                        <input formmethod="post" class="form-control" name="prefixName" placeholder="Tussenvoegsel"
                               value="<?php echo $student["surname_prefix"]; ?>">
                    </div>
                    <div class="form-group">
                        <input formmethod="post" class="form-control" name="surname" placeholder="Achternaam"
                               value="<?php echo $student["surname"]; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="Selecteer klassen">Selecteer Klas</label>
                        <select class="form-control" name="studentClass" required>
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
                    <input hidden name="studentID" value="<?php echo $studentId; ?>">
                    <button type="submit" class="btn btn-light">Wijziging voltooien</button>
                </form>
                <form method="post">
                    <input type="hidden" name="deleteStudent" value="<?php echo $studentId; ?>">
                    <button type="submit" class="btn btn-danger">Verwijder student</button>
                </form>
            </div>
        </div>
    </div>
    <?php
} else if (isset($_POST["editTeacher"])) {
    $teacherId = $_POST["editTeacher"];
    $statement = $connection->prepare("SELECT firstname, surname_prefix, surname FROM docent WHERE docentnumber = ? ;");
    $statement->bind_param("s", $teacherId);
    $statement->execute();
    $result = $statement->get_result();
    $teacher = $result->fetch_assoc();
    ?>
    <div class="bodyWrap">
        <div class="container">
            <h2 class="paragraphMarginSmall"><?php echo $teacher["firstname"] . " " . $teacher["surname_prefix"] . " " . $teacher["surname"]; ?>
                wijzigen</h2>
            <form method="post">
                <div class="form-group">
                    <input formmethod="post" class="form-control" name="firstName" placeholder="Voornaam"
                           value="<?php echo $teacher["firstname"]; ?>" required>
                </div>
                <div class="form-group">
                    <input formmethod="post" class="form-control" name="prefixName" placeholder="Tussenvoegsel"
                           value="<?php echo $teacher["surname_prefix"]; ?>">
                </div>
                <div class="form-group">
                    <input formmethod="post" class="form-control" name="surname" placeholder="Achternaam"
                           value="<?php echo $teacher["surname"]; ?>" required>
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
                    <input formmethod="post" name="adminCheck" class="form-check-input" type="checkbox" value="admin"
                           id="adminCheck">
                    <label class="form-check-label" for="adminCheck">Administrator</label>
                </div>
                <input hidden name="teacherID" value="<?php echo $teacherId; ?>">
                <button type="submit" class="btn btn-light">Wijziging voltooien</button>
            </form>
            <form method="post">
                <input type="hidden" name="deleteTeacher" value="<?php echo $teacherId; ?>">
                <button type="submit" class="btn btn-danger">Verwijder docent</button>
            </form>
        </div>
    </div>
    <?php
} else {
    $allUsers = $connection->query("SELECT studentnumber AS userid, firstname, surname_prefix, surname, '1' AS is_student FROM student UNION SELECT docentnumber AS userid, firstname, surname_prefix, surname, '0' AS is_student FROM docent ORDER BY surname ASC ;");
    ?>
    <div class="bodyWrap">
        <div class="container"><?php
            while ($row = $allUsers->fetch_assoc()) {
                $isStudent = $row["is_student"] == "1";
                ?>
                <div class="row justify-content-center pointsDiv">
                    <span class="col-9"><b><?php echo $row["surname"] . ", " . $row["firstname"] . " " . $row["surname_prefix"]; ?></b><br><i><?php echo $row["userid"]; ?></i></span>
                    <form method="post">
                        <input type="hidden" name="<?php echo $isStudent ? "editStudent" : "editTeacher" ?>"
                               value="<?php echo $row["userid"] ?>">
                        <button type="submit" class="btn btn-outline-success" <?php echo (!$isStudent && !$role->isAdmin() ? "disabled" : ""); ?>>Wijzigen</button>
                    </form>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
    $allUsers->close();
}
?>