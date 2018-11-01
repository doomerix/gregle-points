<?php

//Avoid session forcing
ini_set('session.use_strict_mode', 1);

session_start();

function preventHijacking()
{
    if (!isset($_SESSION['IPaddress']) || !isset($_SESSION['userAgent']))
        return false;

    if ($_SESSION['IPaddress'] != $_SERVER['REMOTE_ADDR'])
        return false;

    if ($_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT'])
        return false;

    return true;
}

function SessionIsValid()
{
    //check if user already has a session
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['roll_id']))
        return false;

    //prevent hijacking by IP en browser check
    if (preventHijacking() == false)
        return false;
    
    return true;
}

?>