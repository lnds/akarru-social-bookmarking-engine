<div>
<?php
	$cats = new categories($bm_db);
		$cats = $cats->fetch_all();
		print '<table width="100%" cellpadding="0" cellspacing="0" >';
		print '<tr>';
		foreach ($cats as $cat)
		{
			$flipflop = 1 - $flipflop;
			if ($cat->post_count <= $bm_promo_level)
			{
				print '<td class="cat_title">'.$cat->cat_title.'</td>';
			}
			else
			{
				print '<td ><a class="cat_title" href="show_cat.php?cat_id='.$cat->ID.'">'.$cat->cat_title.'</a></td>';
			}
			if ($flipflop == 0) 
			{
				print '</tr><tr>';
			}
		}
		print '</tr>';
		print '</table>';
?>
</div>
