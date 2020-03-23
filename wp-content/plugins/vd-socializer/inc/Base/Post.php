<?php


namespace Inc\Base;


class Post {

	private $data;


	public function __construct() {
		require_once ABSPATH . '/wp-admin/includes/post.php';

		$this->data = array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'meta_input' => array()
		);

		$this->setMetaData('post_likes', 0);
		$this->setMetaData('post_shares', 0);

	}

	private function setId($postId){
		$this->data['ID'] = $postId;
	}

	public function getTitle() {
		return $this->data['post_title'];
	}

	public function setTitle( $title ) {
		$this->data['post_title'] = $title;
	}

	public function getAuthor() {
		return $this->data['post_author'];
	}

	public function setAuthor( $author ) {
		$this->data['post_author'] = $author;
	}

	public function getContent() {
		return $this->data['post_content'];
	}
	public function setContent( $content ) {
		$this->data['post_content'] = $content;
	}

	public function getMetaData($key){
		return $this->data['meta_input'][$key];
	}

	public function setMetaData($key, $value){
		$this->data['meta_input'][$key] = $value;
	}

	/**
	 * @return array
	 */
	public function getData(){
		return $this->data;
	}
	public function getMeta(){
		return $this->data['meta_input'];
	}
	/**
	 *
	 */
	public function savePost(){
		$postId = post_exists(null, $this->getContent(), null, $this->getData()['post_type']);
		if($postId > 0){
			$this->setId($postId);
			$error = wp_update_post($this->data);
		}else {
			$error =  wp_insert_post( $this->getData() );
		}
		return $error;
	}
	public function deletePost(){
		$postId = post_exists(null, $this->getContent(), null, $this->getData()['post_type']);
		if($postId > 0){
			wp_delete_post($postId);
		}
	}








}