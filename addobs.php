<?
/** 
 * Script procesor for add/user form
 */
$states = array();
    // Actions token
$verb = 'observations.json';
require_once("../../../wp-load.php"); 

 //   $appid = get_option('inat_login_appid', ''); 
 //   $project_info = get_option('inat_project_info','empty');
    $extrafield = '';
      $aux = 0;
foreach ($_POST['extra'] as $key => $field) {                                                                     
      if ($key == 'transect'){
        $extrafield .= '&observation[observation_field_values_attributes]['.$aux.'][observation_field_id]='.$field['id'];
        $trans = get_option('transects');
        print_r($field['value']);
        $extrafield .= '&observation[observation_field_values_attributes]['.$aux.'][value]='.$trans[$field['value']]['id'];
        $aux=$aux+1;
      }
      else if ($key == 'transect_description') {
        $extrafield .= '&observation[observation_field_values_attributes]['.$aux.'][observation_field_id]='.$field['id'];
        $extrafield .= '&observation[observation_field_values_attributes]['.$aux.'][value]=';
        $aux = $aux+1;
      }
      else {
          $extrafield .= '&observation[observation_field_values_attributes]['.$aux.'][observation_field_id]='.$field['id'];
          $extrafield .= '&observation[observation_field_values_attributes]['.$aux.'][value]='.$field['value'];
          $aux = $aux +1;
      }
}
print_r($extrafield);
    $data = 'observation[species_guess]='.$_POST['inat_obs_add_species_guess'].
      '&observation[taxon_id]='.$_POST['inat_obs_add_taxon_id'].
      '&observation[id_please]='.$_POST['inat_obs_add_id_please'].
      '&observation[observed_on_string]='.$_POST['inat_obs_add_observed_on_string'].
      '&observation[time_zone]='.$_POST['inat_obs_add_time_zone'].
      '&observation[description]='.$_POST['inat_obs_add_description'].
      '&observation[place_guess]='.$_POST['inat_obs_add_place_guess'].
      '&observation[latitude]='.$_POST['inat_obs_add_latitude'].
      '&observation[longitude]='.$_POST['inat_obs_add_longitude'].
      $extrafield;

    $url = $_POST['inat_base_url'].'/'.$verb;
    $opt = array('http' => array('method' => 'POST','content' => $data, 'header' => 'Authorization: Bearer '.$_COOKIE['inat_access_token']));
    $context  = stream_context_create($opt);
    $result = file_get_contents($url, false, $context);
    $json = json_decode($result);
    if (isset($json['0']->id)) {
      $verb = 'project_observations.json';
      $data = '?project_observation[observation_id]='.$json['0']->id.'&project_observation[project_id]='.$_POST['inat_project_id'];
      $url = $_POST['inat_base_url'].'/'.$verb.$data;
      $opt = array('http' => array('method' => 'POST', 'header' => 'Authorization: Bearer '.$_COOKIE['inat_access_token']));
      $context = stream_context_create($opt);
      $result = file_get_contents($url, false, $context);
      $json2 = json_decode($result);
    }
    if (isset($_FILES['p_image'])) {
      $verb = 'observation_photos.json';                                                                                                                      
      $boundary = md5(uniqid());
      $post_data = array(
      'observation_photo[observation_id]' => $json['0']->id,
       'file' => $_FILES['p_image']['tmp_name']['0'],
      );
      $dataphoto = multipart_encode($boundary,$post_data);
      $url = $_POST['inat_base_url'].'/'.$verb;
      $opt = array('http' => array('method' => 'POST','content' => $dataphoto, 'header' => array('Authorization: Bearer '.$_COOKIE['inat_access_token'].'\r\n ' ,'Content-type: multipart/form-data \r\n ')));
     $context  = stream_context_create($opt);
     $result = file_get_contents($url, false, $context);
     
    }
    
    
    //header("Location: ".$_POST['site_url'].'/?'.http_build_query(array('page_id' => $_POST['inat_post_id'], 'verb' => 'observations', 'id' => $json[0]->id)));
    //exit();

    // help functions
    //
function multipart_encode($boundary, $post_data){
  $output = "";
  foreach ($post_data as $key => $value){
    $output .= "--$boundary\r\n";
    if ($key == 'file'){
        $output .= multipart_enc_file($value);
        //$output .= 'aquestes son les dades de la photografia que no podem enseñar';
    } else $output .= multipart_enc_text ($key, $value);
  }
  $output .="--$boundary--";
  return $output;
}
// Function to encode text data.
function multipart_enc_text($name, $value){
  return "Content-Disposition: form-data; name=\"$name\"\r\n\r\n$value\r\n"; 
}
function multipart_enc_file($path){
    if (substr($path, 0, 1) == "@") {
        $path = substr($path, 1);
    }
    $filename = basename($path);
    $mimetype = "application/octet-stream";
    $data = "Content-Disposition: form-data; name=\"file\"; filename=\"$filename\"\r\n";
    $data .= "Content-Transfer-Encoding: binary\r\n";
    $data .= "Content-Type: $mimetype\r\n\r\n";
    $data .= file_get_contents($path) . "\r\n";
    //$data .= "Photo data \r\n";
    //dsm($data);
    return $data;
} 
?>
