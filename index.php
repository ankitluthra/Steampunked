<?php

require __DIR__ . '/lib/steampunked.inc.php';
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Steampunked Welcome</title>
    <link href="reset.css" type="text/css" rel="stylesheet" />
    <link href="game.css" type="text/css" rel="stylesheet" />
    <link href="welcome.css" type="text/css" rel="stylesheet" />
</head>
<body>
  <figure><img src="images/title.png" alt="Steampunked Title"></figure>
  <figure class="align-center"><img src="images/start.png" alt="Steampunked Demo"></figure>
  <h1 class="align-center">Instructions</h1>
  <div>
    <p>
      The objective of the game is to connect your pipe to the end pipe and open
      the valve. Each player will take turns consisting of one of the following
      options:
    </p>
      <ul>
        <li>1. Place a Tile</li>
        <li>2. Rotate a Tile</li>
        <li>3. Discard a Tile</li>
        <li>4. Open Valve</li>
        <li>5. Give Up</li>
      </ul>
      <br/>

      <p>
      Place Tile: Hovering over the grid shows green patches where pipes can be
      placed, selecting a pipe and placing it will move it to the grid. This
      ends your turn.
      <br/>
      <br/>

      Rotate Tile: Selecting a pipe from the available options and pressing the
      Rotate button will rotate the tile clockwise to get it into any position
      you'd like. This does not end your turn.
      <br/>
      <br/>

      Discard Tile: Selecting a pipe from the available options and selecting
      Discard will remove the pipe from the available options and end your turn.
      <br/>
      <br/>

      Open Valve: Opening the valve will check your pipes to see if it is
      connected to the end pipe and without leaks. If you have done so, you win
      the game. Otherwise, you lose.
      <br/>
      <br/>
      
      Give Up: You lose.
      <br/>
      <br/>
      <br/>

    </p>
  </div>


  <form class="align-center" method="post" action="index-post.php">
    <label for="player1name">Player 1 name:</label>
    <input type="text" name="player1name" id="player1name">
    <br/>
    <label for="player2name">Player 2 name:</label>
    <input type="text" name="player2name" id="player2name">
    <br/>
    <label>Grid size:</label>
    <br/>
    <input type="radio" name="gridsize" value="6">6 x 6 <br/>
    <input type="radio" name="gridsize" value="10">10 x 10 <br/>
    <input type="radio" name="gridsize" value="20">20 x 20 <br/>
    <br/>
    <input type="submit" value="Start Game">
  </form>
</body>
</html>
