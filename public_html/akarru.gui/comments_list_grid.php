<?php
foreach ($comments as $comment)
{
?>
<div class="infobox">
	<div style="padding-left:1em;padding-top:1em;padding-right:1em">
		<p class="comment-class"><?= nl2br(decode_plain($comment->content)) ?>
	</div>
	<div class="whowhen-class"><?= $bl_posted_by ?>&nbsp;<a class="whowhen-class" style="font-size:10px" href="profile.php?user_name=<?= $meme->username ?>"><?= $comment->username ?></a>&nbsp;<?= strftime($bf_date_posted, $comment->date_posted) ?></div> 
	<div class="infoboxFooter"><p>&nbsp;</p></div>
</div>
<?php 
}
?>
