<?php
require_once '../security.php';
require_once "../db/connection.php";
require_once "../app/interfaces/CRUD.php";
require_once "../app/classes/Role.php";
require_once "../app/classes/Student.php";
require_once "../app/classes/Teacher.php";

?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--Bootstrap CSS-->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/custom.css">

    <title>Gregle Points</title>
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
                case "accountCommands":
                    //  include account options
                    include "commands/accountCommands.php";
                    break;
                case "addStudent":
                    //  include student account form page
                    include "commands/addStudent.php";
                    break;
                case "addTeacher":
                    //  include teacher account form page
                    include "commands/addTeacher.php";
                    break;
                case "accounts":
                    //  include all accounts overview page
                    include "commands/accounts.php";
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
            <a href="?command=main" class="nav-link footerIcon"><img src="img/person.svg"></a>
            <a href="?command=settings" class="nav-link footerIcon"><img src="img/cog.svg"></a>
        </nav>
    </footer>
    <?php
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
<script src="../js/passrequirements.js" type="text/javascript"></script>
<script>
    $('#passwd1').PassRequirements({
        rules: {
        minlength: {
            text: "be at least minLength characters long",
            minLength: 8,
        },
        containSpecialChars: {
            text: "Your input should contain at least minLength special character",
            minLength: 1,
            regex: new RegExp('([^!,%,&,@,#,$,^,*,?,_,~])', 'g')
        },
        containLowercase: {
            text: "Your input should contain at least minLength lower case character",
            minLength: 1,
            regex: new RegExp('[^a-z]', 'g')
        },
        containUppercase: {
            text: "Your input should contain at least minLength upper case character",
            minLength: 1,
            regex: new RegExp('[^A-Z]', 'g')
        },
        containNumbers: {
            text: "Your input should contain at least minLength number",
            minLength: 1,
            regex: new RegExp('[^0-9]', 'g')
        }
    }});
</script>
</body>