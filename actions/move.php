<?php

$game_id = $_REQUEST['game_id'];
$row = $_REQUEST['row'];
$column = $_REQUEST['column'];
$player = $GLOBALS['USER_ID'];

$game = Game::fetch($game_id);

if (! $game->player1) {
  $game->player1 = $player;
} else if (! $game->player2) {
  $game->player2 = $player;
}

try {
  $game->move($row, $column);
  $game->save();
  $state = $game->getState($player);
  header("Content-type: application/json");
  echo json_encode($state);
} catch (Exception $e) {
  $state = $game->getState($player);
  $state['error'] = $e->getMessage();
  header("Content-type: application/json");
  echo json_encode($state);
}

?>
