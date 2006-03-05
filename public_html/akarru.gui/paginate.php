<?php 
if ($pages > 1) 
{
	print '<div id="paginas-temp"><b>'.$bl_pages.': </b>';
	for ($p = 1; $p <= $pages; $p++) 
	{
		$link = $_SERVER['PHP_SELF']."?page=$p";
		if (isset($tag_id)) {
			$link .= '&tag_id='.$tag_id;
		}
		if (isset($cat_id)) {
			$link .= '&cat_id='.$cat_id;
		}
		if (isset($_GET['page']))
			$class = ($p == $_CET['page']) ? 'page_selected' : 'page_link';
		print '<a href="'.$link.'">'.$p. '</a>';
		if ($p < $pages) {
			print ' | ';
		}
	}
	print '</div>';
}
?>
