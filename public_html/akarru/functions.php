<?php
/**
 * @package AkarruCPE
 * @version 0.6
 * @copyright (c) 2006 Eduardo Diaz Cortes
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License 
 * @author Eduardo Diaz <ediaz@lnds.net>
 */
		 
function __autoload($class_name)
{
	class_exists($class_name) || require('akarru/'.$class_name.'.class.php');
}


// encode special chars 
function check_plain($text)
{
    if (get_magic_quotes_gpc() == 1)
		$text = stripslashes($text);
	return htmlspecialchars($text, ENT_QUOTES);
}

function request_value($arg, $deflt='')
{
	$type = gettype($deflt);
	$var = $_REQUEST[$arg];
	if (!isset($var)) 
	{
		return $deflt;
	}
    $value = check_plain($var);
	settype($value, $type);
	return $value;
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
    return '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="425" height="350"><param name="movie" value="'.$url.'" /><embed src="'.$url.'" width="425" height="350" type="application/x-shockwave-flash" /></object>';
}

function get_googlevideo($url)
{
	$matches = array();
	@preg_match('/docid=(.*)$/', $url, $matches);
	if (empty($matches[1])) {
		return get_myvideoes($url);
	}
	$url = 'http://video.google.com/googleplayer.swf?docId='.$matches[1];
	return '<embed style="width:425px; height:350px;" type="application/x-shockwave-flash" src="'.$url.'" allowScriptAccess="sameDomain" > </embed>';
}

function get_youtube($url)
{
        $matches = array();
        @preg_match('/v=(.*)$/', $url, $matches);
        if (empty($matches[1])) {
                return get_googlevideo($url);
        }
        $url = 'http://youtube.com/v/'.$matches[1];
        return '<object width="425" height="350"><param name="movie" value="'.$url.'"></param><embed src="'.$url.'" type="application/x-shockwave-flash" width="425" height="350"></embed></object>';
}

// code based on excellent article found at  http://www.leosingleton.com/projects/code/phpapp/
// 
// Application vars

define("APP_CACHE_FILE",
    "/tmp/akkaru_application.data");

function set_app_var($var_name, $value)
{
	$_APP[$var_name] = $value;
	$_APP_CHANGED = true;
}

function get_app_var($var_name)
{
	return $_APP[$var_name];
}

function application_start ()
{
    global $_APP;

    // if data file exists, load application
    //   variables
    if (file_exists(APP_CACHE_FILE))
    {
        // read data file
        $file = fopen(APP_CACHE_FILE, "r");
        if ($file)
        {
            $data = fread($file,
                filesize(APP_CACHE_FILE));
            fclose($file);
        }

        // build application variables from
        //   data file
        $_APP = unserialize($data);
    }
}

function application_end ()
{
    global $_APP;
	global $_APP_CHANGED;

	if ($APP_CHANGED) 
	{
		// write application data to file
		$data = serialize($_APP);
		$file = fopen(APP_DATA_FILE, "w");
		if ($file)
		{
			fwrite($file, $data);
			fclose($file);
		}
	}
}

function is_post_back()
{
	return $_SERVER['REQUEST_METHOD']=='POST' || sizeof($_POST) > 0;
}


function request_uri()
{
	return $_SERVER['REQUEST_URI'];
}

function redirect_to($url)
{
	header("Location: $url");
	exit(0);
}

function check_url_format($url)
{
	$url_arr = @parse_url($url);
	if ($url_arr['scheme'] != 'http') 
		return false;
	if (empty($url_arr['host']) )
		return false;
	return $url;
}

function check_url_exists($url)
{
	$furl = @fopen($url, "r");
	if (!$furl) {
		return false;
	}
	@fclose($furl);
	return true;
}

function media_filter($url, $width, $height)
{
	$filters = array(
	'/v=(.*)$/' => '<object width=\"$WIDTH\" height=\"$HEIGHT\"><param name=\"movie\" value=\"http://youtube.com/v/$MOVIE\"></param><embed src=\"http://youtube.com/v/$MOVIE\" type=\"application/x-shockwave-flash\" width=\"$WIDTH\" height=\"$HEIGHT\"></embed></object>',
	'/docid=(.*)$/' => '<embed style="$WIDTH; height:$HEIGHT;" type="application/x-shockwave-flash" src="http://video.google.com/googleplayer.swf?docId=$MOVIE" allowScriptAccess="sameDomain" > </embed>'
	);

	foreach (array_keys($filters) as $filter)
	{
		$matches = array();
		@preg_match($filter, $url, $matches);
		if (!empty($matches[1])) 
		{
			$result = $filters[$filter];
			$MOVIE = $matches[1];
			$WIDTH = $width;
			$HEIGHT = $height;
			@eval("\$result = \"$result\";");
			return $result;
		}
	}
	return '';
}
?>
