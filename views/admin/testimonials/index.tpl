{include file="inc_header.tpl" page_title="Testimonials" menu="testimonials"}
{head}
<script language="JavaScript" type="text/javascript" src="/scripts/jquery/jTPS/jTPS.js"></script>
<link rel="stylesheet" type="text/css" href="/scripts/jquery/jTPS/jTPS.css">
<script type="text/javascript">
	$(function(){ldelim}
		$('.dataTable').jTPS({ldelim}
			perPages:[10,15,20],
			scrollStep: 1
		{rdelim});
	{rdelim});
</script>
{/head}
<div class="clear"></div>
<table class="dataTable">
	<thead>
		<tr>
			<th sort="name">Name</th>
			<th sort="type">Type</td>
			<th sort="homepage">Homepage</th>
			<th sort="active">Active</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$aTestimonials item=aTestimonial}
			<tr>
				<td>{$aTestimonial.name|stripslashes}</td>
				<td class="small center">
					{if !empty($aTestimonial.video)}
						Video
					{else}
						Text
					{/if}
				</td>
				<td class="small center">
					{if $aTestimonial.homepage == 1}
						<img src="/images/admin/icons/accept.png">
					{else}
						<img src="/images/admin/icons/cancel.png">
					{/if}
				</td>
				<td class="small center">
					{if $aTestimonial.active == 1}
						<img src="/images/admin/icons/accept.png">
					{else}
						<img src="/images/admin/icons/cancel.png">
					{/if}
				</td>
				<td class="small center border-end">
					<a href="/admin/testimonials/edit/{$aTestimonial.id}/">
						<img src="/images/admin/icons/pencil.png">
					</a>
					<a href="/admin/testimonials/delete/{$aTestimonial.id}/"
						onclick="return alert('Are you sure you would like to delete this testimonial?');">
						<img src="/images/admin/icons/bin_closed.png">
					</a>
				</td>
			</tr>
		{/foreach}
	</tbody>
	<tfoot class="nav">
		<tr>
			<td colspan="5">
				<div class="pagination"></div>
				<div class="paginationTitle">Page</div>
				<div class="selectPerPage"></div>
				<div class="status"></div>
			</td>
		</tr>
	</tfoot>
</table>
{include file="inc_footer.tpl"}