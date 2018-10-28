<?php

error_reporting(E_ERROR | E_PARSE);

include("./config.php");
include("./header.php");

function urlerror() {
	echo "Verdiğiniz Link/URL Yanlış veya Verdiğiniz Site Kullanım Dışı.";
    include ("footer.php");
	die;
}

$url = $_POST[from];
$filename = substr(strrchr($url, "/"),1);
$invalidchars = array ("\"",";",":","<",">","=");
if (!stristr($url,'http://')) $invalidurl=true;
if (stristr($filename,$invalidchars)) $invalidfilename=true;
if (($invalidurl) || ($invalidfilename)) {
	urlerror();
}


if ($_GET[xfer]) {
	if (($url == "") || ($url == "Paste file url here")) {
		print "You forgot to enter a url.";
	    include("./footer.php");
	    die();
	} else {
		$movefile = "./urltemp/" . $filename;
		copy("$_POST[from]", $movefile)
						or die (urlerror());
		$filecrc = md5_file("./urltemp/" . $filename);
	}
}

$filesize = filesize("./urltemp/$filename");

$bans=file("./bans.txt");
foreach($bans as $line)
{
  if ($line==$filecrc."\n"){
    echo "Bu Dosyanın Yüklenmesine İzin Verilemez.";
	unlink ("./urltemp/" . $filename);
    include("./footer.php");
    die();
  }
  if ($line==$_SERVER['REMOTE_ADDR']."\n"){
    echo "You are not allowed to upload files.";
	unlink ("./urltemp/" . $filename);
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
	    echo "Bu Dosya Zaten Yüklendi.<br /><br />";
	    echo "Dosya Adı: " . $filedata[0] . "<br /><br />";
	    echo "İndirme Linki:<BR><a href=\"" . $scripturl . "download.php?file=" . $filecrc . "\">". $scripturl . "download.php?file=" . $filecrc . "</a><br />";
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
   echo "Geçeriz Dosya Türü.";
   unlink ("./urltemp/" . $filename);
   include("./footer.php");
   die();
}
}

if($filesize==0) {
echo "Yüklemek İçin Herhangi Bir Dosya Seçmediniz.";
include("./footer.php");
die();
}

$filesize = $filesize / 1048576;

if($filesize > $maxfilesize) {
echo "Yüklediğiniz dosya çok büyük. Max :800 MB";
unlink ("./urltemp/" . $filename);
include("./footer.php");
die();
}

$userip = $_SERVER['REMOTE_ADDR'];
$time = time();

$passkey = rand(100000, 999999);

// write file to storage directory
$movefile = "./storage/" . $filecrc;
rename("urltemp/$filename", $movefile);

// write file to storagedata directory
$filedata = fopen("./storagedata/".$filecrc.".txt","w");
fwrite($filedata, $filename ."|". $passkey ."|". $userip ."|". $time."|0\n");

$downloadlink = $scripturl . "download.php?file=" . $filecrc;
$deletelink = $scripturl . "download.php?file=" . $filecrc . "&del=" . $passkey;
$timestamp = date('F j, Y, g:i a');
$senderip = $_SERVER['REMOTE_ADDR'];
$filesize = round($filesize,2);

echo "<div class=content>";
echo "Dosyanız, " . $filename . " Yüklendi!<br /><br />";
echo "Dosyanızın İndirme Linki:<br /><a href=\"$downloadlink\">$downloadlink</a><br /><br />";
echo "Dosyanızın Silme Linki:<br /><a href=\"$deletelink\">$deletelink</a>";


if ($enable_emailing==true) {
  echo "<P>Bu Bağlantıları EPosta Adresime Gönder;<BR>";
  echo "<form action=email.php method=\"post\"><input name=\"email\" maxlength=\"50\" size=\"20\">";
  echo "<input type=\"hidden\" name=filename value=\"$filename\">";
  echo "<input type=\"hidden\" name=filesize value=\"$filesize\">";
  echo "<input type=\"hidden\" name=timestamp value=\"$timestamp\">";
  echo "<input type=\"hidden\" name=downloadlink value=\"$downloadlink\">";
  echo "<input type=\"hidden\" name=deletelink value=\"$deletelink\">";
  echo "<input type=\"hidden\" name=senderip value=\"$senderip\">";
  echo "<input type=\"hidden\" name=sitename value=\"$sitename\">";
  echo "<input type=\"hidden\" name=siteurl value=\"$scripturl\">";
  echo "<input type=\"submit\" value=\"Send the link!\"><br /><br /></form>"; 
} else {
  echo "<P>Bu Linkleri Unutmayın.";
}

echo "</div>";
include("./footer.php");
?>