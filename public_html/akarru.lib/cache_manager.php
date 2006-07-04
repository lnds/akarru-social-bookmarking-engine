<?php
$gadgetsCache = $_SERVER["DOCUMENT_ROOT"] . "/cache/gadgets/";
$feedFlareCache = $_SERVER["DOCUMENT_ROOT"] . "/cache/feedflares/";
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

?>