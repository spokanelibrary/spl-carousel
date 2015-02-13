<?php

function spl_carousel_excerpt_more( $more ) {
	return '&hellip;';
}

class SPL_Carousel { 

	var $id;
	var $kiosk;
	var $title;
	var $subtitle;
	var $thumb = 'large';
	var $hover = 'false'; // 'false' or 'hover'
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
		if ( $this->kiosk ) {
			$this->thumb = 'full';
		}

		if ( isset($this->params['title']) ) {
			$this->title = $this->params['title'];
		}

		if ( isset($this->params['subtitle']) ) {
			$this->subtitle = $this->params['subtitle'];
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

	  if ( in_array('calendar', $this->params) ) {
	    $calendar = $this->getCarouselCalendar();
	    if ( is_array( $calendar ) ) {
	    	foreach ( $calendar as $c => $cal ) {
	    		$slides[] = $cal; 		
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
	    $auto = 'data-ride="carousel" ';
	  } 

	  $interval = null;
	  if ( isset($this->params['interval']) ) {
	    $interval = 'data-interval="'.($this->params['interval']*1000).'" ';
	  }

	  $hover = $this->hover;
		if ( in_array('pause', $this->params) ) {
	    $hover = 'hover';
	  }
	  if ( 'false' == $hover ) {
	  	$pause = 'data-pause="'.$hover.'" ';
	  }

	  $carousel .= PHP_EOL;
    $carousel .= '<div style="width:100%;" id="spl-carousel-'.$this->id.'" class="carousel carousel-hero slide" '.$auto.$pause.$interval.'>'.PHP_EOL;
    
    /*
    if ( !$this->kiosk ) {
			$carousel .= '<div class="carousel-controls spl-hero-panel spl-hero-success">'.PHP_EOL;
      if ( !empty($this->title) ) {
	      $carousel .= '<div class="row">'.PHP_EOL;
	  		$carousel .= '<div class="col-md-5">'.PHP_EOL;
	      $carousel .= '<h3 class="hidden-xs hidden-sm text-center serif">'.$this->title.'</h3>'.PHP_EOL;
	      $carousel .= '</div>'.PHP_EOL; // .col
	      if ( !empty($this->subtitle) ) {
		      $carousel .= '<div class="col-md-7">'.PHP_EOL;
		      $carousel .= '<h3 class="hidden-xs hidden-sm text-center"><small>'.$this->subtitle.'</small></h3>'.PHP_EOL;
		      $carousel .= '</div>'.PHP_EOL; // .col
	    	}
				$carousel .= '</div>'.PHP_EOL; // .row
			}
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
		*/

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

    if ( !$this->kiosk ) {
			$carousel .= '<div class="carousel-controls spl-hero-panel spl-hero-success">'.PHP_EOL;
      if ( !empty($this->title) ) {
	      $carousel .= '<div class="row">'.PHP_EOL;
	  		$carousel .= '<div class="col-md-5">'.PHP_EOL;
	      $carousel .= '<h3 class="hidden-xs hidden-sm text-center serif">'.$this->title.'</h3>'.PHP_EOL;
	      $carousel .= '</div>'.PHP_EOL; // .col
	      if ( !empty($this->subtitle) ) {
		      $carousel .= '<div class="col-md-7">'.PHP_EOL;
		      $carousel .= '<h3 class="hidden-xs hidden-sm text-center"><small>'.$this->subtitle.'</small></h3>'.PHP_EOL;
		      $carousel .= '</div>'.PHP_EOL; // .col
	    	}
				$carousel .= '</div>'.PHP_EOL; // .row
			}
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

	  $carousel .= '</div>'.PHP_EOL; // .carousel
	  $carousel .= PHP_EOL;

	  if ( isset($this->params['timeout']) ) {
	    // convert minutes to microseconds
	    $timeout = $this->params['timeout'] * (1000 * 60);
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

		$col['left'] = 'col-md-5';
		$col['right'] = 'col-md-7';

		$active = null;
    if ( 0 == $s ) {
      $active = ' active';
    } 
    $html .= '<div class="item'.$active.'">'.PHP_EOL;

    switch ( $slide->format ) {
    	case 'news':
  			$html .= '<div class="row">'.PHP_EOL;
  			
  			$html .= '<div class="col-md-12">'.PHP_EOL;
  			$html .= '<h2 class="text-success" style="margin-top:0;">';
        $html .= $slide->date . ' News: ';
        $html .= '<a href="'.$slide->url.'">'.$slide->title.'</a>';
        $html .= '</h2>'.PHP_EOL;
        $html .= '</div>'.PHP_EOL; // .col

		    $html .= '<div class="'.$col['left'].'">'.PHP_EOL;
		    if ( $slide->img ) {
		    	$html .= '<img class="img-responsive img-rounded img-hero" src="'.$slide->img.'" alt="'.$slide->title.'">'.PHP_EOL;
		    }
		    $html .= '</div>'.PHP_EOL; // .col

		    $html .= '<div class="'.$col['right'].'">'.PHP_EOL;
		    $html .= '<div class="carousel-caption">'.PHP_EOL;
        $html .= '<h2 class="text-muted" style="margin-top:0;">';
        $html .= 'also in this issue&hellip;';
        $html .= '</h2>'.PHP_EOL;
        $html .= '<p class="lead">'.$slide->content.'</p>'.PHP_EOL;
        if ( !empty($slide->url) && !$this->kiosk ) { 
          $html .= '<p class="text-right">'.PHP_EOL;
          $html .= '<a class="btn btn-success" href="'.$slide->url.'"> ';
          $html .= '<i class="glyphicon glyphicon-info-sign"></i>'.PHP_EOL;
          $html .= 'Read Library News <span class="">&rarr;</span>'.PHP_EOL;
          $html .= '</a>'.PHP_EOL;
          $html .= '</p>'.PHP_EOL;
        }
	     	$html .= '</div>'.PHP_EOL; // carousel-caption
				$html .= '</div>'.PHP_EOL; // .col

				$html .= '</div>'.PHP_EOL; // .row
    		break;

    	case 'post':
    		if ( $this->kiosk ) {
    			if ( $slide->img ) {
		    		$html .= '<img class="img-responsive img-rounded img-kiosk" src="'.$slide->img.'" alt="'.$slide->title.'">'.PHP_EOL;
		    	}
    		} else {
			    $html .= '<div class="row">'.PHP_EOL;

			    $html .= '<div class="col-md-12">'.PHP_EOL;
	  			$html .= '<h2 class="text-success" style="margin-top:0;">';
	        if ( !empty($slide->url) ) {
		    		$html .= '<a href="'.$slide->url.'">';
		    	}
	        $html .= $slide->title;
	        if ( !empty($slide->url) ) {
		    		$html .= '</a>';
		    	}
	        if ( !empty($slide->subtitle) ) {
	          $html .= ' <small style="color:#666;">'.$slide->subtitle.'</small>';
	        }
	        $html .= '</h2>'.PHP_EOL;
	        $html .= '</div>'.PHP_EOL; // .col

			    $html .= '<div class="'.$col['left'].'">'.PHP_EOL;
			    if ( $slide->img ) {
			    	if ( !empty($slide->url) ) {
			    		$html .= '<a href="'.$slide->url.'">';
			    	}
			    	$html .= '<img class="img-responsive img-rounded img-hero" src="'.$slide->img.'" alt="'.$slide->title.'">';
			    	if ( !empty($slide->url) ) {
			    		$html .= '</a>';
			    	}
			    }
			    $html .= '</div>'.PHP_EOL; // .col

			    $html .= '<div class="'.$col['right'].'">'.PHP_EOL;
			    $html .= '<div class="carousel-caption">'.PHP_EOL;
	        
	        $html .= '<p class="lead"><b>'.$slide->content.'</b></p>'.PHP_EOL;
	        if ( !empty($slide->url) ) { 
	          $html .= '<p class="text-right">'.PHP_EOL;
	          $html .= '<a class="btn btn-default" href="'.$slide->url.'"> ';
	          $html .= '<i class="glyphicon glyphicon-info-sign"></i>'.PHP_EOL;
	          $html .= 'Read more on the library blog <span class="">&rarr;</span>'.PHP_EOL;
	          $html .= '</a>'.PHP_EOL;
	          $html .= '</p>'.PHP_EOL;
	        }
		     	$html .= '</div>'.PHP_EOL; // carousel-caption
					$html .= '</div>'.PHP_EOL; // .col

					$html .= '</div>'.PHP_EOL; // .row
				}
    		break;
   		case 'promo':
   			if ( $this->kiosk ) {
    			if ( $slide->img ) {
		    		$html .= '<img class="img-responsive img-rounded img-kiosk" src="'.$slide->img.'" alt="'.$slide->title.'">'.PHP_EOL;
		    	}
    		} else {
			    $html .= '<div class="row">'.PHP_EOL;

			    $html .= '<div class="'.$col['left'].'">'.PHP_EOL;
			    $html .= $slide->promo.PHP_EOL;
			    $html .= '</div>'.PHP_EOL; // .col

			    $html .= '<div class="'.$col['right'].'">'.PHP_EOL;
			    $html .= '<div class="carousel-caption">'.PHP_EOL;
	        $html .= '<h2 class="text-success" style="margin-top:0;">';
	        if ( !empty($slide->url) ) {
		    		$html .= '<a href="'.$slide->url.'">';
		    	}
	        $html .= $slide->title;
	        if ( !empty($slide->url) ) {
		    		$html .= '</a>';
		    	}
	        if ( !empty($slide->subtitle) ) {
	          $html .= ' <small style="color:#666;">'.$slide->subtitle.'</small>';
	        }
	        $html .= '</h2>'.PHP_EOL;
	        $html .= '<p class="lead"><b>'.$slide->content.'</b></p>'.PHP_EOL;
	        if ( !empty($slide->url) ) { 
	          $html .= '<p class="text-right">'.PHP_EOL;
	          $html .= '<a class="btn btn-info" href="'.$slide->url.'"> ';
	          $html .= '<i class="glyphicon glyphicon-info-sign"></i>'.PHP_EOL;
	          $html .= 'Learn more <span class="">&rarr;</span>'.PHP_EOL;
	          $html .= '</a>'.PHP_EOL;
	          $html .= '</p>'.PHP_EOL;
	        }
		     	$html .= '</div>'.PHP_EOL; // carousel-caption
					$html .= '</div>'.PHP_EOL; // .col

					$html .= '</div>'.PHP_EOL; // .row
				}
   			break;
   		case 'calendar':
    		if ( $this->kiosk ) {
    			if ( $slide->img ) {
		    		$html .= '<img class="img-responsive img-rounded img-kiosk" src="'.$slide->img.'" alt="'.$slide->title.'">'.PHP_EOL;
		    	}
    		} else {
			    $html .= '<div class="row">'.PHP_EOL;

			    $html .= '<div class="col-md-12">'.PHP_EOL;
	  			$html .= '<h2 class="text-success" style="margin-top:0;">';
	        if ( !empty($slide->url) ) {
		    		$html .= '<a href="'.$slide->url.'">';
		    	}
	        $html .= $slide->title;
	        if ( !empty($slide->url) ) {
		    		$html .= '</a>';
		    	}
	        if ( !empty($slide->subtitle) ) {
	          $html .= ' <small style="color:#666;">'.$slide->subtitle.'</small>';
	        }
	        $html .= '</h2>'.PHP_EOL;
	        $html .= '</div>'.PHP_EOL; // .col

			    $html .= '<div class="'.$col['left'].'">'.PHP_EOL;
			    if ( $slide->img ) {
			    	if ( !empty($slide->url) ) {
			    		$html .= '<a href="'.$slide->url.'">';
			    	}
			    	$html .= '<img class="img-responsive img-rounded img-hero" src="'.$slide->img.'" alt="'.$slide->title.'">';
			    	if ( !empty($slide->url) ) {
			    		$html .= '</a>';
			    	}
			    }
			    $html .= '</div>'.PHP_EOL; // .col

			    $html .= '<div class="'.$col['right'].'">'.PHP_EOL;
			    $html .= '<div class="carousel-caption">'.PHP_EOL;
	        
	        if ( !empty($slide->location) ) {
	        	$html .= '<h3 class="text-muted" style="margin-top:0;">'.PHP_EOL;
 						if ( !empty($slide->url) ) { 
 							//$html .= '@'.PHP_EOL;
 							$html .= '<a href="'.$slide->url.'">';
		        	$html .= '<span class="text-success">' . $slide->location.'</span>';
		        	$html .= '</a>'.PHP_EOL;
		      	} else {
		      		//$html .= '@'.PHP_EOL;
		      		$html .= $slide->location.PHP_EOL;
		      	}
		      	$html .= '</h3>'.PHP_EOL;
	      	}

	        $html .= '<h4 class="text-warning">'.PHP_EOL;
	        $html .= '<i class="glyphicon glyphicon-time"></i>'.PHP_EOL;
	        $html .= $slide->datetime.PHP_EOL;
	        $html .= '</h4>'.PHP_EOL;

	        $html .= '<p class="lead"><b>'.$slide->content.'</b></p>'.PHP_EOL;
	        if ( !empty($slide->url) ) { 
	          $html .= '<p class="text-right">'.PHP_EOL;
	          $html .= '<a class="btn btn-warning" href="'.$slide->url.'"> ';
	          $html .= '<i class="glyphicon glyphicon-info-sign"></i>'.PHP_EOL;
	          $html .= 'Learn more or sign up <span class="">&rarr;</span>'.PHP_EOL;
	          $html .= '</a>'.PHP_EOL;
	          $html .= '</p>'.PHP_EOL;
	        }
		     	$html .= '</div>'.PHP_EOL; // carousel-caption
					$html .= '</div>'.PHP_EOL; // .col

					$html .= '</div>'.PHP_EOL; // .row
				}
				break;
    	default:
    		if ( $this->kiosk ) {
    			if ( $slide->img ) {
		    		$html .= '<img class="img-responsive img-rounded img-kiosk" src="'.$slide->img.'" alt="'.$slide->title.'">'.PHP_EOL;
		    	}
    		} else {
			    $html .= '<div class="row">'.PHP_EOL;

			    $html .= '<div class="'.$col['left'].'">'.PHP_EOL;
			    if ( $slide->img ) {
			    	if ( !empty($slide->url) ) {
			    		$html .= '<a href="'.$slide->url.'">';
			    	}
			    	$html .= '<img class="img-responsive img-rounded img-hero" src="'.$slide->img.'" alt="'.$slide->title.'">';
			    	if ( !empty($slide->url) ) {
			    		$html .= '</a>';
			    	}
			    }
			    $html .= '</div>'.PHP_EOL; // .col

			    $html .= '<div class="'.$col['right'].'">'.PHP_EOL;
			    $html .= '<div class="carousel-caption">'.PHP_EOL;
	        $html .= '<h2 class="text-success" style="margin-top:0;">';
	        if ( !empty($slide->url) ) {
		    		$html .= '<a href="'.$slide->url.'">';
		    	}
	        $html .= $slide->title;
	        if ( !empty($slide->url) ) {
		    		$html .= '</a>';
		    	}
	        if ( !empty($slide->subtitle) ) {
	          $html .= ' <small style="color:#666;">'.$slide->subtitle.'</small>';
	        }
	        $html .= '</h2>'.PHP_EOL;
	        $html .= '<p class="lead"><b>'.$slide->content.'</b></p>'.PHP_EOL;
	        if ( !empty($slide->url) ) { 
	          $html .= '<p class="text-right">'.PHP_EOL;
	          $html .= '<a class="btn btn-link" href="'.$slide->url.'"> ';
	          $html .= '<i class="glyphicon glyphicon-info-sign"></i>'.PHP_EOL;
	          $html .= 'Learn more <span class="">&rarr;</span>'.PHP_EOL;
	          $html .= '</a>'.PHP_EOL;
	          $html .= '</p>'.PHP_EOL;
	        }
		     	$html .= '</div>'.PHP_EOL; // carousel-caption
					$html .= '</div>'.PHP_EOL; // .col

					$html .= '</div>'.PHP_EOL; // .row
				}
				break;
			}
		$html .= '</div>'.PHP_EOL; // .item

		return $html;
	}

	protected function getCarouselNews() {
		$slide = new stdClass;
		$slide->format = 'news';
		$slide->url = '/news/';

		$slide->img = 'http://news.spokanelibrary.org/wordpress/media/HoF-SpotlightIcon-300x181.jpg';
		$slide->title = 'Short Month, Not Short on Opportunities at Your Library!';
		$slide->date = 'February';
		$slide->content = '
		<ul class="text-muted">
    <li><a href="http://news.spokanelibrary.org/rfid_mobius/"><b>ch-ch-changes...</b> <small class="text-muted">&rarr;</small></a></li>
    <li><a href="http://news.spokanelibrary.org/habla-espanol/"><b>Habla Español?</b> <small class="text-muted">&rarr;</small></a></li>
    <li><a href="http://news.spokanelibrary.org/dewey_2-15/"><b>Dewey Celebrates African American History Month</b> <small class="text-muted">&rarr;</small></a></li>
    <li><a href="http://news.spokanelibrary.org/5-songs-2-15/"><b>Prepping for the Symphony with Freegal</b> <small class="text-muted">&rarr;</small></a></li>
    </ul>  
		';
		/*
		$slide->img = 'http://news.spokanelibrary.org/wordpress/media/Shadle_Sunday_hours2-300x282.jpg';
		$slide->title = 'New Year, New You, New Day for the Library';
		//$slide->subtitle = 'Subtitle';
		$slide->date = 'January';
		$slide->content = '
		<ul class="text-muted">
    <li><a href="http://news.spokanelibrary.org/new-year-new-you/"><b>What’s on your “to do” list for 2015?</b> <small class="text-muted">&rarr;</small></a></li>
    <li><a href="http://news.spokanelibrary.org/dewey_1-15/"><b>Dewey’s (self) helpful side</b> <small class="text-muted">&rarr;</small></a></li>
    <li><a href="http://news.spokanelibrary.org/5_magazines_1-15/"><b>Five Magazines instead of Five Songs This Month</b> <small class="text-muted">&rarr;</small></a></li>
    </ul>  
		';
		*/

		return $slide;
	}

	protected function getCarouselPromo($promo) {
		$slide = new stdClass;
		$slide->format = 'promo';

		switch ($promo) {
			case 'tech':
				$slide->url = '/tech/';
				//$slide->img = 'img.png';
				$slide->title = 'Technology training & certification';
				$slide->subtitle = 'Self-paced or instructor-led courses for all skill levels.';
				$slide->content = '<i>Windows, Office, SQL Server, Illustrator, InDesign,</i> and much more: Learn the latest applications and technologies from industry professionals.';
				$slide->promo = '
				<div class="panel panel-primary">
					<div class="panel-body">
						<div class="text-center" style="margin-bottom:10px;">
					  	<a href="/it-academy/"><img style="margin:auto;" class="img-responsive img-rounded" src="/assets/img/logos/itacademy.png"></a>
							<p>  		
								<small>
									<b>Learn Microsoft products and qualify for certification testing</b>
						  	</small>				  	
							</p>
						</div>

						<div class="text-center">
					  	<a href="/gale-courses/"><img style="margin:auto;" class="img-responsive img-rounded" src="/assets/img/logos/galecourses.png"></a>
					  	<p>
								<small>
									<b>Taught by college instructors and industry experts</b>						
						  	</small>
							</p>
						</div>
					</div>
				</div>
				';
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

	protected function getCarouselCalendar($limit=3, $chars=175) {
		$feed = null;
		$uri = 'http://www.trumba.com/calendars/spl-web-feed.rss';
		add_filter( 'wp_feed_cache_transient_lifetime', function() {
         return 0;
    });
		$rss = fetch_feed( $uri );
		remove_all_filters( 'wp_feed_cache_transient_lifetime' );
    if ( ! is_wp_error( $rss ) ) {
        $maxitems = $rss->get_item_quantity( $limit ); 
        $feed = $rss->get_items( 0, $maxitems );
    }

    $slides = null;
    if ( is_array($feed) ) {
    	$slides = array();
    	foreach ( $feed as $item ) {
        $event = new stdClass();

        $location = $item->get_item_tags('urn:ietf:params:xml:ns:xcal', 'location');
        $formatteddatetime = $item->get_item_tags('http://schemas.trumba.com/rss/x-trumba', 'formatteddatetime');
        $description = $item->get_item_tags('urn:ietf:params:xml:ns:xcal', 'description');
        $customfields = $item->get_item_tags('http://schemas.trumba.com/rss/x-trumba', 'customfield');

        $event->format = 'calendar';
        $event->title = esc_html( $item->get_title() );
        $event->url = esc_url( $item->get_permalink() );
        $event->datetime = esc_html( $formatteddatetime[0]['data'] );
				$event->location = esc_html( $location[0]['data'] );

				$description = esc_html( $description[0]['data'] );
				$description = substr($description,0,$chars);
     		$description = substr($description,0,strrpos($description," "));
     		if (substr($description, -1) != '.') {
     			$description = $description.'&hellip;';
     		}
     		$event->content = $description;

        switch ( $location[0]['data'] ) {
		    	case 'East Side Library':
		    		$event->branch = 'east-side';
		    		break;
		    	case 'Hillyard Library':
		    		$event->branch = 'hillyard';
		    		break;
		    	case 'Indian Trail Library':
		    		$event->branch = 'indian-trail';
		    		break;
		    	case 'Shadle Library':
		    		$event->branch = 'shadle';
		    		break;
		    	case 'South Hill Library':
		    		$event->branch = 'south-hill';
		    		break;
		    	case 'Downtown Library':
		    		$event->branch = 'downtown';
		    		break;
		    	default:
		    		break;
		    }
		   	if ( is_array($customfields) ) {
		   		foreach( $customfields as $f => $field ) {
		   			$fieldname = trim($field['attribs']['']['name']);
		   			switch ( $fieldname ) 	{
		   				case 'Event image':
		   					$event->img = $field['data'];
		   					break;
		   				default:
		   					//$field['attribs']['']['name'];
		   					//$field['data'];
		   					break;
		   			}
			  	}
		   	}
    	  $slides[] = $event;
      }
    }

		return $slides;
	}

	protected function getCarouselPosts($limit=3, $days=14, $category='featured') {
		$slides = null; 

	  $args = array(
	    'post_type' => 'post',
	    'orderby'   => 'post_date',
	    'order'     => 'DESC',
	    'post_status' => 'publish',
	    //'numberposts' => $limit,
	    'posts_per_page' => $limit,
	    'category_name' => $category,
	    'date_query' => array( 'column' => 'post_date'
	    											,'after' => '-'.$days.' days' )
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