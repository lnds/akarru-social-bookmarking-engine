<?php
  include_once('akarru.lib/common.php');
  include_once('common_elements.php');  
  // Put your email address in $dest and change ??? to .net .com .fr .jp .ru in the sender email address...
  function mailDetails($user, $meme_url, $meme_title, $meme_text)
  {
	global $bm_domain;
    // Configure these
     $fromemail="no-reply@" . $bm_domain;   // Sender's adress
     $dest="admin@" . $bm_domain;  // Receiver address
     
     
    $ip = "[" . $_SERVER["REMOTE_ADDR"] . "] - resolved=[" . gethostbyaddr($_SERVER["REMOTE_ADDR"]) . "]";
    $from = "[" . $_SERVER['HTTP_REFERER'] . "]";
    $what = "what=[" . $_SERVER['HTTP_USER_AGENT'] . "]";        
    $posteddate = date('l dS \of F Y h:i:s A');
     
     $subject="Meme entitled '" . $meme_title . "' has been posted by '" . $user . "'";  // Email subject.
               
    $message ="Posted on " . $posteddate  . "\nIP: " . $ip . "\nFrom: " . $from ."\nUser_agent: " . $what . "\n";
    $message.="By '" .  $user . "',\n URL: " . $meme_url . "\n\n";
    $message.="\n\nMeme text=\n";
    $message.="\n\n------ Begin -----\n";
    $message.= $meme_text;
    $message.="\n\n------ End -----\n";
    
    // Fonction Mail
    @mail($dest,$subject,$message, "From : $fromemail");
  }

