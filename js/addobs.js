
function loadReader(e) {
    var i = jQuery('.image_preview_cont img').length;
    console.log('onload: ' + i);
    jQuery('.image_preview_cont').append('<img src="#" id="img_preview'+i+'" />');
    jQuery('#img_preview'+i).attr('src',e.target.result);
}
function readURL(input) {
  if (input.files && input.files[0]) {
    var readers = [],
        count = input.files.length;
    console.log('Count: ' + count);
    for(i=0;i<count;i++){
        console.log('readUrl: ' + i);
        readers[i] = new FileReader();
        readers[i].onload = loadReader;
        readers[i].readAsDataURL(input.files[i]);
    }
  }
}

jQuery("#inat-obs-trans").submit(function($) {
  var latlng = 'LINESTRING(';
  jQuery.each(drawnItems._layers, function(ind, valu) {
    len = valu._latlngs.length; 
    jQuery.each(valu._latlngs, function(index, value) {
        console.log(value);
        if (index == len - 1) {
         console.log('Last field, submit form here');
         latlng = latlng + value.lat + ' '+ value.lng + ')';
        }
        else {
        latlng = latlng + value.lat + ' '+ value.lng +' , ';
        }
    });
  });
  console.log(latlng);
  alert('vamoooo');
  jQuery("#edit-inat-obs-add-wkt").val(latlng);
}); 
jQuery("#inat-obs-add").submit(function($) {
  jQuery("#edit-inat-obs-add-latitude").val(drawnItems._layers['42']._latlng.lat);
  jQuery("#edit-inat-obs-add-longitude").val(drawnItems._layers['42']._latlng.lng);
}); 
