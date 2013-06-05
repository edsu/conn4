<?php

$GLOBALS['DB'] = new PDO("sqlite:test.db");

require_once "PHPUnit/Framework/TestCase.php";
require_once "models/game.php";

class GameTest extends PHPUnit_Framework_TestCase {

  // to avoid errors about serializing PDO instance when running tests
  protected $backupGlobalsBlacklist = array('DB');

  protected function setUp() {
    Game::bootstrap();
  }

  public function testCreateGame() {
    $player1 = "1234";
    $player2 = "5678";

    $game = new Game();
    $game->player1 = "1234";
    $this->assertEquals(null, $game->id);
    $game->save();
    $this->assertGreaterThan(0, $game->id);
    $gameId = $game->id;

    $game = Game::fetch($gameId);
    $this->assertTrue(get_class($game) == 'Game');
    $this->assertEquals($player1, $game->player1);
    $this->assertEquals($gameId, $game->id);

    $game->player2 = $player2;
    $game->save();
    $game = Game::fetch($gameId);
    $this->assertEquals($player2, $game->player2);
  }

  function testBoardBit() {
    $game = new Game();
    $this->assertEquals(30, $game->boardBit(2, 4));
    $this->assertEquals(47, $game->boardBit(5, 6));
    $this->assertEquals(0, $game->boardBit(0, 0));
    $this->assertEquals(1, $game->boardBit(1, 0));
    $this->assertEquals(17, $game->boardBit(3, 2));
  }

  function testMove() {
    $game = new Game();
    $player1 = "123";
    $player2 = "456";
    $game->player1 = $player1;
    $game->player2 = $player2;
    $game->move($player1, 0, 1);
    $state = $game->getState();
    $expected = '{"turn":2, "board": [[],[1],[],[],[],[],[]]}';
    $this->assertEquals(json_decode($expected), $state);
  }

}

?>
