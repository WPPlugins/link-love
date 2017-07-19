<?php
/*
Plugin Name: Link Love
Plugin URI: http://www.intenseblog.com/wordpress/link-love.html
Description: The Link Love Wordpress plugin will count the number of approved comments each commentator has left on your blog and then reward he/she with a Do-follow link in the Comment Author Link field.
Author: Jennifer Ray
Version: 1.2
Author URI: http://www.intenseblog.com
*/

if (!function_exists('linklove_add_dashboard_widgets'))
{
	function linklove_add_dashboard_widgets() {
		global $wp_version;
		if (version_compare($wp_version,"2.6","<")){
			exit ('');
		} 
		else
		{ wp_add_dashboard_widget('linklove_dashboard_widget', 'Wordpress Resources | Intense Blog', 'linklove_dashboard_widget_function');	
		}
	}
}

if (!function_exists('linklove_dashboard_widget_function'))
{
function linklove_dashboard_widget_function() {
	include_once(ABSPATH . WPINC . '/rss.php');
	$rss = fetch_rss('http://feeds.feedburner.com/IntenseBlog');
	if ($rss) {
	    $items = @array_slice($rss->items, 0, 5);
	    if (empty($items)) 
	    	echo '<li><a href="http://www.intenseblog.com">Wordpress blogging resources.</a></li>';
	    else {
			echo '<div class="rss-widget"><ul>';
	    	foreach ( $items as $item ) { ?>
	    	<li><a href='<?php echo $item['link']; ?>' class="rsswidget" ><?php echo $item['title']; ?></a>
			<div class="rssSummary"><?php echo $item['summary']; ?></div></li>
	    	<?php }
			echo '</ul></div>';
	    }
	}

} 

}

function linklove($string) {
		global $wpdb;
		global $comment;		
		$count = count($wpdb->get_results("SELECT comment_author_email FROM $wpdb->comments WHERE comment_author_email = '$comment->comment_author_email' AND comment_approved = '1' "));
		$ll_num = get_option('ll_num');
		$blacklist_email = get_option('blacklist_email');
		if ($ll_num != '' && $count >= $ll_num) {
			if (is_array($blacklist_email) && in_array($comment->comment_author_email, $blacklist_email)) {
			return $string;
			}
			else {
			$string = preg_replace("/(<a [^>]*( |\t|\n)rel=)('|\")(([^\3]*( [^ \3]*)*) )?nofollow/", "$1$3$5", $string);
			$string = preg_replace("/(<a [^>]*)( |\t|\n)rel=(''|\"\")([^>]*>)/", "$1$4", $string);	
			return $string;
			}
		}
		else {
			return $string;
		}
}

function linklove_admin() {
		include('linklove_admin.php');
}

function linklove_admin_actions() {
		add_options_page("Linklove Plugin Configuration", "Linklove Plugin Configuration", 1, "linklove-configuration", "linklove_admin");
}

add_action('wp_dashboard_setup', 'linklove_add_dashboard_widgets' );
add_action('admin_menu', 'linklove_admin_actions');
remove_filter('pre_comment_content', 'wp_rel_nofollow', 10);
add_filter('get_comment_author_link', 'linklove', 10);
?>