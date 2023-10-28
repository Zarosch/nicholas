<?php

function error_404() {
	header("HTTP/1.0 404 Not Found");
	
	// If there's a custom 404 template in the theme, use that, if not, use default
	if($GLOBALS['config']['use_frontend'] && file_exists('themes/' . $GLOBALS['config']['frontend_theme'] . '/404.php')) {
		require 'themes/' . $GLOBALS['config']['frontend_theme'] . '/404.php';
	} else {
		require 'views/404.php';
	}
}

function get_theme_directory_url() {	
	return $GLOBALS['config']['base_url'] . '/themes/' . $GLOBALS['config']['frontend_theme'];
}

function get_header($title = null, $description = null, $image = null) {
	
	if($title == null) {
		$title = $GLOBALS['config']['blog_name'];
	} else {
		$title = $title . ' ' . $GLOBALS['config']['title_seperator'] . ' ' . $GLOBALS['config']['blog_name']; 
	}
	
	if($description == null) {
		$description = $GLOBALS['config']['blog_description'];
	}
	
	require 'themes/' . $GLOBALS['config']['frontend_theme'] . '/header.php';
}

function get_footer() {	
	require 'themes/' . $GLOBALS['config']['frontend_theme'] . '/footer.php';
}

function get_post_link($post) {
	$post_base = '';
	
	if($GLOBALS['config']['post_base']) {
		$post_base = date('Y/m', $post->date) . '/';
	}
	
	return $GLOBALS['config']['base_url']  . '/' . $post_base . $post->slug . '/';
}

function get_pagination_link($page, $posts, $tag = '') {	
	if($tag) {
		$count = count(get_tag_list($tag));
		$tag = 'tag/' . $tag . '/';	
	} else {
		$count = count(get_post_list());
	}
	
	if(($count / $GLOBALS['config']['posts_per_page']) > $page) {
		$pagination['next'] = $GLOBALS['config']['base_url'] . '/' . $tag . ($page + 1) . '/';
	}
	
	if($page > 1) {
		$pagination['prev'] = $GLOBALS['config']['base_url'] . '/' . $tag . ($page - 1) . '/';
	}
	
	return $pagination;
}