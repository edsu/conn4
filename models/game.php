<?php

class Game {
  public $id = null;
  public $player1 = null;
  public $player2 = null;
  private $board1 = 0;
  private $board2 = 0;

  public function move($player, $row, $col) {
    $bit = $this->boardBit($row, $col);
    $board = $this->board1 ? $player == $this.player1 : $this->board2;
    if ($player == $this->player1) {
      $this->board1 =  $this->board1 | 1 << $bit;
    } else if ($player == $this->player2) {
      $this->board2 = $this->board2 >> $bit;
    }
  }

  public function getState() {
    $state = array("turn" => 2, "board" => array());
    for ($col=0; $col<7; $col++) {
      $column = array();
      for ($row=0; $row<6; $row++) {
        $bit = $this->boardBit($row, $col);
        $x = 1 << $bit;
        echo "\nrow=$row col=$col board1=$this->board1 bis=$bit x=$x ";

        if ($this->board1 & (1 << $bit)) {
          array_push($column, 1);
          echo "hit";
        } else if ($this->board2 & (1 << $bit)) {
          array_push($column, 2);
          echo "hit";
        }
      }
      array_push($state['board'], $column);
    }
    return $state;
    return json_decode('{"turn": 2, "board": [[1],[],[],[],[],[],[]]}');
  }

  public function boardBit($row, $col) {
    return $col * 6 + $col + $row ;
  }

  public function save() {
    if ($this->id) {
      $sql = "UPDATE game SET player1=:player1, player2=:player2, board1=:board1, board2=:board2 WHERE id=:id";
    } else {
      $sql = "INSERT INTO game VALUES(:id, :player1, :player2, :board1, :board2)";
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

  public static function bootstrap() {
    $sql = "
      DROP TABLE IF EXISTS game;
      CREATE TABLE game (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
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
    $sth->execute();
    if (! $this->id) {
      $this->id = $dbh->lastInsertId();
    }
  }

}

?>
