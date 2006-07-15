<?php
$gadgetsCache = $_SERVER["DOCUMENT_ROOT"] . "/cache/gadgets/";
$feedFlareCache = $_SERVER["DOCUMENT_ROOT"] . "/cache/feedflares/";
$rssCache = $_SERVER["DOCUMENT_ROOT"] . "/cache/rss/";
$cache_tolerance = 60*5; // in seconds to avoid too many requests to refresh the cache
$googlegadget_cache_tolerance = 60*5; // in seconds to avoid too many requests to refresh the cache
$cleanup_tolerance = 60*60*6; // in seconds to cleanup the older files in the cache
$google_gadget_forced_refresh = 60*60*6; // in seconds

function processCache($meme_id)
{
    global $gadgetsCache;
    global $feedFlareCache;
    global $cache_tolerance;
    global $cleanup_tolerance;
    global $googlegadget_cache_tolerance;

    $feedFlareCacheFile = $feedFlareCache . $meme_id;
    
    clearstatcache();  // filemtime info gets cached so we must ensure that the cache is empty
    flush();
    // check how old the cache file is
    if (file_exists($feedFlareCacheFile))
    {
        $time_difference = time() - filemtime($feedFlareCacheFile);
    }
    else
    {
        $time_difference = 0;  // update will be done eventually by the feedflare
    }
    
    if  ($time_difference >= $cache_tolerance)
    {
        // the cache has to be updated
        // the quickest way is to delete the cache file
        unlink($feedFlareCacheFile);
    }
    
    // delete leftovers (too old files)
    $dp = opendir($feedFlareCache);
    $files = Array();
    while($entry = readdir($dp)) {
        if ($entry != "." && $entry != "..")
        {
        
            $key = $feedFlareCache . $entry;
            $files[$key] = filemtime($key); // change to fileatime for access and filectime for inode change
        }
    }

    closedir($dp);
    asort($files);

    $currentTime = time();
    
    while ($modTime = current($files))
    {
       $time_difference = $currentTime - $modTime;
       if ($time_difference >= $cleanup_tolerance)
       {
           $key = key($files);
           unlink($key);
       }
       else
       {
         // the rest of the files are ok
         break;
       }
       next($files);
    }
    
    // Now let's look at the googlegadget cache files
    $files = Array();
    // delete the cache files
    $dp = opendir($gadgetsCache);
    
    while($entry = readdir($dp)) {
        if ($entry != "." && $entry != "..")
        {
            $key = $gadgetsCache . $entry;
            $files[$key] = filemtime($key);
        }
    }

    closedir($dp);
    asort($files);

    $currentTime = time();
    
    while ($modTime = current($files))
    {
       $time_difference = $currentTime - $modTime;
       if ($time_difference >= $googlegadget_cache_tolerance)
       {
           $key = key($files);
           unlink($key);
       }
       else
       {
         // change the last modified time in order to guarantee a refresh in googlegadget_cache_tolerance
         $key = key($files);
         $newtime = time() - $google_gadget_forced_refresh + $googlegadget_cache_tolerance;
         touch ($key, $newtime);
       }
       next($files);
    }
}

function isCacheUpdateNeeded($cacheFile, $toleranceInSeconds)
{   
    flush();
    // check how old the cache file is
    if (file_exists($cacheFile))
    {
        clearstatcache();  // filemtime info gets cached so we must ensure that the cache is empty
        $time_difference = time() - filemtime($cacheFile);
    }
    else
    {
        $time_difference = $toleranceInSeconds;  // force update
    }
    
    return ($time_difference >= $toleranceInSeconds);
}

function startUpdate()
{
      ob_start();
}

function endUpdate($cacheFile, $printOutput = 0)
{
  $output = ob_get_contents();
  ob_end_clean();
  
  $handle = fopen($cacheFile, "w");
    
  if ($handle)
  {
    fwrite($handle, $output);
    fclose($handle);
  }
  
  if ($printOutput)
  {
    print $output;
  }
}

function printCache($cacheFile)
{
   @readfile($cacheFile);
}

function doConditionalGet($timestamp, $content) {
$log = $_SERVER["DOCUMENT_ROOT"] . "/cache/logs/";
$log = $log . "log" . $content . "CondGet.txt";

$ip = "ip=[" . $_SERVER['REMOTE_ADDR'] . "] - resolved=[" . gethostbyaddr($_SERVER['REMOTE_ADDR']) . "]";
$heure_access = date("h:i a");
$date_access = date("l, F j, Y");
if (isset($_SERVER["HTTP_USER_AGENT"]))
    $user_agent = " user_agent=[" . $_SERVER["HTTP_USER_AGENT"] . "] - ";
else
    $user_agent = " user_agent=[not set] - ";
    
if (isset($_SERVER["HTTP_REFERER"]))
    $referer = " referer=[" .  $_SERVER["HTTP_REFERER"] . "] - ";
else
    $referer = " referer=[not set] - ";


    // A PHP implementation of conditional get, see 
    //   http://fishbowl.pastiche.org/archives/001132.html
//    $last_modified = substr(date('r', $timestamp), 0, -5).'GMT';
    $last_modified = gmdate("D, d M Y H:i:s", $timestamp).' GMT';
    $etag = '"'.md5($content . $last_modified).'"';
$handleLog = fopen($log, "a");
// Log OFF
//$handleLog = 0;
if ($handleLog)
{
    fwrite($handleLog, $ip . $user_agent ." @: " . $date_access . " - " . $heure_access . " - lastmodified: ". $last_modified . " - Etag: " . $etag .  $referer . " \n"); 
}

    // Send the headers
    header("Last-Modified: $last_modified");
    header("ETag: $etag");
    // See if the client has provided the required headers
    $if_modified_since = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ?
        stripslashes($_SERVER['HTTP_IF_MODIFIED_SINCE']) :
        false;
    $if_none_match = isset($_SERVER['HTTP_IF_NONE_MATCH']) ?
        stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) : 
        false;
    if (!$if_modified_since && !$if_none_match) {
	if ($handleLog)
	{
	    fwrite($handleLog, "no headers!\n\n"); 
	    fclose($handleLog);
	}
        return;
    }

    // At least one of the headers is there - check them
    if ($if_none_match && $if_none_match != $etag) {
	if ($handleLog)
	{
	    fwrite($handleLog, "Etag doesn't match\n\n"); 
	    fclose($handleLog);
	}
        return; // etag is there but doesn't match
    }
    if ($if_modified_since && $if_modified_since != $last_modified) {
	if ($handleLog)
	{
	    fwrite($handleLog, "if-modifed-since doesn't match\n\n"); 
	    fclose($handleLog);
	}
        return; // if-modified-since is there but doesn't match
    }

if ($handleLog)
{
    fwrite($handleLog, "nothing has changed => exit\n\n"); 
    fclose($handleLog);
}
    // Nothing has changed since their last request - serve a 304 and exit
    header('HTTP/1.0 304 Not Modified');
    exit;
}

?>