<?
if (isset($bm_message)) {
	print "<p>$bm_message</p>";
}
$pages = $bm_memes->pages;
$bm_user_id = $bm_users->get_user_id();
foreach ($memes as $meme)
{
?>
<table width="460" border="0" cellspacing="0" cellpadding="2" >
  <tr>
    <td class="votos">
	  <h3  id="vote_count_<?=$meme->ID?>" style="text-align:center" align="center"><?= $meme->vote_count ?></h3>
	  <p><?= $bl_votes ?></p>
	   <div class="vote-class">
	<?php if ($bm_users->is_logged_in()) { ?>
	<a class="vote-class" href="#" onclick="update_vote_div('vote_count_<?=$meme->ID?>','<?=$bm_user_id?>')"><img src="styles/img/meme-votar.png" border="0" alt="votar" /></a>
	<?php } else { ?>
	<a class="vote-class" href="login.php" ><img src="styles/img/meme-votar.png" border="0" alt="votar" /></a>
	<?php } ?>
		   </div>
	</td>
    <td class="meme-content"><a href="<?= htmlspecialchars($meme->url) ?>"><?= $meme->title ?></a>
	<div class="whowhen-class"><?= $bl_posted_by ?>&nbsp;<a class="whowhen-class" style="font-size:10px" href="profile.php?user_name=<?= $meme->username ?>"><?= $meme->username ?></a>&nbsp;<?= strftime($bf_date_posted, $meme->date_posted) ?></div>
	  <p>
	  	  <?= htmlspecialchars($meme->content) ?>
	  </p>
		<div class="meme-footer-class"><a href="comment.php?meme_id=<?= $meme->ID ?>"><?= $bl_comments ?>: <?= $meme->comment_count ?></a> | <a href="tag_meme.php?meme_id=<?= $meme->ID ?>"><?= $bl_tag_meme?></a> | <a href="<?= htmlspecialchars($meme->url) ?>"><?= $bl_url_meme ?></a> | <?= $bl_cat_meme ?>: <a href="show_cat.php?cat_id=<?= $meme->cat_id ?>"><?= $meme->cat_title?></a></div></td>
  </tr>
</table>
<hr />
<? } ?>
<? include_once('akarru.gui/paginate.php'); ?>
