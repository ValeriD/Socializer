<?php


namespace Inc\Base;


class Post {

	private $postId;
	private $title;
	private $author;
	private $content;
	private $description;
	private $likesCount;
	private $commentCount;
	private $imagUrl;


	/**
	 * Post constructor.
	 *
	 * @param $postId
	 */
	public function __construct( $postId ) {

		$this->setPostId($postId);
		$this->setLikesCount(0);
		$this->setCommentCount(0);
	}

	/**
	 * @return mixed
	 */
	public function getImagUrl() {
		return $this->imagUrl;
	}

	/**
	 * @param mixed $imagUrl
	 */
	public function setImagUrl( $imagUrl ) {
		$this->imagUrl = $imagUrl;
	}
	/**
	 * @return mixed
	 */
	public function getPostId() {
		return $this->postId;
	}

	/**
	 * @param mixed $postId
	 */
	public function setPostId( $postId ) {
		$this->postId = $postId;
	}
	/**
	 * @return mixed
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param mixed $title
	 */
	public function setTitle( $title ) {
		$this->title = $title;
	}

	/**
	 * @return mixed
	 */
	public function getAuthor() {
		return $this->author;
	}

	/**
	 * @param mixed $author
	 */
	public function setAuthor( $author ) {
		$this->author = $author;
	}

	/**
	 * @return mixed
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param mixed $content
	 */
	public function setContent( $content ) {
		$this->content = $content;
	}

	/**
	 * @return mixed
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param mixed $description
	 */
	public function setDescription( $description ) {
		$this->description = $description;
	}

	/**
	 * @return mixed
	 */
	public function getLikesCount() {
		return $this->likesCount;
	}

	/**
	 * @param mixed $likesCount
	 */
	public function setLikesCount( $likesCount ) {
		$this->likesCount = $likesCount;
	}

	/**
	 * @return mixed
	 */
	public function getCommentCount() {
		return $this->commentCount;
	}

	/**
	 * @param mixed $commentCount
	 */
	public function setCommentCount( $commentCount ) {
		$this->commentCount = $commentCount;
	}

	/**
	 * @return array
	 */
	public function toArray(){
		return array(
			'post_author' => $this->author,
			'post_content' => $this->content,
			'post_type' => 'post',
			'post_status' => 'publish',
			'post_title' => $this->title,
			'meta_input' => array(
				'post_likes' => $this->likesCount,
				'post_comments' => $this->commentCount,
				'post_img' => $this->imagUrl
			)
		);
	}

	/**
	 *
	 */
	public function savePost(){
		$error = wp_insert_post($this->toArray());
		if(!$error){
			die('Unable to save the post');
		}
	}





}