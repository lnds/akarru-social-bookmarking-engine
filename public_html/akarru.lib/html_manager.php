<?php

include_once('lib/xmlrpc.inc');
include_once('lib/xmlrpcs.inc');


function is_valid_email($addr)
{
	if(substr_count($addr,"@")!=1)
		return false;
	list($local, $domain) = explode("@", $addr);
	
	$pattern_local = '^([0-9a-z]*([-|_]?[0-9a-z]+)*)(([-|_]?)\.([-|_]?)[0-9a-z]*([-|_]?[0-9a-z]+)+)*([-|_]?)$';
	$pattern_domain = '^([0-9a-z]+([-]?[0-9a-z]+)*)(([-]?)\.([-]?)[0-9a-z]*([-]?[0-9a-z]+)+)*\.[a-z]{2,4}$';

	$match_local = eregi($pattern_local, $local);
	$match_domain = eregi($pattern_domain, $domain);
	
	return ($match_local && $match_domain && gethostbyname($domain));
}

// encode special chars 
function check_plain($text)
{
    if (get_magic_quotes_gpc() == 1)
		$text = stripslashes($text);
	return htmlspecialchars($text, ENT_QUOTES);
}

function decode_plain($text)
{
	return html_entity_decode($text, ENT_QUOTES);
}

function filter_bad_protocol($url)
{
	static $allow;
	if (!isset($allow)) {
		$allow = array('http', 'https', 'ftp', 'news', 'nntp', 'telnet', 'mailto', 'irc', 'ssh', 'sftp', 'webcal');
	}

    // Remove soft hyphen
  $url = str_replace(chr(194) . chr(173), '', $string);
  // Strip protocols

  do {
    $before = $url;
    $colonpos = strpos($string, ':');
    if ($colonpos > 0) {
      $protocol = substr($string, 0, $colonpos);
      if (!isset($allow[$protocol])) {
        $url = substr($url, $colonpos + 1);
      }
    }
  } while ($before != $url);
  return true;//check_plain($url); 
}

function check_url($url)
{
	//$url = filter_bad_protocol($url);
	$furl = @fopen($url, "r");
	if (!$furl) {
		return 0;
	}
	@fclose($furl);
	return 1;
}

function request_uri() {

  if (isset($_SERVER['REQUEST_URI'])) {
    $uri = $_SERVER['REQUEST_URI'];
  }
  else {
    if (isset($_SERVER['argv'])) {
      $uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];
    }
    else {
      $uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING'];
    }
  }

  return $uri;
} 


function table($attributes, $content)
{
	return '<table '.$attribute.'>'.$content.'</table>';
}

function tr($attributes, $cells)
{
	return '<tr '.$attributes.'>'.$cells.'</tr>';
}

function td($attributes, $data)
{
	return '<td '.$attributes.'>'.$data.'</td>';
}

function span($attributes, $data)
{
	return '<span '.$attributes.'>'.$data.'</span>';
}

function div($attributes, $content)
{
	return '<div '.$attributes.'>'.$content.'</div>';
}


function find_media_info($url)
{
	$result = array();
	// busca trackback de acuerdo a la especificacion de six apart http://www.sixapart.com/pronet/docs/trackback_spec
	// o sino usa la especificacion de google de pingback


    $f = curl_init($url);
	curl_setopt($f, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($f);
	curl_close($f);
	preg_match('/trackback:ping="([^\"]+)"/i', $data, $regs);
	if (empty($regs[1])) {
		preg_match('/\<a href="([^\"]+)" rel="trackback"\>/i', $data, $regs);
	}

	$result[] = $regs[1];

	// find_favicon($url)
	$icon = '';
    preg_match('/<link rel=[\',\"]icon[\",\'] href=[\",\']([^\",\']+)[\",\']/i', $data, $regs);
	if (empty($regs[1])) {
		preg_match('/<link rel=[\",\']shortcut icon[\",\'] href=[\",\']([^\",\']+)[\".\']/i', $data, $regs);
	}

	if (!empty($regs[1])) {
		$icon = $regs[1];
		$icon_info = @parse_url($icon);
		if (empty($icon_info['scheme'])) {
			$info = parse_url($url);
			$icon = $info['scheme'].'://'.$info['host'].$icon_info['path'];
		}
	}
	$result[] = $icon;
	return $result;
}



function ping_technorati() 
{
    global $bm_url;
	$blogMemes = new xmlrpc_client("/rpc/ping", "rpc.technorati.com", 80);
	$msg = new xmlrpcmsg("weblogUpdates.ping", array(new xmlrpcval("Blog Memes"), new xmlrpcval("http://www.blogmemes.com/")));
	$doPing = $blogMemes->send($msg);
	return ($doPing && $doPing->faultCode() == 0);
}

function get_gravatar($gravatar_id, $size)
{
	global $bm_url;
	$default = $bm_url . "anon${size}.png";
	return "http://www.gravatar.com/avatar.php?gravatar_id=$gravatar_id&amp;default=".urlencode($default)."&amp;size=$size.&amp;rating=R";
}


function get_myvideoes($url)
{
	$matches = array();
	@preg_match('/watch\/(.*)$/', $url, $matches);
	if (empty($matches[1])) {
		return '';
	}
	$url = 'http://www.myvideo.es/movie/'.$matches[1];
    return '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="300" height="250"><param name="movie" value="'.$url.'" /><embed src="'.$url.'" width="300" height="250" type="application/x-shockwave-flash" /></object>';
}

function get_googlevideo($url)
{
	$matches = array();
	@preg_match('/docid=(.*)$/', $url, $matches);
	if (empty($matches[1])) {
		return get_myvideoes($url);
	}
	$url = 'http://video.google.com/googleplayer.swf?docId='.$matches[1];
	return '<embed style="width:300px; height:250px;" type="application/x-shockwave-flash" src="'.$url.'" allowScriptAccess="sameDomain" > </embed>';
}

function get_youtube($url)
{
        $matches = array();
        @preg_match('/v=(.*)$/', $url, $matches);
        if (empty($matches[1])) {
                return get_googlevideo($url);
        }
        $url = 'http://youtube.com/v/'.$matches[1];
        return '<object width="300" height="250"><param name="movie" value="'.$url.'"></param><embed src="'.$url.'" type="application/x-shockwave-flash" width="300" height="250"></embed></object>';
}


function replace_urls($text)
{
	return preg_replace( array(
               "/[^\"'=]((http|ftp|https):\/\/[^\s\"']+)/i",
               "/<a([^>]*)target=\"?[^\"']+\"?/i",
               "/<a([^>]+)>/i"
       ),
         array(
               "<a href=\"\\1\">\\1</a>",
               "<a\\1",
               "<a\\1  >"
           ),
       $text
       );
}

?>
