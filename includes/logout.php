<?php

// use this file to log out
// if we want to log out of a page
// We will call this file and it will redirect us to the index, but we will no longer be logged in

// We will need the logout function located in Auth.php
// In this case we access config.php since it includes it
require_once 'config.php';
// log out of the current user session
Auth::logout();
header('Location: ../public/index.php');
exit;