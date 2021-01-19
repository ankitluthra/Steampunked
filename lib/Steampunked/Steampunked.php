<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2/15/2016
 * Time: 11:12 PM
 */

namespace Steampunked;


class Steampunked
{
    /**
     * Steampunked constructor.
     * @param int|null $seed for random generation of the game
     */
    public function __construct($seed = null) {

        if($seed === null) {
            $seed = time();
        }

        srand($seed);
        $this->seed = $seed;

        $this->pipes =array();

        array_push($this->pipes, new Pipe($this, Pipe::PIPE_CAP_N, -1, -1));
        array_push($this->pipes, new Pipe($this, Pipe::PIPE_CAP_E, -1, -1));
        array_push($this->pipes, new Pipe($this, Pipe::PIPE_CAP_S, -1, -1));
        array_push($this->pipes, new Pipe($this, Pipe::PIPE_CAP_W, -1, -1));

        array_push($this->pipes, new Pipe($this, Pipe::PIPE_NINETY_ES, -1, -1));
        array_push($this->pipes, new Pipe($this, Pipe::PIPE_NINETY_SW, -1, -1));
        array_push($this->pipes, new Pipe($this, Pipe::PIPE_NINETY_WN, -1, -1));
        array_push($this->pipes, new Pipe($this, Pipe::PIPE_NINETY_NE, -1, -1));

        array_push($this->pipes, new Pipe($this, Pipe::PIPE_STRAIGHT_H, -1, -1));
        array_push($this->pipes, new Pipe($this, Pipe::PIPE_STRAIGHT_V, -1, -1));

        array_push($this->pipes, new Pipe($this, Pipe::PIPE_TEE_ESW, -1, -1));
        array_push($this->pipes, new Pipe($this, Pipe::PIPE_TEE_SWN, -1, -1));
        array_push($this->pipes, new Pipe($this, Pipe::PIPE_TEE_WNE, -1, -1));
        array_push($this->pipes, new Pipe($this, Pipe::PIPE_TEE_NES, -1, -1));

    }

    /**
     * Create a Steampunked game
     * @param $size int Size of the grid
     * @param $p1name string Player 1's name
     * @param $p2name string Player 2's name
     */
    public function createGame($size, $p1name, $p2name) {

        if($size < 6) {
            $size = 6;
        }

        $this->size = $size;
        $this->gameOver = false;
        $this->winner = -1;

        $this->currentPlayer = 0;

        $this->pipeOptions = array();
        for($i = 0; $i < 5; $i++) {
            array_push($this->pipeOptions, $this->getRandomPipe());
        }

        $this->players = array();
        array_push($this->players, new Player($p1name));
        array_push($this->players, new Player($p2name));

        $this->foundEnds = array();
        array_push($this->foundEnds, false);
        array_push($this->foundEnds, false);

        $this->setGrid();

        $this->getPlayer(0)->resetFlags();
        $this->getPlayer(0)->getPipes()[0]->indicateLeaks();

        $this->getPlayer(1)->resetFlags();
        $this->getPlayer(1)->getPipes()[0]->indicateLeaks();
    }

    /**
     * @return int Size of the grid
     */
    public function getSize() {
        return $this->size;
    }

    /**
     * @return Player Current player's Player object
     */
    public function getCurrentPlayer() {
        return $this->players[$this->currentPlayer];
    }

    /**
     * @return int Current player index
     */
    public function getCurrentPlayerNumber() {
        return $this->currentPlayer;
    }

    /**
     * @param $ndx int Index of which player to return
     * @return Player Returns that player's Player object
     */
    public function getPlayer($ndx) {
        return $this->players[$ndx];
    }

    /**
     * @return string Returns the name of the player who won.
     */
    public function getWinnerName() {
        if($this->gameOver) {
            return $this->players[$this->winner]->getName();
        }
        else {
            return "WRONG";
        }
    }

    /**
     * Switches whose turn it is.
     */
    public function switchTurn() {
        if($this->currentPlayer == 0) {
            $this->currentPlayer = 1;
        }
        else {
            $this->currentPlayer = 0;
        }

        $this->getPlayer(0)->resetFlags();
        $this->getPlayer(0)->getPipes()[0]->indicateLeaks();

        $this->getPlayer(1)->resetFlags();
        $this->getPlayer(1)->getPipes()[0]->indicateLeaks();
    }

    /**
     * Initializes the grid with start and end points, and null tiles.
     */
    public function setGrid()
    {
        $this->grid = array();
        $size = ($this->getSize()/2);

        $startingPointP1 = $size - 3;
        $startingPointP2 = $size + 2;

        $endPointP1 = $size - 2;
        $endPointP2 = $size + 1;

        for ($i = 0; $i < $this->getSize(); $i++) { // i is vertical position, starting from top
            array_push($this->grid, array());

            for ($j = 0; $j < $this->getSize() + 2; $j++) { // j is horizontal position, starting from left

                if ($j == 0 && ($i == $startingPointP1 || $i == $startingPointP2)) {
                    $newpipe = new Pipe($this, Pipe::PIPE_VALVE_CLOSED, $j, $i);
                    array_push($this->grid[$i], $newpipe);
                    if($i == $startingPointP1) {
                        $this->getPlayer(0)->addToPipes($newpipe);
                        $newpipe->setOwner(0);
                    }
                    else {
                        $this->getPlayer(1)->addToPipes($newpipe);
                        $newpipe->setOwner(1);
                    }
                }
                else if ($j == $this->getSize() + 1 &&
                    ($i == $endPointP1 - 1 || $i == $endPointP2 - 1)) {
                    array_push($this->grid[$i], new Pipe($this, Pipe::PIPE_GAUGE_TOP_0, $j, $i));}
                else if ($j == $this->getSize() + 1
                    && ($i == $endPointP1 || $i == $endPointP2)) {
                    //array_push($this->grid[$i], new Pipe($this, Pipe::PIPE_GAUGE_0, $j, $i));
                    $newpipe = new Pipe($this, Pipe::PIPE_GAUGE_0, $j, $i);
                    array_push($this->grid[$i], $newpipe);
                    if($i == $endPointP1) {
                        $newpipe->setOwner(0);
                    }
                    else {
                        $newpipe->setOwner(1);
                    }}
                else {
                    array_push($this->grid[$i], new Pipe($this, Pipe::PIPE_NULL, $j, $i));}
            }
        }
    }

