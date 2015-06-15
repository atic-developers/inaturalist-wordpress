<?php
/**
 * Adds Foo_Widget widget.
 */
class iNatLogin_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
    // info en el llistat de widgets
		parent::__construct(
			'inat_login_widget', // Base ID
			__('iNaturalist Login', 'inat'), // Name
			array( 'description' => __( 'iNaturalist plugin lateral block for user autentication (or creation)', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget']; // no tocar
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
  if(isset($_COOKIE) &&
    array_key_exists('inat_code', $_COOKIE) &&
    (!array_key_exists('inat_access_token', $_COOKIE) || $_COOKIE['inat_access_token'] == NULL))
  {
      if(!array_key_exists('access_token', $_COOKIE)) {
        echo '<a href="'.get_option('inat_base_url').'/oauth/authorize?client_id='.get_option('inat_login_id','').'&redirect_uri='.get_option('inat_login_callback','').'&response_type=code">'. __('Autorize this app','inat'). '</a>';
      } else {
        //Aquí mostrem la informació del usuari;
        echo '<h2> Aquí lo petamos </h2>';
      }

    } elseif(!isset($_COOKIE) || !array_key_exists('inat_access_token', $_COOKIE) || $_COOKIE['inat_access_token'] == NULL) {
      echo '<a href="'.get_option('inat_base_url').'/oauth/authorize?client_id='.get_option('inat_login_id','').'&redirect_uri='.get_option('inat_login_callback','').'&response_type=code">'. __('Autorize this app','inat'). '</a> or <a href="'.site_url().'/inat/add/user">'.__('create new user','inat').'</a>';
    }
 
    elseif (isset($_COOKIE['inat_access_token'])) {
      $verb = 'users/edit';
      $query = array();
      $options = array('query' => $query, 'https' => FALSE);
      $url = (get_option('inat_base_url').'/'.$verb.'.json');
      $data = array(); 
      $options = array(
           'http' => array( 
              "header" => "Authorization: Bearer ".$_COOKIE['inat_access_token'],
              'method'  => 'GET',  
              'content' => http_build_query($data),  
            ), 
          );
//      $opt = array('http' => array('method' => 'POST', 'header' => 'Authorization: Bearer '.$_COOKIE['inat_access_token']));
      $context  = stream_context_create($options);
      $result = file_get_contents($url, false, $context);
      $data = json_decode($result);
      print_r($data);
      echo '<h2> Aquí lo petamos </h2>';
    };
		echo $args['after_widget']; // no tocar
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
   * opcions de configuracio
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'iNaturalist Login', 'inat' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

} // class Foo_Widget
// register Foo_Widget widget
function register_foo_widget() {
    register_widget( 'iNatLogin_Widget' );
}
add_action( 'widgets_init', 'register_foo_widget' );
?>
