<?php enforceAdminOnly($role);

$executionState = false;

if (isset($_POST["class"])) {
    $className = $_POST["class"];

    $insertClass = $connection->prepare("INSERT INTO class (class) VALUES (?) ;");
    $insertClass->bind_param("s", $className);
    $executionState = $insertClass->execute();
}

?>
<div class="bodyWrap">
    <div class="container">
        <div>
            <h2 class="paragraphMarginSmall">Klas toevoegen</h2>
            <form method="post">
                <?php
                if (isset($_POST["class"])) {
                    if ($executionState) {
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
                ?>
                <div class="form-group">
                    <input formmethod="post" name="class" class="form-control" id="class" placeholder="Klas" required>
                </div>
                <button type="submit" class="btn btn-light">Klas toevoegen</button>
            </form>
        </div>
    </div>
</div>