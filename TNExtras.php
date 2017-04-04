<?php
/*
MU Plugin: TechNutty Extras
Plugin Name: TechNutty Extras
Plugin URI: https://technutty.co.uk
Description: Just some extra PHP code to ensure Wordpress and its plugins behave themselves.
Version: 1.0
Author: Nicholas Griffin
Author URI: https://nicholasgriffin.co.uk
*/
// Remove Jetpack CSS
add_filter( 'jetpack_implode_frontend_css', '__return_false' );

function remove_jetpack_styles() {
  wp_deregister_style( 'AtD_style' ); // After the Deadline
  wp_deregister_style( 'jetpack_likes' ); // Likes
  wp_deregister_style( 'jetpack_related-posts' ); //Related Posts
  wp_deregister_style( 'jetpack-carousel' ); // Carousel
  wp_deregister_style( 'grunion.css' ); // Grunion contact form
  wp_deregister_style( 'the-neverending-homepage' ); // Infinite Scroll
  wp_deregister_style( 'infinity-twentyten' ); // Infinite Scroll - Twentyten Theme
  wp_deregister_style( 'infinity-twentyeleven' ); // Infinite Scroll - Twentyeleven Theme
  wp_deregister_style( 'infinity-twentytwelve' ); // Infinite Scroll - Twentytwelve Theme
  wp_deregister_style( 'noticons' ); // Notes
  wp_deregister_style( 'post-by-email' ); // Post by Email
  wp_deregister_style( 'publicize' ); // Publicize
  wp_deregister_style( 'sharedaddy' ); // Sharedaddy
  wp_deregister_style( 'sharing' ); // Sharedaddy Sharing
  wp_deregister_style( 'stats_reports_css' ); // Stats
  wp_deregister_style( 'jetpack-widgets' ); // Widgets
  wp_deregister_style( 'jetpack-slideshow' ); // Slideshows
  wp_deregister_style( 'presentations' ); // Presentation shortcode
  wp_deregister_style( 'jetpack-subscriptions' ); // Subscriptions
  wp_deregister_style( 'tiled-gallery' ); // Tiled Galleries
  wp_deregister_style( 'widget-conditions' ); // Widget Visibility
  wp_deregister_style( 'jetpack_display_posts_widget' ); // Display Posts Widget
  wp_deregister_style( 'gravatar-profile-widget' ); // Gravatar Widget
  wp_deregister_style( 'widget-grid-and-list' ); // Top Posts widget
  wp_deregister_style( 'jetpack-widgets' ); // Widgets
}
add_action('wp_print_styles', 'remove_jetpack_styles' );

// display featured post thumbnails in RSS feeds
function TechNutty_rss_thumbs( $content ) {
    global $post;
    if( has_post_thumbnail( $post->ID ) ) {
        $content = '<figure>' . get_the_post_thumbnail( $post->ID, 'thumbnail' ) . '</figure>' . $content;
    }
    return $content;
}
add_filter( 'the_excerpt_rss', 'TechNutty_rss_thumbs' );
add_filter( 'the_content_feed', 'TechNutty_rss_thumbs' );


// Remove image sizes
add_action('init', 'remove_plugin_image_sizes_ga');
function remove_plugin_image_sizes() {
	remove_image_size('guest-author-32');
remove_image_size('guest-author-64');
remove_image_size('guest-author-96');
remove_image_size('guest-author-128');
remove_image_size('wcslider');
remove_image_size('wccarousel');
remove_image_size('wccarouselsmall');
}

// Remove wp-oembed
add_action( 'init', function() {
    // Remove the REST API endpoint.
    remove_action('rest_api_init', 'wp_oembed_register_route');
    // Turn off oEmbed auto discovery.
    // Don't filter oEmbed results.
    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
    // Remove oEmbed discovery links.
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    // Remove oEmbed-specific JavaScript from the front-end and back-end.
    remove_action('wp_head', 'wp_oembed_add_host_js');
}, PHP_INT_MAX - 1 );

