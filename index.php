
<!------------------------------------------------
UPLOADSCRIPT v1.03 (Free)
Copyright (c) 2006 Hyperweb. All rights reserved.
Homepage: http://www.uploadscript.net
------------------------------------------------->

<?php


include("./config.php");
include("./header.php");

if(isset($_GET['page']))
  $p = $_GET['page'];
else
  $p = "0";

switch($p) {
case "tos": include("./pages/tos.php"); break;
case "faq": include("./pages/faq.php"); break;
case "top": include("./pages/topten.php"); break;
default: include("./pages/upload.php"); break;
}

include("./footer.php");
if ($using_copyright_info != true) {
	header("Location: http://upload.baykusportal.com");
}
?>