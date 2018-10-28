<?php

include("./config.php");
include("./header.php");

$filename = $_FILES['upfile']['name'];
$filesize = $_FILES['upfile']['size'];

if($filesize==0) {
echo "You didn't pick a file to upload.";
include("./footer.php");
die();
}

$filecrc = md5_file($_FILES['upfile']['tmp_name']);

$bans=file("./bans.txt");
foreach($bans as $line)
{
  if ($line==$filecrc."\n"){
    echo "That file is not allowed to be uploaded.";
    include("./footer.php");
    die();
  }
  if ($line==$_SERVER['REMOTE_ADDR']."\n"){
    echo "You are not allowed to upload files.";
    include("./footer.php");
    die();
  }
}

$dirname = "./storagedata";
$dh = opendir( $dirname ) or die("couldn't open directory");
while ( $file = readdir( $dh ) ) {
  if ($file != '.' && $file != '..' && $file != '.htaccess') {
	$fh = fopen ("./storagedata/".$file,r);
	$filedata= explode('|', fgets($fh));
	$newfilecrc = str_replace(".txt","",$file);
	  if ($newfilecrc == $filecrc){
	    echo "Dosya Baþarýlý Bir Þekilde Yüklendi!.<br /><br />";
	    echo "Dosya Ýsmi: " . $filedata[0] . "<br /><br />";
	    echo "Ýndirme Linki:<BR><a href=\"" . $scripturl . "download.php?file=" . $filecrc . "\">". $scripturl . "download.php?file=" . $filecrc . "</a><br />";
	    include("./footer.php");
	    die();
	  }
	fclose ($fh);
  }
}
closedir( $dh );

if(isset($allowedtypes)){
$allowed = 0;
foreach($allowedtypes as $ext) {
  if(substr($filename, (0 - (strlen($ext)+1) )) == ".".$ext)
    $allowed = 1;
}
if($allowed==0) {
   echo "Öngörülen Dosya Türü Geçersiz...";
   include("./footer.php");
   die();
}
}

$filesize = $filesize / 1048576;

if($filesize > $maxfilesize) {
echo "Yüklediðiniz Dosyanýn Boyutu Çok Büyük!";
include("./footer.php");
die();
}

$userip = $_SERVER['REMOTE_ADDR'];
$time = time();

$passkey = rand(100000, 999999);

$filename = basename($_FILES['upfile']['name']);

// write file to storagedata directory
$filedata = fopen("./storagedata/".$filecrc.".txt","w");
fwrite($filedata, $filename ."|". $passkey ."|". $userip ."|". $time."|0\n");

// write file to storage directory
$movefile = "./storage/" . $filecrc;
move_uploaded_file($_FILES['upfile']['tmp_name'], $movefile);

$downloadlink = $scripturl . "download.php?file=" . $filecrc;
$deletelink = $scripturl . "download.php?file=" . $filecrc . "&del=" . $passkey;
$timestamp = date('F j, Y, g:i a');
$senderip = $_SERVER['REMOTE_ADDR'];
$filesize = round($filesize,2);

echo "<div class=content>";
echo "Dosyanýz, " . $filename . " Yüklendi!<br /><br />";
echo "Dosyayý Ýndirmek Ýçin:<br /><a href=\"$downloadlink\">$downloadlink</a><br /><br />";
echo "Dosyayý Silmek Ýçin:<br /><a href=\"$deletelink\">$deletelink</a>";


if ($enable_emailing==true) {
  echo "<P>Linkleri E-posta Adresime Yolla;<BR>";
  echo "<form action=email.php method=\"post\"><input name=\"email\" maxlength=\"50\" size=\"20\">";
  echo "<input type=\"hidden\" name=filename value=\"$filename\">";
  echo "<input type=\"hidden\" name=filesize value=\"$filesize\">";
  echo "<input type=\"hidden\" name=timestamp value=\"$timestamp\">";
  echo "<input type=\"hidden\" name=downloadlink value=\"$downloadlink\">";
  echo "<input type=\"hidden\" name=deletelink value=\"$deletelink\">";
  echo "<input type=\"hidden\" name=senderip value=\"$senderip\">";
  echo "<input type=\"hidden\" name=sitename value=\"$sitename\">";
  echo "<input type=\"hidden\" name=siteurl value=\"$scripturl\">";
  echo "<input type=\"submit\" value=\"Linkleri Gönder!\"><br /><br /></form>"; 
} else {
  echo "<P>Bu Linkleri Unutmayýn.";
}

echo "</div>";
include("./footer.php");
?>