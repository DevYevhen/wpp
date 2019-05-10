<?php
class CRUD_Handler {
 
    public function __construct() {
    }
 
    public function init() {
         add_action( 'admin_post_quotes_rest_create', array( $this, 'handle_create' ) );
    }
 
	public function handle_create() {
		$at = new Quotes_Rest_Oauth();

		if ( ! isset( $_POST['_wp_http_referer'] ) ) { // Input var okay.
			$_POST['_wp_http_referer'] = admin_url('/edit.php?page=quotes_rest_manage');
		}
		$back_url = sanitize_text_field(
			wp_unslash( $_POST['_wp_http_referer'] ) 
		);

		$headers = $at->prepareHeaders();
		$headers['Content-Type'] = 'application/json';

		$data = [
			'author' => [
				'name' => $_POST['quote_author'],
			],
			'body' => $_POST['quote_body']
		];

		$resp = wp_remote_post($at->getEndp() . '/api/quotes', [
			'headers' => $headers,
			'body' => json_encode($data)
		]);
		if ( is_wp_error( $response ) ){
			$rcode = wp_remote_retrieve_response_code($resp);

			switch($code) {
			case 401:
				wp_die('Authorisation failed', 'Quotes REST', array(
					'response' => 403,
					'back_link' => $back_url,
				));
				break;
			case 500:
				wp_die('Remote API error', 'Quotes REST', array(
					'response' => 500,
					'back_link' => $back_url,
				));
				break;
			}
		}


		$resp_body = json_decode(wp_remote_retrieve_body($resp), true);
		if($resp_body['status']!=='success') {
			wp_die($resp_body, 'Quotes REST', array(
				'response' => 500,
				'back_link' => $back_url,
			));
		}

		wp_safe_redirect( urldecode( $back_url ) );

    }
}
