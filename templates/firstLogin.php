<!doctype html>
<html lang="en">
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
                    <p class="paragraphMarginSmall"><b>Voor de accountveiligheid is het nodig om uw wachtwoord te veranderen bij de eerste login.</b></p>
                    <form>
                        <div class="form-group">
                            <input type="passwordPrim" class="form-control" id="changePassPrim" placeholder="Nieuw wachtwoord">
                        </div>
                        <div class="form-group">
                            <input type="passwordSecond" class="form-control" id="changePassSecond" placeholder="Herhaal wachtwoord">
                        </div>

                        <!-- Unequal password warning -->
                        <div class="alert alert-danger" role="alert">
                            De wachtwoorden komen niet overeen.
                        </div>
                    
                        <button type="submit" class="btn btn-light">Wijzigen</button>
                    </form>
                </div>
            </div>
        </div>
        <!--Required Scripts-->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>