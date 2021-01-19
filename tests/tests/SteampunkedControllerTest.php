<?php

require __DIR__ . "/../../vendor/autoload.php";



use Steampunked\SteampunkedController as SteampunkedController;
use Steampunked\Steampunked as Steampunked;

/** @file
 * @brief Unit tests for the class SteampunkedController
 * @cond
 */
class SteampunkedControllerTest extends \PHPUnit_Framework_TestCase {
  const SEED = 42;

  public function test_construct() {
    $steampunked = new Steampunked(self::SEED);
    $controller = new SteampunkedController($steampunked);

    $this->assertFalse($controller->isReset());
    $this->assertEquals('game.php', $controller->getPage());
  }


  public function test_rotateTile() {
    $steampunked = new Steampunked(self::SEED);
    $controller = new SteampunkedController($steampunked);
    $controller->getGame()->createGame(6, "Player1", "Player2");

    $_POST['rotate'] = "Rotate";
    $_POST['index'] = "0";

    $rotatedpipe = clone $controller->getGame()->getPipeOption()[0];
    $rotatedpipe->rotate();

    $controller->invoke();


    $this->assertEquals($controller->getGame()->getPipeOption()[0], $rotatedpipe);
  }

  public function test_discard() {
    $steampunked = new Steampunked(self::SEED);
    $controller = new SteampunkedController($steampunked);
    $controller->getGame()->createGame(6, "Player1", "Player2");

    $_POST['discard'] = "Discard";
    $_POST['index'] = "0";

    $oldpipe = clone $controller->getGame()->getPipeOption()[0];

    $controller->invoke();


    $this->assertNotEquals($controller->getGame()->getPipeOption()[0], $oldpipe);
  }

  public function test_openvalve() {
    $steampunked = new Steampunked(self::SEED);
    $controller = new SteampunkedController($steampunked);
    $controller->getGame()->createGame(6, "Player1", "Player2");

    $_POST['openvalve'] = "Open Valve";

    $controller->invoke();


    $this->assertEquals($controller->getGame()->getGameOver(), true);
    $this->assertEquals($controller->getGame()->getWinnerName(), "Player2");
  }

  public function test_giveup() {
    $steampunked = new Steampunked(self::SEED);
    $controller = new SteampunkedController($steampunked);
    $controller->getGame()->createGame(6, "Player1", "Player2");

    $_POST['giveup'] = "Give Up";

    $controller->invoke();


    $this->assertEquals($controller->getGame()->getGameOver(), true);
    $this->assertEquals($controller->getGame()->getWinnerName(), "Player2");
  }

  public function test_placetile() {
    $steampunked = new Steampunked(self::SEED);
    $controller = new SteampunkedController($steampunked);
    $controller->getGame()->createGame(6, "Player1", "Player2");

    $_POST['leak'] = "3,3";
    $_POST['index'] = "0";

    $oldpipe = clone $controller->getGame()->getPipeOption()[0];

    $controller->invoke();


    $this->assertNotEquals($controller->getGame()->getGrid()[3][3], $oldpipe);
  }



}

/// @endcond
?>
