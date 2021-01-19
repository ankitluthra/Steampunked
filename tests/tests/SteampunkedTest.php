<?php

require __DIR__ . "/../../vendor/autoload.php";



use Steampunked\Steampunked as Steampunked;

/** @file
 * @brief Unit tests for the class Steampunked
 * @cond
 */
class SteampunkedTest extends \PHPUnit_Framework_TestCase {
	const SEED = 42;

	public function test_construct() {
		$steampunked = new Steampunked(self::SEED);

		$this->assertInstanceOf(Steampunked::class, $steampunked);


		$steampunked2 = new Steampunked();

		$this->assertInstanceOf(Steampunked::class, $steampunked2);
	}


	public function test_createGame() {
		$steampunked = new Steampunked(self::SEED);

		$steampunked->createGame(6, "Player1", "Player2");

		$this->assertEquals($steampunked->getSize(), 6);

		$this->assertEquals($steampunked->getCurrentPlayer(), $steampunked->getPlayer(0));

		$this->assertEquals($steampunked->getGameOver(), false);

		$this->assertEquals(count($steampunked->getPipeOption()), 5);

		$this->assertEquals($steampunked->getGrid()[0][0]->getPipe(), \Steampunked\Pipe::PIPE_VALVE_CLOSED);

		$this->assertEquals($steampunked->getGrid()[1][1]->getPipe(), \Steampunked\Pipe::PIPE_NULL);

	}

	public function test_sizes() {
		$steampunked = new Steampunked(self::SEED);

		$steampunked->createGame(6, "", "");

		$this->assertEquals($steampunked->getSize(), 6);

		$steampunked->createGame(10, "", "");

		$this->assertEquals($steampunked->getSize(), 10);

		$steampunked->createGame(20, "", "");

		$this->assertEquals($steampunked->getSize(), 20);

		$steampunked->createGame(-10, "", "");

		$this->assertNotEquals($steampunked->getSize(), -10);
	}

	public function test_switchTurn() {
		$steampunked = new Steampunked(self::SEED);

		$steampunked->createGame(6, "", "");

		$this->assertEquals($steampunked->getCurrentPlayer(), $steampunked->getPlayer(0));

		$steampunked->switchTurn();

		$this->assertEquals($steampunked->getCurrentPlayer(), $steampunked->getPlayer(1));

		$steampunked->switchTurn();

		$this->assertEquals($steampunked->getCurrentPlayer(), $steampunked->getPlayer(0));

	}

	public function test_giveUp() {
		$steampunked = new Steampunked(self::SEED);

		$steampunked->createGame(6, "", "");

		$steampunked->giveUp();

		$this->assertEquals($steampunked->getGameOver(), true);
		$this->assertEquals($steampunked->getWinnerName(), $steampunked->getPlayer(1)->getName());

		$steampunked = new Steampunked(self::SEED);

		$steampunked->createGame(6, "", "");

		$steampunked->switchTurn();
		$steampunked->giveUp();

		$this->assertEquals($steampunked->getGameOver(), true);
		$this->assertEquals($steampunked->getWinnerName(), $steampunked->getPlayer(0)->getName());
	}

	public function test_getRandomPipe() {
		$steampunked = new Steampunked(self::SEED);

		$steampunked->createGame(6, "", "");

		$pipe = $steampunked->getRandomPipe();

		$this->assertInstanceOf(\Steampunked\Pipe::class, $pipe);
	}

	public function test_pipeOptions() {
		$steampunked = new Steampunked(self::SEED);

		$steampunked->createGame(6, "", "");

		$options = $steampunked->getPipeOption();

		$this->assertEquals($options[0]->getPipe(), \Steampunked\Pipe::PIPE_CAP_N);
		$this->assertEquals($options[1]->getPipe(), \Steampunked\Pipe::PIPE_NINETY_ES);
		$this->assertEquals($options[2]->getPipe(), \Steampunked\Pipe::PIPE_STRAIGHT_V);
		$this->assertEquals($options[3]->getPipe(), \Steampunked\Pipe::PIPE_NINETY_SW);
		$this->assertEquals($options[4]->getPipe(), \Steampunked\Pipe::PIPE_CAP_S);

		$steampunked->discardPipe(0);

		$options = $steampunked->getPipeOption();

		$this->assertEquals($options[0]->getPipe(), \Steampunked\Pipe::PIPE_NINETY_ES);
		$this->assertEquals($options[1]->getPipe(), \Steampunked\Pipe::PIPE_STRAIGHT_V);
		$this->assertEquals($options[2]->getPipe(), \Steampunked\Pipe::PIPE_NINETY_SW);
		$this->assertEquals($options[3]->getPipe(), \Steampunked\Pipe::PIPE_CAP_S);
		$this->assertEquals($options[4]->getPipe(), \Steampunked\Pipe::PIPE_CAP_W);

		$steampunked->rotate(1);
		$options = $steampunked->getPipeOption();

		$this->assertEquals($options[1]->getPipe(), \Steampunked\Pipe::PIPE_STRAIGHT_H);

		$steampunked->rotate(3);
		$options = $steampunked->getPipeOption();

		$this->assertEquals($options[3]->getPipe(), \Steampunked\Pipe::PIPE_CAP_W);

		$steampunked->rotate(3);
		$steampunked->rotate(3);
		$steampunked->rotate(3);
		$options = $steampunked->getPipeOption();

		$this->assertEquals($options[3]->getPipe(), \Steampunked\Pipe::PIPE_CAP_S);
	}

