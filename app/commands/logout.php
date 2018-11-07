<?php
//  unset the session
session_unset();

//  destroy the session
session_destroy();

//  send user back to index page (which will display the login page)
header("Location: ../app/");
exit;