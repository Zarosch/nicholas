<?php
require_once 'vendor/autoload.php';
require_once 'app/posts.php';
require_once 'app/pages.php';
require_once 'app/plugins.php';
require_once 'app/generate.php';
require_once 'app/api.php';

$GLOBALS['config'] = include('config.php');

$router = new AltoRouter();
$router->setBasePath($GLOBALS['config']['base_url']);

/* ============================================
   Plugins
 ============================================ */

load_plugins();

/* ============================================
   Subscription Feeds
 ============================================ */

$router->map('GET','/json/', function() { 
	header('Content-type: application/json');
	echo generate_json(get_posts());
});

$router->map('GET','/rss/', function() { 
	header('Content-type: application/xml');
	echo generate_rss(get_posts());
});

/* ============================================
   API
 ============================================ */

$router->map('GET','/api/feed/', function() { 
	header('Content-type: application/json');
	echo generate_json(api_feed());
});

$router->map('GET','/api/single/', function() { 
	header('Content-type: application/json');
	echo generate_json(api_single());
});

/* ============================================
   Front-end
 ============================================ */

// If the front-end option in config is set to false, skip the loading of frontend functionality
if(!$GLOBALS['config']['use_frontend']) {
	$router->map('GET','/', function() { 
		require 'views/default.php';
	});
} else {
	require_once 'app/frontend.php';
	require_once 'themes/' . $GLOBALS['config']['frontend_theme'] . '/functions.php';
	
	$router->map('GET','/tag/[:tag]/[i:page]?/', function($tag, $page = 1) {
		$posts = get_posts($page, $GLOBALS['config']['posts_per_page'], $tag);
		$tag = str_replace('%20', ' ', $tag);
		
		if($posts) {
			include 'themes/' . $GLOBALS['config']['frontend_theme'] . '/tag.php';
		} else {
			error_404();
		}
	});
	
	$router->map('GET','/[i:page]?/', function($page = 1) {
		$posts = get_posts($page);

		if($posts) {
			include 'themes/' . $GLOBALS['config']['frontend_theme'] . '/home.php';
		} else {
			error_404();
		}
	});
	
	$router->map('GET','/[:slug]/', function($slug) { 
		// If post_base is not active, check posts by slug
		if(!$GLOBALS['config']['post_base']) {
			$post = get_single($slug);
			if($post) {
				include 'themes/' . $GLOBALS['config']['frontend_theme'] . '/single.php';
				return;
			}
		}

		// If no post was found search for page
		$page = get_page($slug);
		if($page->title) {
			include 'themes/' . $GLOBALS['config']['frontend_theme'] . '/page.php';
			return;
		}

		// When no post and no page was found, return error 404.
		error_404();
	});

	// Must be last to ensure other routes get detected first
	if($GLOBALS['config']['post_base']) {
		$router->map('GET','/[:year]/[:month]/[:slug]/', function($year, $month, $slug) { 
			$post = get_single($slug, $year, $month);
			if($post->title) {
				include 'themes/' . $GLOBALS['config']['frontend_theme'] . '/single.php';
			} else {
				error_404();	
			}
		});
	}
}

/* ============================================
   Matching
 ============================================ */

$match = $router->match();

if($match) {
	call_user_func_array( $match['target'], $match['params'] ); 
} else {
	error_404();
}