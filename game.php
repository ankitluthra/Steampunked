<?php
/**
 * Created by PhpStorm.
 * User: Ankit Luthra
 * Time: 2:59 PM
 */

require __DIR__ . '/lib/steampunked.inc.php';
$view = new Steampunked\SteampunkedView($steampunked);
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Steampunked</title>
    <link href="reset.css" type="text/css" rel="stylesheet" />
    <link href="game.css" type="text/css" rel="stylesheet" />
</head>
<body>
    <?php echo $view->present(); ?>
</body>
</html>
