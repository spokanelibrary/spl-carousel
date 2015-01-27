<?php

class SPL_Carousel { 

	var $params;
	
	function __construct($params) {

		//parent::__construct();

		$this->params = $params;
	
		$this->output = $this->getCarousel();
	}

	public function output() {
		return $this->output;
	}

	public function getCarousel() {
		$html = null;
		
		$html .= 'this is a carousel'.'<br>'.PHP_EOL;

		$news = $this->getCarouselNews();
		$promo = $this->getCarouselPromo();
		$slides = $this->getCarouselSlides();

		$html .= '<pre>'.print_r($slides, true).'</pre>';

		return $html;
	}

	protected function getCarouselNews() {


		return 'news'.'<br>'.PHP_EOL;
	}

	protected function getCarouselPromo() {


		return 'promo'.'<br>'.PHP_EOL;
	}

	protected function getCarouselSlides() {

		$slides = null; 

		$id = get_the_ID();

	  if ( isset($this->params['slug']) ) {
	    $imgPage = get_page_by_path($this->params['slug']);
	  }
	  if ( $imgPage ) {
	    $id = $imgPage->ID;
	  }

	  $orderby = 'menu_order';
	  if ( in_array('random', $this->params) ) {
	    $orderby = 'rand';
	  }

	  $carousel = null;
	  $args = array(
	    'post_type' => 'attachment',
	    'orderby'   => $orderby,
	    'order'     => 'ASC',
	    'numberposts' => null,
	    'post_status' => null,
	    'post_parent' => $id
	  ); 
	  $attachments = get_posts($args);

	  if ( is_array($attachments) ) {
	  	foreach ( $attachments as $a => $attachment) {
	  		$slides[] = $this->getCarouselSlide($attachment);
	  	}

	  }

		return $slides;
	}

	protected function getCarouselSlide($attachment) {
		$slide = new stdClass;
		$slide->url = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
		$slide->img = $attachment->guid;
		$slide->title = $attachment->post_title;
		$slide->subtitle = $attachment->post_excerpt;
		$slide->content = $attachment->post_content;

		//$slide = $attachment;
		return $slide;
	}

}

?>