
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
  var latlngformat = '[';
  jQuery.each(drawnItems._layers, function(ind, valu) {
    len = valu._latlngs.length; 
    jQuery.each(valu._latlngs, function(index, value) {
        console.log(value);
        if (index == len - 1) {
         console.log('Last field, submit form here');
         latlng = latlng + value.lat + ' '+ value.lng + ')';
         latlngformat = latlngformat +'['+ value.lat + ' , '+ value.lng + ']]';
        }
        else {
        latlng = latlng + value.lat + ' '+ value.lng +' , ';
        latlngformat = latlngformat +'['+ value.lat + ' , '+ value.lng +'],';
        }
    });
  });
  console.log(latlng);
  alert('vamoooo');
  jQuery("#edit-inat-obs-add-wkt").val(latlng);
  jQuery("#edit-inat-obs-add-leaflet").val(latlngformat);
}); 
 jQuery(".form-item-inat-obs-add-transects").change(function($) {                                                                                       
      alert("epa"); 
      var e = document.getElementById("extra[transect][value]");
      var trans = e.options[e.selectedIndex].value;
      console.log(trans);
      console.log(transects[trans].leaflet);
      var pol = new Array(); 
      var obj = transects[trans].wkt.split('(').pop().split(')').shift().split(',');     
        for (x in obj){
             if(obj[x].substring(0,1) != ' ') {
               var lng = parseFloat(obj[x].split(' ')[0].replace(' ',''));
               var lat = parseFloat(obj[x].split(' ')[1].replace(' ',''));
             } else {
               var lng = parseFloat(obj[x].split(' ')[1].replace(' ',''));
               var lat = parseFloat(obj[x].split(' ')[2].replace(' ',''));
             }
            var point = new L.LatLng(lat, lng);
            pol.push(point);
            console.log(pol);
           }
            map.eachLayer(function(layer) {                                                                                                                          
             if(layer.hasOwnProperty('_latlngs')) {
               map.removeLayer(layer);
             }
           });

           var firstpolyline = new L.Polyline(pol).addTo(rec);
//           map.addLayer(firstpolyline);
         //  var pol = L.polyline(pol).addTo(rec);
           map.fitBounds(pol); 
  //    L.polyline(transects[trans].leaflet).addTo(drawnItems); 
  //    var firstpolyline = new L.Polyline(transects[trans].leaflet);
  //    map.addLayer(firstpolyline);
  //    map.fitBounds(poliline);

 
 });
jQuery("#inat-obs-add").submit(function($) {
  jQuery("#edit-inat-obs-add-latitude").val(drawnItems._layers['42']._latlng.lat);
  jQuery("#edit-inat-obs-add-longitude").val(drawnItems._layers['42']._latlng.lng);
}); 
