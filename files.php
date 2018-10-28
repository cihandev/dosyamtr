
<!------------------------------------------------
UPLOADSCRIPT v1.03 (Free)
Copyright (c) 2006 Hyperweb. All rights reserved.
Homepage: http://www.uploadscript.net
------------------------------------------------->

<?php

include("./config.php");
include("./header.php");

if($enable_filelist==false){
  echo "This page is disabled.";
  include("./footer.php");
  die();
}

if(isset($_GET['act'])){$act = $_GET['act'];}else{$act = "null";}

?>
<div class=content>
<h1>Uploaded Files</h1>
<p><table width="525" cellpadding="0" cellspacing="0" border="0">
<tr><td>No.</td><td width="50%"><b>Filename</b></td><td><b>Size</b></td><td><b>Last Download</b></td><td><b>DLs</b></td></tr>
<tr><td colspan=5 height=10></td></tr>
<?php

if(isset($_GET['act'])){$act = $_GET['act'];}else{$act = "null";}
 
$data = "";
$i=1;
$dirname = "./storagedata";
$dh = opendir( $dirname ) or die("couldn't open directory");
while ( $file = readdir( $dh ) ) {
  if ($file != '.' && $file != '..' && $file != '.htaccess') {
	$fh = fopen ("./storagedata/".$file, r);
	$list= explode('|', fgets($fh));
	$filecrc = str_replace(".txt","",$file);

	$filesize = filesize("./storage/".$filecrc);
    $filesize = ($filesize / 1048576);
    if ($filesize < 1){
       $filesize = round($filesize*1024,0)." KB";
    } else {
       $filesize = round($filesize,1)." MB";
    }
	$date=date('M j/y g:ia', $list[3]);
	$fileurl = "<a href=\"download.php?file=$filecrc\">$list[0]</a>";

	$data .= '<tr><td>'.$i.'</td><td>'.$fileurl.'</td><td>'.$filesize."</td><td>".$date.'</td><td>'.$list[4].'</td></tr>';
	$i++;
	fclose ($fh);
  }
}
 
$files=fopen("./files.txt","w");
fwrite ($files,$data);
fclose ($files);

// output files list and paginate:
require_once('pager.php');
$page=$_GET['page'];
echo paginateRecords('./files.txt',$page,$perpage);
// finished output files list

?>

<tr><td colspan=5 height=10></td></tr>

<tr>
<td colspan=5>
<?
function total_size($dir) {
$handle = opendir($dir);
while($file = readdir($handle)) {
$total = $total + filesize ($dir.$file);
  if((is_dir($dir.$file.'/')) &&($file != '..')&&($file != '.')&&($file != '.htaccess'))
  {
  $total = $total + total_size($dir.$file.'/');
  }
}
return $total;
}

$total = total_size('storage/');
$total = $total / 1048576;
$total = round($total,0);
echo "<span style=color:gray>Total Size: " . $total." MB</span>";

?>
</td>
</tr>

</table>

</div>

<? include("./footer.php"); ?>