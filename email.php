<? 
include("./config.php");
include("./header.php");

extract ($_POST);
$email = $_POST['email']; 
$filename = $_POST['filename']; 
$filesize = $_POST['filesize']; 
$timestamp = $_POST['timestamp']; 
$downloadlink = $_POST['downloadlink']; 
$deletelink = $_POST['deletelink']; 
$senderip = $_POST['senderip']; 
$sitename = $_POST['sitename']; 
$siteurl = $_POST['siteurl']; 
$subject = "File Link: " . $filename;

$body="

Your download link has been delivered from $sitename:

File Name: $filename
File Size: $filesize MB
Time Stamp: $timestamp

Download Link:
$downloadlink

Delete Link:
$deletelink

Sender's IP Address: $senderip

Upload more files at:
$siteurl

";

// mail("EMAIL TO","SUBJECT","MESSAGE","From: name <email>");

mail($email, $subject, $body, "From: $email"); 

echo "<div class=content>";
echo "File Name: " . $filename . "<br />";
echo "File Size: " . $filesize . " MB <br />";
echo "Time Stamp: " . $timestamp . "<br /><br />";
echo "Download Link: <br />";
echo $downloadlink . "<br /><br />";
echo "Delete Link: <br />";
echo $deletelink . "<br /><br />";
echo "Sender's IP Address: " . $senderip . "<br /><br />";
echo "Your file information has been sent to " . $email . "!<br /><br />";
echo "</div>";
include("./footer.php");

?>