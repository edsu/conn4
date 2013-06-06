<?php

require_once('models/game.php');

$game_id = intval($_REQUEST['game_id']);
$player = $GLOBALS['USER_ID'];

$game = Game::fetch($game_id);

if (! $game->player2 and $game->player1 != $player) {
  $game->player2 = $player;
  $game->save();
}

if (array_key_exists('format', $_REQUEST) and $_REQUEST['format'] == 'json') {
  header("Content-type: application/json");
  echo json_encode($game->getState());
} else {
  include('views/game.html');
}

?>
