<?php session_start();
require_once '../db/connection.php';
require_once "../app/classes/Role.php";
require_once "../app/interfaces/CRUD.php";

if (!isset($_SESSION["user_id"])) {
    header('Location: ../login.php');
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

if ($role->isStudent()) {
    header('Location: ../index.php');
    exit;
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
            <div class="paragraphMarginTop">
                <button type="button" class="btn btn-info btn-block">Alle accounts</button>
            </div>
            <?php if ($role->isTeacher() || $role->isAdmin()) {?>
            <div class="paragraphMarginSmallTop">
                <button type="button" class="btn btn-info btn-block">Student toevoegen</button>
            </div>
            <?php } ?>
            <?php if ($role->isAdmin()) { ?>
            <div class="paragraphMarginSmallTop">
                <button type="button" class="btn btn-info btn-block">Docent toevoegen</button>
            </div>
            <?php } ?>
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
