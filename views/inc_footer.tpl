		</section> <!-- #content -->
	
		<aside class="column">
			<h2>List Items</h2>
			<ul>
			   <li>Morbi in sem quis dui placerat ornare. Pellentesque odio nisi, euismod in, pharetra a, ultricies in, diam. Sed arcu. Cras consequat.</li>
			   <li>Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.</li>
			   <li>Phasellus ultrices nulla quis nibh. Quisque a lectus. Donec consectetuer ligula vulputate sem tristique cursus. Nam nulla quam, gravida non, commodo a, sodales sit amet, nisi.</li>
			   <li>Pellentesque fermentum dolor. Aliquam quam lectus, facilisis auctor, ultrices ut, elementum vulputate, nunc.</li>
			</ul>
			{getTestimonials random=true limit=1}
			{if !empty($aTestimonial)}
				<h2>Testimonials</h2>
				<p>
					<blockquote>
						{$aTestimonial.text}
					</blockquote>
					{$aTestimonial.name} - <small>{$aTestimonial.sub_name}</small>
				</p>
			{/if}
			<h2>Promos</h2>
			{getPromo tag="po1"}
			{getPromo tag="po2"}
		</aside>
	
	</div>
	<footer>
		&copy; {$smarty.now|date_format:"%Y"} Your Company Name, All Rights Reserved.
	</footer>

</div> <!-- #wrapper -->
{getSetting tag="analytics_google" assign="aSettingGoogle"}
{if !empty($aSettingGoogle)}
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {ldelim}
	var pageTracker = _gat._getTracker("{$aSettingGoogle}");
	pageTracker._trackPageview();
{rdelim} catch(err) {ldelim}{rdelim}
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