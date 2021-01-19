<?php

namespace Steampunked;


class SteampunkedController
{

  /**
   * SteampunkedController constructor.
   * @param $steampunked Steampunked the game
   * @param $_POST
   */
  public function __construct($steampunked) {
      $this->steampunked = $steampunked;
  }

  public function invoke() {
    if (isset($_POST['rotate']) && $_POST['rotate'] == "Rotate") {
      $this->rotateTile();
    }
    if (isset($_POST['discard']) && $_POST['discard'] == "Discard") {
      $this->discardTile();
    }
    if (isset($_POST['openvalve']) && $_POST['openvalve'] == "Open Valve") {
      $this->openValve();
    }
    if (isset($_POST['giveup']) && $_POST['giveup'] == "Give Up") {
      $this->giveUp();
    }
    if (isset($_POST['leak'])) {
      $params = explode(',', $_POST['leak']);
      $row = intval($params[0]);
      $col = intval($params[1]);
      $this->placeTile($col, $row);
    }
  }

  public function getPage() {
    return $this->page;
  }

  public function isReset() {
    return $this->reset;
  }

  public function rotateTile() {
    if (isset($_POST['index'])) {
      $this->steampunked->rotate($_POST['index']);
    }
  }

  public function placeTile($col, $row) {
    if (isset($_POST['index'])) {
      $this->steampunked->placePipe($col, $row, $_POST['index']);
    }
  }

  public function discardTile() {
    if (isset($_POST['index'])) {
      $this->steampunked->discardPipe($_POST['index']);
    }
  }

  public function openValve() {
      $this->steampunked->openValve();
  }

  public function giveUp() {
    $this->steampunked->giveUp();
  }

  public function getGame() {
    return $this->steampunked;
  }

  private $steampunked;           // The game object we are controlling
  private $page = 'game.php';     // The redirect page
  private $reset = false;         // True if we need to reset the game

}

?>
