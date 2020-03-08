<?php


namespace Inc\Base;


class Post {

	private $data;
	private $metaData;


	public function __construct() {
		require_once ABSPATH . '/wp-admin/includes/post.php';

		$this->metaData =array();

		$this->data = array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'meta_input' => array()
		);

		$this->setMetaData('post_likes', 0);
	}

	private function setId($postId){
		$this->data['ID'] = $postId;
	}

	/**
	 * @return mixed
	 */
	public function getTitle() {
		return $this->data['post_title'];
	}

	/**
	 * @param mixed $title
	 */
	public function setTitle( $title ) {
		$this->data['post_title'] = $title;
	}

	/**
	 * @return mixed
	 */
	public function getAuthor() {
		return $this->data['post_author'];
	}

	/**
	 * @param mixed $author
	 */
	public function setAuthor( $author ) {
		$this->data['post_author'] = $author;
	}

	/**
	 * @return mixed
	 */
	public function getContent() {
		return $this->data['post_content'];
	}

	/**
	 * @param mixed $content
	 */
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

	/**
	 *
	 */
	public function savePost(){
		$postId = post_exists($this->getTitle(), $this->getContent(), null, $this->getData()['post_type']);
		if($postId > 0){
			$this->setId($postId);
			$error = wp_update_post($this->data);
		}else {
			$error =  wp_insert_post( $this->getData() );
		}
		return $error;
	}

	public function getMeta(){
		return $this->data['meta_input'];
	}






}