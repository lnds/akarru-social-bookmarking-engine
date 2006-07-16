<?php

  if (isset($_GET['voted']))
  {
     $voted = (int) $_GET['voted'];
  }
  
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
  include_once('akarru.lib/spam_fight.php');
  
  include_once('common_elements.php');
  
  function mailDetails($user, $meme_url, $comment_text, $spam=0)
  {
	global $bm_domain;
	global $bm_admin_email_address;
     $fromemail = "no-reply@" . $bm_domain;   // Sender's adress
     $dest = $bm_admin_email_address;  // Receiver address
     
     
    $ip = "[" . $_SERVER["REMOTE_ADDR"] . "] - resolved=[" . gethostbyaddr($_SERVER["REMOTE_ADDR"]) . "]";
    $from = "[" . $_SERVER['HTTP_REFERER'] . "]";
    $what = "what=[" . $_SERVER['HTTP_USER_AGENT'] . "]";        
    $posteddate = date('l dS \of F Y h:i:s A');
    
    if ($spam)
    {
        $subject="[SPAM] Comment submitted by '" . $user . "'";  // Email subject.
    }
    else
    {
        $subject="Comment has been posted by '" . $user . "'";  // Email subject.
    }
               
    $message ="Posted on " . $posteddate  . "\nIP: " . $ip . "\nFrom: " . $from ."\nUser_agent: " . $what . "\n";
    $message.="By '" .  $user . "',\n URL: " . $meme_url . "\n\n";
    $message.="\n\nComment text=\n";
    $message.="\n\n------ Begin -----\n";
    $message.= $comment_text;
    $message.="\n\n------ End -----\n";
    
    // Fonction Mail
    @mb_send_mail($dest,$subject,$message, "From : $fromemail\n");
  }

  $smarty->assign('content_title', $content_title_comment);
  $memes = new memes($bm_db, $bm_user);
  if (!empty($_POST))
  {
      $bm_errors = 0;
      $spam = is_spam($bm_user_name, $bm_users->get_user_email(), $bm_users->get_user_url(), $_POST['comment'], $memes->get_permalink($meme_id), "comment");
      mailDetails($bm_users->get_user_name(), $memes->get_permalink($meme_id), $_POST['comment'], $spam);
      if ($spam)
      {
        $smarty->assign('error_comment', true);
        $smarty->assign('comment_value', $_POST['comment']);
        $bm_errors++;
      }
      else
      {
	    $memes->add_comment($meme_id, $_POST['comment']);
        if (isset($_POST['position']))
        {
		  $memes->debate($meme_id, $bm_user, $_POST['position'], false);
        }
        header("Location: /meme/$meme_id");
	    exit();
	    return;
	  }
  }
  $memes->debate($meme_id, $bm_user, 0, true);
  // Kenji : if referer is different than empty or blogmemes
  // then there is a good chance that the user is coming
  // from somewhere else => $share = 1
  $share = 0;
  if (isset($_SERVER['HTTP_REFERER']))
  {
    if (strlen($_SERVER['HTTP_REFERER']) > 0)
  	    $share = stristr($_SERVER['HTTP_REFERER'], $bm_url) ? 1 : 0;
  }
  $meme = $memes->get_meme($meme_id, $share);
  $comments = $memes->get_comments($meme_id);
  $smarty->assign('sub_title', $meme->title);

  $smarty->assign('meme', $meme);
  $smarty->assign('meme_id', $meme_id);
  $smarty->assign('community', true);
  $smarty->assign('content', 'comment');
  $smarty->assign('comments', $comments);

  $memes_tags = array();
  $tags = $memes->get_tags($meme_id,12);
  foreach ($tags as $tag)
  {
	  $memes_tags[] = '&nbsp;<a href="/memes_by_tag.php?tag_name='.$tag->tag.'" rel="tag">'.$tag->tag.'</a>&nbsp;&nbsp;';
  }
  $smarty->assign('tags_of_meme', $memes_tags);
  if ($meme->allows_debates) 
  {
	  $smarty->assign('friends', $memes->get_friends($meme_id));
	  $smarty->assign('foes', $memes->get_foes($meme_id));

	  $sponsors =  $memes->get_voters($meme_id);
	  $smarty->assign('sponsors', $sponsors);
	  $neutrals = $memes->get_neutrals($meme_id);
	  $neutrals = array_diff($neutrals, $sponsors);
	  $neutrals[] = '<img border="0" src="/anon40.png" alt="' . $bl_anonymous . '"/><br /><a href="/register.php">'.$meme->clicks.'&nbsp;'.$bl_anonymous.'</a>';
	  $smarty->assign('neutrals', $neutrals);
  }
  else
  {
	  $smarty->assign('voters', $memes->get_voters($meme_id));
  }
  $smarty->assign('show_ads', showGGAds());
  if (isset($_GET['voted']))
  {
    $smarty->assign('alreadyvoted', ($voted == 0));
    $smarty->assign('voted', $voted);
  }
  else
  {
    $smarty->assign('alreadyvoted', false);
    $smarty->assign('voted', false);
  }
  $smarty->display('master_page.tpl');
?>
