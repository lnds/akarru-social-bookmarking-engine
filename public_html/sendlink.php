<?php
require("lib/class.phpmailer.php");
include_once('akarru.lib/common.php');

$copyto = "admin@blogmemes.com";


function ValidMessage($message)
{ 
  if (empty($message)) return true;
  
  return stristr($message, "http://") === FALSE;
}

  $meme_id = intval($_GET['meme_id']);
  if ($meme_id==0) {
	  $meme_id = intval($_POST['meme_id']);
  }
  if ($meme_id == 0) {
	  header("Location: index.php");
	  exit();
	  return;
  }
    
  $memes = new memes($bm_db, $bm_user);
  $meme = $memes->get_meme($meme_id, 1);
  $smarty->assign('meme', $meme);
  $smarty->assign('meme_id', $meme_id);
  $smarty->assign('permalink', $memes->get_permalink($meme_id));
  $smarty->assign('username', $bm_users->get_user_name());

  $step = intval($_POST['step']);
    
  if (empty($_POST) || $step == 0)
  {
      $step = 1;
      $smarty->assign('email_subject', $bl_site_caption . " - " . $bl_sub_title . ": " . $meme->title);
	  $smarty->assign('sender_email', $bm_users->user->email);
      $smarty->assign('sender_name', $bm_users->get_user_name());
	  $smarty->assign('receiver_email', '');
      $smarty->assign('receiver_name', '');
	  $smarty->assign('message_body', '');
  }
  else if ($step == 1)
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
        elseif (!is_valid_email($to_email))
        {
            $smarty->assign('error_receiver_email', 1);
            $errors++;
        }
        
        if (!$from_email)
        {
            $smarty->assign('error_sender_email', 1);
            $errors++;
        }
        elseif (!is_valid_email($from_email))
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
			$headers  = "From: $from_name <$from_email>\r\n";
            $headers .= 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			ini_set(sendmail_from,'admin@blogmemes.com'); 

			$mail = new PHPMailer();
			$mail->IsSMTP();
            $mail->SMTPAuth = true;     // turn on SMTP authentication
			$mail->Username = "admin";  // SMTP username
			$mail->Password = "dolmen456";
			$mail->From = $from_email;
			$mail->FromName = $from_name;
			$mail->AddAddress($to_email, $to_name);
			$mail->AddReplyTo($from_email, $from_name);
			$mail->AddBCC($copyto, "Sendlink Blogmemes");
			$mail->IsHTML(true);
			$mail->Subject = $title;
			$mail->Body = $body;

            if ($mail->Send())
            {
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