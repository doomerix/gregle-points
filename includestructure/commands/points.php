<?php 
//if (!isset($_POST["class"])) {
//    header("Location: index.php");
//    exit;
//}
?>    
<div class="bodyWrap">
    <div class="container">
        <h1><?php echo $_GET["points_class"]; ?></h1>
        <?php 
        
        $docentnumber = $_SESSION["user_id"];
        $className = $_GET["points_class"];
        
        $selectIdFromClass = $connection->prepare("SELECT id FROM class WHERE class = ? ;");
        $selectIdFromClass->bind_param("s", $className);
        $selectIdFromClass->execute();
        $selectIdFromClass->bind_result($classId);
        $selectIdFromClass->fetch(); 
        $selectIdFromClass->free_result();
        
        $selectDataFromStudents = $connection->prepare("SELECT surname, firstname, surname_prefix, studentnumber, points FROM student WHERE class_id = ? ORDER BY surname ASC ;");
        $selectDataFromStudents->bind_param("i", $classId);
        $selectDataFromStudents->execute();
        $dataStudentResult = $selectDataFromStudents->get_result();
        
        $selectFromDocentClasses = $connection->prepare("SELECT point_timestamp FROM docent_classes LEFT JOIN class ON (class_id =  class.id) WHERE docentnumber = ? AND class_id = ? ;");
        $selectFromDocentClasses->bind_param("si", $docentnumber, $classId);
        $selectFromDocentClasses->execute();
        $selectFromDocentClasses->bind_result($rawTimestamp);
        $selectFromDocentClasses->fetch();
        $selectFromDocentClasses->free_result();
        $timestamp = strtotime($rawTimestamp);
        $canAddPoint = is_null($timestamp) || $timestamp < strtotime("-1 week");
       
        if($canAddPoint && isset($_POST["givepoint"])) {
            $studentnumber = $_POST["givepoint"];
            $updatePoints = $connection->prepare("UPDATE student SET points = points + 1 WHERE studentnumber = ?;");
            $updatePoints->bind_param("s", $studentnumber);
            $updatePoints->execute();

            $updatePointTime = $connection->prepare("INSERT INTO docent_classes (docentnumber, class_id) VALUES(?, ?) ON DUPLICATE KEY UPDATE point_timestamp = NOW();");
            $updatePointTime->bind_param("si", $docentnumber, $classId);
            $updatePointTime->execute();
            header("Location: index.php?points_class=".$className);
        }
        
        while($row = $dataStudentResult->fetch_assoc()) {
            ?> 
            <div class="row justify-content-center pointsDiv">
                <?php
                if($role->isAdmin()){
                    ?><button class="btn btn-outline-danger">-</button><?php
                }
                ?>
                    <span class="col-9"><b><?php echo $row["surname"]." ".$row["firstname"]." ".$row["surname_prefix"]?></b><br><i><?php echo $row["studentnumber"]?></i><br><?php echo $row["points"]." ".($row["points"] == 1 ? "punt":"punten");?></span>
                    <button class="btn btn-outline-success" data-toggle="modal" data-target="#<?php echo "modal".$row["studentnumber"];?>" <?php echo ($canAddPoint ? "":"disabled");?>>+</button>
            </div>
            <!--Modals-->
            <div class="modal fade" id="<?php echo "modal".$row["studentnumber"];?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo "modal".$row["studentnumber"];?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="<?php echo "modal".$row["studentnumber"];?>">Punt toewijzen?</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                            <div class="modal-body">
                                Punt toewijzen aan <?php echo $row["surname"]." ".$row["firstname"]." ".$row["surname_prefix"]?>?
                            </div>
                        <div class="modal-footer">
                            <form method="post">
                                <input type=hidden name="points_class" value="<?php echo $_GET["points_class"]; ?>">
                                <input type=hidden name="givepoint" value="<?php echo $row["studentnumber"]; ?>">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Annuleren</button>
                                <button type="submit" class="btn btn-success">Toewijzen</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>