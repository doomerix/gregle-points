<?php

/* Include config file */
require_once 'connection.php';
//$_SESSION['user'] = 'random string';
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    //get user_id & password from post
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE user_id = ?";

    $stmt = $link->prepare($sql);
    $stmt->bind_param("s", $user_id);

    $result = $stmt->get_result();

    //given user_id can't be found, 0 records are returned/found
    if ($result->num_rows === 0)
    {
        print 'Gebruiker niet gevonden';
        exit;
    }

    $user = $result->fetch_assoc();

    //when the query succeded and a racord has been found we will compare the password with the passwordhash from the database
    if (password_verify($password, $user['PasswordHash']))
    {
        print 'aanmelden gelukt <br />';

        $_SESSION['user'] = $user['user_id'];
//        $_SESSION['isadmin'] = $user['IsAdmin']; //hoe ga ik dit maken zodat ik kan controleren wat voor rol de aangemelde gebruiker heeft?
        /* Redirect to Index page */
//        header('Location: '); //wat wordt de naam van de indexpagina en is er 1 index of zijn er 2 of 3 verschillende (voor elke rol een andere?)
    }
    else
    {
        print 'Aanmelden mislukt!';
    }
    $result->free();
    $link->close();
}
else
{
    print 'Aanmelden mislukt!';
}
$result->free();
$link->close();

//if a logged-in user should nevertheless return to this login page, he/she will be sent back to the index page
if (isset($_SESSION['user']))
{
//    header('Location: '); //wat wordt de naam van de indexpagina en is er 1 index of zijn er 2 of 3 verschillende (voor elke rol een andere?)
}
?>
