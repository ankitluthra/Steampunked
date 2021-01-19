<?php

namespace Steampunked;


class StartpageController
{

  public function __construct($steampunked) {
    $steampunked->createGame($_POST['gridsize'], $_POST['player1name'],
        $_POST['player2name']);
  }

  public function getGame() {
    return $this->steampunked;
  }

  private $steampunked;           // The game object we are controlling

}
?>
