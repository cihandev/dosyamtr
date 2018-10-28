<?php
/*******************************************************************
UPLOADSCRIPT v1.0 BETA
Copyright (c) 2006 Hyperweb. All rights reserved.
Homepage: http://www.uploadscript.net
*******************************************************************/


include("./config.php");
include("./header.php");
include("./ads.html");

$bans=file("./bans.txt");
foreach($bans as $line)
{
  if ($line==$_SERVER['REMOTE_ADDR']){
    echo "You are not allowed to download files.";
	include("./ads.html");
    include("./footer.php");
    die();
  }
}

$foundfile=0;
if (isset($_GET['file']) && file_exists("./storagedata/".($_GET['file']).".txt")) {
	$filecrc = $_GET['file'];
	$fh1=fopen("./storagedata/".$filecrc.".txt",r);
	$foundfile= explode('|', fgets($fh1));
	fclose($fh1);
  
} else {
  echo "Invalid download link.<br />";
  include("./ads.html");
  include("./footer.php");
  die();
}

if(isset($_GET['del'])) {
$deleted=0;
$filecrc = $_GET['file'];
$filecrctxt = $filecrc . ".txt";
$passcode = $_GET['del'];
if (file_exists("./storagedata/".$filecrctxt)) {
	$fh2=fopen ("./storagedata/".$filecrctxt,r);
	$filedata= explode('|', fgets($fh2));
	if($filedata[1] == $passcode){
		$deleted=1;
		unlink("./storagedata/".$filecrctxt);
	}

}

if($deleted==1){
unlink("./storage/".$_GET['file']);
echo "Dosya Silindi!<br />";
} else {
echo "Invalid delete link.<br />";
}
include("./ads.html");
include("./footer.php");
die();

}

$filesize = filesize("./storage/".$file);
$filesize = $filesize / 1048576;

if($filesize > $nolimitsize) {

$userip=$_SERVER['REMOTE_ADDR'];
$time=time();
$downloaders = fopen("./downloaders.txt","r+");
flock($downloaders,2);

while (!feof($downloaders)) { 
  $user[] = chop(fgets($downloaders));
}

fseek($downloaders,0,SEEK_SET);
ftruncate($downloaders,0);

foreach ($user as $line) {
  list($savedip,$savedtime) = explode("|",$line);
  if ($savedip == $userip) {
    if ($time < $savedtime + ($downloadtimelimit*60)) {
		$toosoon = true;
		$waittime = (($savedtime + ($downloadtimelimit*60))-$time) ;
		if ($waittime < 60) {
			$waittime .= " seconds.";
		} else {
			$waittime = round(($waittime/60),0) . " minutes.";
		}
    }
  }

  if ($time < $savedtime + ($downloadtimelimit*60)) {
    fputs($downloaders,"$savedip|$savedtime\n");
  }
}

}


$fsize = 0;
$fsizetxt = "";
  if ($filesize < 1)
  {
     $fsize = round($filesize*1024,2);
     $fsizetxt = "".$fsize." KB";

  }
  else
    {
     $fsize = round($filesize,2);
     $fsizetxt = "".$fsize." MB";
  }

$fh3 = fopen("./storagedata/".$file.".txt" ,r);
$filedata= explode('|', fgets($fh3));

echo "<h1>".$filedata[0]." - ".$fsizetxt."</h1>";

if ($toosoon) {
		echo "You're trying to download again too soon!  ";
		echo "Wait " . $waittime . "<BR>";
		echo "(Note: Files over " . ($nolimitsize * 1000) . " KB require a " . $downloadtimelimit . " minute wait time.)<BR><BR>";
		include("./ads.html");
		include("./footer.php");
		die();
}

echo "Dosyanýn Ýndirilmesi Ýçin ".$filedata[4]." Saniye.";

$randcounter = rand(100,999);
?>
<div id="dl" align="center">
<?php 
if($downloadtimer == 0) {
echo "<a href=\"" .$scripturl . "download2.php?a=" . $filecrc . "&b=" . md5($foundfile[1].$_SERVER['REMOTE_ADDR']) . "\">Dosyayý Ýndirmek Ýçin Týkla!</a>";
} else { ?>
If you're seeing this message, you need to enable JavaScript
<?php } ?>
</div>
<script language="Javascript">
x<?php echo $randcounter; ?>=<?php echo $downloadtimer; ?>;
function countdown() 
{
 if ((0 <= 100) || (0 > 0))
 {
  x<?php echo $randcounter; ?>--;
  if(x<?php echo $randcounter; ?> == 0)
  {
   document.getElementById("dl").innerHTML = '<a href="<?php echo $scripturl . "download2.php?a=" . $filecrc . "&b=" . md5($foundfile[1].$_SERVER['REMOTE_ADDR']) ?>">Dosyayý Ýndirmek Ýçin Týkla!</a>';
  }
  if(x<?php echo $randcounter; ?> > 0)
  {
   document.getElementById("dl").innerHTML = 'Dosyanýn Ýndirilmesine <b>'+x<?php echo $randcounter; ?>+'</b> Saniye Var...';
   setTimeout('countdown()',1000);
  }
 }
}
countdown();
</script>
<br /><p>
<a href="report.php?file=<?php echo $filecrc;?>">Bu dosya kurallarý ihlal ediyorsa bize bildir!</a></p>
<?php
include("./ads.html");
include("./footer.php");
?>