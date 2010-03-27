{include file="inc_header.tpl" page_title="Testimonials" menu="testimonials"}

{getContent tag="testimonials" var="aContent"}

	<section id="content" class="content column">

		<h2>{$aContent.title|clean_html}</h2>
		<p id="testiContent">
			{$aContent.content|stripslashes}
		</p>

		<p>
			<h3>{$aCurTestimonial.name|clean_html} - <small>{$aCurTestimonial.sub_name|clean_html}</small></h3>
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
					{$aCurTestimonial.text|clean_html}
				</blockquote>
			{/if}
		</p>
		<div class="clear">&nbsp;</div>

		<div id="tours">
			{foreach from=$aTestimonials item=aTestimonial}
				{if !empty($aTestimonial.video)}
					<div class="{if $aTestimonial.id == $aCurTestimonial.id}selected{/if} tours">
						<a href="/testimonials/{$aTestimonial.id}/">
							<img src="/image/resize/?file=/uploads/testimonials/posters/{$aTestimonial.poster}&width=200&height=200">
						</a>
					</div>
				{else}
					<div class="{if $aTestimonial.id == $aCurTestimonial.id}selected{/if} tours textTest">
						<a href="/testimonials/{$aTestimonial.id}/">
							{$aTestimonial.name|clean_html}
						</a>
					</div>
				{/if}
			{/foreach}
		</div>
		
	</section> <!-- #content -->

	{include file="inc_sidebar.tpl"}
		
{include file="inc_footer.tpl"}
