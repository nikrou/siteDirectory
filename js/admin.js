$(function() {
    $('#site_directory_theme').change(function() {
	$.theme = $(this).val();
	$('#site_directory_subtheme').html('<option value=""></option>');
	if (site_directory_subthemes[$.theme]!==undefined) {
	  for (var i in site_directory_subthemes[$.theme]) {
	    $('#site_directory_subtheme').append('<option value="'+site_directory_subthemes[$.theme][i]['id']+'">'+site_directory_subthemes[$.theme][i]['label']+'</option>');
	  }
	}
      });

    $('form .media-remove').click(function() {
	if (window.confirm($(this).attr('title'))) {
	  var media_id = $(this).attr('href').replace('.*id=','');
	  var site_id = $('#site_directory_id').val();
	  $.get('services.php',
		{f:'removeSiteMedia', site_id:site_id, media_id:media_id },
		function(data) {
		  var rsp = $(data).children('rsp')[0];
		  if (rsp.attributes[0].value != 'ok') {
		    alert($(rsp).find('message').text());
		  } else {
		    $('#site-directory-media').hide();
		    $('#site-directory-image').removeClass('hidden');
		  }
		});
	}
	return false;
      });
    
    $('.checkboxes-helpers').each(function() {
	dotclear.checkboxesHelpers(this);
      });

    // retrieve latitide and longitude from google
    $('#findLatLng').click(function(e) {
	var address = $('#site_directory_address').val();
	if (address=='') {
	  return false;
	}
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({address:address}, 
			 function(results, status) {
			   if (status == google.maps.GeocoderStatus.OK) {
			     $('#site_directory_address').val(results[0].formatted_address);
			     $('#site_directory_latitude').val(results[0].geometry.location.lat());
			     $('#site_directory_longitude').val(results[0].geometry.location.lng());
			   } else {
			     alert("Geocode was not successful for the following reason: " + status);
			   }
			 });
	e.preventDefault();
      });
  });
