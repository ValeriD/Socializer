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
	}

	public function usageByCategory(){
		$sql = 'SELECT post_category, count(distinct social_id) FROM `socializer-270013.SocializerDataset1.SocializerDataset1` where post_author='. get_current_user_id() .' group by post_category';
		$data = $this->serializQueryResults($sql);
		include 'Assets/usageByCategory.php';
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