<?php
require_once __DIR__ . '/../class.oauth.php';
class CRUD_Page {

	public function fetch_quotes() {
		$at = new Quotes_Rest_Oauth();
		$response = wp_remote_get($at->getEndp() . '/api/quotes', [
			'headers' => $at->prepareHeaders(),
		]);

		if ( is_wp_error( $response ) ){
			return [];
		}

		if (wp_remote_retrieve_response_code( $response ) !== 200) {
			return [];
		}
		return json_decode(wp_remote_retrieve_body( $response ), true);

	}
 
	public function render() {
		$quotes = $this->fetch_quotes();
		include_once( 'views/crud.php' );
    }
}
