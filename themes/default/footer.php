	</main>
	
	<footer class="site-footer">
	    <nav>
			<?php foreach($GLOBALS['config']['menus']['footer'] as $label => $link) { ?>
				<a href="<?= $link ?>"><?= $label ?></a>
			<?php } ?>
	    </nav>
	    <p>
		    Powered by <a href="https://nicholas.adgr.dev/" target="_blank">Nicholas</a> ✨
		</p>
	</footer>
</body>
</html>