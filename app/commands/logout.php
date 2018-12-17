<?php
//  unset the session
session_unset();

//  destroy the session
session_destroy();

// remove cookies
setcookie("user_id", null, time() - 3600);
setcookie("password", null, time() - 3600);                     

//  send user back to index page (which will display the login page)
header("Location: ../app/");
exit;