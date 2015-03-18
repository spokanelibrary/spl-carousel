<?php

/**
 * @package SPL_Carousel
 * @version 0.1
 */

/*
Plugin Name: SPL Carousel
Plugin URI: http://www.spokanelibrary.org/
Description: Hooks the <code>[spl_carousel]</code> shortcode to show a <a href="http://twitter.github.io/bootstrap/javascript.html#carousel" target="_blank">Bootstrap Carousel</a>.
Author: Sean Girard
Author URI: http://seangirard.com
Version: 0.1
*/

//add_filter( 'use_default_gallery_style', '__return_false' );

function wp_spl_carousel($atts) {
  
  $id = get_the_ID();

  if ( isset($atts['slug']) ) {
    $imgPage = get_page_by_path($atts['slug']);
  }
  if ( $imgPage ) {
    $id = $imgPage->ID;
  }

  $orderby = 'menu_order';
  if ( in_array('random', $atts) ) {
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

  $auto = null;
  if ( in_array('auto', $atts) ) {
    $auto = 'data-ride="carousel"';
  } 

  if ( isset($atts['interval']) ) {

    $interval = 'data-interval="'.($atts['interval']*1000).'"';
  }

  if ($attachments) {
    $carousel .= ''.PHP_EOL;
    $carousel .= '<div style="width:100%;" id="spl-carousel-'.$id.'" class="carousel slide" '.$auto.' '.$interval.'>'.PHP_EOL;
    
    if ( !in_array('kiosk', $atts) ) {
      $i = 0;
      $carousel .= '<ol class="carousel-indicators">'.PHP_EOL;  
      foreach ($attachments as $attachment) {
        $active = null;
        if ( 0 == $i ) {
          $active = ' class="active"';
        } 
        $carousel .= '<li data-target="#spl-carousel-'.$id.'" data-slide-to="'.$i.'"'.$active.'></li>'.PHP_EOL;
        $i++;
      }
      $carousel .= '</ol>'.PHP_EOL; 
    }  

    $carousel .= '<div class="carousel-inner">'.PHP_EOL;
    
    $i = 0;
    foreach ($attachments as $attachment) {
      
      $alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
      
      $active = null;
      if ( 0 == $i ) {
        $active = ' active';
      } 

      $carousel .= '<div class="item'.$active.'">'.PHP_EOL;

      if ( !empty($alt) ) { 
        $carousel .= '<a href="'.$alt.'">'.PHP_EOL;
      }
      $carousel .= '<img class="img-rounded" src="'.$attachment->guid.'" alt="'.$attachment->post_title.'">'.PHP_EOL;
      if ( !empty($alt) ) { 
        $carousel .= '</a>'.PHP_EOL;
      }

      if ( !in_array('kiosk', $atts) ) {
        $carousel .= '<div class="carousel-caption">'.PHP_EOL;

        if ( !empty($alt) ) { 
          $carousel .= '<a class="pull-right" href="'.$alt.'"> ';
          $carousel .= '<b>More</b> <span class="text-muted">&rarr;</span>'.PHP_EOL;
          $carousel .= '</a>'.PHP_EOL;
        }


        $carousel .= '<h4>';
        $carousel .= $attachment->post_title;
        if ( !empty($attachment->post_excerpt) ) {
          $carousel .= ' <small style="color:#666;">'.$attachment->post_excerpt.'</small>';
        }
        $carousel .= '</h4>'.PHP_EOL;
        
        $carousel .= '<p>'.$attachment->post_content.'</p>'.PHP_EOL;

        $carousel .= '</div>'.PHP_EOL;
      }
      $carousel .= '</div>'.PHP_EOL;

      $i++;
    }
    
    $carousel .= '</div>'.PHP_EOL;
    
    if ( !in_array('kiosk', $atts) ) {
      $carousel .= '<a class="left carousel-control" href="#spl-carousel-'.$id.'" data-slide="prev"><span class="glyphicon glyphicon-circle-arrow-left"></span></a>'.PHP_EOL;
      $carousel .= '<a class="right carousel-control" href="#spl-carousel-'.$id.'" data-slide="next"><span class="glyphicon glyphicon-circle-arrow-right"></span></a>'.PHP_EOL;
    }

    $carousel .= '</div>'.PHP_EOL;
    
  }

  if ( isset($atts['timeout']) ) {
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
  
  foreach ($attachments as $attachment) {
    /*
    $carousel .= '<pre>'.PHP_EOL;
    $carousel .= print_r($attachment, true) .PHP_EOL;
    $carousel .= '</pre>'.PHP_EOL;
    */

  }
  

  return $carousel;
}

//remove_shortcode( 'gallery', 'gallery_shortcode' ); /* Remove original shortcode */
//add_shortcode( 'gallery', 'wp_spl_carousel' ); /* Add custom shortcode */

add_shortcode('spl_carousel', 'wp_spl_carousel');










function wp_spl_carousel_photo($atts) {
  
  $id = get_the_ID();

  if ( isset($atts['slug']) ) {
    $imgPage = get_page_by_path($atts['slug']);
  }
  if ( $imgPage ) {
    $id = $imgPage->ID;
  }

  $orderby = 'menu_order';
  if ( in_array('random', $atts) ) {
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

  $auto = null;
  if ( in_array('auto', $atts) ) {
    $auto = 'data-ride="carousel"';
  } 

  if ( isset($atts['interval']) ) {

    $interval = 'data-interval="'.($atts['interval']*1000).'"';
  }

  if ($attachments) {
    $carousel .= ''.PHP_EOL;
    $carousel .= '<div style="width:100%;" id="spl-carousel-'.$id.'" class="carousel slide" '.$auto.' '.$interval.'>'.PHP_EOL;
    
    if ( !in_array('kiosk', $atts) ) {
      $i = 0;
      $carousel .= '<ol class="carousel-indicators">'.PHP_EOL;  
      foreach ($attachments as $attachment) {
        $active = null;
        if ( 0 == $i ) {
          $active = ' class="active"';
        } 
        $carousel .= '<li data-target="#spl-carousel-'.$id.'" data-slide-to="'.$i.'"'.$active.'></li>'.PHP_EOL;
        $i++;
      }
      $carousel .= '</ol>'.PHP_EOL; 
    }  

    $carousel .= '<div class="carousel-inner">'.PHP_EOL;
    
    $i = 0;
    foreach ($attachments as $attachment) {
      
      $alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
      
      $active = null;
      if ( 0 == $i ) {
        $active = ' active';
      } 

      $carousel .= '<div class="item'.$active.'">'.PHP_EOL;

      if ( !empty($alt) ) { 
        $carousel .= '<a href="'.$alt.'">'.PHP_EOL;
      }
      $carousel .= '<img class="" src="'.$attachment->guid.'" alt="'.$attachment->post_title.'">'.PHP_EOL;
      if ( !empty($alt) ) { 
        $carousel .= '</a>'.PHP_EOL;
      }

      if ( !in_array('kiosk', $atts) ) {
        $carousel .= '<div class="row">'.PHP_EOL;
        $carousel .= '<div class="col-sm-8 col-sm-offset-8 col-md-6 col-md-offset-6">'.PHP_EOL;
        $carousel .= '<div style="position:relative;">'.PHP_EOL;
        $carousel .= '<div style="background:#fff; position:absolute; bottom:0; width:100%; margin:10px;">'.PHP_EOL;
        $carousel .= '<div class="carousel-caption">'.PHP_EOL;

        if ( !empty($alt) ) { 
          $carousel .= '<a class="pull-right" href="'.$alt.'"> ';
          $carousel .= '<b>More</b> <span class="text-muted">&rarr;</span>'.PHP_EOL;
          $carousel .= '</a>'.PHP_EOL;
        }


        $carousel .= '<h4>';
        $carousel .= $attachment->post_title;
        if ( !empty($attachment->post_excerpt) ) {
          $carousel .= ' <small style="color:#666;">'.$attachment->post_excerpt.'</small>';
        }
        $carousel .= '</h4>'.PHP_EOL;
        
        $carousel .= '<p>'.$attachment->post_content.'</p>'.PHP_EOL;

        $carousel .= '</div>'.PHP_EOL;
        $carousel .= '</div>'.PHP_EOL;
        $carousel .= '</div>'.PHP_EOL;
        $carousel .= '</div>'.PHP_EOL;
        $carousel .= '</div>'.PHP_EOL;

      }
      $carousel .= '</div>'.PHP_EOL;

      $i++;
    }
    
    $carousel .= '</div>'.PHP_EOL;
    
    if ( !in_array('kiosk', $atts) ) {
      $carousel .= '<a class="left carousel-control" href="#spl-carousel-'.$id.'" data-slide="prev"><span class="glyphicon glyphicon-circle-arrow-left"></span></a>'.PHP_EOL;
      $carousel .= '<a class="right carousel-control" href="#spl-carousel-'.$id.'" data-slide="next"><span class="glyphicon glyphicon-circle-arrow-right"></span></a>'.PHP_EOL;
    }

    $carousel .= '</div>'.PHP_EOL;
    
  }

  if ( isset($atts['timeout']) ) {
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
  
  foreach ($attachments as $attachment) {
    /*
    $carousel .= '<pre>'.PHP_EOL;
    $carousel .= print_r($attachment, true) .PHP_EOL;
    $carousel .= '</pre>'.PHP_EOL;
    */

  }
  

  return $carousel;
}

//remove_shortcode( 'gallery', 'gallery_shortcode' ); /* Remove original shortcode */
//add_shortcode( 'gallery', 'wp_spl_carousel' ); /* Add custom shortcode */

add_shortcode('spl_carousel_photo', 'wp_spl_carousel_photo');
















function wp_spl_carousel_hero($atts) {
  
  $id = get_the_ID();

  if ( isset($atts['slug']) ) {
    $imgPage = get_page_by_path($atts['slug']);
  }
  if ( $imgPage ) {
    $id = $imgPage->ID;
  }

  $orderby = 'menu_order';
  if ( in_array('random', $atts) ) {
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

  $auto = null;
  if ( in_array('auto', $atts) ) {
    $auto = 'data-ride="carousel"';
  } 

  if ( isset($atts['interval']) ) {

    $interval = 'data-interval="'.($atts['interval']*1000).'"';
  }

  if ($attachments) {
    $carousel .= ''.PHP_EOL;
    $carousel .= '<div style="width:100%;" id="spl-carousel-'.$id.'" class="carousel slide" '.$auto.' '.$interval.'>'.PHP_EOL;
    
    if ( !in_array('kiosk', $atts) ) {
      $carousel .= '<div class="row">'.PHP_EOL;
      $carousel .= '<div class="col-md-5">'.PHP_EOL;
      $carousel .= '<ol class="carousel-indicators">'.PHP_EOL;  
      // ToDo: news
      $i = 0;
      foreach ($attachments as $attachment) {
        $active = null;
        if ( 0 == $i ) {
          $active = ' class="active"';
        } 
        $carousel .= '<li data-target="#spl-carousel-'.$id.'" data-slide-to="'.$i.'"'.$active.'></li>'.PHP_EOL;
        $i++;
      }
      $carousel .= '</ol>'.PHP_EOL; 
      $carousel .= '</div>'.PHP_EOL; // col
      $carousel .= '</div>'.PHP_EOL; // row
    }  

    /*
    if ( !in_array('kiosk', $atts) ) {
      $carousel .= '<div class="row">'.PHP_EOL;
      $carousel .= '<div class="col-md-5"  style="z-index:5";>'.PHP_EOL;
      $carousel .= '<a class="left carousel-control hero" href="#spl-carousel-'.$id.'" data-slide="prev"><span class="glyphicon glyphicon-circle-arrow-left"></span></a>'.PHP_EOL;
      $carousel .= '<a class="right carousel-control hero" href="#spl-carousel-'.$id.'" data-slide="next"><span class="glyphicon glyphicon-circle-arrow-right"></span></a>'.PHP_EOL;
      $carousel .= '</div>'.PHP_EOL; // col
      $carousel .= '</div>'.PHP_EOL; // row
    }
    */

    $carousel .= '<div class="carousel-inner">'.PHP_EOL;
    
    // BEGIN NEWSLETTER
    $carousel .= '<div class="item active">'.PHP_EOL;
    
    $carousel .= '<div class="row">'.PHP_EOL;
    $carousel .= '<div class="col-md-12"  style="z-index:16;">'.PHP_EOL;
    $carousel .= '<h2 class="text-success" style="margin-top:0;">';
    $carousel .= 'Library News: ';
    $carousel .= '<a href="#">';
    $carousel .= 'New Year, New You, New Day for the Library';
    $carousel .= '</a>'.PHP_EOL;
    $carousel .= '</h2>'.PHP_EOL;
    $carousel .= '</div>'.PHP_EOL; // col

    $carousel .= '<div class="col-md-1">'.PHP_EOL;
    $carousel .= '&nbsp;'.PHP_EOL;
    $carousel .= '</div>'.PHP_EOL; // col
    $carousel .= '<div class="col-md-3">'.PHP_EOL;
    $news_thumb = true;
    if ( !empty($news_thumb) ) { 
      $carousel .= '<a href="http://news.spokanelibrary.org/newsletter/new-year-new-you-new-day-for-the-library/">'.PHP_EOL;
    }
    $carousel .= '<img class="img-responsive img-rounded" src="http://news.spokanelibrary.org/wordpress/media/Shadle_Sunday_hours2-300x282.jpg" alt="Read Library News">'.PHP_EOL;
    if ( !empty($news_thumb) ) { 
      $carousel .= '</a>'.PHP_EOL;
    }
    $carousel .= '</div>'.PHP_EOL; // col
    $carousel .= '<div class="col-md-1">'.PHP_EOL;
    $carousel .= '&nbsp;'.PHP_EOL;
    $carousel .= '</div>'.PHP_EOL; // col

    $carousel .= '<div class="col-md-7">'.PHP_EOL;

    if ( !in_array('kiosk', $atts) ) {
      $carousel .= '<div class="carousel-caption">'.PHP_EOL;

      $carousel .= '<h3 class="text-muted" style="margin-top:0;">';
      $carousel .= 'also in this issue&hellip;';
      $carousel .= '</h3>'.PHP_EOL;

      $carousel .= '<ul class="nav nav-pills nav-stacked">'.PHP_EOL;
      $carousel .= '<li><a href="http://news.spokanelibrary.org/new-year-new-you/">What’s on your “to do” list for 2015? <small class="text-muted">&rarr;</small></a></li>'.PHP_EOL;
      $carousel .= '<li><a href="http://news.spokanelibrary.org/dewey_1-15/">Dewey’s (self) helpful side <small class="text-muted">&rarr;</small></a></li>'.PHP_EOL;
      $carousel .= '<li><a href="http://news.spokanelibrary.org/5_magazines_1-15/">Five Magazines instead of Five Songs This Month <small class="text-muted">&rarr;</small></a></li>'.PHP_EOL;
      $carousel .= '</ul>'.PHP_EOL;   

      $carousel .= '</div>'.PHP_EOL; // carousel-caption
    }

    $carousel .= '</div>'.PHP_EOL; // col
    $carousel .= '</div>'.PHP_EOL; // row

    $carousel .= '</div>'.PHP_EOL; // item
    // FINISH NEWSLETTER

    $i = 1; //0
    foreach ($attachments as $attachment) {
      
      $alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
      
      $active = null;
      if ( 0 == $i ) {
        $active = ' active';
      } 

      $carousel .= '<div class="item'.$active.'">'.PHP_EOL;

      $carousel .= '<div class="row">'.PHP_EOL;

      $carousel .= '<div class="col-md-5">'.PHP_EOL;

      if ( !empty($alt) ) { 
        $carousel .= '<a href="'.$alt.'">'.PHP_EOL;
      }
      $carousel .= '<img class="img-responsive img-rounded" src="'.$attachment->guid.'" alt="'.$attachment->post_title.'">'.PHP_EOL;
      if ( !empty($alt) ) { 
        $carousel .= '</a>'.PHP_EOL;
      }

      $carousel .= '</div>'.PHP_EOL; // col

      $carousel .= '<div class="col-md-7">'.PHP_EOL;

      if ( !in_array('kiosk', $atts) ) {
        $carousel .= '<div class="carousel-caption">'.PHP_EOL;

        /*
        if ( !empty($alt) ) { 
          $carousel .= '<a class="pull-right" href="'.$alt.'"> ';
          $carousel .= '<b>More</b> <span class="text-muted">&rarr;</span>'.PHP_EOL;
          $carousel .= '</a>'.PHP_EOL;
        }
        */


        $carousel .= '<h2 class="text-success" style="margin-top:0;">';
        $carousel .= $attachment->post_title;
        if ( !empty($attachment->post_excerpt) ) {
          $carousel .= ' <small style="color:#666;">'.$attachment->post_excerpt.'</small>';
        }
        $carousel .= '</h2>'.PHP_EOL;
        
        $carousel .= '<p class="lead">'.$attachment->post_content.'</p>'.PHP_EOL;

        if ( !empty($alt) ) { 
          $carousel .= '<p class="text-right">'.PHP_EOL;
          $carousel .= '<a class="btn btn-default" href="'.$alt.'"> ';
          $carousel .= 'More <span class="text-muted">&rarr;</span>'.PHP_EOL;
          $carousel .= '</a>'.PHP_EOL;
          $carousel .= '</p>'.PHP_EOL;
        }

        $carousel .= '</div>'.PHP_EOL; // carousel-caption
      }

      $carousel .= '</div>'.PHP_EOL; // col
      $carousel .= '</div>'.PHP_EOL; // row


      $carousel .= '</div>'.PHP_EOL; // item?

      $i++;
    }

    $carousel .= '</div>'.PHP_EOL; // carousel-inner
    /*
    if ( !in_array('kiosk', $atts) ) {
      $carousel .= '<a style="top:0" class="left carousel-control hero" href="#spl-carousel-'.$id.'" data-slide="prev"><span class="glyphicon glyphicon-circle-arrow-left"></span></a>'.PHP_EOL;
      $carousel .= '<a style="top:0" class="right carousel-control hero" href="#spl-carousel-'.$id.'" data-slide="next"><span class="glyphicon glyphicon-circle-arrow-right"></span></a>'.PHP_EOL;
    }
    */
    if ( !in_array('kiosk', $atts) ) {
      $carousel .= '<div class="row">'.PHP_EOL;
      $carousel .= '<div class="col-md-5"  style="z-index:5";>'.PHP_EOL;
      $carousel .= '<a class="left carousel-control hero" href="#spl-carousel-'.$id.'" data-slide="prev"><span class="glyphicon glyphicon-circle-arrow-left"></span></a>'.PHP_EOL;
      $carousel .= '<a class="right carousel-control hero" href="#spl-carousel-'.$id.'" data-slide="next"><span class="glyphicon glyphicon-circle-arrow-right"></span></a>'.PHP_EOL;
      $carousel .= '</div>'.PHP_EOL; // col
      $carousel .= '</div>'.PHP_EOL; // row
    }

    $carousel .= '</div>'.PHP_EOL; // carousel
    
  }

  if ( isset($atts['timeout']) ) {
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
  
  foreach ($attachments as $attachment) {
    /*
    $carousel .= '<pre>'.PHP_EOL;
    $carousel .= print_r($attachment, true) .PHP_EOL;
    $carousel .= '</pre>'.PHP_EOL;
    */

  }
  

  return $carousel;
}

//remove_shortcode( 'gallery', 'gallery_shortcode' ); /* Remove original shortcode */
//add_shortcode( 'gallery', 'wp_spl_carousel' ); /* Add custom shortcode */

add_shortcode('spl_carousel_hero', 'wp_spl_carousel_hero');












function wp_spl_carousel_get_news() {

  return 'news';
}

function wp_spl_carousel_get_promo() {
  return 'promo';
}

function wp_spl_carousel_get_slides() {
  return 'slides';
}

function wp_spl_carousel_beta($params) {

  require_once 'class/SPL_Carousel.php';
  $carousel = new SPL_Carousel($params);

  if ( is_object($carousel) ) {
    return $carousel->output();
  }

  $html = null;

  $html .= wp_spl_carousel_get_news();
  $html .= wp_spl_carousel_get_promo();
  $html .= wp_spl_carousel_get_slides();

  return $html;

  $id = get_the_ID();

  if ( isset($atts['slug']) ) {
    $imgPage = get_page_by_path($atts['slug']);
  }
  if ( $imgPage ) {
    $id = $imgPage->ID;
  }

  $orderby = 'menu_order';
  if ( in_array('random', $atts) ) {
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

  $auto = null;
  if ( in_array('auto', $atts) ) {
    $auto = 'data-ride="carousel"';
  } 

  if ( isset($atts['interval']) ) {

    $interval = 'data-interval="'.($atts['interval']*1000).'"';
  }

  if ($attachments) {
    $carousel .= ''.PHP_EOL;
    $carousel .= '<div style="width:100%;" id="spl-carousel-'.$id.'" class="carousel slide" '.$auto.' '.$interval.'>'.PHP_EOL;
    
    if ( !in_array('kiosk', $atts) ) {
      $carousel .= '<div class="row">'.PHP_EOL;
      $carousel .= '<div class="col-md-5">'.PHP_EOL;
      $carousel .= '<ol class="carousel-indicators">'.PHP_EOL;  
      // ToDo: news
      $i = 0;
      foreach ($attachments as $attachment) {
        $active = null;
        if ( 0 == $i ) {
          $active = ' class="active"';
        } 
        $carousel .= '<li data-target="#spl-carousel-'.$id.'" data-slide-to="'.$i.'"'.$active.'></li>'.PHP_EOL;
        $i++;
      }
      $carousel .= '</ol>'.PHP_EOL; 
      $carousel .= '</div>'.PHP_EOL; // col
      $carousel .= '</div>'.PHP_EOL; // row
    }  

    /*
    if ( !in_array('kiosk', $atts) ) {
      $carousel .= '<div class="row">'.PHP_EOL;
      $carousel .= '<div class="col-md-5"  style="z-index:5";>'.PHP_EOL;
      $carousel .= '<a class="left carousel-control hero" href="#spl-carousel-'.$id.'" data-slide="prev"><span class="glyphicon glyphicon-circle-arrow-left"></span></a>'.PHP_EOL;
      $carousel .= '<a class="right carousel-control hero" href="#spl-carousel-'.$id.'" data-slide="next"><span class="glyphicon glyphicon-circle-arrow-right"></span></a>'.PHP_EOL;
      $carousel .= '</div>'.PHP_EOL; // col
      $carousel .= '</div>'.PHP_EOL; // row
    }
    */

    $carousel .= '<div class="carousel-inner">'.PHP_EOL;
    
    // BEGIN NEWSLETTER
    $carousel .= '<div class="item active">'.PHP_EOL;
    
    $carousel .= '<div class="row">'.PHP_EOL;
    $carousel .= '<div class="col-md-12"  style="z-index:16;">'.PHP_EOL;
    $carousel .= '<h2 class="text-success" style="margin-top:0;">';
    $carousel .= 'Library News: ';
    $carousel .= '<a href="#">';
    $carousel .= 'New Year, New You, New Day for the Library';
    $carousel .= '</a>'.PHP_EOL;
    $carousel .= '</h2>'.PHP_EOL;
    $carousel .= '</div>'.PHP_EOL; // col

    $carousel .= '<div class="col-md-1">'.PHP_EOL;
    $carousel .= '&nbsp;'.PHP_EOL;
    $carousel .= '</div>'.PHP_EOL; // col
    $carousel .= '<div class="col-md-3">'.PHP_EOL;
    $news_thumb = true;
    if ( !empty($news_thumb) ) { 
      $carousel .= '<a href="http://news.spokanelibrary.org/newsletter/new-year-new-you-new-day-for-the-library/">'.PHP_EOL;
    }
    $carousel .= '<img class="img-responsive img-rounded" src="http://news.spokanelibrary.org/wordpress/media/Shadle_Sunday_hours2-300x282.jpg" alt="Read Library News">'.PHP_EOL;
    if ( !empty($news_thumb) ) { 
      $carousel .= '</a>'.PHP_EOL;
    }
    $carousel .= '</div>'.PHP_EOL; // col
    $carousel .= '<div class="col-md-1">'.PHP_EOL;
    $carousel .= '&nbsp;'.PHP_EOL;
    $carousel .= '</div>'.PHP_EOL; // col

    $carousel .= '<div class="col-md-7">'.PHP_EOL;

    if ( !in_array('kiosk', $atts) ) {
      $carousel .= '<div class="carousel-caption">'.PHP_EOL;

      $carousel .= '<h3 class="text-muted" style="margin-top:0;">';
      $carousel .= 'also in this issue&hellip;';
      $carousel .= '</h3>'.PHP_EOL;

      $carousel .= '<ul class="nav nav-pills nav-stacked">'.PHP_EOL;
      $carousel .= '<li><a href="http://news.spokanelibrary.org/new-year-new-you/">What’s on your “to do” list for 2015? <small class="text-muted">&rarr;</small></a></li>'.PHP_EOL;
      $carousel .= '<li><a href="http://news.spokanelibrary.org/dewey_1-15/">Dewey’s (self) helpful side <small class="text-muted">&rarr;</small></a></li>'.PHP_EOL;
      $carousel .= '<li><a href="http://news.spokanelibrary.org/5_magazines_1-15/">Five Magazines instead of Five Songs This Month <small class="text-muted">&rarr;</small></a></li>'.PHP_EOL;
      $carousel .= '</ul>'.PHP_EOL;   

      $carousel .= '</div>'.PHP_EOL; // carousel-caption
    }

    $carousel .= '</div>'.PHP_EOL; // col
    $carousel .= '</div>'.PHP_EOL; // row

    $carousel .= '</div>'.PHP_EOL; // item
    // FINISH NEWSLETTER

    $i = 1; //0
    foreach ($attachments as $attachment) {
      
      $alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
      
      $active = null;
      if ( 0 == $i ) {
        $active = ' active';
      } 

      $carousel .= '<div class="item'.$active.'">'.PHP_EOL;

      $carousel .= '<div class="row">'.PHP_EOL;

      $carousel .= '<div class="col-md-5">'.PHP_EOL;

      if ( !empty($alt) ) { 
        $carousel .= '<a href="'.$alt.'">'.PHP_EOL;
      }
      $carousel .= '<img class="img-responsive img-rounded" src="'.$attachment->guid.'" alt="'.$attachment->post_title.'">'.PHP_EOL;
      if ( !empty($alt) ) { 
        $carousel .= '</a>'.PHP_EOL;
      }

      $carousel .= '</div>'.PHP_EOL; // col

      $carousel .= '<div class="col-md-7">'.PHP_EOL;

      if ( !in_array('kiosk', $atts) ) {
        $carousel .= '<div class="carousel-caption">'.PHP_EOL;

        /*
        if ( !empty($alt) ) { 
          $carousel .= '<a class="pull-right" href="'.$alt.'"> ';
          $carousel .= '<b>More</b> <span class="text-muted">&rarr;</span>'.PHP_EOL;
          $carousel .= '</a>'.PHP_EOL;
        }
        */


        $carousel .= '<h2 class="text-success" style="margin-top:0;">';
        $carousel .= $attachment->post_title;
        if ( !empty($attachment->post_excerpt) ) {
          $carousel .= ' <small style="color:#666;">'.$attachment->post_excerpt.'</small>';
        }
        $carousel .= '</h2>'.PHP_EOL;
        
        $carousel .= '<p class="lead">'.$attachment->post_content.'</p>'.PHP_EOL;

        if ( !empty($alt) ) { 
          $carousel .= '<p class="text-right">'.PHP_EOL;
          $carousel .= '<a class="btn btn-default" href="'.$alt.'"> ';
          $carousel .= 'More <span class="text-muted">&rarr;</span>'.PHP_EOL;
          $carousel .= '</a>'.PHP_EOL;
          $carousel .= '</p>'.PHP_EOL;
        }

        $carousel .= '</div>'.PHP_EOL; // carousel-caption
      }

      $carousel .= '</div>'.PHP_EOL; // col
      $carousel .= '</div>'.PHP_EOL; // row


      $carousel .= '</div>'.PHP_EOL; // item?

      $i++;
    }

    $carousel .= '</div>'.PHP_EOL; // carousel-inner
    /*
    if ( !in_array('kiosk', $atts) ) {
      $carousel .= '<a style="top:0" class="left carousel-control hero" href="#spl-carousel-'.$id.'" data-slide="prev"><span class="glyphicon glyphicon-circle-arrow-left"></span></a>'.PHP_EOL;
      $carousel .= '<a style="top:0" class="right carousel-control hero" href="#spl-carousel-'.$id.'" data-slide="next"><span class="glyphicon glyphicon-circle-arrow-right"></span></a>'.PHP_EOL;
    }
    */
    if ( !in_array('kiosk', $atts) ) {
      $carousel .= '<div class="row">'.PHP_EOL;
      $carousel .= '<div class="col-md-5"  style="z-index:5";>'.PHP_EOL;
      $carousel .= '<a class="left carousel-control hero" href="#spl-carousel-'.$id.'" data-slide="prev"><span class="glyphicon glyphicon-circle-arrow-left"></span></a>'.PHP_EOL;
      $carousel .= '<a class="right carousel-control hero" href="#spl-carousel-'.$id.'" data-slide="next"><span class="glyphicon glyphicon-circle-arrow-right"></span></a>'.PHP_EOL;
      $carousel .= '</div>'.PHP_EOL; // col
      $carousel .= '</div>'.PHP_EOL; // row
    }

    $carousel .= '</div>'.PHP_EOL; // carousel
    
  }

  if ( isset($atts['timeout']) ) {
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
  
  foreach ($attachments as $attachment) {
    /*
    $carousel .= '<pre>'.PHP_EOL;
    $carousel .= print_r($attachment, true) .PHP_EOL;
    $carousel .= '</pre>'.PHP_EOL;
    */

  }
  

  return $carousel;
}

//remove_shortcode( 'gallery', 'gallery_shortcode' ); /* Remove original shortcode */
//add_shortcode( 'gallery', 'wp_spl_carousel' ); /* Add custom shortcode */

add_shortcode('spl_carousel_beta', 'wp_spl_carousel_beta');









?>