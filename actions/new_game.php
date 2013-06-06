<?php

require_once('models/game.php');

$game = new Game();
$game->save();

header("Location: $game->id");

?>
