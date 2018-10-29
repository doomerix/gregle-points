<?php session_start(); require_once "../db/connection.php"; require_once "../app/interfaces/CRUD.php"; require_once "../app/classes/Student.php";

if (!isset($_SESSION["user_id"])) {
    header('Location: ../login.php');
    exit;
}

$createdAccount = isset($_POST["firstName"], $_POST["prefixName"], $_POST["surname"], $_POST["studentID"], $_POST["studentClass"]);
if ($createdAccount) {
    $response = new Student($_POST["firstName"], $_POST["prefixName"], $_POST["surname"], $_POST["studentID"], $_POST["studentClass"]);
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
            <h2 class="paragraphMarginSmall">Student Toevoegen</h2>
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
                            ?>
                            <div class="alert alert-success" role="alert">
                                Gebruiker <?php echo $response->getFirstName() . " " . $response->getPrefix() . " " . $response->getSurName() . " (" . $response->getStudentId() . ") is aangemaakt." ?>
                                <br>
                                Het standaard wachtwoord voor dit account is <?php echo "welkom" . date("Y") ?>.
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="alert alert-danger" role="alert">
                                Er is wat fout gegaan met het aanmaken van het account.<br>
                                Is het studentnummer wel uniek?<br>
                            </div>
                            <?php
                        }
                    }
                }
                ?>
                <div class="form-group">
                    <input type="firstName" class="form-control" name="firstName" placeholder="Voornaam" required>
                </div>
                <div class="form-group">
                    <input formmethod="post" type="prefixName" class="form-control" name="prefixName" placeholder="Tussenvoegsel">
                </div>
                <div class="form-group">
                    <input formmethod="post" type="surname" class="form-control" name="surname" placeholder="Achternaam" required>
                </div>
                <div class="form-group">
                    <input formmethod="post" type="studentID" class="form-control" name="studentID" placeholder="ID" required>
                </div>
                <div class="form-group">
                    <label for="Selecteer klassen">Selecteer Klas</label>
                    <select class="form-control" name="studentClass" required>
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

                <button type="submit" class="btn btn-light">Student Toevoegen</button>
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