// Following functions are to decode urlencoded strings.
// Needed for Blogmemes that would have non ASCII characters in the URL, title and so on
    function code2utf($num){
  if($num<128)return chr($num);
  if($num<2048)return chr(($num>>6)+192).chr(($num&63)+128);
  if($num<65536)return chr(($num>>12)+224).chr((($num>>6)&63)+128).chr(($num&63)+128);
  if($num<2097152)return chr(($num>>18)+240).chr((($num>>12)&63)+128).chr((($num>>6)&63)+128) .chr(($num&63)+128);
  return '';
}
  function unescape($source, $iconv_to = 'UTF-8') {
   $decodedStr = '';
   $pos = 0;
   $len = strlen ($source);
   while ($pos < $len) {
       $charAt = substr ($source, $pos, 1);
       if ($charAt == '%') {
           $pos++;
           $charAt = substr ($source, $pos, 1);
           if ($charAt == 'u') {
               // we got a unicode character
               $pos++;
               $unicodeHexVal = substr ($source, $pos, 4);
               $unicode = hexdec ($unicodeHexVal);
               $decodedStr .= code2utf($unicode);
               $pos += 4;
           }
           else {
               // we have an escaped ascii character
               $hexVal = substr ($source, $pos, 2);
               $decodedStr .= chr (hexdec ($hexVal));
               $pos += 2;
           }
       }
       else {
           $decodedStr .= $charAt;
           $pos++;
       }
   }

   if ($iconv_to != "UTF-8") {
       $decodedStr = iconv("UTF-8", $iconv_to, $decodedStr);
   }
  
   return $decodedStr;
}


  $bm_cats = new categories($bm_db);
  $bm_cats = $bm_cats->fetch_all_enabled();
  $smarty->assign('bm_cats', $bm_cats);

  if (!$bm_users->is_logged_in()) {
	  $bm_home = $bm_page;
	  $login_url = 'login.php?from=post';
	  $action = '';
	  if (!empty($_GET['title'])) {
		  $action .= '&title='.check_plain($_GET['title']);
	  }
	  if (!empty($_GET['url'])) {
		  $action .= '&url='.urlencode($_GET['url']);
	  }
	  if (!empty($action)) {
		  $login_url .= '&action=ep'.$action;
	  }
	  header("Location: $login_url");
	  exit();
	  return;
  }
  else if (!$bm_users->is_valid_account()) {
	  $bm_home = $bm_page;
      header("Location: /validate_user.php");      
	  exit();
	  return;
  }

  $step = intval($_POST['step']);
  if (empty($_POST) || $step == 0)
  {
	  $step = 1;
	  $url = $_GET['url'];
	  if (!empty($url))
	  {
		  $smarty->assign('url', $url);
	  }
	  else {
		  $smarty->assign('url', 'http://');
	  }

      $_GET['title'] = unescape($_GET['title']);
      
	  $smarty->assign('title', $_GET['title']);
	  $smarty->assign('meme_trackback', '');

  }
  else
  {
	  $bm_options = array('0'=>$content_title_post_seleccione);
	  foreach ($bm_cats as $cat)
	  {
		  $bm_options[$cat->ID] = $cat->cat_title;
	  }

	 $bm_errors = 0;
	 $step = $_POST['step'];
	 $memes = new memes($bm_db, $bm_user, $bm_promo_level);
	 if ($_POST['step'] == 1) {
		 $smarty->assign('debates', $_POST['debates']);
   		 $title = $_POST['title'];
		 $url   = $_POST['url'];
		 if ($url == 'http://') {
			 $url = '';
		 }
		 $content_type = $_POST['content_type'];
		 $smarty->assign('content_type',  $content_type);
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
			 if (check_url($url) == 0) {
				 $smarty->assign('error_url', true);
				 $bm_errors++;
			 }
			 if ($memes->check_url_exists_in_db(check_plain($url))) {
				 $smarty->assign('error_duplicate_url', true);
				 $bm_errors++;
			 }
		 }
		 if ($bm_errors == 0) {
			 $step = 2;
			 $smarty->assign('cats', $bm_options);
			 $info = find_media_info($url);
			 $smarty->assign('meme_trackback', $info[0]);
			 $smarty->assign('favicon', $info[1]);
			 $smarty->assign('debates', isset($_POST['debates']));
		 }
		 else
         {
             $step = 1;
         }
	 }
	 elseif ($_POST['step'] == 2) {
		 $smarty->assign('content_type',  $_POST['content_type']);
		 $title = $_POST['title'];
		 $url   = $_POST['url'];
		 $content_body = $_POST['content_body'];
		 $category = $_POST['category'];
		 $meme_trackback = $_POST['meme_trackback'];
		 $favicon = $_POST['favicon'];
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
		 $smarty->assign('favicon', $favicon);
		 $smarty->assign('gravatar', get_gravatar($bm_users->get_user_gravatar(), 16));
		 $video = get_youtube($url);
		 $smarty->assign('micro_content', $video);
		 if (empty($video))
			 $smarty->assign('page_image', 'http://img.simpleapi.net/small/'.$url);
		 $smarty->assign('debates', $_POST['debates']);
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
		 $smarty->assign('debates', $_POST['debates']);
		 if (!empty($_POST['do_edit'])) {
			 $step = 2;
			 $title = $_POST['title'];
			 $url   = $_POST['url'];
			 $content_body = $_POST['content_body'];
			 $category = $_POST['category'];
			 $meme_trackback = $_POST['meme_trackback'];
			 $meme_tags = $_POST['meme_tags'];
			 $favicon = $_POST['favicon'];


			 $smarty->assign('title', check_plain($title));
			 $smarty->assign('url', check_plain($url));
			 $smarty->assign('content_body', check_plain($content_body));
			 $smarty->assign('category', $category);
			 $smarty->assign('category_name', $bm_options[$category]);
			 $smarty->assign('meme_trackback', check_plain($meme_trackback));
			 $smarty->assign('meme_tags', check_plain($meme_tags));
			 $smarty->assign('cats', $bm_options);
			 $smarty->assign('gravatar', get_gravatar($bm_url, $bm_users->get_user_email(), 16)); 
			 $video = get_youtube($url);
			 $smarty->assign('micro_content', $video);
			 if (empty($video))
				 $smarty->assign('page_image', 'http://img.simpleapi.net/small/'.$url);
			 $smarty->assign('favicon', $favicon);
		 }
		 else
		 {
			 $_POST['user_id'] = $bm_users->get_user_id();
			 $memes->add_meme($_POST);
			 $smarty->clear_all_cache();
             $title = $_POST['title'];
			 $url   = $_POST['url'];
             $category = $_POST['category'];
			 $content_body = $_POST['content_body'];
			 mailDetails($bm_users->get_user_name(), $url, $title, $content_body);
             header("Location: /show_cat.php?cat_name=" .$bm_options[$category]);
			 return exit;
		 }
	 }
  }
  $bm_title = $content_title_post.$step.$content_title_post2;
  $bm_content = "post_$step";
  $smarty->assign('content_title', $bm_title);
  $smarty->assign('content', $bm_content);
  $smarty->assign('step', $step);
  $smarty->display('master_page.tpl');
?>
