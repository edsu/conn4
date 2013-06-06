<?php

require_once('config.php');
require_once('models/game.php');

Game::dbSetup();

switch ($_REQUEST['action']) {
case 'new_game':
  include('actions/new_game.php');
  break;
case 'game':
  include('actions/game.php');
  break;
case 'move':
  include('actions/move.php');
  break;
}

?>
