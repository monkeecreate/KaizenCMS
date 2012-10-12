{$menu = "home"}
{include file="inc_header.tpl"}

	<h2>HTML Ipsum Presents</h2>

	<p><strong>Pellentesque habitant morbi tristique</strong> senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. <em>Aenean ultricies mi vitae est.</em> Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, <code>commodo vitae</code>, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. <a href="#">Donec non enim</a> in turpis pulvinar facilisis. Ut felis.</p>

	<h3>Header Level 3</h3>

	<ol>
		<li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
		<li>Aliquam tincidunt mauris eu risus.</li>
	</ol>

	<blockquote>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus magna. Cras in mi at felis aliquet congue. Ut a est eget ligula molestie gravida. Curabitur massa. Donec eleifend, libero at sagittis mollis, tellus est malesuada tellus, at luctus turpis elit sit amet quam. Vivamus pretium ornare est.</blockquote>

	<h4>Header Level 4</h4>

	<ul>
		<li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
		<li>Aliquam tincidunt mauris eu risus.</li>
	</ul>

	<pre><code>
	#header h1 a {
		display: block;
		width: 300px;
		height: 80px;
	}
	</code></pre>

	<h2>Heading Two</h2>
	<h3>Heading Three</h3>
	<h4>Heading Four</h4>
	<h5>Heading Five</h5>
	<h6>Heading Six</h6>

	<form>
		<fieldset>
			<legend>This is known as a form.</legend>

			<p><label for="form-name">Your Name <input type="text" name="name" id="form-name"></label></p>
			<p><label for="form-email">Your Email <input type="text" name="email" id="form-email"></label></p>
			<p><label for="form-comments">Your Comments <textarea name="comments" id="form-comments"></textarea></label></p>
			<p><input type="submit" value="Submit"></p>
		</fieldset>
	</form>

{include file="inc_footer.tpl"}