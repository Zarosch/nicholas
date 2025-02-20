<?php
	
function display_tag_list($tags) {
	$output = '';
	
	foreach ($tags as $tag) {
		$output .= 	'<a href="' . $GLOBALS['config']['base_url'] . '/tag/' . urlencode(strtolower($tag)) . '/" title="Posts tagged ' . $tag . '">' . $tag . '</a>, ';
	}
	
	$output = rtrim($output, ', ');
	return $output;
}