(function($) {

  function main() {
    $.getJSON(location.pathname + ".json", drawBoard);
  }

  function drawBoard(game) {
    for (var i=0; i<game.board.length; i++) {
      $("#board").append(i);
    }
  }

  main();

})(jQuery);