// Custom login stuff
function login_checked_remember_me() {
add_filter( 'login_footer', 'rememberme_checked' );
}
add_action( 'init', 'login_checked_remember_me' );
function rememberme_checked() {
echo "<script>document.getElementById('rememberme').checked = true;</script>";
}
function wrong_login() {
  return 'Wrong username or password.';
}
add_filter('login_errors', 'wrong_login');
function my_custom_login() {
echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('stylesheet_directory') . '/login/custom-login-styles.css?v=1.8" />';
}
add_action('login_head', 'my_custom_login');
function my_login_logo_url() {
return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'my_login_logo_url' );
function my_login_logo_url_title() {
return 'TechNutty | Homepage';
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );
function remove_style_id($link) {
        return preg_replace("/id='.*-css'/", "", $link);
}
add_filter('style_loader_tag', 'remove_style_id');
function wpcontent_svg_mime_type( $mimes = array() ) {
  $mimes['svg']  = 'image/svg+xml';
  $mimes['svgz'] = 'image/svg+xml';
  return $mimes;
}
add_filter( 'upload_mimes', 'wpcontent_svg_mime_type' );

//Remove Emoji and other Wordpress crap

function disable_emoji_dequeue_script() {
    wp_dequeue_script( 'emoji' );
}
add_action( 'wp_print_scripts', 'disable_emoji_dequeue_script', 100 );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 ); 
remove_action( 'wp_print_styles', 'print_emoji_styles' );
// Header Links entfernen
add_action('init', 'remheadlink');
function remheadlink()
	{
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'feed_links', 2);
	remove_action('wp_head', 'feed_links_extra', 3);
	remove_action('wp_head', 'parent_post_rel_link', 10, 0);
	remove_action('wp_head', 'start_post_rel_link', 10, 0);
	remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
	remove_action('wp_head', 'wp_shortlink_header', 10, 0);
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	}  

add_action('init', 'stop_heartbeat', 1);
function stop_heartbeat()
	{
	global $pagenow;
	if ($pagenow != 'post.php' && $pagenow != 'post-new.php') wp_deregister_script('heartbeat');
	}

function evolution_remove_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'evolution_remove_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'evolution_remove_wp_ver_css_js', 9999 );


function ah_footer_enqueue_scripts() {
   remove_action('wp_head', 'wp_print_scripts');
    remove_action('wp_head', 'wp_print_head_scripts', 9);
    remove_action('wp_head', 'wp_enqueue_scripts', 1);
    add_action('wp_footer', 'wp_print_scripts', 5);
    add_action('wp_footer', 'wp_enqueue_scripts', 5);
    add_action('wp_footer', 'wp_print_head_scripts', 5);
}
add_action('wp_enqueue_scripts', 'ah_footer_enqueue_scripts');


/* Twitter excerpt */
function get_the_twitter_excerpt(){
$excerpt = get_the_content();
$excerpt = strip_shortcodes($excerpt);
$excerpt = strip_tags($excerpt);
$the_str = substr($excerpt, 0, 200);
return $the_str;
}

/* HTTPS canonical */
function my_rel_canonical () {
    ob_start () ;
    rel_canonical () ;
    $rel_content = ob_get_contents () ;
    ob_end_clean () ;
    echo str_replace ( "http:" , "https:" , $rel_content ) ;
}


/* TechNutty Gravatar */
add_filter( 'avatar_defaults', 'newgravatar' );
function newgravatar ($avatar_defaults) {
$myavatar = 'https://mufasa.technutty.co.uk/wp-content/uploads/2015/05/tiny.png';
$avatar_defaults[$myavatar] = "TechNutty";
return $avatar_defaults;
}


/* Yoast Fixes */
add_action('get_header', 'rmyoast_ob_start');
add_action('wp_head', 'rmyoast_ob_end_flush', 100);
function rmyoast_ob_start() {
    ob_start('remove_yoast');
}
function rmyoast_ob_end_flush() {
    ob_end_flush();
}
function remove_yoast($output) {
    if (defined('WPSEO_VERSION')) {
        $output = str_ireplace('<!-- This site is optimized with the Yoast WordPress SEO plugin v' . WPSEO_VERSION . ' - https://yoast.com/wordpress/plugins/seo/ -->', '', $output);
        $output = str_ireplace('<!-- / SEO activated. -->', '', $output);
    }
    return $output;
}
add_filter ('wpseo_breadcrumb_output','mc_microdata_breadcrumb');
function mc_microdata_breadcrumb ($link_output) {
$link_output = preg_replace(array('#<span xmlns:v="http://rdf.data-vocabulary.org/\#">#','#<span typeof="v:Breadcrumb"><a href="(.*?)" .*?'.'>(.*?)</a></span>#','#<span typeof="v:Breadcrumb">(.*?)</span>#','# property=".*?"#','#</span>$#'), array('','<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="$1" itemprop="url"><span itemprop="title">$2</span></a></span>','<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">$1</span></span>','',''), $link_output);
return $link_output;
}

?>