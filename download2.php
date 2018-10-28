<?php

include("./config.php");

$bans=file("./bans.txt");
foreach($bans as $line)
{
  if ($line==$_SERVER['REMOTE_ADDR']){
    echo "You are not allowed to download files.";
    include("./footer.php");
    die();
  }
}

if(!isset($_GET['a']) || !isset($_GET['b']))
{
  echo "<script>window.location = '".$scripturl."';</script>";
}

$validdownload = 0;

$filecrc = $_GET['a'];
$filecrctxt = $filecrc.".txt";
if (file_exists("./storagedata/".$filecrctxt)) {
	$fh = fopen ("./storagedata/".$filecrctxt,r);
	$filedata= explode('|', fgets($fh));
	if (md5($filedata[1].$_SERVER['REMOTE_ADDR'])==$_GET['b'])
		$validdownload=$filedata;
	fclose($fh);
}

if($validdownload==0) {
    echo "Invalid download link.";
    include("./footer.php");
    die();
}

$userip = $_SERVER['REMOTE_ADDR'];
$time = time();

$filesize = filesize("./storage/".$filecrc);
$filesize = $filesize / 1048576;

if($filesize > $nolimitsize) {
$downloaders = fopen("./downloaders.txt","a+");
fputs($downloaders,"$userip|$time\n");
fclose($downloaders);
}

$validdownload[3] = time();

$newfile = "./storagedata/$filecrc" . ".txt";
$f=fopen($newfile, "w");
fwrite ($f,$validdownload[0]."|". $validdownload[1]."|". $validdownload[2]."|". $validdownload[3]."|".($validdownload[4]+1)."\n");
fclose($f);

header('Content-type: application/octetstream');
header('Content-Length: ' . filesize("./storage/".$filecrc));
header('Content-Disposition: attachment; filename="'.$validdownload[0].'"');
readfile("./storage/".$filecrc);

?>