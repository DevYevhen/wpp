<?php
class CRUD_Handler {
 
    public function __construct() {
    }
 
    public function init() {
         add_action( 'admin_post_quotes_rest_create', array( $this, 'handle_create' ) );
         add_action( 'admin_post_quotes_rest_edit', array( $this, 'handle_edit' ) );
         add_action( 'admin_post_quotes_rest_delete', array( $this, 'handle_delete' ) );
	}

	public function prepare_operation($at) {

		if ( ! isset( $_POST['_wp_http_referer'] ) ) { // Input var okay.
			$_POST['_wp_http_referer'] = admin_url('/edit.php?page=quotes_rest_manage');
		}

		$headers = $at->prepareHeaders();
		$headers['Content-Type'] = 'application/json';

		return $headers;
	}

	private function handle_response($resp) {
		if ( is_wp_error( $resp ) ){
			$rcode = wp_remote_retrieve_response_code($resp);

			switch($rcode) {
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
			case 404:
				wp_die('Quote was not found', 'Quotes REST', array(
					'response' => 404,
					'back_link' => $back_url,
				));
				break;
			}
		}

		return  json_decode(wp_remote_retrieve_body($resp), true);
	}

	public function handle_edit() {
		$at = new Quotes_Rest_Oauth();
		$headers = $this->prepare_operation($at);

		$back_url = sanitize_text_field(
			wp_unslash( $_POST['_wp_http_referer'] ) 
		);

		$data = [
			'author' => [
				'name' => $_POST['quote_author'],
			],
			'body' => $_POST['quote_body']
		];

		$resp = wp_remote_request($at->getEndp() . '/api/quotes/'.$_POST['quote_id'], [
			'headers' => $headers,
			'body' => json_encode($data),
			'method' => 'PUT'
		]);

		$resp_body = $this->handle_response($resp);

		if($resp_body['status']!=='success') {
			wp_die($resp_body, 'Quotes REST', array(
				'response' => 500,
				'back_link' => $back_url,
			));
		}

		wp_safe_redirect( urldecode( $back_url ) );
	}
 
	public function handle_create() {
		$at = new Quotes_Rest_Oauth();
		$headers = $this->prepare_operation($at);

		$back_url = sanitize_text_field(
			wp_unslash( $_POST['_wp_http_referer'] ) 
		);

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

		$resp_body = $this->handle_response($resp);

		if($resp_body['status']!=='success') {
			wp_die($resp_body, 'Quotes REST', array(
				'response' => 500,
				'back_link' => $back_url,
			));
		}

		wp_safe_redirect( urldecode( $back_url ) );
    }

	public function handle_delete() {
		$at = new Quotes_Rest_Oauth();
		$headers = $this->prepare_operation($at);

		$back_url = sanitize_text_field(
			wp_unslash( $_POST['_wp_http_referer'] ) 
		);

		$data = [
			'author' => [
				'name' => $_POST['quote_author'],
			],
			'body' => $_POST['quote_body']
		];

		$resp = wp_remote_request($at->getEndp() . '/api/quotes/'.$_POST['quote_id'], [
			'headers' => $headers,
			'body' => json_encode($data),
			'method' => 'DELETE'
		]);

		$resp_body = $this->handle_response($resp);

		if($resp_body['status']!=='success') {
			wp_die($resp_body, 'Quotes REST', array(
				'response' => 500,
				'back_link' => $back_url,
			));
		}

		wp_safe_redirect( urldecode( $back_url ) );
    }
}
