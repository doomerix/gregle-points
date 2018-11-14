<?php enforceAdminOnly($role);

$addClassState = false;
$deleteState = false;

if (isset($_POST["addClass"])) {
    $className = $_POST["addClass"];

    $insertClass = $connection->prepare("INSERT INTO class (class) VALUES (?) ;");
    $insertClass->bind_param("s", $className);
    $addClassState = $insertClass->execute();
    $insertClass->free_result();
}

if (isset($_POST["deleteClass"])) {
    $classId = $_POST["deleteClass"];
    $selectStudentsFromClass = $connection->prepare("SELECT studentnumber FROM student LEFT JOIN class ON class_id = class.id WHERE class = ? ;");
    $selectStudentsFromClass->bind_param("s", $classId);
    $selectStudentsFromClass->execute();
    $selectStudentsResult = $selectStudentsFromClass->get_result();

    while ($row = $selectStudentsResult->fetch_assoc()) {
        $deleteUser = $connection->prepare("DELETE FROM user WHERE user_id = ? ;");
        $deleteUser->bind_param("s", $row["studentnumber"]);
        $deleteUser->execute();
        $deleteUser->free_result();
    }

    $deleteFromClass = $connection->prepare("DELETE FROM class WHERE class = ? ;");
    $deleteFromClass->bind_param("s", $classId);
    $deleteState = $deleteFromClass->execute();
}

if ($deleteState) {
    ?>
    <div class="alert alert-success" role="alert">
        De klas is verwijderd.
    </div>
    <?php
}

if (isset($_POST["addClass"])) {
    if ($addClassState) {
        ?>
        <div class="alert alert-success" role="alert">
            Klas <?php echo $className ?> is aangemaakt.
        </div>
        <?php
    } else {
        ?>
        <div class="alert alert-danger" role="alert">
            De klas kon niet worden aangemaakt.
            <br>
            Bestaat de klas al?
        </div>
        <?php
    }
}

$allClasses = $connection->query("SELECT id, class FROM class ORDER BY class ASC ;");
?>
    <div class="bodyWrap">
        <div class="container">
            <div>
                <h2 class="paragraphMarginSmall">Klas toevoegen</h2>
                <form method="post">
                    <div class="form-group">
                        <input formmethod="post" name="addClass" class="form-control" id="addClass" placeholder="Klas"
                               required>
                    </div>
                    <button type="submit" class="btn btn-light">Klas toevoegen</button>
                </form>
            </div>
            <hr>
            <div>
                <?php
                while ($row = $allClasses->fetch_assoc()) {
                    $selectStudentCount = $connection->prepare("SELECT COUNT(*) FROM student WHERE class_id = ? ;");
                    $selectStudentCount->bind_param("i", $row["id"]);
                    $selectStudentCount->execute();
                    $selectStudentCount->bind_result($studentCount);
                    $selectStudentCount->fetch();
                    $selectStudentCount->free_result();
                    ?>
                    <div class="row justify-content-center pointsDiv">
                        <div class="col-5">
                            <a href="?points_class=<?php echo $row["class"]; ?>"><span><?php echo "<b>" . $row["class"] . " </br></b><i>(" . $studentCount . " studenten)"; ?></i></span></a>
                        </div>
                        <button class="btn btn-outline-danger" data-toggle="modal"
                                data-target="#<?php echo "modalclassremove" . $row["class"]; ?>">Verwijderen
                        </button>
                        <div class="modal fade" id="<?php echo "modalclassremove" . $row["class"]; ?>" tabindex="-1"
                             role="dialog" aria-labelledby="<?php echo "modalclassremove" . $row["studentnumber"]; ?>"
                             aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="<?php echo "modalclassremove" . $row["class"]; ?>">
                                            Klas verwijderen?</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Weet je zeker dat je klas <?php echo $row["class"]; ?> wilt verwijderen?
                                        <?php
                                        if ($studentCount > 0) {
                                            ?>
                                            <br>
                                            <b style="color: red">Als je een klas de verwijdert worden ook alle
                                                <br>studenten die in klas <?php echo $row["class"]; ?> zitten
                                                verwijdert!</b>

                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="modal-footer">
                                        <form method="post">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                Annuleren
                                            </button>
                                            <input type="hidden" name="deleteClass" value="<?php echo $row["class"] ?>">
                                            <button type="submit" class="btn btn-outline-danger">Verwijderen</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
<?php
$allClasses->close();