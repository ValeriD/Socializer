<?php


namespace Inc\Base;


class Post {

	private $data;
	private $metaData;
	/**
	 * Post constructor.
	 *
	 * @param $postId
	 */
	public function __construct( $postId ) {
		$this->metaData =array();
		$this->data = array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'meta_input' => array()
		);

		$this->setMetaData('post_likes', 0);
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

		$error = wp_insert_post( $this->getData() );
		if(!$error){
			die('Unable to save the post');
		}
	}






}