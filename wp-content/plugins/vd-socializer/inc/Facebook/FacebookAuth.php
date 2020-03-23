<?php


namespace Inc\Facebook;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Facebook\GraphNodes\GraphNode;
use Inc\Base\Post;
use Inc\Base\SocialNetwork;

class FacebookAuth extends SocialNetwork {


	private $helper;
	private $permissions;

	public function __construct() {
			SocialNetwork::__construct( 'Facebook' );
	}

	protected function initialize() {
		$this->setClient($this->apiInit());

		//Getting the helper
		$this->helper = $this->getClient() -> getRedirectLoginHelper();
		$this->permissions = ['public_profile', 'email', 'user_location', 'user_hometown','user_birthday','user_photos', 'user_posts']; //you may change it according to your need.
		//Used for CSRF
		if ( isset( $_GET['state'] ) ) {
			$this->helper->getPersistentDataHandler()->set( 'state', $_GET['state'] );
		}
	}



	public function apiInit(){
		try {
			return new Facebook( [
				'app_id'                => $this->getAppId(),
				'app_secret'            => $this->getAppSecret(),
				'default_graph_version' => "v5.0",
			] );
		} catch ( FacebookSDKException $e ) {
			die($e->getMessage());
		}
	}


	public function generateAccessToken() {
		try {
			$accesstoken = $this->helper->getAccessToken();

		} catch (FacebookResponseException $e) {
			var_dump($e->getMessage());
		} catch (FacebookSDKException $e) {
			var_dump($e->getMessage());
		}
		$_SESSION['facebook_access_token'] = $accesstoken;
		return $accesstoken;
	}


	protected function getLoginUrl() {
		return $this->helper->getLoginUrl($this->getClientCallback(), $this->permissions);

	}

	public function getUserData() {
		try {
			$response = $this->getClient()->get('/me?fields=email,first_name,last_name,name,picture,hometown,birthday');
			$userNode = $response->getGraphUser();
		} catch (FacebookResponseException $e) {
			var_dump('Graph phase 1: error in processing your request while fetching token');
		} catch (FacebookSDKException $e) {
			var_dump('Here ' . $e->getMessage());
		}

		return $userNode;
	}


	protected function serializeUserData( $payload ) {
		return  array(
			'name' => $payload->getName(),
			'social_id' => $payload->getId(),
			'email'=>$payload->getEmail(),
			'user_img'=>$payload->getPicture()['url'],
			'hometown' => $payload->getHometown()['name'],
			'birthday' => $payload->getBirthday()->format('d/m/Y')
		);
	}

	public function getUserPosts() {
		try {
			$response = $this->getClient()->get( '/me/posts?fields=id,shares,likes.summary(true),created_time,attachments,message' );
		} catch ( FacebookSDKException $e ) {
			var_dump($e->getMessage());
		}
		try {
			$graphEdge = $response->getGraphEdge();
		} catch ( FacebookSDKException $e ) {
			var_dump($e->getMessage());
		}


		$posts = $this->filterPosts($graphEdge);
		return $posts;
	}

	private function filterPosts($graphEdge){
		$result = array();
		$filter = ['cover_photo', 'photo', 'profile_media'];
		foreach ($graphEdge as $post){
			if( in_array($post['attachments'][0]['type'], $filter) and (isset($post['message']) or isset($post['attachments'][0]['description']))){
				$result[] = $post;
			}
		}
		return $result;
	}


	public function serializeDataForDB( $postData, Post $post ) {
		$post->setAuthor(get_current_user_id());
		$post->setMetaData('social_id', explode('_',$postData['id'])[1]);
		$post->setMetaData('post_link', $postData['attachments'][0]['url']);

		if(isset($postData['attachments'][0]['description'])) {
			$post->setContent( $postData['attachments'][0]['description'] );
		}else if(isset($postData['message'])){
			$post->setContent( $postData['message'] );
		}
		$post->setMetaData('post_img', $postData['attachments'][0]['media']['image']['src']);
		$post->setMetaData('post_date',$postData['created_time']);

		if($postData->getField('likes')->getTotalCount()){
			$post->setMetaData('post_likes', $postData->getField('likes')->getTotalCount());
		}
		if( $postData->getField('shares')['count']){
			$post->setMetaData('post_shares', $postData->getField('shares')['count']);

		}
	}
}