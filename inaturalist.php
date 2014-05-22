<?php
/**
 * Plugin Name: iNaturalist
 * Plugin URI: 
 * Description: 
 * Version: 0.1
 * Author: Bacterio
 * Author URI: 
 * License: 
 */
//Include widget
require_once('inat-widgets.php');
require_once('inat-callapi.php');
//Afegir pàgina d'opcions i salvar-les
add_action( 'admin_menu', 'add_inat_menu' );

function add_inat_menu() {
	$inat_options = add_options_page( 'iNaturalist configuration page', 'iNaturalist', 'manage_options', 'inaturalist', 'inat_options' );
}


function inat_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	if(isset($_POST['this'])){
		update_option( 'inat_id',$_POST['this'] );		
	}

	echo '<div class="wrap">';
  echo '<h2>'.__('iNaturalist configuration page', 'inat').'</h2>';
	echo '<form action="" method="post">';
  echo '<table class="form-table"><tbody>';
  echo '<tr><th scope="row"><label for="this">'.__('Base URL of iNaturalist','inat').' </label></th>';
	echo '<td><input type="text" class="regular-text" value="'.get_option( 'inat_base_url' ).'" name="inat_base_url">';
  echo '<p class="description">'.__('The URL used to access iNaturalist data, for example http://www.inaturalist.org','inat').'</p></td></tr>';
  echo '<tr><th scope="row"><label for="this">'.__('Reduce plugin behavior to this project ','inat').' </label></th>';
	echo '<td><input type="text" class="regular-text" value="'.get_option( 'inat_reduce_project' ).'" name="inat_reduce_project">';
  echo '<p class="description">'.__('The project id to reduce the plugin behavior','inat').'</p></td></tr>';
  echo '<tr><th scope="row"><label for="this">'.__('Reduce plugin behavior to this user ','inat').' </label></th>';
	echo '<td><input type="text" class="regular-text" value="'.get_option( 'inat_reduce_user' ).'" name="inat_reduce_user">';
  echo '<p class="description">'.__('The user loginname to reduce the plugin behavior','inat').'</p></td></tr>';
  echo '<tr><th colspan=2><h3>'.__('Configurations for login as iNaturalist application', 'inat').'</h3></th></tr>';
  echo '<tr><th scope="row"><label for="this">'.__('Callback url','inat').' </label></th>';
	echo '<td><input type="text" class="regular-text" value="'.get_option( 'inat_login_callback' ).'" name="inat_login_callback">';
  echo '<p class="description">'.__('iNat application callback url','inat').'</p></td></tr>';
  echo '<tr><th scope="row"><label for="this">'.__('Application Id','inat').' </label></th>';
	echo '<td><input type="text" class="regular-text" value="'.get_option( 'inat_login_id' ).'" name="inat_login_id">';
  echo '<p class="description">'.__('iNat application identifyer','inat').'</p></td></tr>';
  echo '<tr><th scope="row"><label for="this">'.__('Secret','inat').' </label></th>';
	echo '<td><input type="text" class="regular-text" value="'.get_option( 'inat_login_secret' ).'" name="inat_login_secret">';
  echo '<p class="description">'.__('iNat application secret key','inat').'</p></td></tr>';
  echo '<tr><th scope="row"><label for="this">'.__('Numeric id of your application','inat').' </label></th>';
	echo '<td><input type="text" class="regular-text" value="'.get_option( 'inat_login_app' ).'" name="inat_login_app">';
  echo '<p class="description">'.__('Get it at list of applications http://www.inaturalist.org/oauth/applications','inat').'</p></td></tr>';
	//echo '<input type="text" value="'.get_option( 'inat_' ).'" name="">';
  echo '</tbody></table>';
	echo '<input type="submit" name="dp_submit" value="Save Settings" />';
	echo '</form>';
	echo '</div>';
}


//Filtrar the_content de la pàgina


function my_the_content_filter($content) {
  if (isset($GLOBALS['_REQUEST']['inat'])) {
    //$ret_cont .= 'inat in!';
    $cont = test_call();
    return theme_list_obs($cont);
  }
  return $content;
}

add_filter( 'the_content', 'my_the_content_filter' );


//afegir camp a l'usuari

//add_action( 'profile_personal_options', 'inat_user' );
add_action( 'show_user_profile', 'inat_user' );

    
function inat_user( $user ) {

    $inat_user_value = get_user_meta( $user->ID, 'inat_user', true );

    ?>
<h3>INature</h3>
<table class="form-table">
	<tbody><tr>
		<th><label for="user_login">Usuari INature</label></th>
		<td><input type="text" value="<?php echo esc_attr( $inat_user_value ); ?>" name="inat_user" /></td>
	</tr>
</tbody></table>
    <?php
}

 add_action('personal_options_update', 'update_inat_user');
 
 function update_inat_user($user_id) {
     if ( current_user_can('edit_user',$user_id) )
         update_user_meta($user_id, 'inat_user', $_POST['inat_user']);
 }




?>
