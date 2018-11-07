<?php enforceAdminOnly($role);
if (isset($_POST["deleteClass"])) {
    $classId = $_POST["deleteClass"];
    $deleteFromClass = $connection->prepare("DELETE FROM class WHERE id = ? ;");
    $deleteFromClass->bind_param("i", $classId);
    $deleteFromClass->execute();
}

$allClasses = $connection->query("SELECT id, class FROM class ORDER BY class ASC ;");
?>
<div class="bodyWrap">
    <div class="container"><?php
        if (isset($_POST["deleteClass"])) {
            ?>
            <div class="alert alert-success" role="alert">
                De klas is verwijdert.
            </div>
            <?php
        }
        while ($row = $allClasses->fetch_assoc()) {
            $selectStudentCount = $connection->prepare("SELECT COUNT(*) FROM student WHERE class_id = ? ;");
            $selectStudentCount->bind_param("i", $row["id"]);
            $selectStudentCount->execute();
            $selectStudentCount->bind_result($studentCount);
            $selectStudentCount->fetch();
            $selectStudentCount->free_result();
            ?>
            <div class="row justify-content-center pointsDiv">
                <form method="post">
                    <a href="?points_class=<?php echo $row["class"];?>"><span class="col-9"><b><?php echo $row["class"]." (".$studentCount." studenten)"; ?></b></i></span></a>
                    <input type="hidden" name="deleteClass" value="<?php echo $row["id"] ?>">
                    <button type="submit" class="btn btn-outline-danger">Verwijderen</button>
                </form>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<?php
$allClasses->close();