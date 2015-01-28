<?php

function spl_carousel_excerpt_more( $more ) {
	return '&hellip;';
}

class SPL_Carousel { 

	var $id;
	var $kiosk;
	var $thumb = 'large';
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

		if ( in_array('kiosk', $this->params) ) {
	    $this->kiosk = true;
	  }
		
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
		$carousel = '';
		
		$auto = null;
	  if ( in_array('auto', $this->params) ) {
	    $auto = 'data-ride="carousel"';
	  } 

	  $interval = null;
	  if ( isset($this->params['interval']) ) {
	    $interval = 'data-interval="'.($this->params['interval']*1000).'"';
	  }

	  $pause = 'false';
		if ( isset($this->params['pause']) ) {
	    $pause = 'hover';
	  }	  

	  $carousel .= PHP_EOL;
    $carousel .= '<div style="width:100%;" id="spl-carousel-'.$this->id.'" class="carousel carousel-hero slide" '.$auto.' data-pause="'.$pause.'" '.$interval.'>'.PHP_EOL;
    
    if ( !$this->kiosk ) {
			$carousel .= '<div class="carousel-controls">'.PHP_EOL;
      $carousel .= '<a class="left carousel-control" href="#spl-carousel-'.$this->id.'" data-slide="prev"><span class="glyphicon glyphicon-circle-arrow-left"></span></a>'.PHP_EOL;
      $carousel .= '<a class="right carousel-control" href="#spl-carousel-'.$this->id.'" data-slide="next"><span class="glyphicon glyphicon-circle-arrow-right"></span></a>'.PHP_EOL;

      $carousel .= '<ol class="carousel-indicators">'.PHP_EOL;  
      $i = 0;
      foreach ( $this->slides as $s => $slide ) {
        $active = '';
        if ( 0 == $i ) {
          $active = ' class="active"';
        } 
        $carousel .= '<li data-target="#spl-carousel-'.$this->id.'" data-slide-to="'.$i.'"'.$active.'></li>'.PHP_EOL;
        $i++;
      }
      $carousel .= '</ol>'.PHP_EOL; 
      $carousel .= '</div>'.PHP_EOL; // .clearfix

		}

    /*
    // indicator pips
    if ( !$this->kiosk ) {
      $carousel .= '<div class="row">'.PHP_EOL;
      $carousel .= '<div class="col-md-5">'.PHP_EOL;
      $carousel .= '<ol class="carousel-indicators">'.PHP_EOL;  
      $i = 0;
      foreach ( $this->slides as $s => $slide ) {
        $active = '';
        if ( 0 == $i ) {
          $active = ' class="active"';
        } 
        $carousel .= '<li data-target="#spl-carousel-'.$this->id.'" data-slide-to="'.$i.'"'.$active.'></li>'.PHP_EOL;
        $i++;
      }
      $carousel .= '</ol>'.PHP_EOL; 
      $carousel .= '</div>'.PHP_EOL; // .col
      $carousel .= '</div>'.PHP_EOL; // .row
    } 
		*/
    // slides
    $carousel .= '<div class="carousel-inner">'.PHP_EOL;
		foreach ( $this->slides as $s => $slide ) {
	  	$carousel .= $this->getCarouselSlideFormatted($slide, $s);
	  }
		$carousel .= '</div>'.PHP_EOL; // .carousel-inner

		/*
		// next/prev links
	  if ( !$this->kiosk ) {

      $carousel .= '<div class="row">'.PHP_EOL;
      $carousel .= '<div class="col-md-5"  style="z-index:5";>'.PHP_EOL;
      $carousel .= '<a class="left carousel-control" href="#spl-carousel-'.$this->id.'" data-slide="prev"><span class="glyphicon glyphicon-circle-arrow-left"></span></a>'.PHP_EOL;
      $carousel .= '<a class="right carousel-control" href="#spl-carousel-'.$this->id.'" data-slide="next"><span class="glyphicon glyphicon-circle-arrow-right"></span></a>'.PHP_EOL;
      $carousel .= '</div>'.PHP_EOL; // col
      $carousel .= '</div>'.PHP_EOL; // row
    }
    */

	  $carousel .= '</div>'.PHP_EOL; // .carousel
	  $carousel .= PHP_EOL;

	  if ( isset($this->params['timeout']) ) {
	    // convert minutes to microseconds
	    $timeout = $atts['timeout'] * (1000 * 60);
	    $carousel .= '
	                <script>
	                setTimeout(function(){
	                   window.location.reload(1);
	                }, '.$timeout.');
	                </script>
	                ';
	  } 

	  return $carousel;
	}

	protected function getCarouselSlideFormatted($slide, $s) {
		$html = '';

		$active = null;
    if ( 0 == $s ) {
      $active = ' active';
    } 
    $html .= '<div class="item'.$active.'">'.PHP_EOL;

    switch ( $slide->format ) {
    	case 'news':
    	case 'post':
    	case 'promo':
    		//break;

    	default:
		    $html .= '<div class="row">'.PHP_EOL;

		    $html .= '<div class="col-md-5">'.PHP_EOL;
		    if ( $slide->img ) {
		    	$html .= '<img class="img-responsive img-rounded img-hero" src="'.$slide->img.'" alt="'.$slide->title.'">'.PHP_EOL;
		    }
		    $html .= '</div>'.PHP_EOL; // .col


		    $html .= '<div class="col-md-7"">'.PHP_EOL;

		    $html .= '<div class="carousel-caption">'.PHP_EOL;

        $html .= '<h2 class="text-success" style="margin-top:0;">';
        $html .= $slide->title;
        if ( !empty($slide->subtitle) ) {
          $html .= ' <small style="color:#666;">'.$slide->subtitle.'</small>';
        }
        $html .= '</h2>'.PHP_EOL;
        
        $html .= '<p class="lead">'.$slide->content.'</p>'.PHP_EOL;

        if ( !empty($slide->url) ) { 
          $html .= '<p class="text-right">'.PHP_EOL;
          $html .= '<a class="btn btn-success" href="'.$slide->url.'"> ';
          $html .= 'More <span class="">&rarr;</span>'.PHP_EOL;
          $html .= '</a>'.PHP_EOL;
          $html .= '</p>'.PHP_EOL;
        }

	     	$html .= '</div>'.PHP_EOL; // carousel-caption

				$html .= '</div>'.PHP_EOL; // .col
				$html .= '</div>'.PHP_EOL; // .row
		}
		$html .= '</div>'.PHP_EOL; // .item

		return $html;
	}

	protected function getCarouselNews() {
		$slide = new stdClass;
		$slide->format = 'news';

		$slide->url = '/news/';
		$slide->img = 'http://news.spokanelibrary.org/wordpress/media/Shadle_Sunday_hours2-300x282.jpg';
		$slide->title = 'New Year, New You, New Day for the Library';
		//$slide->subtitle = 'Subtitle';
		$slide->content = 'Content';

		return $slide;
	}

	protected function getCarouselPromo($promo) {
		$slide = new stdClass;
		$slide->format = 'promo';

		switch ($promo) {
			case 'learning':
				$slide->url = '/promo/';
				//$slide->img = 'img.png';
				$slide->title = 'Digital Promo';
				$slide->subtitle = 'Subtitle';
				$slide->content = 'Content';
				break;
			case 'digital':
				$slide->url = '/promo/';
				//$slide->img = 'img.png';
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
					$slide->img = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $this->thumb);
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
				$slide->img = wp_get_attachment_image_src($attachment->ID, $this->thumb);
				$slide->img = $slide->img[0];
				//$slide->img = $attachment->guid;
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