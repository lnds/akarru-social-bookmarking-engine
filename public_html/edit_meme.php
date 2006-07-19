<?php
  $meme_id = intval($_GET['meme_id']);
  if ($meme_id == 0) {
	  $meme_id = intval($_POST['meme_id']);
  }
  if ($meme_id == 0) {
	  header("Location: /404.php");
	  exit();
	  return;
  }
  include_once('akarru.lib/common.php');
  include_once('common_elements.php');

  $smarty->assign('content_title', $content_title_editar);
  $memes = new memes($bm_db, $bm_user);
  $meme = $memes->get_original_meme($meme_id);
  if ($meme->submitted_user_id != $bm_user && !$bm_users->get_is_admin()) {
	  header("Location: /meme/$meme_id");
	  exit();
	  return;
  }
  if (!empty($_POST)) 
  {
      if ($bm_users->get_is_admin())
      {
        if ($_POST['disable'] == 1)
        {
            $memes->disable_meme($meme_id);
        }
        else
        {
            $memes->enable_meme($meme_id);
        }
      }
      
	  $_POST['user_id'] = $bm_users->get_user_id();
	  $memes->update_meme($_POST);
	  $smarty->clear_cache(null,'db|memes');

	  header("Location: /meme/$meme_id");
	  exit();
	  return;
  }
  $bm_cats = new categories($bm_db);

  $bm_cats = $bm_cats->fetch_all();
  $smarty->assign('bm_cats', $bm_cats);

  $bm_options = array('0'=>$content_title_post_seleccione);
  foreach ($bm_cats as $cat)
  {
	  $bm_options[$cat->ID] = $cat->cat_title;
  }
  $smarty->assign('cats', $bm_options);


  $meme = $memes->get_original_meme($meme_id);
  $comments = $memes->get_comments($meme_id);
  $smarty->assign('meme', $meme);
  // Kenji : fix to get the tags and type of content
  $tags = $memes->get_tags($meme_id);
  $memes_tags = "";
  foreach ($tags as $tag)
  {
	  if (strlen($memes_tags) == 0)
      {
	     $memes_tags .= $tag->tag;
      }
      else
      {
         $memes_tags .= ',' . $tag->tag;
      }
  }
  $smarty->assign('meme_tags', $memes_tags);
  $smarty->assign('meme_type', $meme->is_micro_content);
  // end fix Kenji
  $smarty->assign('content', 'edit_post');
  $smarty->assign('show_ads', showGGAds());
  $smarty->display('master_page.tpl');
?>
