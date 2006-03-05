<form method="post" action="post.php">
<div class="infobox">
  <h4><?= $bm_post_step_title ?></h4>
  <div class="infoboxBody">
	  <?= $bm_post_step_instructions ?>
  </div>
  <div style="padding-left:2em;">
	  <? if ($bm_step == 1)  { ?>
	  <table width="380" border="0" cellspacing="0" cellpadding="2" >
	  <tr><td class="view-label-class"><b><?= $bl_url_label ?></b></td></tr>
	  <tr><td ><input class="view-input-class" type="text" name="url" value="<?= htmlspecialchars($url) ?>"  size="60" /></td></tr>
	  <tr><td class="error"><?= $bm_url_error; ?></td></tr>
	  <tr><td><input type="hidden" value="1" name="_step" /><input type="submit" value="<?= $bl_url_submit ?>" /></td></tr>
	  </table>
	  <? } elseif ($bm_step == 2) { ?>
	  <table width="380" border="0" cellspacing="0" cellpadding="2" >
	  <tr><td class="view-label-class"><?= $bl_url_label ?></td></tr>
	  <tr><td class="view-data-class"><?= htmlspecialchars($url) ?></td></tr>
	  <tr><td class="view-label-class"><input type="hidden" name="url" value="<?= $url?>" /><?= $bl_post_meme_title_label ?></td></tr>
		  <tr><td><input class="ciew-input-class" type="text" name="title" value="<?= $title ?>" size="60" /></td></tr>
	  <tr><td class="view-label-classs"><?= $bl_post_meme_content_label ?></td></tr>
	<tr><td>
	  <textarea class="view-input-class" name="content" cols="45" rows="2"><?= $content ?></textarea>
	</td></tr>
	<tr><td class="view-label-class"><?= $bl_post_meme_categories_label ?></td></tr>
	<tr><td class="view-input-class">
	  <select name="cat_id">
	  <?php 
			$cats = new categories($bm_db);
	  		$cats = $cats->fetch_all();
	  		foreach ($cats as $cat) {
				print '<option value="'.$cat->ID.' ';
				if ($cat_id == $cat->ID) {
					print 'selected="selected" ';
				}
				print '">'.$cat->cat_title.'</option>';
			}
	  ?>
	  </select>
		</td></tr>
	<tr><td class="view-label-class"><?= $bl_post_meme_trackback ?></td></tr>
	<tr><td class="view-input-class"><input class="view-input-class" type="text" size="60" value="<?= $trackback ?>" name="trackback" /></td></tr>
	<tr><td class="view-label-class"><?= $bl_post_meme_tags ?></td></tr>
	<tr><td><input class="view-input-class" type="text" size="60" value="<?= $tags ?>" name="tags" /></td></tr>
	<tr><td><input type="hidden" value="2" name="_step" /><input class="view-input-class" type="submit" value="<?= $bl_url_submit ?>" /></td></tr>
    </table>
	  <? } elseif ($bm_step == 3) { ?>
<table width="380" border="0" cellspacing="0" cellpadding="2" >
  <tr><td class="view-label-class"><?= $bl_url_label?></td></tr>
  <tr><td class="view-data-class"><a  href="<?= $url ?>"><?=$url ?></a></td></tr>
  <tr><td class="view-label-class"><?= $bl_post_meme_title_label ?></td></tr>
  <tr><td class="view-data-class"><?=$title ?></td></tr>
  <tr><td class="view-label-class"><?= $bl_post_meme_content_label ?></b></td></tr>
  <tr><td class="view-content-class"><?= $content ?></td></tr>
  <tr><td class="view-label-class"><?= $bl_post_meme_categories_label ?></b></td></tr>
  <tr><td class="view-data-class">
	  <?php 
			$cats = new categories($bm_db); 
		  	print $cat->get_category_name($cat_id);
	  ?>
</td></tr>
  <tr><td class="view-label-class"><?= $bl_post_meme_trackback ?></td></tr>
  <tr><td class="view-data-class"><a  href="<?= $trackback ?>"><?=$trackback?></a></td></tr>



</table>
	  <? } ?>

  </div>
  <div class="infoboxFooter"><p>&nbsp;</p></div>
</div>


</form>
