<?php

require_once('models/game.php');

$game_id = $_REQUEST['game_id'];
$game = Game::fetch($game_id);

if (array_key_exists('format', $_REQUEST) and $_REQUEST['format'] == 'json') {
  header("Content-type: application/json");
  echo json_encode($game->getState());
} else {
  include('views/game.html');
}

?>
