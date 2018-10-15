<?php
/* Zonder de session_start-functie weet de applicatie niet wie je bent en werkt hij dus niet */
session_start();

/*remove all session variables*/
session_unset();

/*destroy session*/
session_destroy();
//hoi