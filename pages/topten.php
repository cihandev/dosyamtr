<?php

if($enable_toplist==false){
echo "This page is disabled.";
include("./footer.php");
die();
}
include ("ads.html")
?>

<h1>Top 10 Ýndirme</h1>
<table border=0 align=center><tr><td>
<ol>

<?

if(isset($_GET['act'])){$act = $_GET['act'];}else{$act = "null";}
 
include("./config.php");

$order = array();
$dirname = "./storagedata";
$dh = opendir( $dirname ) or die("couldn't open directory");
while ( $file = readdir( $dh ) ) {
if ($file != '.' && $file != '..' && $file != '.htaccess') {
	$fh = fopen ("./storagedata/".$file, r);
	$list= explode('|', fgets($fh));
	$filecrc = str_replace(".txt","",$file);
    $order[] = $list[4].','.$filecrc.','.$list[0];
	fclose ($fh);
}
}
 
sort($order, SORT_NUMERIC);
$order = array_reverse($order);
 
$i = 1;
 
foreach($order as $line)
{
  $line = explode(',', $line);
  
  echo "<li><a href=\"download.php?file=".$line[1]."\" style=font-size:11px>".$line[2]."</a><span style=font-size:9px>&nbsp;&nbsp;( ".$line[0];
 
 $filesize = filesize("./storage/".$line[1]);
  $filesize = ($filesize / 1048576);
 
  if ($filesize < 1)
  {
     $filesize = round($filesize*1024,0);
     echo "downloads, " . $filesize . "KB";
 
  }
  else
    {
     $filesize = round($filesize,0);
     echo "downloads, " . $filesize . "MB";
     
  }
echo ")</span><br>";
if($i == 10) break;
$i++;
 
}
?>
</ol>
</td></tr></table>