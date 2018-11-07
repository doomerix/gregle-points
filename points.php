<?php 
if (!isset($_POST["class"])) {
    header("Location: index.php");
    exit;
}
?>    
<div class="bodyWrap">
    <div class="container">
        <h1><?php echo $_POST["class"]; ?></h1>
        <?php 
        $className = $_POST["class"];
        $selectIdFromClass = $connection->prepare("SELECT id FROM class WHERE class = ? ;");
        $selectIdFromClass->bind_param("s", $className);
        $selectIdFromClass->execute();
        $selectIdFromClass->bind_result($classId);
        $selectIdFromClass->fetch(); 
        $selectIdFromClass->free_result();

        $selectDataFromStudents = $connection->prepare("SELECT surname, firstname, surname_prefix FROM student WHERE class_id = ? ORDER BY surname ASC ;");
        $selectDataFromStudents->bind_param("i", $classId);
        $selectDataFromStudents->execute();
        $dataStudentResult = $selectDataFromStudents->get_result();
        while($row = $dataStudentResult->fetch_assoc()) {
            ?> 
            <div class="row justify-content-center pointsDiv">
                <button class="btn btn-outline-danger">-</button>
                <span class="col-9"><b><?php echo $row["surname"]." ".$row["firstname"]." ".$row["surname_prefix"]?></b><br><i>{{id}}</i><br>{{p}} punten</span>
                <button class="btn btn-outline-success" data-toggle="modal" data-target="#pointWarning">+</button>
            </div>    
            <?php
        }
        ?>
    </div>
</div>

<!--Modals-->
<div class="modal fade" id="pointWarning" tabindex="-1" role="dialog" aria-labelledby="pointWarning" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pointWarning">Punt toewijzen?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <div class="modal-body">
                    Punt toewijzen aan {{full name}}?
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Annuleren</button>
                <button type="button" class="btn btn-success">Toewijzen</button>
            </div>
        </div>
    </div>
</div>