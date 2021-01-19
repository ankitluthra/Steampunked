<?php

require __DIR__ . "/../../vendor/autoload.php";



use Steampunked\Pipe as Pipe;

/** @file
 * @brief Unit tests for the class Steampunked
 * @cond
 */
class PipeTest extends \PHPUnit_Framework_TestCase {
	const SEED = 42;

	public function test_construct() {
		$steam = new \Steampunked\Steampunked(self::SEED);
		$pipe = new Pipe($steam, Pipe::PIPE_NULL, -1, -2);

		$this->assertInstanceOf(Pipe::class, $pipe);
		$this->assertEquals($pipe->getPipe(), Pipe::PIPE_NULL);

		$xy = $pipe->getXY();

		$this->assertEquals($xy[0], -1);
		$this->assertEquals($xy[1], -2);
	}

	public function test_getSet() {
		$steam = new \Steampunked\Steampunked(self::SEED);
		$pipe = new Pipe($steam, Pipe::PIPE_NULL, -1, -2);

		$xy = $pipe->getXY();

		$this->assertEquals($xy[0], -1);
		$this->assertEquals($xy[1], -2);

		$pipe->setXY(1,2);

		$xy = $pipe->getXY();

		$this->assertEquals($xy[0], 1);
		$this->assertEquals($xy[1], 2);


		$this->assertEquals($pipe->getPipe(), Pipe::PIPE_NULL);
		$pipe->setPipe(Pipe::PIPE_LEAK_E);
		$this->assertEquals($pipe->getPipe(), Pipe::PIPE_LEAK_E);


		$pipe->setOwner(0);
		$this->assertEquals($pipe->getOwner(), 0);


		$this->assertEquals($pipe->getFlag(), false);
		$pipe->setFlag(true);
		$this->assertEquals($pipe->getFlag(), true);
	}

	public function test_rotate() {
		$steam = new \Steampunked\Steampunked(self::SEED);
		$pipe = new Pipe($steam, Pipe::PIPE_CAP_N, -1, -2);

		$pipe->rotate();

		$this->assertEquals($pipe->getPipe(), Pipe::PIPE_CAP_E);

		$pipe->rotate();
		$pipe->rotate();
		$pipe->rotate();

		$this->assertEquals($pipe->getPipe(), Pipe::PIPE_CAP_N);
	}

	public function test_open() {
		$steam = new \Steampunked\Steampunked(self::SEED);

		$pipe1 = new Pipe($steam, Pipe::PIPE_STRAIGHT_H, -1, -2);

		$pipe1->setFlag(true);

		$leaks = $pipe1->indicateLeaks();

		$this->assertEquals($leaks, false);

		$open = $pipe1->open();

		$this->assertEquals($open["N"], false);
		$this->assertEquals($open["E"], true);
		$this->assertEquals($open["S"], false);
		$this->assertEquals($open["W"], true);
	}


}

/// @endcond
?>
