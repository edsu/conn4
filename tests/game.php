<?php

$GLOBALS['DB'] = new PDO("sqlite:test.db");

require_once "PHPUnit/Framework/TestCase.php";
require_once "models/game.php";

class GameTest extends PHPUnit_Framework_TestCase {

  // to avoid errors about serializing PDO instance when running tests
  protected $backupGlobalsBlacklist = array('DB');

  protected function setUp() {
    Game::dbDrop();
    Game::dbSetup();
  }

  public function testCreateGame() {
    $game = new Game("1234");
    $this->assertEquals("1234", $game->player1);
    $this->assertEquals(null, $game->player2);
    $this->assertEquals(null, $game->id);
    $game->save();

    # saving should give the game an id
    $this->assertGreaterThan(0, $game->id);
    $gameId = $game->id;

    # should be able to fetch the game from the db using its id
    $game = Game::fetch($gameId);
    $this->assertTrue(get_class($game) == 'Game');
    $this->assertEquals("1234", $game->player1);
    $this->assertEquals($gameId, $game->id);

    # should be able to add the 2nd player and persist it
    $game->player2 = "5678";
    $game->save();
    $game = Game::fetch($gameId);
    $this->assertEquals("5678", $game->player2);
  }

  function testMove() {
    $game = new Game("1234");
    $state = $game->getState("1234");
    $this->assertEquals($state['status'], 'share');

    $state = $game->getState("5678");
    $this->assertEquals($state['status'], 'join');

    $game->player2 = "5678";
    $expected = ["status" => "play", "board" => [[],[],[],[],[],[],[]]];
    $this->assertEquals($expected, $game->getState("1234"));

    $game->move(0, 1);
    $expected = ["status" => "wait", "board" => [[],[1],[],[],[],[],[]]];
    $this->assertEquals($expected, $game->getState("1234"));

    $game->move(1, 1);
    $expected = ["status" => "play", "board" => [[],[1,2],[],[],[],[],[]]];
    $this->assertEquals($expected, $game->getState("1234"));

    $game->move(0, 0);
    $expected = ["status" => "wait", "board" => [[1],[1,2],[],[],[],[],[]]];
    $this->assertEquals($expected, $game->getState("1234"));
  }

  function testDuplicateMove() {
    $this->setExpectedException('InvalidMove');
    $game = new Game("1234");
    $game->player2 = "5678";
    $game->move(0, 0);
    $game->move(0, 0);
  }

  function testMissingSlotBelow() {
    $this->setExpectedException('InvalidMove');
    $game = new Game("1234");
    $game->player2 = "5678";
    $game->move(1, 0);
  }

  function testVerticalWinner() {
    $game = new Game("1234");
    $game->player2 = "5678";
    $game->move(0, 0);
    $game->pass();
    $this->assertEquals($game->winner(), null);
    $game->move(1, 0);
    $game->pass();
    $this->assertEquals($game->winner(), null);
    $game->move(2, 0);
    $game->pass();
    $this->assertEquals($game->winner(), null);
    $game->move(3, 0);
    $this->assertEquals($game->winner(), "1234");
    $this->assertEquals("won", $game->getStatus("1234"));
    $this->assertEquals("lost", $game->getStatus("4678"));
  }

  function testHorizontalWinner() {
    $game = new Game("1234");
    $game->player2 = "5678";
    $game->move(0, 0);
    $game->pass();
    $this->assertEquals($game->winner(), null);
    $game->move(0, 1);
    $game->pass();
    $this->assertEquals($game->winner(), null);
    $game->move(0, 2);
    $game->pass();
    $this->assertEquals($game->winner(), null);
    $game->move(0, 3);
    $this->assertEquals($game->winner(), "1234");
  }

  function testDiagonalWinner() {
    $game = new Game("1234");
    $game->player2 = "5678";
    $game->move(0, 0); #p1
    $game->move(0, 1); #p2
    $game->move(1, 1); #p1
    $game->move(0, 2); #p2
    $game->move(1, 2); #p1
    $game->pass();     #p2
    $game->move(2, 2); #p1
    $game->move(0, 3); #p2
    $game->move(1, 3); #p1
    $game->move(2, 3); #p2
    $game->move(3, 3); #p1
    $this->assertEquals($game->winner(), "1234");
  }

  function testBoardBit() {
    $game = new Game();
    $this->assertEquals(30, $game->boardBit(2, 4));
    $this->assertEquals(47, $game->boardBit(5, 6));
    $this->assertEquals(0, $game->boardBit(0, 0));
    $this->assertEquals(1, $game->boardBit(1, 0));
    $this->assertEquals(17, $game->boardBit(3, 2));
  }

}

?>
