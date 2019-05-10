<?php

require_once __DIR__ . '/class.oauth.php';
// The widget class
class Quotes_API_Footer_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'quotes_rest_random_quote',
			__("Display random Quote", 'text_domain'),
			array(
				'customize_selective_refresh' => true,
			)
		);
	}

	public function form( $instance ) {	
	}

	public function update( $new_instance, $old_instance ) {
	}

	public function widget( $args, $instance ) {
		$at = new Quotes_Rest_Oauth();

		$response = wp_remote_get($at->getEndp() . '/api/quotes/random', [
			'headers' => $at->prepareHeaders(),
		]);

		if ( is_wp_error( $response ) ){
			return;
		}

		if (wp_remote_retrieve_response_code( $response ) !== 200) {
			return;
		}

		$body = json_decode(wp_remote_retrieve_body( $response ), true);
		echo '<div><p style="color:red">' . $body['body'] . '</p><p style="float:right">' . $body['author']['name'] . "</p></div>";

	}

}

// Register the widget
function quotes_rest_register_footer_widget() {
	register_widget( 'Quotes_API_Footer_Widget' );
}
add_action( 'widgets_init', 'quotes_rest_register_footer_widget' );
