<?php $this->tplDisplay("inc_header.php", ['menu'=>'dashboard','sPageTitle'=>"Dashboard"]); ?>

	<h1>Your Dashboard</h1>

	<div class="row-fluid">
		<div class="accordion-group span4">
			<div class="accordion-heading">
				<a class="accordion-toggle" data-toggle="collapse" href="#sitestats">Site Stats/Google Analytics</a>
			</div>
			<div id="sitestats" class="accordion-body in collapse">
				<div class="accordion-inner">
					<div class="controls">
						<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.</p>
					</div>
				</div>
			</div>
		</div>

		<div class="accordion-group span4">
			<div class="accordion-heading">
				<a class="accordion-toggle" data-toggle="collapse" href="#expiringpromos">Expiring Promos</a>
			</div>
			<div id="expiringpromos" class="accordion-body in collapse">
				<div class="accordion-inner">
					<div class="controls">
						<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.</p>
					</div>
				</div>
			</div>
		</div>

		<div class="accordion-group span4">
			<div class="accordion-heading">
				<a class="accordion-toggle" data-toggle="collapse" href="#webmastermessages">Webmaster Messages</a>
			</div>
			<div id="webmastermessages" class="accordion-body in collapse">
				<div class="accordion-inner">
					<div class="controls">
						<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.</p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row-fluid">
		<div class="accordion-group span4">
			<div class="accordion-heading">
				<a class="accordion-toggle" data-toggle="collapse" href="#latestcomments">Latest Comments</a>
			</div>
			<div id="latestcomments" class="accordion-body in collapse">
				<div class="accordion-inner">
					<div class="controls">
						<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.</p>
					</div>
				</div>
			</div>
		</div>

		<?php $sTwitterUsername = $this->getSetting('twitter-username') ?>
		<?php if(!empty($sTwitterUsername)): ?>
		<div class="accordion-group span4">
			<div class="accordion-heading">
				<a class="accordion-toggle" data-toggle="collapse" href="#twittermentions">@<?= $sTwitterUsername ?> Twitter Mentions</a>
			</div>
			<div id="twittermentions" class="accordion-body in collapse">
				<div class="accordion-inner">
					<div class="controls">
						<ul>
							<li>Loading tweets...</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>
	</div>

{footer}
<script>
$(function(){

	$.getJSON("http://search.twitter.com/search.json?q=%40twofivethreetwo&rrp=5&callback=?", function(data) {
		console.log(data);

		html = '';
		jQuery.each(data.results, function() {
			html += '<li><strong><a href="http://twitter.com/'+this.from_user+'">@'+this.from_user+'</a></strong>: '+this.text+'</li>';
		});

		$('#twittermentions ul').html(html);
	});
});
</script>
{/footer}
<?php $this->tplDisplay("inc_footer.php"); ?>
