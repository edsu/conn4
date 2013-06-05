<?php

require_once('models/game.php');

$game = new Game($GLOBALS['USER_ID']);
$game->save();

header("Location: $game->id");

?>
