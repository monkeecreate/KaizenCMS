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