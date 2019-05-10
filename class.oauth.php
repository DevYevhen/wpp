<?php
require_once __DIR__ . '/vendor/autoload.php';

use \League\OAuth2\Client\Provider\GenericProvider;
use \League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;

class Quotes_Rest_Oauth {

	private $access_token;
	private $api_enpoint;
	private $client_id;
	private $client_secret;

	const GRANT_TYPE = 'client_credentials';
	const ACCESS_TOKEN_URL='/api/token';
	const ENDPOINT = 'quotes_rest_endpoint';
	const CLIENT_ID = 'quotes_rest_client_id';
	const CLIENT_SECRET = 'quotes_rest_client_secret';

	const TOKEN_STORE = 'quotes_rest_access_token';

	public function __construct() {
		$this->api_endpoint = get_option(self::ENDPOINT);
		try {
			$access_token_data = json_decode(get_option(self::TOKEN_STORE), true);
			if(!is_array($access_token_data)) {
				throw new \InvalidArgumentException("No token");
			}
			$this->access_token = new AccessToken($access_token_data);

			if($this->access_token->hasExpired()) {
				throw new \InvalidArgumentException("Token has expired");
			}
			
		} catch (Exception $e) {
			$this->access_token = null;
			$this->client_id = get_option(self::CLIENT_ID);
			$this->client_secret = get_option(self::CLIENT_SECRET);
		}
	}

	public function getEndp() {
		return $this->api_endpoint;
	}

	public function prepareHeaders() {
		$atoken = $this->getToken();

		if(!$atoken) {
			return array();
		}
	   
		return [
			'Authorization' => "Bearer " . $atoken->getToken(),
			'Accept' => '*/*',
		];
	}

	public function getToken() {
		if(!$this->access_token) {
		//client_credentials doesn't require urlAuthorize and so on,
		//but GenericProvider does.
			$provider = new GenericProvider([
				'clientId' => $this->client_id,
				'clientSecret' => $this->client_secret,
				'urlAuthorize' => 'http://dummy.url/auth', 
				'urlAccessToken' => $this->api_endpoint . self::ACCESS_TOKEN_URL,
				'urlResourceOwnerDetails' => 'http://dummy.url/details',
			]);

			try {
				$this->access_token = $provider->getAccessToken(self::GRANT_TYPE);
				update_option(self::TOKEN_STORE, 
					json_encode($this->access_token->jsonSerialize())
				);

			} catch (IdentityProviderException $e) {
				echo "Can't retrive info:" . $e->getMessage();
				$this->access_token = null;
			}
		}

		return $this->access_token;
	}
}

