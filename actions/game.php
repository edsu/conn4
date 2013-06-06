<?php

require_once('models/game.php');

$game_id = intval($_REQUEST['game_id']);
$player = $GLOBALS['USER_ID'];
$game = Game::fetch($game_id);
$state = $game->getState($player);

if (array_key_exists('format', $_REQUEST) and $_REQUEST['format'] == 'json') {
  header("Content-type: application/json");
  echo json_encode($state);
} else {
  include('views/game.html');
}

?>
