<?php require_once "../db/connection.php"; require_once "../app/interfaces/CRUD.php"; require_once "../app/classes/Teacher.php";

$createdAccount = isset($_POST["firstName"], $_POST["prefixName"], $_POST["surname"], $_POST["teacherID"]);
if ($createdAccount) {
    $response = new Teacher($_POST["firstName"], $_POST["prefixName"], $_POST["surname"], $_POST["teacherID"], $_POST["teacherClasses"], isset($_POST["adminCheck"]));
}
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--Bootstrap CSS-->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/custom.css">

    <title>MVT-Points</title>
</head>

<body class="text-center basicBody">
<div class="bodyWrap">
    <div class="container">
        <div>
            <h2 class="paragraphMarginSmall">Docent Toevoegen</h2>
            <form method="post">
                <?php
                if ($createdAccount) {
                    if (!$response->isValid()) {
                        ?>
                        <!-- Missing forms warning -->
                        <div class="alert alert-danger" role="alert">
                            Niet alle velden waren ingevuld!
                        </div>
                        <?php
                    } else {
                        if ($response->create($connection)) {
                            //  this is done afterwards because we need to assign the classes to the teachers
                            foreach ($response->getClasses() as $class) {
                                $insertIntoTeacherClasses = $connection->prepare("INSERT INTO docent_classes (docentnumber, class_id) VALUES (?, (SELECT id FROM class WHERE class = ?)) ;");
                                $teacherId = $response->getTeacherId();
                                $insertIntoTeacherClasses->bind_param("ss", $teacherId,$class);
                                $insertIntoTeacherClasses->execute();
                            }
                            ?>
                            <div class="alert alert-success" role="alert">
                                Gebruiker <?php echo $response->getFirstName() . " " . $response->getPrefix() . " " . $response->getSurName() . " (" . $response->getTeacherId() . ") is aangemaakt." ?>
                                <br>
                                Het standaard wachtwoord voor dit account is <?php echo "welkom" . date("Y") ?>.
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="alert alert-danger" role="alert">
                                Er is wat fout gegaan met het aanmaken van het account.<br>
                                Is het docentnummer wel uniek?<br>
                            </div>
                            <?php
                        }
                    }
                }
                ?>
                <div class="form-group">
                    <input formmethod="post" name="firstName" class="form-control" id="firstName" placeholder="Voornaam" required>
                </div>
                <div class="form-group">
                    <input formmethod="post" name="prefixName" class="form-control" id="prefixName" placeholder="Tussenvoegsel">
                </div>
                <div class="form-group">
                    <input formmethod="post"  name="surname" class="form-control" id="surname" placeholder="Achternaam" required>
                </div>
                <div class="form-group">
                    <input formmethod="post" name="teacherID" class="form-control" id="teacherID" placeholder="ID" required>
                </div>
                <div class="form-group">
                    <label for="Selecteer klassen">Selecteer Klassen</label>
                    <select name="teacherClasses[]" class="form-control" id="teacherClasses" multiple required>
                        <?php
                        $classes = $connection->query("SELECT class FROM class ;");
                        while($row = $classes->fetch_assoc()) {
                            ?>
                            <option><?php echo $row["class"];?></option>
                            <?php
                        }
                        $classes->close();
                        ?>
                    </select>
                </div>
                <div class="form-check">
                    <input formmethod="post" name="adminCheck" class="form-check-input" type="checkbox" value="admin" id="adminCheck">
                    <label class="form-check-label" for="adminCheck">Administrator</label>
                </div>
                <button type="submit" class="btn btn-light">Docent Toevoegen</button>
            </form>
        </div>
    </div>

    <footer class="footerBar boxShadowFooter justify-content-center">
        <nav class="nav justify-content-center">
            <a class="nav-link footerIcon"><img src="../img/person.svg"></a>
            <a class="nav-link footerIcon"><img src="../img/achievement.svg"></a>
            <a class="nav-link footerIcon"><img src="../img/cog.svg"></a>
        </nav>
    </footer>
</div>


<!--Required Scripts-->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
