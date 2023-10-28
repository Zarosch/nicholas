<?php get_header(); ?>

		
<?php foreach ($posts as $post) { ?>		

<article class="blog-preview">
	<a href="<?= get_post_link($post); ?>">
		<h2><?= $post->title; ?></h2>
	</a>
	<p><?= $post->excerpt; ?></p>
	<p class="small"><?= date($GLOBALS['config']['date_format'], $post->date); ?> • Filed under <?= display_tag_list($post->tags); ?></p>
</article>

<?php } ?>

<div class="pagination">
	<div class="prev">
		<?php 
			$prevLink = get_pagination_link($page, $posts)['prev'];
			if($prevLink) { echo '<a href="' . $prevLink . '" title="Previous Page">&laquo; Newer Posts </a>'; }
		?>	
	</div>
	<div class="next">
		<?php 
			$nextLink = get_pagination_link($page, $posts)['next'];
			if($nextLink) { echo '<a href="' . $nextLink . '" title="Next Page">Older Posts &raquo;</a>'; }
		?>	
	</div>
</div>




<?php get_footer(); ?>