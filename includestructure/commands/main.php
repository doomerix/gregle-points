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
    ?>
    <header class="headerMargin">
        <h2 class="paragraphMarginSmall"><?php echo $docent["firstname"] . " " . $docent["surname_prefix"] . " " . $docent["surname"]; ?></h2>
        <p class="paragraphMarginSmall"><?php echo $user_id; ?></p>
    </header>
    <!-- PUT THE CODE DOWN HERE STAN! -->

    <div class="container pointsDivMain">
        <h5>Klassen</h5>
        <div class="row justify-content-center">
            <button type="button" class="btn btn-info btn-block col-11"><span>{{class}}<br>1/1 punten beschikbaar</span>
            </button>
        </div>
        <div class="row justify-content-center pointsDiv">
            <button type="button" class="btn btn-secondary btn-block col-11">
                <span>{{class}}<br>0/1 punten beschikbaar</span></button>
        </div>
    </div>
<?php } ?>