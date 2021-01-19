<?php

require __DIR__ . "/../../vendor/autoload.php";



use Steampunked\Player as Player;

/** @file
 * @brief Unit tests for the class Steampunked
 * @cond
 */
class PlayerTest extends \PHPUnit_Framework_TestCase {
	const SEED = 42;

	public function test_construct() {
		$player = new Player("Player 1");

		$this->assertInstanceOf(Player::class, $player);
		$this->assertEquals($player->getName(), "Player 1");
	}

	public function test_pipes() {
		$steam = new Steampunked\Steampunked();
		$player = new Player("Player 1");

		$pipes = $player->getPipes();

		$this->assertEquals($pipes, array());

		$newpipe = new \Steampunked\Pipe($steam, \Steampunked\Pipe::PIPE_CAP_N, -1, -1);
		$newpipe->setFlag(true);

		$player->addToPipes($newpipe);

		$pipes = $player->getPipes();

		$this->assertEquals($pipes[0], $newpipe);
		$this->assertEquals($pipes[0]->getFlag(), true);

		$player->resetFlags();

		$pipes = $player->getPipes();
		$this->assertEquals($pipes[0]->getFlag(), false);


	}

}

/// @endcond
?>
