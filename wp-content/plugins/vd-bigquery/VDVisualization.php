<?php


class VDVisualization {

	private $bqClient;

	/**
	 * VDVisualization constructor.
	 */
	public function __construct() {
		$this->bqClient = new \VDBigQuery();
	}



}