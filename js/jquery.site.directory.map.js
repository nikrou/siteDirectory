$(function() {
    var latlng = new google.maps.LatLng(site_directory_latitude, site_directory_longitude);
    var myOptions = { zoom: site_directory_map_zoom,
		      center: latlng,
		      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById('site-directory-canvas'),  myOptions);
    var marker = new google.maps.Marker({position: latlng});
    marker.setMap(map);
  });
