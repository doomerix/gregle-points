<?php session_start();
require_once "db/connection.php";
require_once "app/classes/Role.php";
require_once "app/interfaces/CRUD.php";

if (!isset($_SESSION["user_id"])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$selectFirstlogin = $connection->prepare("SELECT firstlogin FROM user WHERE user_id = ? ;");
$selectFirstlogin->bind_param("s", $user_id);
$selectFirstlogin->execute();
$selectFirstlogin->bind_result($isFirstLogin);
$selectFirstlogin->fetch();
$selectFirstlogin->free_result();

if ($isFirstLogin == true) {
    header("Location: firstLogin.php");
    exit;
}

//  get the role name by id, use the result as constructor parameter for the Role object
$selectRole = $connection->prepare("SELECT role FROM role WHERE id = ? ;");
$selectRole->bind_param("i", $_SESSION["role_id"]);
$selectRole->execute();
$selectRole->bind_result($role_name);
$selectRole->fetch();
$selectRole->free_result();

//  the Role object has some nice functions to check if the created role is a student, administrator or teacher
$role = new Role($role_name);

//  if its the "true login" of the user, display this message
if (isset($_GET["fls"])) {
    ?>
    <!-- First time login Password was successfully editted. -->
    <div class="alert alert-success" role="alert">
        Je wachtwoord is net zojuist voor het eerst gewijzigd.<br>
        Je hebt nu toegang tot de applicatie! :-)
    </div>
    <?php
}
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--Bootstrap CSS-->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/custom.css">

    <title>Gregle Points</title>
</head>

<!-- If the role of the user is student, display student information-->
<body class="text-center">
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
<?php
}
if ($role->isTeacher() || $role->isAdmin()) {
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
    
    <?php 
    if (!isset($_POST["class"])){?>
    <div class="container pointsDivMain">
        <h5>Klassen</h5>
        <?php 
        $selectFromDocentClasses = $connection->prepare("SELECT class, point_timestamp FROM docent_classes LEFT JOIN class ON (class_id =  class.id) WHERE docentnumber = ? ;");
        $selectFromDocentClasses->bind_param("s", $user_id);
        $selectFromDocentClasses->execute();
        $docentResult = $selectFromDocentClasses->get_result();
        ?><form method="post"> <?php
        while($row = $docentResult->fetch_assoc()){
            if(!is_null($row["point_timestamp"])){
                $timestamp = strtotime($row["point_timestamp"]);
                if($timestamp < strtotime("-1 week")){
                    ?>
                    <div class="row justify-content-center pointsDiv">
                        <input type=hidden name="class" value="<?php echo $row["class"]; ?>">
                        <button type="submit" class="btn btn-secondary btn-block col-11"><span> <?php echo $row["class"]; ?><br>0/1 punten beschikbaar</span></button>
                    </div>
                    <?php
                }else{
                    ?>
                    <div class="row justify-content-center">
                        <input type=hidden name="class" value="<?php echo $row["class"]; ?>">
                        <button type="submit" class="btn btn-info btn-block col-11"><span> <?php echo $row["class"]; ?><br>1/1 punten beschikbaar</span></button>
                    </div>
                    <?php
                }
            }else{
                ?>
                <div class="row justify-content-center">
                    <input type=hidden name="class" value="<?php echo $row["class"]; ?>">
                    <button type="submit" class="btn btn-info btn-block col-11"><span> <?php echo $row["class"]; ?><br>1/1 punten beschikbaar</span></button>
                </div>
                <?php
            }
        }
        ?>
        </form>
    </div>
<?php
    } else {
        include "points.php";
    }
}
?>

<footer>
    <nav class="nav justify-content-center footerBar">
        <a class="nav-link footerIcon"><img src="img/person.svg"></a>
        <!-- Only display this icon if the user is not a student -->
        <?php if (!$role->isStudent()) { ?>
        <a class="nav-link footerIcon"><img src="img/achievement.svg"></a>
        <?php } ?>
        <a class="nav-link footerIcon"><img src="img/cog.svg"></a>
    </nav>
</footer>

<!--Required Scripts-->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>
<script>
    
</script>
</body>