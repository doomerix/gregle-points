<?php include_once "db/connection.php"; 

$user_id = $_SESSION['$user_id'];
$statement = $connection->prepare("SELECT firstname, surname_prefix, surname, points, class FROM student LEFT JOIN class ON (class_id = class.id) WHERE studentnumber = ? ;");
$statement->bind_param("s", $user_id);
$statement->execute();

$result = $statement->get_result();
$student = $result->fetch_assoc();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--Bootstrap CSS-->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/custom.css">

    <title>Gregle Points</title>
</head>

<body class="text-center">
<header class="headerMargin">
    <h1 class="paragraphMarginSmall"><?php echo $student["firstname"] . " " . $student["surname_prefix"] . " " . $student["surname"];?></h1>
    <p class="paragraphMarginSmall"><?php echo $user_id;?></p>
    <p class="paragraphMarginSmall"><?php echo $student["class"];?></p>
</header>

<div class="divPointbar">
    <p class="pointAmount paragraphMarginSmall"><?php echo $student["points"]; ?></p>
    <p class="paragraphMarginSmall pointPunten">punten</p>
</div>

<footer>
    <nav class="nav justify-content-center footerBar">
        <a class="nav-link footerIcon"><img src="img/person.svg"></a>
        <a class="nav-link footerIcon"><img src="img/cog.svg"></a>
    </nav>
</footer>

<!--Required Scripts-->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>