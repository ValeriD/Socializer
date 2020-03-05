<?php


use Google\Cloud\BigQuery\BigQueryClient;

class VDBigQuery {

	private $client;
	private $datasetId;
	private $tableId;
	/**
	 * VDBigQuery constructor.
	 */
	public function __construct() {
		//Authenticating the service account
		$this->client = new BigQueryClient(array('keyFilePath' => __DIR__.'\credentials.json'));

		//Default values:
		$this->datasetId = 'SocializerDataset1';
		$this->tableId = 'SocializerPosts';


	}
	public function getDefaultDatasetId(){
		return $this->datasetId;
	}
	public function getDefaultTableId(){
		return $this->datasetId;
	}
	public static function registerDatasets(){
		$vdbq = new VDBigQuery();
		$vdbq->createDataset($vdbq->getDefaultDatasetId());
		$vdbq->createTable($vdbq->getDefaultTableId(), $vdbq->getDefaultDatasetId());
	}

	public function getClient(){
		return $this->client;
	}

	private function datasetExist($datasetId){
		return $this->getClient()->dataset($datasetId)->exists();
	}

	private function createDataset($datasetId){
		if(!$this->datasetExist($datasetId)) {
			$this->getClient()->createDataset( $datasetId );
		}
	}

	public function getDataset($datasetId){
		if($this->datasetExist($datasetId)) {
			return $this->getClient()->dataset( $datasetId );
		}
	}
	private function deleteDataset($datasetId){
		if($this->datasetExist($datasetId)){
			$this->getDataset($datasetId)->delete();
		}
	}


	private function tableExist($tableId, $datasetId){
		return $this->getDataset($datasetId)->table($tableId)->exists();
	}
	private function createTable($tableId, $datasetId){
		if(!$this->tableExist($tableId, $datasetId)) {
			$this->getDataset($datasetId)->createTable( $tableId );
		}
	}
	public function getTable($tableId, $datasetId){
		if($this->tableExist($tableId, $datasetId)) {
			return $this->getDataset($datasetId)->table( $tableId );
		}
	}
	public function updateTable($tableId, $datasetId, $metadata){
		if($this->tableExist($tableId, $datasetId)){
			$this->getTable($tableId, $datasetId)->update($metadata);
		}
	}
	private function deleteTable($tableId, $datasetId){
		if($this->tableExist($tableId, $datasetId)){
			$this->getTable($tableId, $datasetId)->delete();
		}
	}
	public function addInTable($tableId, $datasetId, $row){
		if($this->tableExist($tableId, $datasetId)){
			$this->getTable($tableId, $datasetId)->insertRow($row);
		}
	}


}