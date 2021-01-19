<?php
/**
 * Created by Ankit Luthra
 * Time: 7:49 PM
 */

namespace Steampunked;

class SteampunkedView
{

    /**
     * Constructor
     * @param Steampunked $steampunked The Steampunked object
     */
    public function __construct(Steampunked $steampunked)
    {
        $this->steampunked = $steampunked;
    }

    /**
     * Create the HTML we present
     * @return string HTML to present
     */
    public function present()
    {
        $html = '<figure><img src="images/title.png" alt="Steampunked Title"></figure>';

        //Check if game is over or not
        $isOver = $this->steampunked->getGameOver();

        // Get the current player id
        $currentPlayer = $this->steampunked->getCurrentPlayer();

        // Get the current player name
        $currentPlayerName = $currentPlayer->getName();

        // Get the current player number
        $currentPlayerNumber = $this->steampunked->getCurrentPlayerNumber();

        // Creates the game grid
        $html .= $this->getGridView($currentPlayerNumber);

        //Set the message: who's turn is it or who wins.
        $html .= $this->setMessage($isOver,$currentPlayerName);

        // Shows the buttons if the game isn't over
        if(!$isOver){
            $html .= $this->getButtons();
        }

        $html .= '</form>';

        return $html;
    }

    /**
     * Gets the action buttons
     * @return string the html
     */
    private function getButtons(){
        $html = '<div class="align-center">';
        //$html .= '<form action="game-post.php" method="post">';

        // Gets the pipe options and radio buttons
        $html .= $this->getPipePieces();

        // Create the buttons
        $html .=
            '<input class="buttons" type="submit" name="rotate" value="Rotate">
            <input class="buttons" type="submit" name="discard" value="Discard">
            <input class="buttons" type="submit" name="openvalve" value="Open Valve">
            <input class="buttons" type="submit" name="giveup" value="Give Up">';

        $html .= '</div>';

        return $html;
    }

    /**
     * Displays the different pipes and buttons
     * @return string the html
     */
    private function getPipePieces(){
        $html = '';

        // Get the different pipe options
        $pipeOptions = $this->steampunked->getPipeOption();

        // Loop through the 5 pipes
        for($i = 0; $i < count($pipeOptions); $i++){
            // Get the pipe type
            $pipe = $pipeOptions[$i]->getPipe();

            // Display the pipe and radio button
            $html .= '<div class="pipeOption">';
            $html .= '<label for="pipe'.$i.'">
                        <img src="images/'.$pipe.'.png" alt="pipes" />
                      </label>
                  <input type="radio" name="index" id="pipe'.$i.'" value="'.$i.'">';
            $html .= '</div>';
        }

        $html .= '<br>';

        return $html;
    }


    /**
     * @param $isOver
     * @param $currentPlayerName
     * @return string
     */
    private function setMessage($isOver, $currentPlayerName) {
        $message = '';

        if (!$isOver) {
            $message = $message . '<div class="message"><p>' . $currentPlayerName . " it is your turn!" . '</p></div>';
        } else {
            $winnerName = $this->steampunked->getWinnerName();
            $message = $message . '<div class="message"><p>' . $winnerName . " wins the game!".'</p></div>';
            $message = $message . '<input type="submit" name="reset" value="Reset">';
        }

        return $message;
    }

    /**
     * Creates the game grid html
     * @return string the html
     */
    private function getGridView($currentPlayerNumber){
        // Getter for grid
        $grid = $this->steampunked->getGrid();
        // Count for number of rows
        $rows = $this->steampunked->getSize();
        // Count for number of columns
        $columns = $this->steampunked->getSize() + 2;

        // Form for grid
        $html = '<form class="align-center" method="post" action="game-post.php">';

        $html .= '<div class="game">';

        // Loop that traverse through each row
        for($i = 0; $i < $rows; $i++){

            $html .= '<div class="row">';

            // Loop that traverse through each column
            for($j = 0; $j < $columns; $j++){
                // Get the pipe from pipe.php
                $pipe = $grid[$i][$j]->getPipe();

                $owner = -1;

                foreach(array("N", "W", "S", "E") as $direction) {
                    $n = $grid[$i][$j]->neighbor($direction);
                    if(!($n === null)) {
                        if ($direction == "N" && $n->open()["S"]) {
                            $owner = $n->getOwner();
                        } elseif ($direction == "E" && $n->open()["W"]) {
                            $owner = $n->getOwner();
                        } elseif ($direction == "S" && $n->open()["N"]) {
                            $owner = $n->getOwner();
                        } elseif ($direction == "W" && $n->open()["E"]) {
                            $owner = $n->getOwner();
                        }
                    }
                }

                // Div so that non leak images can't be clicked
                if($pipe != Pipe::PIPE_LEAK_E && $pipe != Pipe::PIPE_LEAK_W &&
                    $pipe != Pipe::PIPE_LEAK_N && $pipe != Pipe::PIPE_LEAK_S){
                    $html .= '<div class="cell '.$pipe.'">';
                }

                else{
                    if($currentPlayerNumber == $owner) {
                        $html .= '<div class="cell">';
                        $html .= '<input class="' . $pipe . ' hover-leak"';
                        $html .= ' type="submit" name="leak" value="'.$i.','.$j.'">';
                    }
                    else{
                        $html .= '<div class="cell '.$pipe.'">';
                    }
                }

                $html .= '</div>';
            }
            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;
    }

    private $steampunked;   // The Steampunked object
}
