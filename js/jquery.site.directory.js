$(function() {
    $('.site-directory-summary')
      .css('cursor', 'pointer')
      .click(function(e) {
	var a = $(this).find('p.name a');
	if (a.length>0) {
	  window.location = a.attr('href');
	} 
	e.preventDefault();
      });
  });
