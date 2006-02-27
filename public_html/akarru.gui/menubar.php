	<div id="header">
		<h1><a href="<?= $bm_home ?>"><?= $bm_title ?></a></h1>
			<form action="search.php" method="post" name="search-form" id="search-form">
			<label for="search" accesskey="100" class="inside"><?= $bl_search ?></label>
			<input type="text" name="search" id="search" size="10" value="" />
			</form>
	
			<ul>
				<li><?= $bm_main_feeds ?></li>
				<li>
				<?php if ($bm_users->is_logged_in()) {?>
				<a href="profile.php?user_name=<?php echo ($bm_users->get_user_name()) ?>"><?= $bl_profile_of ?><?php echo $bm_users->get_user_name() ?></a>
				<?php } else { ?>
				<a href="login.php"><?= $bl_login ?></a>
				<?php } ?>
				</li>
				<li>
				<?php if ($bm_users->is_logged_in()) {?>
				<a href="logout.php"><?= $bl_logoff ?></a>
				<?php } else { ?>
				<a href="register.php"><?= $bl_register ?></a>
				<?php } ?>
				</li>
				<li>
				<a href="about.php"><?= $bl_about ?></a>
				</li>
				<li><a href="http://www.lnds.net/2005/12/sugerencias_para_akarru.html#comments"><?= $bl_suggestions ?></a></li>
			</ul>
		<?php include_once('akarru.gui/top_banner.php') ?>
	</div>
