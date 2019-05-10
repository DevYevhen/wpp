<?php
require_once __DIR__ . '/../class.oauth.php';
class CRUD_Page {

	private function make_response($api) {
		$at = new Quotes_Rest_Oauth();
		$response = wp_remote_get($at->getEndp() . $api, [
			'headers' => $at->prepareHeaders(),
		]);

		if ( is_wp_error( $response ) ){
			return [];
		}

		if (wp_remote_retrieve_response_code( $response ) !== 200) {
			return [];
		}

		return $response;
	}

	public function fetch_quotes() {
		$response = $this->make_response('/api/quotes');
		return json_decode(wp_remote_retrieve_body( $response ), true);
	}

	public function load_quote($quote_id) {
		$response = $this->make_response('/api/quotes/'.$quote_id);

		if(empty($response)) {
			return [];
		}

		return json_decode(wp_remote_retrieve_body( $response ), true);
	}
 
	public function render() {
		$mode = isset($_GET['action'])?$_GET['action']:'list';

		switch($mode) {
		case 'list':
			$quotes = $this->fetch_quotes();
			include_once( 'views/crud.php' );
			break;
		case 'edit':
			$quote_id = isset($_GET['quote'])?$_GET['quote']:-1;
			$quote = array_shift($this->load_quote($quote_id));
			include_once( 'views/edit.php' );
			break;
		case 'delete':
			$quote_id = isset($_GET['quote'])?$_GET['quote']:-1;
			$quote = array_shift($this->load_quote($quote_id));
			include_once( 'views/delete.php' );
			break;
		default:
			wp_die('Wrong mode', 'Quote REST API');
		}
    }
}
