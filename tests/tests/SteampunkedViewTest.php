<?php

require __DIR__ . "/../../vendor/autoload.php";

use Steampunked\Steampunked as Steampunked;
use Steampunked\SteampunkedView as SteampunkedView;

/** @file
 * @brief Unit tests for the class SteampunkedView
 * @cond
 */

class SteampunkedViewTest extends \PHPUnit_Framework_TestCase {
	const SEED = 32;

	public function test_construct() {
		$steampunked = new Steampunked(self::SEED);
		$view = new SteampunkedView($steampunked);
		//With seed
		$this->assertInstanceOf('Steampunked\SteampunkedView', $view);

		$steampunked2 = new Steampunked();
		$view2 = new SteampunkedView($steampunked2);
		//Without seed
		$this->assertInstanceOf('Steampunked\SteampunkedView', $view2);
	}

	public function test_size(){
		$steampunked = new Steampunked();

		$steampunked->createGame(6, "", "");
		$this->assertEquals($steampunked->getSize(), 6);

		$steampunked->createGame(10, "", "");
		$this->assertEquals($steampunked->getSize(), 10);

		$steampunked->createGame(20, "", "");
		$this->assertNotEquals($steampunked->getSize(), 10);

		$steampunked->createGame(20, "", "");
		$this->assertEquals($steampunked->getSize(), 20);

		$steampunked->createGame(-20, "", "");
		$this->assertNotEquals($steampunked->getSize(), -20);

	}

	public function test_buttons()
	{
		$steampunked = new Steampunked();
		$gridSize = 6;
		$steampunked->createGame($gridSize, "", "");
		$view = new SteampunkedView($steampunked);
		$html = $view->present();

		// rotate button
		$this->assertTrue(substr_count($html, 'type="submit" name="rotate"') == 1);
		// discard button
		$this->assertTrue(substr_count($html, 'type="submit" name="discard"') == 1);
		// Open Valve button
		$this->assertTrue(substr_count($html, 'type="submit" name="openvalve"') == 1);
		// Give Up button
		$this->assertTrue(substr_count($html, 'type="submit" name="giveup"') == 1);
		// Tests all the radio button
		$this->assertTrue(substr_count($html, 'type="radio"') == 5);
	}

	public function test_messages()
	{
		$steampunked = new Steampunked();
		$gridSize = 6;
		$steampunked->createGame($gridSize, "P1", "P2");

		$view = new SteampunkedView($steampunked);
		$html = $view->present();
		// Test the game message when it is player1's turn
		$this->assertTrue(substr_count($html, '<p>P1 it is your turn!</p>') == 1);

		$steampunked->switchTurn();
		$view = new SteampunkedView($steampunked);
		$html = $view->present();
		// Test the game message when it is player2's turn
		$this->assertTrue(substr_count($html, '<p>P2 it is your turn!</p>') == 1);

		$steampunked->switchTurn();
		$view = new SteampunkedView($steampunked);
		$html = $view->present();
		// Test the game message when it is player1's turn
		$this->assertTrue(substr_count($html, '<p>P1 it is your turn!</p>') == 1);

		$steampunked->switchTurn();
		$view = new SteampunkedView($steampunked);
		$html = $view->present();
		// Test the game message when it is player2's turn
		$this->assertTrue(substr_count($html, '<p>P2 it is your turn!</p>') == 1);

		$steampunked->openValve();
		$view = new SteampunkedView($steampunked);
		$html = $view->present();
		// Test the game message player1's turn
		$this->assertTrue(substr_count($html, '<p>P1 wins the game!</p>') == 1);
	}


}