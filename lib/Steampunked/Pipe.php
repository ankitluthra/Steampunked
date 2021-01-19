<?php

/**
 * Created by PhpStorm.
 * User: Ankit
 * Date: 2/9/13
 * Time: 7:59 PM
 */

namespace Steampunked;


class Pipe
{
    const PIPE_CAP_E = "cap-e";
    const PIPE_CAP_N = "cap-n";
    const PIPE_CAP_S = "cap-s";
    const PIPE_CAP_W = "cap-w";

    const PIPE_GAUGE_0 = "gauge-0";
    const PIPE_GAUGE_190 = "gauge-190";
    const PIPE_GAUGE_TOP_0 = "gauge-top-0";
    const PIPE_GAUGE_TOP_190 = "gauge-top-190";

    const PIPE_LEAK_E = "leak-e";
    const PIPE_LEAK_N = "leak-n";
    const PIPE_LEAK_S = "leak-s";
    const PIPE_LEAK_W = "leak-w";

    const PIPE_NINETY_ES = "ninety-es";
    const PIPE_NINETY_NE = "ninety-ne";
    const PIPE_NINETY_SW = "ninety-sw";
    const PIPE_NINETY_WN = "ninety-wn";

    const PIPE_STRAIGHT_H = "straight-h";
    const PIPE_STRAIGHT_V = "straight-v";

    const PIPE_TEE_ESW = "tee-esw";
    const PIPE_TEE_NES = "tee-nes";
    const PIPE_TEE_SWN = "tee-swn";
    const PIPE_TEE_WNE = "tee-wne";

    const PIPE_VALVE_CLOSED = "valve-closed";
    const PIPE_VALVE_OPEN  = "valve-open";

    const PIPE_NULL = "null";

    /**
     * Constructor
     * @param Steampunked $game
     * @param string $pipe
     * @param int $x
     * @param int $y
     */
    public function __construct($game, $pipe, $x, $y) {
        $this->game = $game;
        $this->pipe = $pipe;
        $this->x = $x;
        $this->y = $y;
        $this->flag = false;
    }

    public function getPipe() {
        return $this->pipe;
    }

    public function setPipe($pipe) {
        $this->pipe = $pipe;
    }

    public function getFlag() {
        return $this->flag;
    }

    public function setFlag($flag) {
        $this->flag = $flag;
    }

    public function getXY() {
        return array($this->x, $this->y);
    }

    public function setXY($x, $y) {
        $this->x = $x;
        $this->y = $y;
    }

    public function getOwner() {
        return $this->owner;
    }

    public function setOwner($owner) {
        $this->owner = $owner;
    }


    public function rotate() {
        switch($this->pipe) {
            case(Pipe::PIPE_CAP_E):
                $this->pipe = Pipe::PIPE_CAP_S;

                break;
            case(Pipe::PIPE_CAP_N):
                $this->pipe = Pipe::PIPE_CAP_E;

                break;
            case(Pipe::PIPE_CAP_S):
                $this->pipe = Pipe::PIPE_CAP_W;

                break;
            case(Pipe::PIPE_CAP_W):
                $this->pipe = Pipe::PIPE_CAP_N;

                break;

            case(Pipe::PIPE_NINETY_ES):
                $this->pipe = Pipe::PIPE_NINETY_SW;

                break;
            case(Pipe::PIPE_NINETY_SW):
                $this->pipe = Pipe::PIPE_NINETY_WN;

                break;
            case(Pipe::PIPE_NINETY_WN):
                $this->pipe = Pipe::PIPE_NINETY_NE;

                break;
            case(Pipe::PIPE_NINETY_NE):
                $this->pipe = Pipe::PIPE_NINETY_ES;

                break;

            case(Pipe::PIPE_STRAIGHT_H):
                $this->pipe = Pipe::PIPE_STRAIGHT_V;

                break;
            case(Pipe::PIPE_STRAIGHT_V):
                $this->pipe = Pipe::PIPE_STRAIGHT_H;

                break;

            case(Pipe::PIPE_TEE_ESW):
                $this->pipe = Pipe::PIPE_TEE_SWN;

                break;
            case(Pipe::PIPE_TEE_NES):
                $this->pipe = Pipe::PIPE_TEE_ESW;

                break;
            case(Pipe::PIPE_TEE_WNE):
                $this->pipe = Pipe::PIPE_TEE_NES;

                break;
            case(Pipe::PIPE_TEE_SWN):
                $this->pipe = Pipe::PIPE_TEE_WNE;

                break;

            default:
                break;
        }
    }

