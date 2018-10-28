<?php
/*******************************************************************
UPLOADSCRIPT v1.0 (Free)
Copyright (c) 2006 Hyperweb. All rights reserved.
Homepage: http://www.uploadscript.net
*******************************************************************/

extract ($_REQUEST);

$sitename = "Site Başlığı";
//// the name of your site as you want it to appear

$scripturl = "http://www.site-ismi.com/";
//// the URL to this script with a trailing slash

$maxfilesize = 10;
//// the maximum file size allowed to be uploaded (in megabytes)

$downloadtimelimit = 60;
//// time users must wait before downloading another file (in minutes)

$nolimitsize = 1;
//// if a file is under this many megabytes, there is no time limit

$deleteafter = 30;
//// delete files if not downloaded after this many days

$downloadtimer = 30;
//// length of the timer on the download page (in seconds)

$enable_filelist = true;
//// allows users to see a list of uploaded files -- set to false to disable

$perpage = 50;
//// if $enable_filelist is true (above), how many files to display per page (recommended default is 50);

$enable_toplist = true;
//// allows users to see a "top 10" list of uploaded files -- set to false to disable

$enable_emailing = true;
//// allows users to send the file link to a specified email address -- set to false to disable

//$allowedtypes = array("txt","gif","jpg","jpeg","bmp","zip","rar","ace","wmv","mpg","mpeg","mov","avi","mp3");
//// remove the //'s from the above line to enable file extention blocking
//// only file extentions that are noted in the above array will be allowed
?>