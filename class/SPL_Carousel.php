<?php

function spl_carousel_excerpt_more( $more ) {
	return '&hellip;';
}

class SPL_Carousel { 

	var $id;
	var $params;
	var $slides;
	
	function __construct($params) {

		//parent::__construct();

		$this->params = $params;
	
		$this->output = $this->getCarousel();
	}

	public function output() {
		return $this->output;
	}

	public function getCarousel() {
		$this->id = get_the_ID();
		
		$slides = $this->getCarouselSlides();

		if ( in_array('posts', $this->params) ) {
	    $posts = $this->getCarouselPosts();
	    if ( is_array( $posts ) ) {
	    	foreach ( $posts as $p => $post ) {
	    		$slides[] = $post; 		
	    	}
	    }
	  }

		if ( isset($this->params['promo']) ) {
			$promos = explode(',', $this->params['promo']);
			if ( is_array($promos) ) {
				foreach ( $promos as $p => $promo ) {
					$slides[] = $this->getCarouselPromo($promo);
				}
			}
		}

		if ( in_array('news', $this->params) ) {
	    $slides[] = $this->getCarouselNews();
	  }
		
		$slides = array_reverse($slides);

		if ( in_array('shuffle', $this->params) ) {
	    shuffle($slides);
	  }

	  if ( is_array($slides) ) {
	  	$this->slides = $slides;
	  	$carousel = $this->getCarouselFormatted();
	  }

	  //$carousel = '<pre>'.print_r($slides, true).'</pre>';
		return $carousel;
	}

	protected function getCarouselFormatted() {
		$carousel = null;
		
		$auto = null;
	  if ( in_array('auto', $this->params) ) {
	    $auto = 'data-ride="carousel"';
	  } 

	  $interval = null;
	  if ( isset($this->params['interval']) ) {
	    $interval = 'data-interval="'.($this->params['interval']*1000).'"';
	  }
	  

	  $carousel .= ''.PHP_EOL;
    //$carousel .= '<div style="width:100%;" id="spl-carousel-'.$id.'" class="carousel slide" '.$auto.' '.$interval.'>'.PHP_EOL;
    

		$slides = null;
		foreach ( $this->slides as $s => $slide ) {
	  	$slides .= $this->getCarouselSlideFormatted($slide);
	  }

	  $carousel .= $slides;

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
				$slide->title = 'Digital Promo';
				$slide->subtitle = 'Subtitle';
				$slide->content = 'Content';
				break;
			case 'digital':
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

	protected function getCarouselPosts($limit=3, $category='featured') {
		$slides = null; 

	  $args = array(
	    'post_type' => 'post',
	    'orderby'   => 'post_date',
	    'order'     => 'DESC',
	    'post_status' => 'publish',
	    //'numberposts' => $limit,
	    'posts_per_page' => $limit,
	    'category_name' => $category
	  ); 
	  
	  $posts = new WP_query($args);
	  if ($posts->have_posts()) {
			add_filter( 'excerpt_more', 'spl_carousel_excerpt_more' );
			while ($posts->have_posts()) {
				$posts->the_post(); 

				$slide = new stdClass;
				$slide->format = 'post';
				$slide->url = get_permalink();
				if ( has_post_thumbnail() ) { 
					$slide->img = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'medium');
					$slide->img = $slide->img[0];
				}
				//$slide->id = get_the_ID();
				$slide->title = get_the_title();
				$slide->content = get_the_excerpt();

				$slides[] = $slide;
			}
			remove_filter( 'excerpt_more', 'spl_carousel_excerpt_more' );
	  }
	  wp_reset_postdata();

		return $slides;
	}

	protected function getCarouselSlides() {

		$slides = null; 

		$id = $this->id;

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
	  		$slide = new stdClass;

				$slide->url = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
				$slide->img = $attachment->guid;
				$slide->title = $attachment->post_title;
				$slide->subtitle = $attachment->post_excerpt;
				$slide->content = $attachment->post_content;

				$slides[] = $slide;
	  	}
	  }

		return $slides;
	}

}

?>