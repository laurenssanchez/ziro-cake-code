$("#SignatureInitial").summernote({
  height: 400,
 //  toolbar: [
	// ['style', ['bold', 'italic', 'underline', 'clear']],
	// ['misc', ['undo', 'redo']],
 //   ],
  hint: {
    mentions: ['#credito', 'nombre', 'cedula', 'ciudad','monto'],
    match: /\B@(\w*)$/,
    search: function (keyword, callback) {
      callback($.grep(this.mentions, function (item) {
        return item.indexOf(keyword) == 0;
      }));
    },
    content: function (item) {
      return '@' + item + '@';
    }    
  }
});

$("#SignatureFullText").summernote({
  height: 800,
  hint: {
    mentions: ['#credito', 'nombre', 'cedula', 'ciudad','monto_letras','monto_numeros','email','fecha','ip','codigo'],
    match: /\B@(\w*)$/,
    search: function (keyword, callback) {
      callback($.grep(this.mentions, function (item) {
        return item.indexOf(keyword) == 0;
      }));
    },
    content: function (item) {
      return '@' + item + '@';
    }    
  }
});