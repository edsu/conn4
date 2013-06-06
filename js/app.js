(function($) {

  function main() {
    $.getJSON(location.pathname + ".json", drawBoard);
    $("table td").click(clickCell);
  }

  function drawBoard(game) {
    for (var i=0; i<game.board.length; i++) {
      var col = game.board[i];
      for (var j=0; j<col.length; j++) {
        var player = col[j];
        var id = "r" + j + "c" + i;
        var cell = $("#" + id);
        console.log(id);
        cell.empty();
        cell.append(player);
      }
    }

    if (game.error) {
      $("#error").empty();
      var hail = getHail();
      $("#error").append('<div class="alert alert-block alert-error fade in span7 offset2"><button type="button" class="close" data-dismiss="alert">&times;</button> <strong>' + hail + '</strong>, ' + game.error + '</div>');
    } else if (game.waitingForOpponent) {
      $("#status").empty();
      $("#status").append('<div class="alert fade in span7 offset2"><button type="button" class="close" data-dismiss="alert">&times;</button>Hey, share this <a href="">link</a> with a friend so they can join your new <strong>conn4</strong> game.</div>');
    }

  }

  function clickCell() {
    var id = $(this).attr('id');
    var cell = $("#" + id);
    var m = id.match(/r(\d+)c(\d+)/);
    var row = parseInt(m[1]);
    var column = parseInt(m[2]);
    var move = {row: row, column: column};
    $.post(location.pathname + "/move", move, drawBoard);
    return false;
  }

  function getHail() {
    var hails = ["Woah there", "R'uh r'oh", "Hold on partner", "Alas"];
    var i = Math.floor((Math.random() * hails.length));
    return hails[i];
  }

  main();

})(jQuery);


