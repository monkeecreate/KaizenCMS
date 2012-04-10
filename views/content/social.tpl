{*
Template Name: Social Helper Demo
Description: Examples of how to use the many social helpers included.
Version: 1.0
Restricted: true
Author: Crane | West
*}

{$menu = "social"}
{include file="inc_header.tpl" page_title="Social"}

	<h2>Flickr Stream</h2>
	{flickr method=photoStream number=5 size=1}

{include file="inc_footer.tpl"}