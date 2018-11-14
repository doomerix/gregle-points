<?php if ($role->isStudent()) { ?>
    <?php
    //  load data for students
    $statement = $connection->prepare("SELECT firstname, surname_prefix, surname, points, class FROM student LEFT JOIN class ON (class_id = class.id) WHERE studentnumber = ? ;");
    $statement->bind_param("s", $user_id);
    $statement->execute();
    $result = $statement->get_result();
    $student = $result->fetch_assoc();
    ?>
    <header class="headerMargin">
        <h2 class="paragraphMarginSmall"><?php echo $student["firstname"] . " " . $student["surname_prefix"] . " " . $student["surname"]; ?></h2>
        <p class="paragraphMarginSmall"><?php echo $user_id; ?></p>
        <p class="paragraphMarginSmall"><?php echo $student["class"]; ?></p>
    </header>

    <div class="divPointbar">
        <p class="pointAmount paragraphMarginSmall"><?php echo $student["points"]; ?></p>
        <p class="paragraphMarginSmall pointPunten">punten</p>
    </div>
<?php } ?>
<?php if ($role->isTeacher() || $role->isAdmin()) { ?>
    <?php
    //  load data for teachers/admins
    $statement = $connection->prepare("SELECT firstname, surname_prefix, surname FROM docent WHERE docentnumber = ? ;");
    $statement->bind_param("s", $user_id);
    $statement->execute();
    $result = $statement->get_result();
    $docent = $result->fetch_assoc();

    if (!isset($_GET["points_class"])) {
        ?>
        <header class="headerMargin">
            <h2 class="paragraphMarginSmall"><?php echo $docent["firstname"] . " " . $docent["surname_prefix"] . " " . $docent["surname"]; ?></h2>
            <p class="paragraphMarginSmall"><?php echo $user_id; ?></p>
        </header>

        <div class="container pointsDivMain col-sm-7">
        <?php
        $selectFromDocentClasses = $connection->prepare("SELECT class, point_timestamp FROM docent_classes LEFT JOIN class ON (class_id =  class.id) WHERE docentnumber = ? ;");
        $selectFromDocentClasses->bind_param("s", $user_id);
        $selectFromDocentClasses->execute();
        $docentResult = $selectFromDocentClasses->get_result();
        if ($docentResult->num_rows > 0) {
            ?>
            <h5>Klassen</h5>
            <?php
        }
        while ($row = $docentResult->fetch_assoc()) {
            if (!is_null($row["point_timestamp"])) {
                $timestamp = strtotime($row["point_timestamp"]);
                if ($timestamp > strtotime(date("Y-m-d H:i:s"))) {
                    ?>
                    <form method="get">
                        <div class="row justify-content-center pointsDiv">
                            <input type=hidden name="points_class" value="<?php echo $row["class"]; ?>">
                            <button type="submit" class="btn btn-secondary btn-block col-11">
                                <span> <?php echo $row["class"]; ?><br>0/1 punten beschikbaar</span></button>
                        </div>
                    </form>
                    <?php
                } else {
                    ?>
                    <form method="get">
                        <div class="row justify-content-center">
                            <input type=hidden name="points_class" value="<?php echo $row["class"]; ?>">
                            <button type="submit" class="btn btn-info btn-block col-11">
                                <span> <?php echo $row["class"]; ?><br>1/1 punten beschikbaar</span></button>
                        </div>
                    </form>
                    <?php
                }
            } else {
                ?>
                <form method="get">
                    <div class="row justify-content-center">
                        <input type=hidden name="points_class" value="<?php echo $row["class"]; ?>">
                        <button type="submit" class="btn btn-info btn-block col-11"><span> <?php echo $row["class"]; ?>
                                <br>1/1 punten beschikbaar</span></button>
                    </div>
                </form>
                <?php
            }
        }
    } else {
        include "points.php";
    }
    ?>
    </div>
<?php } ?>