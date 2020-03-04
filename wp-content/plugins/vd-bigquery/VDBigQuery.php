<?php



class VDBigQuery {

	private $client;
	private $service;
	private $projectId = 'socializer-270013';
	/**
	 * VDBigQuery constructor.
	 */
	public function __construct() {

//		$this->client = $this->getClient();
//		$this->service = new Google_Service_Bigquery($this->client);
//		var_dump($this->service->datasets->listDatasets($this->projectId));
//		$dataset = $this->service->datasets->insert($this->projectId, new Google_Service_Bigquery_Dataset(), ['datasetId'=>'dataset1']);
//		var_dump(json_decode(json_encode($dataset), true));



	}


	private function getClient() {
		$client = new Google_Client();
		$client->setApplicationName( 'Socializer' );
		$client->setScopes( Google_Service_Bigquery::BIGQUERY );
		try {
			$client->setAuthConfig( __DIR__ . '/credentials.json' );
		} catch ( Google_Exception $e ) {
		}
		$client->setAccessType( 'offline' );
		$client->setPrompt( 'select_account consent' );

		if ( isset($_SESSION['google_token']) ) {
			$accessToken = $_SESSION['google_token'];
			$client->setAccessToken( $accessToken );
		}
		if ( $client->isAccessTokenExpired() ) {
			if ( $client->getRefreshToken() ) {
				$client->fetchAccessTokenWithRefreshToken();
			}
			$_SESSION['google_token'] = $client->getAccessToken();
		}

		return $client;

	}
}