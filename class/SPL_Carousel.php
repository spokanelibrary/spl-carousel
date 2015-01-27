<?php

class SPL_Carousel { 

	var $params;
	
	function __construct($params) {

		//parent::__construct();

		$this->params = $params;

		
		$this->output = $this->getCarousel();
		
		//$this->output = '<pre>'.print_r($this->params, true).print_r($this->filter, true).'</pre>' . $this->output;
	}

	public function output() {
		return $this->output;
	}

	public function getCarousel() {
		return 'this is a carousel';
	}

}

?>