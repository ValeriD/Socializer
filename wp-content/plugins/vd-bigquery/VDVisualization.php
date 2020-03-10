<?php


class VDVisualization {

	private $bqClient;

	/**
	 * VDVisualization constructor.
	 */
	public function __construct() {
		$this->bqClient = new \VDBigQuery();
		//$sql = 'SELECT post_category, count(distinct social_id) FROM `socializer-270013.SocializerDataset1.SocializerDataset1` where post_author='. get_current_user_id() .' group by post_category';
		add_shortcode('usageByCategory', array($this, 'usageByCategory'));
		add_shortcode('likesHoursCurve', array($this, 'likesHoursCurve'));
	}

	public function usageByCategory(){
		$sql = 'SELECT post_category, count(distinct social_id) FROM `socializer-270013.SocializerDataset1.SocializerDataset1` where post_author='. get_current_user_id() .' group by post_category';
		$data = $this->serializQueryResults($sql);
		include 'Assets/usageByCategory.php';
	}

	public function likesHoursCurve(){
		$sql = 'select datetime_trunc(post_date,  HOUR), count(post_likes) from (SELECT distinct social_id, post_likes, post_date FROM `socializer-270013.SocializerDataset1.SocializerDataset1` group by social_id, post_likes, post_date) group by post_date ';
		$data = $this->serializQueryResults($sql);
		include 'Assets/likesHoursCurve.php';
	}

	private function serializQueryResults($sql){
		$results = $this->bqClient->vdRunQuery($sql);
		$data = array();
		foreach ($results as $row) {
			array_push($data, $row);
		}
		return $data;
	}



}