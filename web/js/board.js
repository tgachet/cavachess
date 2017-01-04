/* chessboard & chess init */
var board,
  game = new Chess(),
  statusEl = $('#status'),
  fenEl = $('#fen'),
  pgnEl = $('#pgn');


// game.header('White', 'Robert James Fischer');
// game.header('Black', 'Mikhail Tal');

      // if ((game.turn() === 'w' && player === 'black' ) || (game.turn() === 'b' && player === 'white' ))
      // {
      //  board.position(game.fen());
      // }

/* Cases d'aide */
var removeGreySquares = function() {
  $('#board .square-55d63').css('background', '');
};

var greySquare = function(square) {
  var squareEl = $('#board .square-' + square);
  
  var background = '#a9a9a9';
  if (squareEl.hasClass('black-3c85d') === true) {
    background = '#696969';
  }

  squareEl.css('background', background);
};

// do not pick up pieces if the game is over
// only pick up pieces for the side to move
var onDragStart = function(source, piece, position, orientation) {
  if (game.game_over() === true ||
      (game.turn() === 'w' && piece.search(/^b/) !== -1) ||
      (game.turn() === 'b' && piece.search(/^w/) !== -1) ||
      (game.turn() === 'w' && player === 'black') ||
      (game.turn() === 'b' && player === 'white'))
  {
    return false;
  }
};

var last_move = '';
var onDrop = function(source, target) {
  removeGreySquares();
  // see if the move is legal
  var move = game.move({
    from: source,
    to: target,
    promotion: 'q' // NOTE: always promote to a queen for example simplicity
  });

  // illegal move
  if (move === null) return 'snapback';

  updateStatus();
};

/* Cases d'aide */
var onMouseoverSquare = function(square, piece) {
  // get list of possible moves for this square
  var moves = game.moves({
    square: square,
    verbose: true
  });

  // exit if there are no moves available for this square
  if (moves.length === 0) return;

  // highlight the square they moused over
  greySquare(square);

  // highlight the possible squares for this piece
  for (var i = 0; i < moves.length; i++) {
    greySquare(moves[i].to);
  }
};

var onMouseoutSquare = function(square, piece) {
  removeGreySquares();
};


// update the board position after the piece snap 
// for castling, en passant, pawn promotion
var onSnapEnd = function() {
  board.position(game.fen());
};

var updateStatus = function() {
  var status = '';

  var moveColor = 'White';
  if (game.turn() === 'b') {
    moveColor = 'Black';
  }

  // checkmate?
  if (game.in_checkmate() === true) {
    status = 'Game over, ' + moveColor + ' is in checkmate.';
  }

  // draw?
  else if (game.in_draw() === true) {
    status = 'Game over, drawn position';
  }

  // game still on
  else {
    status = moveColor + ' to move';

    // check?
    if (game.in_check() === true) {
      status += ', ' + moveColor + ' is in check';
    }
  }

  if (game.game_over() === true)
  {
  	game.reset();
    game.fen();
    status += moveColor;
  }

  statusEl.html(status);
  fenEl.html(game.fen());
  pgnEl.html(game.pgn());

  /* Envoi des infos à l'adversaire */
  if (connected === true) 
  {
    var message = {
        turn : status,
        fen : game.fen(),
        history : game.pgn(),
    };
    socket.emit('gameturninfo', message);
  }


};

var cfg = {
  draggable: true,
  position: 'start',
  onDragStart: onDragStart,
  onDrop: onDrop,
  onMouseoutSquare: onMouseoutSquare,
  onMouseoverSquare: onMouseoverSquare,  
  onSnapEnd: onSnapEnd,
};

board = ChessBoard('board', cfg);

updateStatus();


/* Réception du tour de l'adversaire */
socket.on('gameturninfo', function(data)
{
  game.load_pgn(data.history);
  statusEl.html(data.turn);
  fenEl.html(data.fen);
  pgnEl.html(data.history); 
});

$(window).on('focus', function() {
  board.position(game.fen());
});