<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?= $bl_site_caption ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<style type="text/css" media="all">@import "styles/memes.css";</style>
<!--[if gte IE 5.5000]>
<script type="text/javascript" src="pngfix.js"></script>
<![endif]-->
<link rel="icon" href="/favicon.ico" type="image/x-icon" />
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?= $bm_url_feeds ?>" />

<?php include_once('akarru.gui/java_script.php') ?>
</head>

<body>

<div id="container">
	<?php include_once('akarru.gui/menubar.php') ?>
</div>
<div id="main-wrapper">

	<?php include_once('akarru.gui/main_content.php') ?>
	<div id="sidebar">
		<div id="sidebar-tab1"><a href="memes_queue.php"><?= $bl_promote ?></a></div>
		<div id="sidebar-tab2"><a href="post.php"><?= $bl_publish ?></a></div>
		<div id="sidebar-tab3"><a href="popular.php"><?= $bl_popular ?></a>
			<br /><a href="unpopular.php"><?= $bl_unpopular ?></a></div>
		<div id="sidebar-cat">
			<h1><span style="padding-right: 1.5em;"><?= $bl_categories ?></span></h1>
			<?php include_once('akarru.gui/categories_bar.php') ?>
			<?php if (!isset($bm_no_folkbar)) { include_once('akarru.gui/folksonomy_bar.php'); } ?>
			<?php include_once('akarru.gui/button_bar.php') ?>
	    </div>
	</div>
</div>
<div id="main-end">
</div>
</body>
</html>
