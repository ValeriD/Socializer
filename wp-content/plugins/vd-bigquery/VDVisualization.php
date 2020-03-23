<?php


class VDVisualization {

	private $bqClient;

	/**
	 * VDVisualization constructor.
	 */
	public function __construct() {
		$this->bqClient = new \VDBigQuery();

		add_shortcode('usageByCategory', array($this, 'usageByCategory'));
		add_shortcode('likesHoursCurve', array($this, 'likesHoursCurve'));
	}

	public function usageByCategory(){
		$sql = 'SELECT post_category, COUNT(DISTINCT social_id) FROM `socializer-270013.SocializerDataset1.SocializerDataset1`
 				WHERE post_author='. get_current_user_id() .' GROUP BY post_category';
		$data = $this->serializeQueryResults($sql);
		include 'Assets/usageByCategory.php';
	}

	public function likesHoursCurve(){
		$sql = 'SELECT datetime_trunc(post_date,  HOUR), SUM(post_likes) FROM 
			(SELECT DISTINCT social_id, post_likes, post_date FROM `socializer-270013.SocializerDataset1.SocializerDataset1` 
				GROUP BY social_id, post_likes, post_date) GROUP BY post_date ORDER BY post_date ASC';
		$data = $this->serializeQueryResults($sql);
		include 'Assets/likesHoursCurve.php';
	}

	private function serializeQueryResults($sql){
		$results = $this->bqClient->vdRunQuery($sql);
		$data = array();
		foreach ($results as $row) {
			array_push($data, $row);
		}
		return $data;
	}



}