<?php enforceAdminOnly($role);

$addClassState = false;
$deleteState = false;

if (isset($_POST["addClass"])) {
    $className = $_POST["addClass"];

    $insertClass = $connection->prepare("INSERT INTO class (class) VALUES (?) ;");
    $insertClass->bind_param("s", $className);
    $addClassState = $insertClass->execute();
}

if (isset($_POST["deleteClass"])) {
    $classId = $_POST["deleteClass"];
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
                            <form method="post" style="margin-bottom:0;">
                                <a href="?points_class=<?php echo $row["class"]; ?>"><span><?php echo "<b>" . $row["class"] . " </br></b><i>(" . $studentCount . " studenten)"; ?></i></span></a>
                        </div>
                        <input type="hidden" name="deleteClass" value="<?php echo $row["class"] ?>">
                        <button type="submit" class="btn btn-outline-danger">Verwijderen</button>
                        </form>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
<?php
$allClasses->close();