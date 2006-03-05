<?php // esto no es muy elegante pero necesito salir luego con blogmemes, despues vamos a cambiar el manejo de los forms ?>
<div class="infobox">
  <h3><?= $bl_profile_personal_info_title ?></h3>
  <div class="infoboxBody">
<?php
	 $user_profile = $bm_users->get_user_profile($user_name); 
?>
	<div style="padding-left:1em">
	  <table width="240" border="0">
	  <tr><td><b><?= $bl_profile_username ?></b></td><td><?= $user_profile->username ?></td></tr>
	  <tr><td><b><?= $bl_profile_fullname ?></b></td><td><?= $user_profile->fullname ?></td></tr>
	  <tr><td><b><?= $bl_profile_blog?></b></td><td><a href="<?= $user_profile->blog?>"><?= $user_profile->blog?></a></td></tr>
	  <tr><td><b><?= $bl_profile_website?></b></td><td><a href="<?= $user_profile->website ?>"><?= $user_profile->website ?></a></td></tr>
	  </table>
	</div>
<div style="padding-left:1em">
<?php
	  if ($user_profile->username == $bm_users->get_user_name() && $bm_users->is_logged_in()) {
		  print '<a href="profile_edit.php?user_id='.$bm_users->get_user_id().'">modificar perfil</a><br/>';

	  }
?>
<h2><?= $bl_profile_stats_title ?></h2>
	<div style="padding-left:1em">
	  <table width="240" border="0">
	  	  <tr><td><b><?= $bl_profile_memes ?></b></td><td><?= $user_profile->memes ?></td></tr>
		  <tr><td><b><?= $bl_profile_promoted_memes ?></b></td><td><?= $user_profile->promoted_memes ?></td></tr> 
		  <tr><td><b><?= $bl_profile_votes ?></b></td><td><?= $user_profile->votes ?></td></tr> 
		  <tr><td><b><?= $bl_profile_comments ?></b></td><td><?= $user_profile->comments ?></td></tr> 
		  <tr><td><b><?= $bl_profile_votes_for ?></b></td><td><?= $user_profile->memes_votes ?></td></tr> 
	  </table>
	 </div>
</div>
</div>
  <div class="infoboxFooter"><p>&nbsp;</p></div>
</div>
