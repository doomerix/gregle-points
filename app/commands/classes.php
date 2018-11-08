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
                De klas is verwijderd.
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
            <div class="col-5">
                <form method="post" style="margin-bottom:0;">
                    <a href="?points_class=<?php echo $row["class"];?>"><span><b><?php echo $row["class"]." </br></b><i>(".$studentCount." studenten)"; ?></i></span></a>
                    <button class="btn btn-info col-5">KLAS</button>
                    </div>
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