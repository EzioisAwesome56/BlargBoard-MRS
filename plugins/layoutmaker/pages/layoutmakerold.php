<?php

include("bases/list.php");
$offers = "";
foreach($bases as $base)
{
	$layoutimage = $base['name'];
	$picture = '';
	if (file_exists('pages/bases/'.$base['name'].'.png'))
		$picture = '<img src="'.$boardroot.'/plugins/layoutmaker/pages/bases/'.$layoutimage.'.png" />';
	$cellClass = ($cellClass + 1) % 2;
	$offers .= "
	<tr class=\"cell$cellClass\">
		<td>
			$picture
		</td>
		<td>
		".actionLinkTag($base['title'], "layoutmaker2", $base['name'])."
		</td>
		<td>
			".$base['description']."
		</td>
	</tr>";
}

write(
"
<table class=\"outline margin width50\">
	<tr class=\"header1\">
		<th>Preview</th>
		<th>Name</th>
		<th>Description</th>
	</tr>
	{0}
</table>
<p>
Obvious problems I'm well aware of that you shouldn't bother bugging me about:
<ul>
	<li>Introduction page. this is plain and boring</li>
	<li>Sliders don't have buddy ranges</li>
	<li>The CGA palette in the color picker doesn't trigger the auto-update.</li>
	<li>Layouts need the WHole post box setting turned on</li>
	<li>There are only stock templates, gonna make more</li>
</ul>
</p>
",	$offers);


