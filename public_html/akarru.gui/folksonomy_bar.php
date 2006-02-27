<div id="folk" style="padding-right: 15px;">
<h2><span style="padding-right: 0.5em;"><a style="font-size:16pt;" href="show_folksonomy.php"><?= $bl_folksonomy ?></a></span>
	</h2>
<!-- <h2><span style="padding-right: 1.5em;"><?= $bl_folksonomy ?></span></h2> -->
<div style="letter-spacing: normal; width: 190px; margin: 10px; margin-top: 0;"><?= $bl_folksonomy_bar_text ?></div>
<div>
<?php
	$tags = new folksonomy($bm_db);
	$n_memes = $tags->count();
	if ($n_memes <= 0) $n_memes = 1;

	$tags = $tags->fetch_top(10);
	foreach ($tags as $tag)
	{
		$font_size = ceil(($tag->memes/$n_memes)*100)+8;
		print '<a style="font-size:'.$font_size.'pt" href="memes_by_tag.php?tag_id='.$tag->tag_id.'">'.$tag->tag.'</a>&nbsp; '; 
	}
?>
<br/>
<a href="show_folksonomy.php"><?= $bl_folksonomy_bar_link ?></a>
</div>

</div>

