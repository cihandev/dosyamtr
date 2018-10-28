<?php
session_start();


include("./config.php");
if(isset($_GET['act'])){$act = $_GET['act'];}else{$act = "null";}
$contents = file("password.txt");
$pass = md5(implode($contents));

if(isset($_GET['act']) && ($act=="login")) {
  $entered = md5($_POST['passwordx']);
  $contents = file("password.txt");
  $adminpass = implode($contents);
  
  if($entered == $adminpass){ 
    $cookiepass = md5($adminpass);
    setcookie('logged', $cookiepass, 0, "/", $SERVER['SERVER_NAME'], 0);
    echo "<script>window.location=\"admin.php\";</script>";    
    } 
  else {
    include ("./header.php");
    echo "Invalid password<br>";
    echo "<p><center><a href=\"admin.php\">Try again</a>";
    include ("./footer.php");
    die();
  }
}

if(isset($_GET['act']) && $act=="nopass" && (filesize("password.txt") == 0)) {

  $password = md5($_POST['pass']);
  $fp = fopen("password.txt", 'w');
  fputs ($fp,$password);
  fclose ($fp);
  @chmod("password.txt", 0666);
  echo "<script>window.location=\"admin.php\"</script>";
  break;
}

if(isset($_COOKIE['logged']) && $_COOKIE['logged'] == $pass) {

if(isset($_GET['act']) && ($act=="logout"))  {
setcookie('logged', "", time()-60, "/", $SERVER['SERVER_NAME'], 0);
session_unset();
include ("./header.php");
echo "You have been logged out.";
echo "<p><center><a href=\"admin.php\">Login again</a>";
include ("./footer.php");
die();
}

include("./adminheader.php");
if(isset($_GET['act']) && ($act=="newpass")) {

if(isset($_POST['newpassword'])){
$fb = fopen( "password.txt", 'w');
$changedpass = md5($_POST['newpassword']);
fputs ($fb, $changedpass);
fclose ($fb);
@chmod("password.txt", 0666);
echo "<script>window.location=\"admin.php?act=logout\"</script>";
} 
?>
<h1>Change Password</h1><p>
<center>
After changing the password you will be logged out and will have to log in again with the new password.

<p>
<form action="admin.php?act=newpass" method="post">
Enter a new password: <input type="password" name="newpassword">
<input type="submit" value="Change Password">
<br /></form></center>

<?php
include ("./footer.php");
die();
}

if(isset($_GET['download'])){
$filecrc = $_GET['download'];
$filecrctxt = $filecrc . ".txt";
if (file_exists("./storagedata/" . $filecrctxt)) {
	$fh = fopen("./storagedata/" . $filecrctxt, r);
	$filedata= explode('|', fgets($fh));
}
echo "<script>window.location='".$scripturl."download2.php?a=".$filecrc."&b=".md5($filedata[1].$_SERVER['REMOTE_ADDR'])."';</script>";
fclose ($fh);
}

if(isset($_GET['delete'])) {
unlink("./storagedata/".$_GET['delete'].".txt");
unlink("./storage/".$_GET['delete']);
}

if(isset($_GET['banreport'])) {
$bannedfile = $_GET['banreport'];
if (file_exists("./storagedata/$bannedfile".".txt")) {
	unlink("./storagedata/".$bannedfile.".txt");
	unlink("./storage/".$bannedfile);
	$deleted=$bannedfile;
}

$fc=file("./reports.txt");
$f=fopen("./reports.txt","w+");
foreach($fc as $line)
{
  $thisline = explode('|', $line);
  if ($thisline[0] != $_GET['banreport'])
    fputs($f,$line);
}
fclose($f);
$f=fopen("./bans.txt","a+");
fputs($f,$deleted[3]."\n".$deleted[0]."\n");
unlink("./storage/".$_GET['banreport']);

}

if(isset($_GET['ignore'])) {

$fc=file("./reports.txt");
$f=fopen("./reports.txt","w+");
foreach($fc as $line)
{
  $thisline = explode('|', $line);
  if ($thisline[0] != $_GET['ignore'])
    fputs($f,$line);
}
fclose($f);
}

if(isset($_GET['act']) && $_GET['act']=="bans") {
if(isset($_GET['unban'])) {
$fc=file("./bans.txt");
$f=fopen("./bans.txt","w+");
foreach($fc as $line)
{
  if (md5($line) != $_GET['unban'])
    fputs($f,$line);
}
fclose($f);
}

if(isset($_POST['banthis'])) {
$f=fopen("./bans.txt","a+");
fputs($f,$_POST['banthis']."\n");
}


?>
<h1>Bans</h1><p> <center><form action="admin.php?act=bans" method="post">Enter an IP or file hash to ban:  
<input type="text" name="banthis"> 
<input type="submit" value="BAN!">
<br />
</form></center>
<?php

$fc=file("./bans.txt");
foreach($fc as $line)
{
  echo $line . " - <a href=\"admin.php?act=bans&unban=".md5($line)."\">Un-ban</a><br />";
}

include("./footer.php");
die();
}

if(isset($_GET['act']) && $_GET['act']=="delete") {
	//delete old files
	echo "<h1>Delete Old Files</h1>";
	echo "<span style=color:gray>Deleting files not downloaded for " . $deleteafter . " days:</span><BR><BR>";
	$deleteseconds = time() - ($deleteafter * 24 * 60 * 60);
	$dirname = "./storagedata";
	$dh = opendir( $dirname ) or die("couldn't open directory");
	while ( $file = readdir( $dh ) ) {
	if ($file != '.' && $file != '..' && $file != ".htaccess") {
	  $fh=fopen("./storagedata/" . $file ,r);
	  $filedata= explode('|', fgets($fh));
	  if ($filedata[3] < $deleteseconds) {
	    $deletedfiles="yes";
		echo "Deleting - " . $filedata[0] . ":<BR>"; 
	    unlink("./storagedata/".$file);
		echo "Deleted /storagedata/" . $file . "<BR>"; 
	    unlink("./storage/".str_replace(".txt","",$file));
		echo "Deleted /storage/" . str_replace(".txt","",$file) . "<BR><BR>"; 
	  }
	  fclose($fh);
	}
	}
	closedir( $dh );
	if (!$deletedfiles) echo "No old files to delete!<br /><br />";
	//done deleting old files
	include("./footer.php");
	die();
}


if(isset($_GET['act']) && $_GET['act']=="check") {
	//check files
	echo "<h1>Check Files</h1>";
	echo "<div class='content'>";
	echo "<span style=color:gray>Note: This section checks and makes sure that for each file in the 'storage' folder, there is a matching datafile in the 'storagedata' folder (and vice versa).  Normally, there should be no problems.  It is possible for one of the files to be missing -- this is rare and would only occur in unusual circumstances such as a server crash etc. In such a case you may just want to delete the mismatched files as they will be useless!</span>";
	echo "<P>Comparing storage to storagedata folder...<br><br>";
	$mismatch1=0;
	$mismatch2=0;

	echo "Reading storagedata directory...<BR><BR>";
	
	$dirname = "./storagedata";
	$dh = opendir( $dirname ) or die("couldn't open directory");
	while ( $file = readdir( $dh ) ) {
	if ($file != '.' && $file != '..') {
	  $filecrc = str_replace(".txt","",$file);
	  if ((!file_exists("./storage/". $filecrc)) && ($file != ".htaccess") ){
	    echo "Mismatch for " . $file . " in storagedata -- storage file (".$filecrc.") does not exist!<BR>";
		echo "Recommend manual deletion of /storagedata/" . $file . ".<BR>";
		$mismatch1=1;
	  }
	}
	}
	closedir( $dh );
	
	echo "<P>Reading storage directory...<BR><BR>";
	
	$dirname = "./storage";
	$dh2 = opendir( $dirname ) or die("couldn't open directory");
	while ( $filecrc = readdir( $dh2 ) ) {
	if ($filecrc != '.' && $filecrc != '..') {
	  $file = $filecrc . ".txt";
	  if ((!file_exists("./storagedata/". $file)) && ($filecrc != ".htaccess") ){
	    echo "Mismatch for " . $filecrc . " in storage -- storagedata (".$file.") file does not exist!<BR>";
		echo "Recommend manual deletion of /storage/" . $filecrc . ".<BR>";
		$mismatch2=1;
	  }
	}
	}
	closedir( $dh2 );
	
	echo "Finished checking files.<BR>";
	if (($mismatch1) || ($mismatch2)) {
	  echo "Errors were found!";
	} else {
	  echo "<P>Everything looks good! No mismatches were found.";
	}
	echo "</div>";
	//done checking files
	include("./footer.php");
	die();
}

if(isset($_GET['act']) && $_GET['act']=="reports") {
  echo "<h1>Reported Files</h1>";
  echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
  echo "<tr><td><b>Filename</b></td><td><b>Uploader IP</b></td><td><b>Delete & Ban</b></td><td><b>Ignore Report</b></td></tr>";
  $checkreports=file("./reports.txt");
  foreach($checkreports as $line) {
	  $thisreport = explode('|', $line);
	  $filecrc = $thisreport[0];
	  if (file_exists("./storagedata/$filecrc".".txt")) {
	  	$fr=fopen("./storagedata/".$filecrc.".txt",r);
	  	$foundfile= explode('|', fgets($fr));
	  	fclose($fr);
	  }
	  echo "<tr><td><a href=\"admin.php?download=".$filecrc."\">".$foundfile[0]."</td>";
	  echo "<td>".$foundfile[2]."</td>";
	  echo "<td><a href=\"admin.php?banreport=".$filecrc."\">delete & ban</a></td>";
	  echo "<td><a href=\"admin.php?ignore=".$filecrc."\">ignore</a></td></tr>";
	}

  echo "</table>";
  include ("footer.php");
  die;
}

?>

  <h1>Uploaded Files</h1>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td>No.</td><td><b>Filename</b></td><td><b>Size (MB)</b></td><td><b>Uploader IP</b></td><td><b>Downloads</b></td><td><b>B/W(MB)</b></td><td><b>Delete</b></td></tr>
<?php

$admindata="";
$counter = 1;
$dirname = "./storagedata";
$dh = opendir( $dirname ) or die("couldn't open directory");
while ( $file = readdir( $dh ) ) {
if ($file != '.' && $file != '..' && $file != '.htaccess') {
  $filecrc = str_replace(".txt","",$file);
  $filesize = filesize("./storage/". $filecrc);
  $filesize = ($filesize / 1048576);
  $fh = fopen ("./storagedata/".$file, r);
  $filedata= explode('|', fgets($fh));
  $admindata .= "<tr><td>$counter. </td><td><a href=\"admin.php?download=".$filecrc."\">".$filedata[0]."</a></td><td>".round($filesize,2)."</td><td>".$filedata[2]."</td><td style=padding-left:5px>".$filedata[4]." </td><td style=padding-left:5px>".round($filesize*$filedata[4],2)."</td><td style=padding-left:5px><a href=\"admin.php?delete=".$filecrc."\">[x]</a></td></tr>\n";
  $counter += 1;
  fclose ($fh);
}
}
closedir( $dh );

$adminfiles=fopen("./adminfiles.txt","w");
fwrite ($adminfiles,$admindata);
fclose ($adminfiles);

// output files list and paginate:
require_once('pager.php');
$page=$_GET['page'];
echo paginateRecords('./adminfiles.txt',$page,100);
// finished output files list

echo "</table>";

} else {

  if (filesize("password.txt") != 0){
  	include("./header.php");?>
    <center>
    <h1>Admin Login</h1><br />
    <form action="admin.php?act=login" method="post">Password:  
    <input type="password" name="passwordx"> 
    <input type="submit" value="Login">
    <br /><br />
    </form></center>
    <?php 
  } else { 
	include("./header.php");
  	?>
    <h1>Set Admin Password</h1><p>
	<center>
	<form action="admin.php?act=nopass" method="post">
	Enter your admin password: <input type="password" name="pass">
	<input type="submit" value="Set Password">
	<br /></form></center>
	<?php 
  }
}
include("./footer.php");
?>