<?php
/**
 * Plugin Name: Quotes REST Demo
 * Description: This plugin was built to demonstrate Quotes REST API
 * License:     BSD
 */

if ( ! defined( 'WPINC' ) ) {
	     die;
}

define("QUOTES_REST_DIR", plugin_dir_path( __FILE__ ));

require_once( QUOTES_REST_DIR . 'class.footer_widget.php' );


function quotes_rest_register_settings() {
	add_option( 'quotes_rest_endpoint', 'http://api.witara.pp.ua');
	add_option( 'quotes_rest_client_id', '');
	add_option( 'quotes_rest_client_secret', '');
	add_option( 'quotes_rest_access_token', '');

	register_setting( 'quotes_rest_options_server', 'quotes_rest_endpoint', 'quotes_rest_callback');
	register_setting( 'quotes_rest_options_server', 'quotes_rest_client_id', 'quotes_rest_callback');
	register_setting( 'quotes_rest_options_server', 'quotes_rest_client_secret', 'quotes_rest_callback');
}




function quotes_rest_register_options_page() {
	add_options_page('Quotes REST API settings', 'Quotes REST API', 'manage_options', 'quotes_rest', 'quotes_rest_options_page');
}

function quotes_rest_options_page() {
?>
  <div>
	  <?php screen_icon(); ?>
	  <h2>Quotes REST API settings</h2>
	  <form method="post" action="options.php">
		  <?php settings_fields( 'quotes_rest_options_server' ); ?>
		  <h3>Server settings</h3>
		  <p>Put values taken from api server register form</p>
		  <style type="text/css">
			label {
				font-weight: bold;
			}
			div.label {
				float: left;
				width: 10%;
			}
          </style>
		  <div>
			<div class="row">
				  <div class="label"><label for="quotes_rest_endpoint">Quotes server endpoint</label></div>
				  <div class="value"><input type="text" id="quotes_rest_endpoint" name="quotes_rest_endpoint" value="<?php echo get_option('quotes_rest_endpoint'); ?>" /></div>
			</div>
			<div class="row">
				  <div class="label"><label for="quotes_rest_client_id">Client ID</label></div>
				  <div class="value"><input type="text" id="quotes_rest_client_id" name="quotes_rest_client_id" value="<?php echo get_option('quotes_rest_client_id'); ?>" /></div>
		    </div>
				  <div class="label"><label for="quotes_rest_client_secret">Client Secret</label></div>
				  <div class="value"><input type="text" id="quotes_rest_client_secret" name="quotes_rest_client_secret" value="<?php echo get_option('quotes_rest_client_secret'); ?>" /></div>
			</div>
		  </div>
		  <?php  submit_button(); ?>
	  </form>
  </div>
<?php
}

// Include the dependencies needed to instantiate the plugin.
foreach ( glob( plugin_dir_path( __FILE__ ) . 'admin/*.php' ) as $file ) {
	include_once $file;
}

function quotes_rest_admin_crud() {
	$plugin = new CRUD_Menu( new CRUD_Page());
	$plugin->init();

	$crud = new CRUD_Handler();
	$crud->init();
}


add_action( 'plugins_loaded', 'quotes_rest_admin_crud' );
add_action('admin_menu', 'quotes_rest_register_options_page');
add_action('admin_init', 'quotes_rest_register_settings');
