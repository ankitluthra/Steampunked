<?php

require __DIR__ . "/../vendor/autoload.php";

// Start the PHP session system
session_start();

define("STEAMPUNKED_SESSION", 'steampunked');

// If there is a Steampunked session, use that. Otherwise, create one
if(!isset($_SESSION[STEAMPUNKED_SESSION])) {
    $_SESSION[STEAMPUNKED_SESSION] = new Steampunked\Steampunked();
}

$steampunked = $_SESSION[STEAMPUNKED_SESSION];
