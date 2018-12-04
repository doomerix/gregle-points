<div class="bodyWrap">
    <div class="container">
        <h1><?php echo $_GET["points_class"]; ?></h1>
        <?php 
        
        $docentnumber = $_SESSION["user_id"];
        $className = $_GET["points_class"];

        //  we will use this query to check if there are any rows returned. this should happen if the account of the user
        //  has the class assigned to them.
        $canViewClassData = $connection->prepare("SELECT docentnumber FROM docent_classes LEFT JOIN class ON (class_id = class.id) WHERE class = ? AND docentnumber = ? ;");
        $canViewClassData->bind_param("ss",$className, $docentnumber);
        $canViewClassData->execute();
        $canViewClassData->store_result();
        $canViewClassData->fetch();

        $hasClassAssigned = $canViewClassData->num_rows > 0;

        //  boolean variable, if the user is admin or has the class assigned to them it should return true
        $canViewClass = $role->isAdmin() || $hasClassAssigned;
        $canViewClassData->free_result();

        //  if the user does not actually have access to view this class.. send them back to the main page
        if (!$canViewClass) {
            header("Location: ../app/");
            exit;
        }

        //  everything is all fine and well! Code will run

        $selectIdFromClass = $connection->prepare("SELECT id, points FROM class WHERE class = ? ;");
        $selectIdFromClass->bind_param("s", $className);
        $selectIdFromClass->execute();
        $selectIdFromClass->bind_result($classId, $classPoints);
        $selectIdFromClass->fetch(); 
        $selectIdFromClass->free_result();

        $selectFromDocentClasses = $connection->prepare("SELECT point_timestamp, docent_classes.points FROM docent_classes LEFT JOIN class ON (class_id =  class.id) WHERE docentnumber = ? AND class_id = ? ;");
        $selectFromDocentClasses->bind_param("si", $docentnumber, $classId);
        $selectFromDocentClasses->execute();
        $selectFromDocentClasses->bind_result($rawTimestamp, $giveablePoints);
        $selectFromDocentClasses->fetch();
        $selectFromDocentClasses->free_result();

        //  get the timestamp
        $timestamp = strtotime($rawTimestamp);

        //  get if the user can add a point
        $canAddPoint = $giveablePoints > 0;

        //  if a point can be added and the POST has been set, run the code
        if($canAddPoint && isset($_POST["givepoint"])) {
            $studentnumber = $_POST["givepoint"];
            $updatePoints = $connection->prepare("UPDATE student SET points = points + 1 WHERE studentnumber = ?;");
            $updatePoints->bind_param("s", $studentnumber);
            $updatePoints->execute();

            $nextPointsTime = date("Y-m-d H:i:s", strtotime("next week wednesday"));
            $remainingPoints = $giveablePoints - 1;

            $updatePointTime = $connection->prepare("INSERT INTO docent_classes (docentnumber, class_id) VALUES(?, ?) ON DUPLICATE KEY UPDATE point_timestamp = ?, points = ? ;");
            $updatePointTime->bind_param("sisi", $docentnumber, $classId, $nextPointsTime, $remainingPoints);
            $updatePointTime->execute();
            header("Location: ../app/?points_class=".$className);
        }

        //  if a point is being removed and the user is an admin, run the code
        if($role->isAdmin() && isset($_POST["removepoint"])) {
            $studentnumber = $_POST["removepoint"];
            $updatePoints = $connection->prepare("UPDATE student SET points = points - 1 WHERE studentnumber = ? AND points > 0;");
            $updatePoints->bind_param("s", $studentnumber);
            $updatePoints->execute();
            $updatePoints->close();

            header("Location: ../app/?points_class=".$className);
        }

        $selectDataFromStudents = $connection->prepare("SELECT surname, firstname, surname_prefix, studentnumber, points FROM student WHERE class_id = ? ORDER BY surname ASC ;");
        $selectDataFromStudents->bind_param("i", $classId);
        $selectDataFromStudents->execute();
        $selectDataFromStudents->bind_result($surname, $firstname, $surname_prefix, $studentnumber, $points);
        ?>
        <hr>
        <?php
        while($selectDataFromStudents->fetch()) {
            ?> 
            <div class="row justify-content-center pointsDiv">
                <?php
                if($role->isAdmin()){
                    ?>
                    <button class="btn btn-outline-danger" data-toggle="modal" data-target="#<?php echo "modalremove".$studentnumber;?>">-</button>
                    <div class="modal fade" id="<?php echo "modalremove".$studentnumber;?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo "modalremove".$studentnumber;?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="<?php echo "modalremove".$studentnumber;?>">Punt verwijderen?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                    <div class="modal-body">
                                        Punt verwijderen van <?php echo $firstname." ".$surname_prefix." ".$surname?>?
                                    </div>
                                <div class="modal-footer">
                                    <form method="post">
                                        <input type=hidden name="points_class" value="<?php echo $_GET["points_class"]; ?>">
                                        <input type=hidden name="removepoint" value="<?php echo $studentnumber; ?>">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Annuleren</button>
                                        <button type="submit" class="btn btn-success">Verwijderen</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <span class="col-9"><b><?php echo $surname.", ".$firstname." ".$surname_prefix?></b><br><i><?php echo $studentnumber?></i><br><?php echo $points." ".($points == 1 ? "punt":"punten");?></span>
                <button class="btn btn-outline-success" data-toggle="modal" data-target="#<?php echo "modal".$studentnumber;?>" <?php echo ($canAddPoint ? "":"disabled");?>>+</button>
            </div>
            <hr>
            <!--Modals-->
            <div class="modal fade" id="<?php echo "modal".$studentnumber;?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo "modal".$studentnumber;?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="<?php echo "modal".$studentnumber;?>">Punt toewijzen?</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                            <div class="modal-body">
                                Punt toewijzen aan <?php echo $firstname." ".$surname_prefix." ".$surname?>?
                            </div>
                        <div class="modal-footer">
                            <form method="post">
                                <input type=hidden name="points_class" value="<?php echo $_GET["points_class"]; ?>">
                                <input type=hidden name="givepoint" value="<?php echo $studentnumber; ?>">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Annuleren</button>
                                <button type="submit" class="btn btn-success">Toewijzen</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        $selectDataFromStudents->free_result();
        ?>
    </div>
</div>