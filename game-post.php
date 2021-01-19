<?php
require __DIR__ . '/lib/steampunked.inc.php';

$gamecontroller = new
Steampunked\SteampunkedController($steampunked);
if(isset($_POST['reset']) && $_POST['reset'] == "Reset") {
      unset($_SESSION[STEAMPUNKED]);
      header('Location: ' . "index.php");
}

else {
  $gamecontroller->invoke();
  header('Location: ' . $gamecontroller->getPage());
}


exit;
