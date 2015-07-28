<?php
// print_r($_FILES);
// print_r($_POST);

 // We are goingo to save de Transect data in Option talbe of wordpress
//
require_once("../../../wp-load.php");
 $trans = array( 'id' => $_POST['inat_obs_add_tran_id'], 'name' =>$_POST['inat_obs_add_trans_name'], 'description' => $_POST['edit-inat-obs-add-trans-description'], 'image' => $_POST['p_image'], 'wkt' => $_POST['inat_obs_add_wkt'], 'leaflet' => $_POST['inat_obs_add_leaflet'] );
//print_r($trans);
 $transArray = get_option('transects');
$length = sizeof($transArray);
if($length == 1) { 
   if (isset($transArray['0']['id'])){
    $transArray[$length+1] = $trans;
   } else {
    $transArray['0'] = $trans;
   }
} 
else {
    $transArray[$length+1] = $trans;
}
print_r($transArray);
update_option('transects',$transArray );

?>
