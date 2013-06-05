<?php

class Game {
  public $id = null;
  public $turn = 1;
  public $player1 = null;
  public $player2 = null;
  private $board1 = 0;
  private $board2 = 0;

  public function __construct($player1=null) {
    if ($player1) {
      $this->player1 = $player1;
    }
  }

  public function move($row, $col) {
    if ($this->occupied($row, $col)) {
      throw new InvalidMove("slot already occupied");
    }
    if ($row > 0 and ! $this->occupied($row - 1, $col)) {
      throw new InvalidMove("slot below is unoccupied");
    }
    $bit = $this->boardBit($row, $col);
    if ($this->turn == 1) {
      $this->board1 = $this->board1 | 1 << $bit;
      $this->turn = 2;
    } else {
      $this->board2 = $this->board2 | 1 << $bit;
      $this->turn = 1;
    }
  }

  public function pass() {
    if ($this->turn == 1) 
      $this->turn = 2;
    else
      $this->turn = 1;
  }

  public function winner() {
    if ($this->_winner($this->board1)) 
      return $this->player1;
    return null;
  }

  private function _winner($board) {
    # magic math from: http://stackoverflow.com/a/7053051/324921
    $y = $board & ($board >> 6);
    if ($y & ($y >> 2 * 6))
      return true;
    $y = $board & ($board >> 7);
    if ($y & ($y >> 2 * 7))
      return true;
    $y = $board & ($board >> 8);
    if ($y & ($y >> 2 * 8))
      return true;
    $y = $board & ($board >> 1);
    if ($y & ($y >> 2))
      return true;
    return false;
  }

  public function getState() {
    $state = array("turn" => $this->turn, "board" => array());
    for ($col=0; $col<7; $col++) {
      $column = array();
      for ($row=0; $row<6; $row++) {
        $bit = $this->boardBit($row, $col);
        $x = 1 << $bit;
        if ($this->board1 & (1 << $bit)) {
          array_push($column, 1);
        }
        if ($this->board2 & (1 << $bit)) {
          array_push($column, 2);
        }
      }
      array_push($state['board'], $column);
    }
    return $state;
  }

  public function boardBit($row, $col) {
    return $col * 6 + $col + $row ;
  }

  public function save() {
    if ($this->id) {
      $sql = "UPDATE game SET turn=:turn, player1=:player1, player2=:player2, board1=:board1, board2=:board2 WHERE id=:id";
    } else {
      $sql = "INSERT INTO game VALUES(:id, :turn, :player1, :player2, :board1, :board2)";
    }
    $this->db($sql);
  }

  public static function fetch($id) {
    $dbh = $GLOBALS['DB'];
    $sth = $dbh->prepare("SELECT * FROM game WHERE id = :id");
    $sth->bindValue(':id', $id);
    $sth->setFetchMode(PDO::FETCH_CLASS, "Game");
    $sth->execute();
    $game = $sth->fetch();
    return $game;
  }

  public static function dbDrop() {
    $GLOBALS['DB']->exec("DROP TABLE IF EXISTS game;");
  }

  public static function dbSetup() {
    $sql = "
      CREATE TABLE IF NOT EXISTS game (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        turn INTEGER,
        player1 VARCHAR(255),
        player2 VARCHAR(255),
        board1 INTEGER,
        board2 INTEGER
      );
    ";
    $GLOBALS['DB']->exec($sql);
  }

  private function db($sql) {
    $dbh = $GLOBALS['DB'];
    $sth = $dbh->prepare($sql);
    $sth->bindValue(':id', $this->id);
    $sth->bindValue(':player1', $this->player1);
    $sth->bindValue(':player2', $this->player2);
    $sth->bindValue(':board1', $this->board1);
    $sth->bindValue(':board2', $this->board2);
    $sth->bindValue(':turn', $this->turn);
    $sth->execute();
    if (! $this->id) {
      $this->id = $dbh->lastInsertId();
    }
  }

  private function occupied($row, $col) {
    $bit = $this->boardBit($row, $col);
    if ($this->board1 & (1 << $bit))
      return true;
    else if ($this->board2 & (1 << $bit)) 
      return true;
    else
      return false;
  }

}

class InvalidMove extends Exception {}

?>

