<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2/29/2016
 * Time: 2:26 AM
 */

namespace Steampunked;


class Player {

    /**
     * Player constructor.
     * @param $name string The player's name
     */
    public function __construct($name) {
        $this->name = $name;
        $this->pipes = array();
    }

    /**
     * @return string The Player's name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return array The array of pipes owned by the Player
     */
    public function getPipes() {
        return $this->pipes;
    }

    /**
     * @param $pipe Pipe The Pipe object to add
     */
    public function addToPipes($pipe) {
        array_push($this->pipes, $pipe);
    }

    /**
     * Resets the flags of the Player's Pipes
     */
    public function resetFlags() {
        foreach($this->pipes as $pip) {
            $pip->setFlag(false);
        }
    }

    private $name;
    private $pipes;
}