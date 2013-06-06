(function($) {

  var myMove = false;
  var sendingMove = false;
  var lastError = null;
  var winner = null;

  function main() {
    updateBoard();
    $("table td").click(clickCell);
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
          disc.fadeIn({duration: 2000});
        }
      }
    }

    if (game.winner) {
      var newGame = location.pathname.replace(/\d+$/, '');
      if (game.winner == 'you') {
        setStatus('<strong>Congrats</strong>, you won! Shall we <a href="' + newGame + '">play again</a>?');
      } else {
        setStatus('<em>Sorry</em>, you lost, better luck <a href="' + newGame + '">next time</a>.');
      }
    } else if (game.error) {
      setError(game.error);
    } else if (game.waitingForOpponent) {
      setStatus('Hey, share this <a href="">link</a> with a friend so they can join your new <strong>conn4</strong> game.');
    } else if (game.recentlyJoined) {
      setStatus("<b>Welcome to the game!</b>");
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
    var move = {row: row, column: column};
    sendingMove = true;
    $.post(location.pathname + "/move", move, function(board) {
      sendingMove = false;
      drawBoard(board);
    });
  }

  function setStatus(msg) {
    $("#status").empty();
    $("#status").append('<div class="alert fade in span7 offset2"><button type="button" class="close" data-dismiss="alert">&times;</button>' + msg + '</div>');
  }

  function setError(msg) {
    if (lastError == msg) return;
    lastError = msg;
    var hails = ["Woah there", "R'uh r'oh", "Hold on partner", "Alas"];
    var i = Math.floor((Math.random() * hails.length));
    var hail = hails[i];
    $("#error").empty();
    $("#error").append('<div class="alert alert-block alert-error fade in span7 offset2"><button type="button" class="close" data-dismiss="alert">&times;</button> <strong>' + hail + '</strong>, ' + msg + '</div>');
  }

  main();

})(jQuery);


