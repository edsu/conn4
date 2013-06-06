(function($) {

  var myMove = false;
  var sendingMove = false;
  var lastError = null;
  var lastStatus = null;
  var winner = null;
  var newGame = location.pathname.replace(/\d+$/, '');

  $("a.brand").attr("href", newGame);

  function main() {
    updateBoard();
    $("table td").on('click', clickCell);
  }

  function updateBoard() {
    console.log("updating board");
    $.getJSON(location.pathname + ".json", drawBoard);
  }

  function drawBoard(game) {
    myMove = game.myMove;
    for (var i=0; i<game.board.length; i++) {
      var col = game.board[i];
      for (var j=0; j<col.length; j++) {
        var player = col[j];
        var id = "r" + j + "c" + i;
        var cell = $("#" + id);
        if (! cell.html()) {
          console.log('adding ' + id);
          var disc = $('<img class="disc">');
          disc.hide();
          if (player == 1) {
            disc.attr({src: "img/circle-red.svg"});
          } else {
            disc.attr({src: "img/circle-yellow.svg"});
          }
          cell.append(disc);
          disc.fadeIn({duration: 1500});
        }
      }
    }

    if (game.error)
      setError(game.error);

    if (game.status == "join") {
      setStatus("This game is open, feel free to join in.");
      myMove = true;
    } else if (game.status == "share") {
      setStatus('Hey, share this <a href="">link</a> with a friend so they can join your new game.');
      myMove = false;
    } else if (game.status == "play") {
      setStatus("Ok, it's your move. Make it a good one, ok?");
      myMove = true;
    } else if (game.status == "wait") {
      setStatus("Your opponent is thinking...very, very hard.");
      myMove = false;
    } else if (game.status == "watch") {
      setStatus("These game is underway, feel free to watch if you want.");
      myMove = false;
    } else if (game.status == "won") {
      setStatus('<strong>Congrats</strong>, you won! Shall we <a href="' + newGame + '">play again</a>?');
      $("table td").off('conn4');
    } else if (game.status == "lost") {
      setStatus('<em>Sorry</em>, you lost, better luck <a href="' + newGame + '">next time</a>.');
      $("table td").off('click');
    }
  
    setTimeout(updateBoard, 5000);
  }

  function clickCell() {
    if (! myMove) {
      setError("it's not your move!");
      return;
    }
    var id = $(this).attr('id');
    var cell = $("#" + id);
    var m = id.match(/r(\d+)c(\d+)/);
    var row = parseInt(m[1]);
    var column = parseInt(m[2]);

    var move = nextAvailableCell(column);
    if (move) {
      sendingMove = true;
      $.post(location.pathname + "/move", move, function(board) {
        sendingMove = false;
        drawBoard(board);
      });
    } else {
      setError("You can't move there :(");
    }
  }

  function nextAvailableCell(column) {
    for (var row=0; row<60; row++) {
      var id = cellId(row, column);
      var cell = $(id);
      if (! cell.html()) {
        return {row: row, column: column};
      }
    }
    return null;
  }

  function cellId(row, col) {
    return "#r" + row + "c" + col;
  }

  function setStatus(msg) {
    if (lastStatus == msg) return;
    lastStatus = msg;
    $("#status").empty();
    $("#status").append('<div class="alert fade in"><button type="button" class="close" data-dismiss="alert">&times;</button>' + msg + '</div>');
  }

  function setError(msg) {
    if (lastError == msg) return;
    lastError = msg;
    var hails = ["Woah there", "R'uh r'oh", "Hold on partner", "Alas"];
    var i = Math.floor((Math.random() * hails.length));
    var hail = hails[i];
    $("#error").empty();
    $("#error").append('<div class="alert alert-block alert-error fade in"><button type="button" class="close" data-dismiss="alert">&times;</button> <strong>' + hail + '</strong>, ' + msg + '</div>');
  }

  main();

})(jQuery);


