	</div> <!-- #main -->

	<aside role="complementary">
		<form class="searchForm" name="search" method="get" action="/search/">
			<input type="text" name="query" placeholder="Search...">
			<input type="submit" value="Search">
		</form>

		<h2>Lorem Ipsum Stuff</h2>

		<ul>
			<li>Morbi in sem quis dui placerat ornare. Pellentesque odio nisi, euismod in, pharetra a, ultricies in, diam. Sed arcu. Cras consequat.</li>
			<li>Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.</li>
			<li>Phasellus ultrices nulla quis nibh. Quisque a lectus. Donec consectetuer ligula vulputate sem tristique cursus. Nam nulla quam, gravida non, commodo a, sodales sit amet, nisi.</li>
			<li>Pellentesque fermentum dolor. Aliquam quam lectus, facilisis auctor, ultrices ut, elementum vulputate, nunc.</li>
		</ul>
	</aside>

	<footer role="contentinfo">
		<p>&copy; Copyright <?= date("Y") ?> <?= ''/*getSetting tag="site-title"*/ ?>, All Rights Reserved.</p>
	</footer>

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="/js/jquery-1.8.3.min.js"><\/script>')</script>
	<script src="/js/main-min.js"></script>

	<?php $sGoogleAnalytics = $this->getSetting("analytics-google"); ?>
	<?php if(!empty($sGoogleAnalytics)): ?>
	<script>
	var _gaq=[['_setAccount','{$sGoogleAnalytics}'],['_trackPageview'],['_trackPageLoadTime']];
	(function(d,t){ var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
	g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
	s.parentNode.insertBefore(g,s) }(document,'script'));
	</script>
<?php endif;?>
</body>
</html>
