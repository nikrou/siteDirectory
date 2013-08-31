$(function() {
    $('#site-directory-list, #thematics-list').tableDnD({
      onDrop:function(table,row) {
	  var rows = table.tBodies[0].rows;
	  for (var i=0; i<rows.length; i++) {
	    $(rows[i])
	      .find('input[type=hidden]').attr('value', i+1);
	  }
	  $('.save-order').removeAttr('disabled').removeClass('disabled');
	}
      });
  });
