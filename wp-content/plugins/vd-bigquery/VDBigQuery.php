<?php


use Google\Cloud\BigQuery\BigQueryClient;
use Google\Cloud\Core\ExponentialBackoff;

class VDBigQuery {

	private $client;
	private $datasetId;
	private $tableId;
	private $schema;

	public function __construct() {
		//Authenticating the service account
		$this->client = new BigQueryClient(array('keyFilePath' => __DIR__.'\credentials.json'));

		//Default values:
		$this->setDatasetId('SocializerDataset1');
		$this->setTableId( 'SocializerPosts');
		$fields = [
			['name' => 'social_id', 'type' => 'integer', 'mode' => 'required'],
			['name' => 'post_text', 'type' => 'string', 'mode' => 'nullable'],
			['name' => 'post_author', 'type' => 'integer', 'mode' => 'nullable'],
			['name' => 'post_category', 'type' => 'string', 'mode' => 'nullable'],
			['name' => 'post_likes', 'type' => 'integer', 'mode' => 'nullable'],
			['name' => 'post_shares', 'type' => 'integer', 'mode' => 'nullable'],
			['name' => 'post_img', 'type' => 'string', 'mode' => 'nullable'],
			['name' => 'post_date', 'type' => 'datetime', 'mode' => 'nullable']
		];
		$this->setSchema($fields);
	}

	public static function registerDatasets(){
		$vdbq = new VDBigQuery();
		$vdbq->createDataset($vdbq->getDatasetId());
		$vdbq->createTable($vdbq->getTableId(), $vdbq->getDatasetId());
	}

	public function getSchema() {
		return $this->schema;
	}

	public function setSchema( $fields ) {
		$this->schema = ['fields'=>$fields];
	}

	public function getDatasetId(){
		return $this->datasetId;
	}

	public function setDatasetId($datasetId){
		$this->datasetId=$datasetId;
	}

	public function setTableId( $tableId ) {
		$this->tableId = $tableId;
	}

	public function getTableId(){
		return $this->datasetId;
	}

	public function getClient(){
		return $this->client;
	}

	private function datasetExist($datasetId){
		return $this->getClient()->dataset($datasetId)->exists();
	}

	public function createDataset($datasetId){
		if(!$this->datasetExist($datasetId)) {
			$this->getClient()->createDataset( $datasetId );
		}
	}

	public function getDataset($datasetId){
		if($this->datasetExist($datasetId)) {
			return $this->getClient()->dataset( $datasetId );
		}
	}
	public function deleteDataset($datasetId){
		if($this->datasetExist($datasetId)){
			$this->getDataset($datasetId)->delete();
		}
	}


	private function tableExist($tableId, $datasetId){
		return $this->getDataset($datasetId)->table($tableId)->exists();
	}

	public function createTable($tableId, $datasetId){
		if(!$this->tableExist($tableId, $datasetId)) {
			$this->getDataset($datasetId)->createTable( $tableId, ['schema' => $this->getSchema()] );
		}else {
			$this->getTable($tableId, $datasetId)->update(['schema' => $this->getSchema()]);
		}
	}
	public function getTable($tableId, $datasetId){
		if($this->tableExist($tableId, $datasetId)) {
			return $this->getDataset($datasetId)->table( $tableId );
		}
	}

	public function deleteTable($tableId, $datasetId){
		if($this->tableExist($tableId, $datasetId)){
			$this->getTable($tableId, $datasetId)->delete();
		}
	}
	public function addInTable($tableId, $datasetId, $row){

		if($this->tableExist($tableId, $datasetId)){
			$insert = $this->getTable($tableId, $datasetId)->insertRows([['data' => $row]]);
			if(!$insert->isSuccessful()){
				var_dump('Error: Unsuccessful insertion in BigQuery table!');
			}
		}
	}

	public function vdRunQuery($sql){
		$jobConfig = $this->client->query($sql);
		return  $this->client->runQuery($jobConfig);
	}



}