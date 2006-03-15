<?php
  include_once('akarru.lib/common.php');
  $bm_cats = new categories($bm_db);
  $bm_cats = $bm_cats->fetch_all();
  $smarty->assign('bm_cats', $bm_cats);

  if (!$bm_users->is_logged_in()) {
	  $bm_home = $_GET['page'];
	  header("Location: login.php?from=post");
	  exit();
	  return;
  }

  if (empty($_POST) || empty($_POST['step']))
  {
	  $step = 1;
	  $smarty->assign('url', 'http://');
	  $smarty->assign('meme_trackback', '');

  }
  else
  {
	  $bm_options = array('0'=>'-- seleccione --');
	  foreach ($bm_cats as $cat)
	  {
		  $bm_options[$cat->ID] = $cat->cat_title;
	  }

	 $bm_errors = 0;
	 $memes = new memes($bm_db, $bm_user, $bm_promo_level);
	 if ($_POST['step'] == 1) {
   		 $title = $_POST['title'];
		 $url   = $_POST['url'];
		 $smarty->assign('content_type',  $_POST['content_type']);
		 if (empty($title)) {
			 $smarty->assign('error_title', true);
			 $bm_errors++;
		 }
		 else
		 {
			 $smarty->assign('title', check_plain($title));
		 }
		 if (!empty($url)) {
			 $smarty->assign('url', check_plain($url));
			 if (!check_url($url)) {
				 $smarty->assign('error_url', true);
				 $bm_error++;
			 }
			 if ($memes->check_url_exists_in_db(check_plain($url))) {
				 $smarty->assign('error_duplicate_url', true);
				 $bm_error++;
			 }
		 }
		 if ($bm_errors == 0) {
			 $step = 2;
			 $smarty->assign('cats', $bm_options);
			 $smarty->assign('meme_trackback', find_trackback($url));
		 }
	 }
	 elseif ($_POST['step'] == 2) {
		 $smarty->assign('content_type',  $_POST['content_type']);
		 $title = $_POST['title'];
		 $url   = $_POST['url'];
		 $content_body = $_POST['content_body'];
		 $category = $_POST['category'];
		 $meme_trackback = $_POST['meme_trackback'];
		 $meme_tags = $_POST['meme_tags'];

		 $smarty->assign('title', check_plain($title));
		 $smarty->assign('url', check_plain($url));
		 if (empty($content_body)) {
			 $smarty->assign('error_content_body',true);
			 $bm_errors++;
		 }
		 else
			 $smarty->assign('content_body', check_plain($content_body));
		 if ($category == 0) {
			 $smarty->assign('error_category', true);
			 $bm_errors++;
		 }
		 $smarty->assign('category', $category);
		 $smarty->assign('category_name', $bm_options[$category]);
		 $smarty->assign('meme_trackback', check_plain($meme_trackback));
		 $smarty->assign('meme_tags', check_plain($meme_tags));
		 $smarty->assign('micro_content', get_youtube($url));
		 if ($bm_errors == 0) {
			 $step = 3;
		 }
		 else
		 {
			 $step = 2;
			 $smarty->assign('cats', $bm_options);
		 }
	 }
	 else if ($_POST['step'] == 3) {
		 $smarty->assign('content_type',  $_POST['content_type']);
		 if (!empty($_POST['do_edit'])) {
			 $step = 2;
			 $title = $_POST['title'];
			 $url   = $_POST['url'];
			 $content_body = $_POST['content_body'];
			 $category = $_POST['category'];
			 $meme_trackback = $_POST['meme_trackback'];
			 $meme_tags = $_POST['meme_tags'];


			 $smarty->assign('title', check_plain($title));
			 $smarty->assign('url', check_plain($url));
			 $smarty->assign('content_body', check_plain($content_body));
			 $smarty->assign('category', $category);
			 $smarty->assign('category_name', $bm_options[$category]);
			 $smarty->assign('meme_trackback', check_plain($meme_trackback));
			 $smarty->assign('meme_tags', check_plain($meme_tags));
			 $smarty->assign('cats', $bm_options);
			 $smarty->assign('micro_content', get_youtube($url));
		 }
		 else
		 {
			 $_POST['user_id'] = $bm_users->get_user_id();
			 $memes->add_meme($_POST);
			 header("Location: memes_queue.php");
			 return exit;
		 }
	 }
  }
  $bm_title = 'publicar meme, paso '.$step.' de 3';
  $bm_content = "post_$step";
  $smarty->assign('content_title', $bm_title);
  $smarty->assign('content', $bm_content);
  $smarty->assign('step', $step);
  $smarty->display('master_page.tpl');
?>
