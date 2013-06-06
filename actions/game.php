<?php

require_once('models/game.php');

$game_id = intval($_REQUEST['game_id']);
$player = $GLOBALS['USER_ID'];
$game = Game::fetch($game_id);

// look to see if the game needs a second player

if (! $game->player2 and $game->player1 != $player) {
  $game->player2 = $player;
  $game->save();
  $justJoined = true;
} else {
  $justJoined = false;
}

// get the game state, and add a few bits of information

$state = $game->getState();
$state['justJoined'] = $justJoined;
if ($game->whoseMove() == $player) {
  $state['myMove'] = true;
} else {
  $state['myMove'] = false;
}

// see if the game is finished
$winner = $game->winner();
if ($winner and $winner == $player) {
  $state['winner'] = 'you';
} else if ($winner) {
  $state['winner'] = 'them';
} else {
  $state['winner'] = null;
}

if ($player != $game->player1 and $player != $game->player2) {
  $state['error'] = "This ain't your game, but you are welcome to watch.";
  $state['watching'] = true;
} else {
  $state['watching'] = false;
}

// send back json or html representation of the game

if (array_key_exists('format', $_REQUEST) and $_REQUEST['format'] == 'json') {
  header("Content-type: application/json");
  echo json_encode($state);
} else {
  include('views/game.html');
}

?>
