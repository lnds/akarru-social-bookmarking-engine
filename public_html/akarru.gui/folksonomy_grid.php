<p>
<?= $bl_folksonomy_explain ?>
</p>

<table width="460" border="0" cellspacing="2" cellpadding="2" >
	<tr>
		<td>
<?php
	$tags = new folksonomy($bm_db);
	$n_memes = $tags->count();
	if ($n_memes <= 0) $n_memes = 1;

	$tags = $tags->fetch_all();
	foreach ($tags as $tag)
	{
		$font_size = ceil(($tag->memes/$n_memes)*100)+8;
		print '<a style="font-size:'.$font_size.'pt;text-decoration:none" href="memes_by_tag.php?tag_id='.$tag->tag_id.'">'.$tag->tag.'</a>&nbsp; '; 
	}
?>

		</td>
	</tr>
</table>
