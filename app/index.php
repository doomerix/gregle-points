<?php
require_once "security.php";
require_once "db/connection.php";
require_once "interfaces/CRUD.php";
require_once "classes/Role.php";
require_once "classes/Student.php";
require_once "classes/Teacher.php";

date_default_timezone_set("Europe/Amsterdam");
mb_internal_encoding("UTF-8");

?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--Bootstrap CSS-->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/custom.css">

    <title>MVT-Points</title>
</head>

<body class="text-center <?php echo !isset($_SESSION["user_id"]) ? "loginBody" : "basicBody"; ?>">
<?php
$role = null;
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
updateSession($connection);

if (!isset($_SESSION["user_id"])) {
    //  include login.php code
    include "commands/login.php";
} else {
    $user_id = $_SESSION['user_id'];

    $selectFirstlogin = $connection->prepare("SELECT firstlogin FROM user WHERE user_id = ? ;");
    $selectFirstlogin->bind_param("s", $user_id);
    $selectFirstlogin->execute();
    $selectFirstlogin->bind_result($isFirstLogin);
    $selectFirstlogin->fetch();
    $selectFirstlogin->free_result();

    if ($isFirstLogin == true) {
        //  include firstlogin.php code
        include "commands/firstLogin.php";
    } else {
        //  get the role name by id, use the result as constructor parameter for the Role object
        $selectRole = $connection->prepare("SELECT role FROM role WHERE id = ? ;");
        $selectRole->bind_param("i", $_SESSION["role_id"]);
        $selectRole->execute();
        $selectRole->bind_result($role_name);
        $selectRole->fetch();
        $selectRole->free_result();

        //  the Role object has some nice functions to check if the created role is a student, administrator or teacher
        $role = new Role($role_name);

        if (isset($_GET["command"])) {
            $command = $_GET["command"];
            switch ($command) {
                case "main":
                    //  include main page code
                    include "commands/main.php";
                    break;
                case "settings":
                    //  include main settings page code
                    include "commands/settings.php";
                    break;
                case "adminCommands":
                    //  include account options
                    include "commands/adminCommands.php";
                    break;
                case "addStudent":
                    //  include student account form page
                    include "commands/addStudent.php";
                    break;
                case "addTeacher":
                    //  include teacher account form page
                    include "commands/addTeacher.php";
                    break;
                case "manageAccounts":
                    //  include all accounts overview page
                    include "commands/accounts.php";
                    break;
                case "manageClasses":
                    //  include all classes overview page
                    include "commands/classes.php";
                    break;
                case "logout":
                    //  include logout function page
                    include "commands/logout.php";
                    break;
            }
        } else {
            //  include main page code
            include "commands/main.php";
        }
    }
}

if (!isset($_SESSION["user_id"])) {
    ?>
    <footer class="loginFooterBar boxShadowFooter justify-content-center">
        <p>&copy; Team Gregle, 2018-2019</p>
    </footer>
    <?php
} else {
    ?>
    <footer>
        <nav class="nav justify-content-center footerBar">
            <a href="?command=main" class="nav-link footerIcon"><img src="../img/person.svg"></a>
            <a href="?command=settings" class="nav-link footerIcon"><img src="../img/cog.svg"></a>
        </nav>
    </footer>
    <?php
}

function enforceAdminOnly(Role $role) {
    if (is_null($role) || !$role->isAdmin()) {
        header("Location: ../app/");
        exit;
    }
}

function sendPasswordMail($name, $address, $id, $password, $new) {
    $mail = '<!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    </head>
    <body>
        <h4>Beste ' . $name . ',</h4>
        <div style="font-size:12px">
        <p>' . ($new ? "Er is een account voor je aangemaakt voor MVT-Points." : "Je wachtwoord voor MVT-Points is gereset.") .' Als je inlogt, moet je een nieuw wachtwoord instellen in verband met de veiligheid.
        </p>
        <span>Loginnummer: ' . $id . '</span></br>
        <span>Wachtwoord: ' . $password . '</span></br></br>
        <p><a href="http://mvtpoints.beverwijk.ictacademie.net/app/">Klik hier om naar MVT-Points te gaan.</a></p>
        <p>MVT-Points</p>
        </div>
    </body>
</html>';
    $headers = "From: MVT Points\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    mail($address, ($new ? "MVT Account" : "Password reset"), $mail, $headers);
}

function randomString($length = 8) {
    $result = "";
    for($i = 0; $i < $length; $i++){
        $symbol = rand(1, 3);
        switch($symbol){
            case 1:
                $symbol = chr(rand(65,90));
                break;
            case 2:
                $symbol = chr(rand(97,122));
                break;
            case 3:
                $symbol = rand(0, 9);
                break;
        }
        $result = $result . $symbol;
    }
    return $result;
}

function resetPoints(mysqli $sql) {
    //  update points for teachers/admins if it needs to be done
    $selectData = $sql->query("SELECT docentnumber, point_timestamp, docent_classes.points, class.id, class.points FROM docent_classes LEFT JOIN class ON (class_id =  class.id) ;");
    while ($row = $selectData->fetch_assoc()) {
        $timestamp = strtotime($row["point_timestamp"]);
        if ($timestamp <= strtotime(date("Y-m-d H:i:s"))) {
            $updateData = $sql->prepare("UPDATE docent_classes SET points = ?, point_timestamp = ? WHERE class_id = ? AND docentnumber = ? ;");
            $nextPointsTime = date("Y-m-d H:i:s", strtotime("next week thursday"));
            $updateData->bind_param("isis",$row["points"], $nextPointsTime, $row["id"], $row["docentnumber"]);
            $updateData->execute();
            $updateData->free_result();
        }
    }
    $selectData->free_result();
}

function updateSession(mysqli $sql) {
    //  if account with user_id no longer exists in the database, we will unset the user_id from their SESSION
    if (isset($_SESSION["user_id"])) {
        $id = $_SESSION["user_id"];
        //  yes. this prepared statement absolutely makes sense.
        $selectData = $sql->prepare("SELECT user_id FROM user WHERE user_id = ? ;");
        $selectData->bind_param("s", $id);
        $selectData->execute();
        $selectData->store_result();

        if ($selectData->num_rows == 0) {
            unset($_SESSION["user_id"]);
            unset($_SESSION["role_id"]);
        }
        $selectData->free_result();

        if (isset($_SESSION["role_id"])) {
            $selectRole = $sql->prepare("SELECT role_id FROM user WHERE user_id = ? ;");
            $selectRole->bind_param("s", $id);
            $selectRole->execute();
            $selectRole->bind_result($roleId);
            $selectRole->fetch();

            $_SESSION["role_id"] = $roleId;
            $selectRole->free_result();
        }
    }
}
?>

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
<script src="js/passrequirements.js" type="text/javascript"></script>
<script>
    $('#passwd1').PassRequirements({
        rules: {
        minlength: {
            text: "Minimaal minLength karakters lang",
            minLength: 8,
        },
        containSpecialChars: {
            text: "Minimaal minLength speciaal karakter.",
            minLength: 1,
            regex: new RegExp('([^!,%,&,@,#,$,^,*,?,_,~])', 'g')
        },
        containLowercase: {
            text: "Minimaal minLength kleine letter.",
            minLength: 1,
            regex: new RegExp('[^a-z]', 'g')
        },
        containUppercase: {
            text: "Minimaal minLength hoofd letter.",
            minLength: 1,
            regex: new RegExp('[^A-Z]', 'g')
        },
        containNumbers: {
            text: "Minimaal minLength nummer.",
            minLength: 1,
            regex: new RegExp('[^0-9]', 'g')
        }
    }});
</script>
</body>