	public function test_placePipe() {
		$steampunked = new Steampunked(self::SEED);

		$steampunked->createGame(6, "", "");


		$steampunked->placePipe(1, 0, 3);

		$options = $steampunked->getPipeOption();
		$grid = $steampunked->getGrid();

		$this->assertEquals($grid[0][1]->getPipe(), \Steampunked\Pipe::PIPE_NINETY_SW);
		$this->assertEquals($options[4]->getPipe(), \Steampunked\Pipe::PIPE_CAP_W);

		$steampunked->placePipe(1, 1, 1);

		$options = $steampunked->getPipeOption();
		$grid = $steampunked->getGrid();

		$this->assertEquals($grid[1][1]->getPipe(), \Steampunked\Pipe::PIPE_LEAK_N);
		$this->assertEquals($options[2]->getPipe(), \Steampunked\Pipe::PIPE_STRAIGHT_V);

		$steampunked->placePipe(1, 5, 1);

		$options = $steampunked->getPipeOption();
		$grid = $steampunked->getGrid();

		$this->assertEquals($grid[5][1]->getPipe(), \Steampunked\Pipe::PIPE_LEAK_W);
		$this->assertEquals($options[2]->getPipe(), \Steampunked\Pipe::PIPE_STRAIGHT_V);
	}

	public function test_gamePlay() {
		/// TEST 1 - Winning the game
		$steampunked = new Steampunked(self::SEED);

		$steampunked->createGame(6, "P1", "P2");

		$steampunked->placePipe(1, 0, 3); // P1 elbow west south

		$steampunked->discardPipe(4); // P2 Cap W

		$steampunked->rotate(1);
		$steampunked->rotate(1);
		$steampunked->rotate(1);
		$steampunked->placePipe(1, 1, 1); // P1 elbow north east

		$steampunked->discardPipe(2); // P2 Cap S

		$steampunked->placePipe(2, 1, 2); // P1 straight h

		$steampunked->discardPipe(4); // P2 Cap N

		$steampunked->rotate(1);
		$steampunked->placePipe(3, 1, 1); // P1 straight h

		$steampunked->discardPipe(3); // P2 90-sw

		$steampunked->placePipe(4, 1, 3); // P1 tee-esw

		$steampunked->discardPipe(2); // P2 90-es

		$steampunked->placePipe(4, 2, 0); // P1 cap-n

		$steampunked->discardPipe(1); // P2 90-es

		$steampunked->placePipe(5, 1, 3); // P1 tee-esw

		$steampunked->discardPipe(4); // P2 90-ne

		$steampunked->rotate(3);
		$steampunked->rotate(3);
		$steampunked->rotate(3);
		$steampunked->placePipe(5, 2, 3); // P1 cap-n

		$steampunked->discardPipe(3); // P2 90-ne

		$steampunked->placePipe(6, 1, 3); // P1 tee-esw

		$steampunked->discardPipe(0); // P2 tee-wne

		$steampunked->rotate(3);
		$steampunked->placePipe(6, 2, 3); // P1 cap-n

		$steampunked->discardPipe(0); // P2 cap-e


		$steampunked->openValve();


		$this->assertEquals($steampunked->getGameOver(), true);
		$this->assertEquals($steampunked->getCurrentPlayer()->getName(), $steampunked->getPlayer(0)->getName());
		$this->assertEquals($steampunked->getWinnerName(), $steampunked->getPlayer(0)->getName());
		/// END TEST 1

		/// TEST 2 - Losing by not connected
		$steampunked = new Steampunked(self::SEED);

		$steampunked->createGame(6, "P1", "P2");

		$steampunked->openValve();


		$this->assertEquals($steampunked->getGameOver(), true);
		$this->assertEquals($steampunked->getCurrentPlayer()->getName(), $steampunked->getPlayer(0)->getName());
		$this->assertEquals($steampunked->getWinnerName(), $steampunked->getPlayer(1)->getName());
		/// END TEST 2


		/// TEST 3 - Losing by having leaks
		$steampunked = new Steampunked(self::SEED);

		$steampunked->createGame(6, "P1", "P2");

		$steampunked->placePipe(1, 0, 3); // P1 elbow west south

		$steampunked->discardPipe(4); // P2 Cap W

		$steampunked->rotate(1);
		$steampunked->rotate(1);
		$steampunked->rotate(1);
		$steampunked->placePipe(1, 1, 1); // P1 elbow north east

		$steampunked->discardPipe(2); // P2 Cap S

		$steampunked->placePipe(2, 1, 2); // P1 straight h

		$steampunked->discardPipe(4); // P2 Cap N

		$steampunked->rotate(1);
		$steampunked->placePipe(3, 1, 1); // P1 straight h

		$steampunked->discardPipe(3); // P2 90-sw

		$steampunked->placePipe(4, 1, 3); // P1 tee-esw

		$steampunked->discardPipe(2); // P2 90-es

		$steampunked->placePipe(4, 2, 0); // P1 cap-n

		$steampunked->discardPipe(1); // P2 90-es

		$steampunked->placePipe(5, 1, 3); // P1 tee-esw

		$steampunked->discardPipe(4); // P2 90-ne

		$steampunked->rotate(3);
		$steampunked->rotate(3);
		$steampunked->rotate(3);
		$steampunked->placePipe(5, 2, 3); // P1 cap-n

		$steampunked->discardPipe(3); // P2 90-ne

		$steampunked->placePipe(6, 1, 3); // P1 tee-esw

		$steampunked->discardPipe(0); // P2 tee-wne


		$steampunked->openValve();


		$this->assertEquals($steampunked->getGameOver(), true);
		$this->assertEquals($steampunked->getCurrentPlayer()->getName(), $steampunked->getPlayer(0)->getName());
		$this->assertEquals($steampunked->getWinnerName(), $steampunked->getPlayer(1)->getName());
		/// END TEST 3
	}




}

/// @endcond
?>
