<?php



class VDBigQuery {

	private $client;
	private $service;

	/**
	 * VDBigQuery constructor.
	 */
	public function __construct() {
		$this->client = $this->getClient();
		$this->service = new Google_Service_Bigquery($this->client);
	}


	private function getClient() {
		$client = new Google_Client();
		$client->setApplicationName( 'Socializer' );
		$client->setScopes( Google_Service_Bigquery::DEVSTORAGE_FULL_CONTROL );
		try {
			$client->setAuthConfig( __DIR__ . '/credentials.json' );
		} catch ( Google_Exception $e ) {
		}
		$client->setAccessType( 'offline' );
		$client->setPrompt( 'select_account consent' );

		$tokenPath = __DIR__ . '/token.json';
		if ( file_exists( $tokenPath ) ) {
			$accessToken = json_decode( file_get_contents( $tokenPath ), true );
			$client->setAccessToken( $accessToken );
		}
		if ( $client->isAccessTokenExpired() ) {
			if ( $client->getRefreshToken() ) {
				$client->fetchAccessTokenWithRefreshToken();
			}
			return $client;

			//else{
//				$authUrl = $client->createAuthUrl();
//				printf("Open the following link in your browser:\n%s\n", $authUrl);
//				print 'Enter verification code: ';
//				$authCode = trim(fgets(STDIN));
//
//				// Exchange authorization code for an access token.
//				$accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
//				$client->setAccessToken($accessToken);
//
//				// Check to see if there was an error.
//				if (array_key_exists('error', $accessToken)) {
//					throw new Exception(join(', ', $accessToken));
//				}
		}
		// Save the token to a file.
//			if (!file_exists(dirname($tokenPath))) {
//				mkdir(dirname($tokenPath), 0700, true);
//			}
//			file_put_contents($tokenPath, json_encode($client->getAccessToken()));
//	    }
	}
}