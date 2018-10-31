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
            <h2 class="paragraphMarginSmall">{{full name}} wijzigen</h2>
            <form method="post">
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

                    </select>
                </div>
                <button type="submit" class="btn btn-light">Student wijzigen</button>
            </form>
            <button type="submit" class="btn btn-danger">Student verwijderen</button>
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