    public function indicateLeaks() {

        if($this->flag) {
            // Already visited
            return false;
        }

        $foundLeak = false;
        $this->flag = true;

        $open = $this->open();
        foreach(array("N", "W", "S", "E") as $direction) {
            // Are we open in this direction?
            if($open[$direction]) {
                $n = $this->neighbor($direction);
                if($n === null) {
                    // We have a leak in this direction...
                    $foundLeak = true;

                }
                else if($n->getPipe() == Pipe::PIPE_NULL){
                    switch($direction) {
                        case "N":
                            $n->setPipe(Pipe::PIPE_LEAK_S);
                            $foundLeak = true;
                            break;
                        case "S":
                            $n->setPipe(Pipe::PIPE_LEAK_N);
                            $foundLeak = true;
                            break;
                        case "E":
                            $n->setPipe(Pipe::PIPE_LEAK_W);
                            $foundLeak = true;
                            break;
                        case "W":
                            $n->setPipe(Pipe::PIPE_LEAK_E);
                            $foundLeak = true;
                            break;


                    }
                }
                else if($direction == "E" && $n->getPipe() == Pipe::PIPE_GAUGE_0) {
                    $this->game->foundEnd($this->owner);
                }
                else {
                    // Recurse
                    $connected = false;
                    if ($direction == "N" && $n->open()["S"]) {
                        $connected = true;
                    } elseif ($direction == "E" && $n->open()["W"]) {
                        $connected = true;
                    } elseif ($direction == "S" && $n->open()["N"]) {
                        $connected = true;
                    } elseif ($direction == "W" && $n->open()["E"]) {
                        $connected = true;
                    }

                    if(!$connected) {
                        $foundLeak = true;
                    }
                    else if(!$foundLeak) {
                        $foundLeak = $n->indicateLeaks();
                    }
                    else {
                        $n->indicateLeaks();
                    }
                }

            }
        }

        return $foundLeak;
    }

    public function open() {
        $open = array("N" => false, "E" => false, "S" => false, "W" => false);
        switch($this->pipe) {
            case(Pipe::PIPE_CAP_E):
                $open["E"] = true;

                break;
            case(Pipe::PIPE_CAP_N):
                $open["N"] = true;

                break;
            case(Pipe::PIPE_CAP_S):
                $open["S"] = true;

                break;
            case(Pipe::PIPE_CAP_W):
                $open["W"] = true;

                break;

            case(Pipe::PIPE_GAUGE_0):
                $open["W"] = true;

                break;
            case(Pipe::PIPE_GAUGE_190):
                $open["W"] = true;

                break;
            case(Pipe::PIPE_GAUGE_TOP_0):

                break;
            case(Pipe::PIPE_GAUGE_TOP_190):

                break;

            case(Pipe::PIPE_NINETY_ES):
                $open["E"] = true;
                $open["S"] = true;

                break;
            case(Pipe::PIPE_NINETY_SW):
                $open["W"] = true;
                $open["S"] = true;

                break;
            case(Pipe::PIPE_NINETY_WN):
                $open["W"] = true;
                $open["N"] = true;

                break;
            case(Pipe::PIPE_NINETY_NE):
                $open["E"] = true;
                $open["N"] = true;

                break;

            case(Pipe::PIPE_STRAIGHT_H):
                $open["E"] = true;
                $open["W"] = true;

                break;
            case(Pipe::PIPE_STRAIGHT_V):
                $open["N"] = true;
                $open["S"] = true;

                break;

            case(Pipe::PIPE_TEE_ESW):
                $open["E"] = true;
                $open["S"] = true;
                $open["W"] = true;

                break;
            case(Pipe::PIPE_TEE_NES):
                $open["E"] = true;
                $open["S"] = true;
                $open["N"] = true;

                break;
            case(Pipe::PIPE_TEE_WNE):
                $open["E"] = true;
                $open["N"] = true;
                $open["W"] = true;

                break;
            case(Pipe::PIPE_TEE_SWN):
                $open["N"] = true;
                $open["S"] = true;
                $open["W"] = true;

                break;

            case(Pipe::PIPE_VALVE_CLOSED):
                $open["E"] = true;

                break;
            case(Pipe::PIPE_VALVE_OPEN):
                $open["E"] = true;

                break;
            default:
                break;
        }

        return $open;
    }

    public function neighbor($direction) {
        if($direction == "N") {
            if($this->y > 0) {
                if($this->game->getGrid()[$this->y - 1][$this->x]->pipe == Pipe::PIPE_NULL) {
                    return $this->game->getGrid()[$this->y - 1][$this->x]; //
                }
                else {
                    return $this->game->getGrid()[$this->y - 1][$this->x];
                }
            }
            else {
                return null;
            }
        }
        else if($direction == "E") {
            if($this->x < $this->game->getSize() + 1) {
                if($this->game->getGrid()[$this->y][$this->x + 1]->pipe == Pipe::PIPE_NULL) {
                    return $this->game->getGrid()[$this->y][$this->x + 1]; //
                }
                else {
                    return $this->game->getGrid()[$this->y][$this->x + 1];
                }
            }
            else {
                return null;
            }
        }
        else if($direction == "S") {
            if($this->y < $this->game->getSize() - 1) {
                if($this->game->getGrid()[$this->y + 1][$this->x]->pipe == Pipe::PIPE_NULL) {
                    return $this->game->getGrid()[$this->y + 1][$this->x]; //
                }
                else {
                    return $this->game->getGrid()[$this->y + 1][$this->x];
                }
            }
            else {
                return null;
            }
        }
        else if($direction == "W") {
            if($this->x > 0) {
                if($this->game->getGrid()[$this->y][$this->x - 1]->pipe == Pipe::PIPE_NULL) {
                    return $this->game->getGrid()[$this->y][$this->x - 1]; //
                }
                else {
                    return $this->game->getGrid()[$this->y][$this->x - 1];
                }
            }
            else {
                return null;
            }
        }
    }



    private $game;
    private $flag;
    private $pipe;
    private $owner;
    private $x;
    private $y;
}