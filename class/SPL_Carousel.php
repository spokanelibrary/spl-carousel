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
		$html = 'null';
		
		$html .= 'this is a carousel'.'<br>'.PHP_EOL;
		$html .= $this->getCarouselNews();
		$html .= $this->getCarouselPromo();
		$html .= $this->getCarouselSlides();

		return $html;
	}

	protected function getCarouselNews() {


		return 'news'.'<br>'.PHP_EOL;
	}

	protected function getCarouselPromo() {


		return 'promo'.'<br>'.PHP_EOL;
	}

	protected function getCarouselSlides() {


		return 'slides'.'<br>'.PHP_EOL;
	}

}

?>