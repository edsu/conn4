<?php

switch ($_REQUEST['action']) {
case 'new_game':
  include('actions/new_game.php');
  break;
case 'game':
  include('actions/game.php');
  break;
}

?>