    /**
     * @return array[array[Pipe]] Array of arrays of Pipe objects representing the playing field
     */
    public function getGrid() {
        return $this->grid;
    }

    /**
     * Place a tile in the grid from the selected option
     * @param $x int X position to place the tile in
     * @param $y int Y position to place the tile in
     * @param $index int Index out of the chooseable pipe options for which pipe to place
     */
    public function placePipe($x, $y, $index) {

        $canPlace = false;

        $open = $this->pipeOptions[intval($index)]->open();
        foreach(array("N", "W", "S", "E") as $direction) {
            if($open[$direction]) {
                $n = $this->grid[$y][$x]->neighbor($direction);

                if($n === null) {

                }
                else {
                    if ($direction == "N" && $n->open()["S"] && $n->getOwner() == $this->currentPlayer) {
                        $canPlace = true;
                    } elseif ($direction == "E" && $n->open()["W"] && $n->getOwner() == $this->currentPlayer) {
                        $canPlace = true;
                    } elseif ($direction == "S" && $n->open()["N"] && $n->getOwner() == $this->currentPlayer) {
                        $canPlace = true;
                    } elseif ($direction == "W" && $n->open()["E"] && $n->getOwner() == $this->currentPlayer) {
                        $canPlace = true;
                    }
                }
            }
        }

        if($canPlace) {
            $this->grid[$y][$x] = $this->pipeOptions[intval($index)];

            $this->grid[$y][$x]->setXY($x, $y);
            $this->grid[$y][$x]->setOwner($this->currentPlayer);

            $this->getCurrentPlayer()->addToPipes($this->pipeOptions[intval($index)]);
            // Remove from pipeOptions
            $this->discardPipe($index);
        }
    }

    /**
     * Rotate the selected tile
     * @param $index int Index of the selected tile
     */
    public function rotate($index) {
        $this->pipeOptions[intval($index)]->rotate();
    }

    /**
     * Discard the selected tile and pull a new one in, ending the player's turn
     * @param $index int Index of the selected tile
     */
    public function discardPipe($index) {
        unset($this->pipeOptions[intval($index)]);
        $this->pipeOptions = array_values($this->pipeOptions);

        array_push($this->pipeOptions, $this->getRandomPipe());

        $this->switchTurn();
    }

    /**
     * Open the valve, and check who wins.
     */
    public function openValve() {
        $this->gameOver = true;

        $this->getCurrentPlayer()->resetFlags();
        $leaks = $this->getCurrentPlayer()->getPipes()[0]->indicateLeaks();

        $this->getCurrentPlayer()->getPipes()[0]->setPipe(Pipe::PIPE_VALVE_OPEN);

        if(!$leaks && $this->foundEnds[$this->currentPlayer]) {
            $this->winner = $this->currentPlayer;
        }
        else {
            if($this->currentPlayer == 0) {
                $this->winner = 1;
            }
            else {
                $this->winner = 0;
            }
        }
    }

    /**
     * Make the current player lose, because quitters never win.
     */
    public function giveUp() {
        $this->gameOver = true;

        if($this->currentPlayer == 0) {
            $this->winner = 1;
        }
        else {
            $this->winner = 0;
        }
    }

    /**
     * Indicate that a player is connected to his valve
     * @param $player int Index of the player who found the end of his pipe.
     */
    public function foundEnd($player) {
        $this->foundEnds[$player] = true;
    }

    /**
     * @return array Array of Pipe objects for the options the players can pick from
     */
    public function getPipeOption(){
        return $this->pipeOptions;
    }

    /**
     * @return Pipe Returns a clone of a random pipe object
     */
    public function getRandomPipe() {
        return clone $this->pipes[rand(0, count($this->pipes)-1)];
    }

    /**
     * @return bool Returns a bool representing whether the game is over
     */
    public function getGameOver() {
        return $this->gameOver;
    }


    private $grid; // Array of arrays representing the grid
    private $pipeOptions; // Array of available pipe options to place
    private $pipes; // Array of placeable pipe types

    private $winner; // Index of the winning player

    private $currentPlayer; // Index of whose turn it is
    private $players; // Array of two players

    private $size = 6; // Size of game board
    private $seed; // Seed for random generation

    private $gameOver; // Bool for whether the game is over
    private $foundEnds; // Array of bools for whether the end pipe for each player is connected



    // Player class to hold name and list of pipes owned by the player
}