<?php

include_once('lib/xmlrpc.inc');
include_once('lib/xmlrpcs.inc');

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
  return check_plain($url); 
}

function check_url($url)
{
	$url = filter_bad_protocol($url);
	$furl = @fopen($url, "r");
	if (!$furl) {
		return false;
	}
	@fclose($furl);
	return true;
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
	$msg = new xmlrpcmsg("weblogUpdates.ping", array(new xmlrpcval("Blog Memes"), new xmlrpcval($bm_url)));
	$doPing = $blogMemes->send($msg);
	return ($doPing && $doPing->faultCode() == 0);
}

function get_gravatar($base_url, $gravatar_id, $size)
{
    global $bm_url;
	$default = $bm_url . "anon${size}.png";
	return "http://www.gravatar.com/avatar.php?gravatar_id=$gravatar_id&amp;default=".urlencode($default)."&amp;size=$size.&amp;rating=R";
}

function get_youtube($url)
{
        $matches = array();
        @preg_match('/v=(.*)$/', $url, $matches);
        if (empty($matches[1])) {
                return '';
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
