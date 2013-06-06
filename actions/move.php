<?php

$game_id = $_REQUEST['game_id'];
$row = $_REQUEST['row'];
$column = $_REQUEST['column'];
$player = $GLOBAL['USER_ID'];

$game = Game::fetch($game_id);

try {
  $game->move($row, $column);
  $game->save();
  $state = $game->getState();
  header("Content-type: application/json");
  echo json_encode($state);
} catch (Exception $e) {
  $state = $game->getState();
  $state['error'] = $e->getMessage();
  header("Content-type: application/json");
  echo json_encode($state);
}

?>
