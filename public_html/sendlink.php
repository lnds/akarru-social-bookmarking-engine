<?php
include_once('akarru.lib/common.php');

$copyto = "adminjp@blogmemes.jp";

function ValidEmail($addr){
	if(substr_count($addr,"@")!=1)
		return false;
	list($local, $domain) = explode("@", $addr);
	
	$pattern_local = '^([0-9a-z]*([-|_]?[0-9a-z]+)*)(([-|_]?)\.([-|_]?)[0-9a-z]*([-|_]?[0-9a-z]+)+)*([-|_]?)$';
	$pattern_domain = '^([0-9a-z]+([-]?[0-9a-z]+)*)(([-]?)\.([-]?)[0-9a-z]*([-]?[0-9a-z]+)+)*\.[a-z]{2,4}$';

	$match_local = eregi($pattern_local, $local);
	$match_domain = eregi($pattern_domain, $domain);
	
	return ($match_local && $match_domain && gethostbyname($domain));
}

function ValidMessage($message)
{ 
  if (empty($message)) return true;
  
  return strstr($message, "http://") === FALSE;
}

  $meme_id = $_GET['meme_id'];
  if (empty($meme_id)) {
	  $meme_id = $_POST['meme_id'];
  }
  if (empty($meme_id)) {
	  header("Location: index.php");
	  exit();
	  return;
  }
    
  $memes = new memes($bm_db, $bm_user);
  $meme = $memes->get_meme($meme_id);
  $smarty->assign('meme', $meme);
  $smarty->assign('meme_id', $meme_id);
  $smarty->assign('permalink', $memes->get_permalink($meme_id));
  $smarty->assign('username', $bm_users->get_user_name());

    
  if (empty($_POST) || empty($_POST['step']))
  {
      $step = 1;
      $smarty->assign('email_subject', $bl_site_caption . " - " . $bl_sub_title . ":" . $meme->title);
	  $smarty->assign('sender_email', $bm_users->user->email);
      $smarty->assign('sender_name', $bm_users->get_user_name());
	  $smarty->assign('receiver_email', '');
      $smarty->assign('receiver_name', '');
	  $smarty->assign('message_body', '');
  }
  else if ($_POST['step'] == 1)
  {
        $to_email=$_POST['receiver_email'];
        $to_name=$_POST['receiver_name'];
        $from_name=$_POST['sender_name'];
        $from_email=$_POST['sender_email'];
        $message=$_POST['message_body'];
        $title=$_POST['email_subject'];
        $url_to_send= $memes->get_permalink($meme_id);
        
        $errors=0;
        
        if (empty($title))
        {
            $title = $bl_site_caption . " - " . $bl_sub_title . ":" . $meme->title;
        }
        
        if (!$to_email)
        {
            $smarty->assign('error_receiver_email', 1);
            $errors++;
        }
        elseif (!ValidEmail($to_email))
        {
            $smarty->assign('error_receiver_email', 1);
            $errors++;
        }
        
        if (!$from_email)
        {
            $smarty->assign('error_sender_email', 1);
            $errors++;
        }
        elseif (!ValidEmail($from_email))
        {
            $smarty->assign('error_sender_email', 1);
            $errors++;
        }
        
        if (!ValidMessage($message))
        {
            $smarty->assign('error_message_body', 1);
            $errors++;
        }
        
        if ($errors == 0)
        {
            $body= $message;
            $automatic_message = $bl_automatic_message_email;
            $automatic_message = str_replace("%USER%", $bm_users->get_user_name(), $automatic_message);
            $automatic_message = str_replace("%MEME_URL%", $memes->get_permalink($meme_id), $automatic_message);
            $automatic_message = str_replace("%TITLE%", $meme->title, $automatic_message);
            $automatic_message = str_replace("%TEXT%", $meme->content, $automatic_message);
            $automatic_message = str_replace("%VOTES%", $meme->votes, $automatic_message);
            $body .= "\n\n---\n" . $automatic_message;
            
            if (@mail("$to_name <$to_email>",$title, $body, "From: $from_name <$from_email>\n\n"))
            {
                if(!empty($copyto))
                {
				     @mail($copyto,"Sendlink Blogmemes",$automatic_message,"From: no-reply@".$_SERVER['HTTP_HOST']);
                }
                
                $step = 2;
                
                header("Location: sendlink_ok.php?meme_id=" . $meme_id);
			    return exit;
            }
            else
            {
                $step = 1;
            }
        }
        else
        {
            $step = 1;
        }
        
        if ($step == 1)
        {
            $smarty->assign('email_subject', $title);
	        $smarty->assign('sender_email', $from_email);
            $smarty->assign('sender_name', $from_name);
            $smarty->assign('receiver_email', $to_email);
            $smarty->assign('receiver_name', $to_name);
            $smarty->assign('message_body', $message);
        }
  }

  $smarty->assign('content', 'sendlink');
  $smarty->assign('content_title', $content_title_sendlink);
  $smarty->assign('community', true);
  $smarty->assign('page', $page);
  $smarty->assign('step', $step);
  $smarty->display('master_page.tpl');
?>