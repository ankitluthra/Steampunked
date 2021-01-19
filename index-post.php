<?php
require __DIR__ . '/lib/steampunked.inc.php';

$startpagecontroller = new Steampunked\StartpageController($steampunked
    );

header('Location: ' . 'game-post.php');

exit;
