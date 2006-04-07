<?php

// code based on excellent article found at  http://www.leosingleton.com/projects/code/phpapp/
// 
// Application vars

define("APP_DATA_FILE",
    "/tmp/blogmemes_application.data");

function application_start ()
{
    global $_APP;

    // if data file exists, load application
    //   variables
    if (file_exists(APP_DATA_FILE))
    {
        // read data file
        $file = fopen(APP_DATA_FILE, "r");
        if ($file)
        {
            $data = fread($file,
                filesize(APP_DATA_FILE));
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

    // write application data to file
    $data = serialize($_APP);
    $file = fopen(APP_DATA_FILE, "w");
    if ($file)
    {
        fwrite($file, $data);
        fclose($file);
    }
}

?>
