<?php enforceAdminOnly($role);
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
            Gebruiker <?php echo $response->getStudentId() . " is verwijderd." ?>
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
            Gebruiker <?php echo $response->getTeacherId() . " is verwijderd." ?>
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

//  display form
if (isset($_POST["editStudent"]) || isset($_POST["editTeacher"])) {
    $isStudent = isset($_POST["editStudent"]) ? true : false;
    $id = $isStudent ? $_POST["editStudent"] : $_POST["editTeacher"];

    $statement = $connection->prepare("SELECT firstname, surname_prefix, surname FROM " . ($isStudent ? "student" : "docent") . " WHERE " . ($isStudent ? "student" : "docent") . "number = ? ;");
    $statement->bind_param("s", $id);
    $statement->execute();
    $result = $statement->get_result();
    $user = $result->fetch_assoc();

    ?>
    <div class="bodyWrap">
    <div class="container">
    <div>
    <h2 class="paragraphMarginSmall"><?php echo $user["firstname"] . " " . $user["surname_prefix"] . " " . $user["surname"]; ?>
        wijzigen</h2>
    <form method="post">
        <div class="form-group">
            <label for="voornaam">Voornaam</label>
            <input formmethod="post" class="form-control" name="firstName" placeholder="Voornaam"
                   value="<?php echo $user["firstname"]; ?>" required>
        </div>
        <div class="form-group">
            <label for="tussenvoegsel">Tussenvoegsel</label>
            <input formmethod="post" class="form-control" name="prefixName" placeholder="Tussenvoegsel"
                   value="<?php echo $user["surname_prefix"]; ?>">
        </div>
        <div class="form-group">
            <label for="achternaam">Achternaam</label>
            <input formmethod="post" class="form-control" name="surname" placeholder="Achternaam"
                   value="<?php echo $user["surname"]; ?>" required>
        </div>
        <?php
        if ($isStudent) {
            ?>
            <div class="form-group">
                <label for="Selecteer klassen">Selecteer Klas</label>
                <select class="form-control" name="studentClass" required>
                    <?php
                    $selectClassFromStudent = $connection->prepare("SELECT class FROM student LEFT JOIN class ON class_id = class.id WHERE studentnumber = ? ;");
                    $selectClassFromStudent->bind_param("s", $id);
                    $selectClassFromStudent->execute();
                    $selectClassFromStudent->bind_result($className);
                    $selectClassFromStudent->fetch();
                    $selectClassFromStudent->free_result();

                    $classes = $connection->query("SELECT class FROM class ;");
                    while ($row = $classes->fetch_assoc()) {
                        ?>
                        <option <?php echo($row["class"] == $className ? "selected" : ""); ?>><?php echo $row["class"]; ?></option>
                        <?php
                    }
                    $classes->close();
                    ?>
                </select>
            </div>
            <?php
        } else {
            //  handle docent/admin options
            ?>
            <div class="form-group">
                <label for="Selecteer klassen">Selecteer Klassen</label>
                <select name="teacherClasses[]" class="form-control" id="teacherClasses" multiple required>
                    <?php
                    //  display classes (and already selected classes)
                    $docentClasses = array();
                    $selectClassesFromDocent = $connection->prepare("SELECT class FROM docent_classes LEFT JOIN class ON class_id = class.id WHERE docentnumber = ? ;");
                    $selectClassesFromDocent->bind_param("s", $id);
                    $selectClassesFromDocent->execute();
                    $classesResult = $selectClassesFromDocent->get_result();

                    //  push docent their classes
                    while ($row = $classesResult->fetch_assoc()) {
                        array_push($docentClasses, $row["class"]);
                    }
                    $classesResult->free_result();

                    //  query all the classes that can be chosen, mark the ones already assigned to the docent as "selected"
                    $classes = $connection->query("SELECT class FROM class ;");
                    while ($row = $classes->fetch_assoc()) {
                        ?>
                        <option <?php echo in_array($row["class"], $docentClasses) ? "selected" : ""; ?>><?php echo $row["class"]; ?></option>
                        <?php
                    }
                    $classes->close();
                    ?>
                </select>
            </div>
            <?php
            $docentRole = Role::fromUserId($connection, $id);
            ?>
            <label for="admin">Administrator</label>
            <div class="form-row justify-content-center" style="margin-bottom:10px;">
                <div class="form-check form-check-inline">
                    <input formmethod="post" name="adminCheck" class="form-check-input" type="radio" value="admin"
                           id="adminCheckX" <?php echo($docentRole->isAdmin() ? "checked" : ""); ?>>
                    <label class="form-check-label" for="adminRadio1">
                        Ja
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <input formmethod="post" name="adminCheck" class="form-check-input" type="radio" value="admin"
                           id="adminCheckY" <?php echo(!$docentRole->isAdmin() ? "checked" : ""); ?>>
                    <label class="form-check-label" for="adminRadio2">
                        Nee
                    </label>
                </div>
            </div>
            <?php
        }
        //  save changes accordingly
        ?>
        <input hidden name="<?php echo $isStudent ? "student" : "teacher"; ?>ID" value="<?php echo $id; ?>">
        <button type="submit" class="btn btn-light">Wijziging voltooien</button>
    </form>
    <?php
    //  if the id of the user is the same as yours, don't display the delete button.
    if ($id != $_SESSION["user_id"]) {
        ?>
        <form method="post">
            <input type="hidden" name="delete<?php echo $isStudent ? "Student" : "Teacher"; ?>"
                   value="<?php echo $id; ?>">
            <button type="submit" class="btn btn-danger">
                Verwijder <?php echo $isStudent ? "student" : "docent"; ?></button>
        </form>
        <?php
    }
} else {
    $allUsers = $connection->query("SELECT studentnumber AS userid, firstname, surname_prefix, surname, '1' AS is_student FROM student UNION SELECT docentnumber AS userid, firstname, surname_prefix, surname, '0' AS is_student FROM docent ORDER BY surname ASC ;");
    ?>
    <div class="bodyWrap">
        <div class="container">
            <hr>
            <?php
            while ($row = $allUsers->fetch_assoc()) {
                    $id = $row["userid"];

                //  get user their role (and name)
                $userRole = Role::fromUserId($connection, $id);
                $isStudent = $row["is_student"] == "1";
                ?>
                <div class="row justify-content-center">
                    <span class="col-9"><b><?php echo $row["surname"] . ", " . $row["firstname"] . " " . $row["surname_prefix"]; ?></b><br><i><?php echo ($id . " " . "(" . $userRole->getName() . ")"); ?></i></span>
                    <form method="post">
                        <input type="hidden" name="<?php echo $isStudent ? "editStudent" : "editTeacher" ?>"
                               value="<?php echo $id;?>">
                        <button type="submit"
                                class="btn btn-outline-success" <?php echo(!$isStudent && !$role->isAdmin() ? "disabled" : ""); ?>>
                            Wijzigen
                        </button>
                    </form>
                </div>
                <hr>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
    $allUsers->close();
}
?>