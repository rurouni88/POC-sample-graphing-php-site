<div>
<h3>Site Pages</h3>
<ul>
<?php
$cache = API::getSiteNav();
$nav_objects = $cache->data;
foreach ($nav_objects as $nav_object) {
echo <<<NAV_OUTPUT
	<li><strong><a href="$nav_object->path">$nav_object->display</a></strong>:
		<span style="font-size:88%">$nav_object->description</span>
	</li>
NAV_OUTPUT;
}
?>
<ul>
</div>
