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
                    </select>
                </div>
                <div class="form-check">
                    <input formmethod="post" name="adminCheck" class="form-check-input" type="checkbox" value="admin" id="adminCheck">
                    <label class="form-check-label" for="adminCheck">Administrator</label>
                </div>
                <button type="submit" class="btn btn-light">Docent wijzigen</button>
            </form>
            <button type="submit" class="btn btn-danger">Docent verwijderen</button>
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