{include file="inc_header.tpl" page_title="Testimonials"}

{getContent tag="testimonials" var="aContent"}
<h2>{$aContent.title|stripslashes}</h2>
<div id="testiContent">
	{$aContent.content|stripslashes}
</div>
<p>
	<h4>{$aCurTestimonial.name} - <small>{$aCurTestimonial.sub_name}</small></h4>
	{if !empty($aCurTestimonial.video)}
		<div class="player">
			{include file="inc_corners.tpl"}
			<a href="/uploads/testimonials/{$aCurTestimonial.video}"  
				style="display:block;width:280px;height:234px"  
				id="splash_{$aCurTestimonial.id}"> <img src="/uploads/testimonials/posters/{$aCurTestimonial.poster}"></a>
		</div>
		<script>
		flowplayer("splash_{$aCurTestimonial.id}", "/flash/flowplayer-3.1.0.swf", {ldelim}
			clip: {ldelim}
				autoPlay: true, 
				autoBuffering: true 
			{rdelim},
			canvas: {ldelim}
				backgroundImage: 'url(/images/h_vidBG.jpg)',
				backgroundColor: '#ffffff'
			{rdelim},
			screen: {ldelim}
				width:280, height:210, top:0, left:0,
				display:'none'
			{rdelim},
			plugins: {ldelim}
				controls: {ldelim}
					backgroundColor: '#cccccc',
					backgroundGradient: 'low',
					play:true, 
					volume:false, 
					mute:true, 
					time:false, 
					stop:false, 
					playlist:false, 
					fullscreen:false
				{rdelim}
			{rdelim}
		{rdelim});
		</script>
	{else}
		<blockquote>
			{$aCurTestimonial.text|stripslashes}
		</blockquote>
	{/if}
</p>
<div class="clear">&nbsp;</div>
<div id="tours">
	{foreach from=$aTestimonials item=aTestimonial}
		{if !empty($aTestimonial.video)}
			<div class="{if $aTestimonial.id == $aCurTestimonial.id}selected{/if} tours">
				<a href="/{$aSection.tag}/testimonials/{$aTestimonial.id}/">
					<img src="/image/resize/?file=/uploads/testimonials/posters/{$aTestimonial.poster}&width=200&height=200">
				</a>
			</div>
		{else}
			<div class="{if $aTestimonial.id == $aCurTestimonial.id}selected{/if} tours textTest">
				<a href="/{$aSection.tag}/testimonials/{$aTestimonial.id}/">
					{$aTestimonial.name}
				</a>
			</div>
		{/if}
	{/foreach}
</div>

{include file="inc_footer.tpl"}