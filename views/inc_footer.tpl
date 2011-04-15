		</section> <!-- #content -->

		<aside>
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
		
		<footer>
			<p>&copy; Copyright {$smarty.now|formatDate:"Y"} {getSetting tag="title"}, All Rights Reserved.</p>
		</footer>
	</div>
	
	<script src="/scripts/jquery-1.5.2.min.js"></script>
	<script src="/scripts/jquery.scrollTo.min.js"></script>
	<script src="/scripts/common.js"></script>
	<!--[if lt IE 9]>
	<script src="/scripts/IE9.js">IE7_PNG_SUFFIX=".png";</script>
	<![endif]-->

{getSetting tag="analytics_google" assign="aSettingGoogle"}
{if !empty($aSettingGoogle)}
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '{$aSettingGoogle}']);
  _gaq.push(['_trackPageview']);

  (function() {ldelim}
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  {rdelim})();
</script>
{/if}

{getSetting tag="analytics_woopra" assign="aSettingWoopra"}
{if $aSettingWoopra == 1}
<script type="text/javascript" src="//static.woopra.com/js/woopra.v2.js"></script> 
<script type="text/javascript">
woopraTracker.track(); 
</script> 
{/if}
</body>
</html>