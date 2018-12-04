<?php enforceAdminOnly($role);

$addClassState = false;
$deleteState = false;

if (isset($_POST["addClass"]) && isset($_POST["addClassPoints"])) {
    $className = $_POST["addClass"];
    $classPoints = $_POST["addClassPoints"];
    if ($classPoints < 1) $classPoints = 1;

    $insertClass = $connection->prepare("INSERT INTO class (class, points) VALUES (?, ?) ;");
    $insertClass->bind_param("si", $className, $classPoints);
    $addClassState = $insertClass->execute();
    $insertClass->free_result();
}

if (isset($_POST["editClassId"]) && isset($_POST["editClassPoints"])) {
    $operation = $_POST["editClassPoints"];
    $classId = $_POST["editClassId"];
    $updateClassPoints = null;

    if ($operation == "+") {
        $updateClassPoints = $connection->prepare("UPDATE class SET points = points+1 WHERE id = ? ;");
    } else if ($operation == "-") {
        $updateClassPoints = $connection->prepare("UPDATE class SET points = points-1 WHERE id = ? AND points <> 1 ;");
    }

    if (!is_null($updateClassPoints)) {
        $updateClassPoints->bind_param("s", $classId);
        $updateClassPoints->execute();
        $updateClassPoints->free_result();
    }
}

if (isset($_POST["deleteClass"])) {
    $classId = $_POST["deleteClass"];
    $selectStudentsFromClass = $connection->prepare("SELECT studentnumber FROM student LEFT JOIN class ON class_id = class.id WHERE class = ? ;");
    $selectStudentsFromClass->bind_param("s", $classId);
    $selectStudentsFromClass->execute();
    $selectStudentsFromClass->bind_result($studentnumber);
    $selectStudentsFromClass->free_result();

    while ($selectStudentsFromClass->fetch()) {
        $deleteUser = $connection->prepare("DELETE FROM user WHERE user_id = ? ;");
        $deleteUser->bind_param("s", $studentnumber);
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

if (isset($_POST["addClass"]) && isset($_POST["addClassPoints"])) {
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

$allClasses = $connection->query("SELECT id, class, points FROM class ORDER BY class ASC ;");
?>
    <div class="bodyWrap">
        <div class="container">
            <div>
                <h2 class="paragraphMarginSmall">Klas toevoegen</h2>
                <form method="post">
                    <div class="form-group">
                        <div class="form-row form-group">
                            <div class="col-9 form-group">
                                <input formmethod="post" name="addClass" class="form-control" id="addClass" placeholder="Klas" required>
                            </div>
                            <div class="col-3 form-group">
                                <input formmethod="post" name="addClassPoints" class="form-control" id="addClass" placeholder="Punten" required>
                            <div>
                        </div>             
                    </div>
                    <div class="col-12 text-center">
                    <button type="submit" class="btn btn-light">Klas toevoegen</button>
                    </div>
                </form>
            </div>
            <span style="padding:0";>Om het mogelijk te maken om meer of minder punten per week te geven aan een klas kun je op [+] of [-] klikken.</span>
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
                        <div class="col-12 col-sm-7 justify-content-center">
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <b><?php echo $row["class"];?></b>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <i><?php echo $studentCount;?> studenten</i>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-2 justify-content-center">
                                    <form method="post">
                                        <input type="hidden" name="editClassPoints" value="-">
                                        <input type="hidden" name="editClassId" value="<?php echo $row["id"];?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">-</button>
                                    </form>
                                </div>
                                <div class="col-8 justify-content-center">
                                    <i><?php echo $row["points"];?> punten</i>
                                </div>
                                <div class="col-2 justify-content-center">
                                    <form method="post">
                                        <input type="hidden" name="editClassPoints" value="+">
                                        <input type="hidden" name="editClassId" value="<?php echo $row["id"];?>">
                                        <button type="submit" class="btn btn-outline-success btn-sm">+</button>
                                    </form>
                                </div>  
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <button class="btn btn-danger" data-toggle="modal" data-target="#<?php echo "modalclassremove" . $row["class"]; ?>">Klas verwijderen</button>
                        </div>
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
                                            <button type="button" class="btn btn-success" data-dismiss="modal">
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