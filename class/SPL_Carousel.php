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
		$carousel = null;
		
		$slides = $this->getCarouselSlides();

		if ( !isset($this->params['promo']) ) {
			$slides[] = $this->getCarouselPromo('learning');
		}

		if ( in_array('news', $this->params) ) {
	    $slides[] = $this->getCarouselNews();
	  }
		
		$slides = array_reverse($slides);

		if ( in_array('shuffle', $this->params) ) {
	    shuffle($slides);
	  }

	  if ( is_array($slides) ) {
	  	foreach ( $slides as $s => $slide ) {
	  		$carousel .= $this->getCarouselSlideFormatted($slide);
	  	}
	  }

	  //$carousel = '<pre>'.print_r($slides, true).'</pre>';
		return $carousel;
	}

	protected function getCarouselSlideFormatted($slide) {
		$html = null;

		$html .= $slide->title.'<br>';

		return $html;
	}

	protected function getCarouselNews() {
		$slide = new stdClass;
		$slide->format = 'news';

		$slide->url = '/news/';
		$slide->img = 'img.png';
		$slide->title = 'News Title';
		$slide->subtitle = 'Subtitle';
		$slide->content = 'Content';

		return $slide;
	}

	protected function getCarouselPromo($promo) {
		$slide = new stdClass;
		$slide->format = 'promo';

		switch ($promo) {
			case 'learning':
				$slide->url = '/promo/';
				$slide->img = 'img.png';
				$slide->title = 'My Promo';
				$slide->subtitle = 'Subtitle';
				$slide->content = 'Content';
				break;
			default:
				/*
				$slide->url = '';
				$slide->img = '';
				$slide->title = '';
				$slide->subtitle = '';
				$slide->content = '';
				*/
				break;
		}

		return $slide;
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