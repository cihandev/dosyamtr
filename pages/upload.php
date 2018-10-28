<SCRIPT language="JavaScript">
function OnSubmitForm()
{
  a=document.getElementById('form').style;
  a.display='none';
  b=document.getElementById('part2').style;
  b.display='inline';

  if(document.myform.operation[0].checked == true)
  {
    document.myform.action ="upload.php";
  }
  else
  if(document.myform.operation[1].checked == true)
  {
    document.myform.action ="transload.php?xfer=true";
  }
  return true;

}

function toggleuploadmode(file) {
	if (file) {
		document.getElementById('upfile').style.display='block';
		document.getElementById('upurl').style.display='none';
		document.getElementById('upform').action='index.php';
	} else {
		document.getElementById('upfile').style.display='none';
		document.getElementById('upurl').style.display='block';
		document.getElementById('upform').action='transload.php';
	}
}
function focusfield(fl) {
	if (fl.value=="Yüklemek Ýstediðiniz Dosya Linkini Girin / Yapýþtýrýn.") {
		fl.value='';
		fl.style.color='black';
	}
}
</SCRIPT>

<SCRIPT language="JavaScript">
var checkobj
function agreesubmit(el){
checkobj=el
if (document.all||document.getElementById){
for (i=0;i<checkobj.form.length;i++){  //hunt down submit button
var tempobj=checkobj.form.elements[i]
if(tempobj.type.toLowerCase()=="submit")
tempobj.disabled=!checkobj.checked
}
}
}

function defaultagree(el){
if (!document.all&&!document.getElementById){
if (window.checkobj&&checkobj.checked)
return true
else{
alert("Please read and accept terms to submit form")
return false
}
}
}
</script>

<? include ("ads.html") ?>
<P>
<div style=text-align:center;vertical-align:middle;height:150px>
<div style=vertical-align:middle>
    <h1>Dosya Yükle!</h1>
	
	<form enctype="multipart/form-data" name="myform" id="form" method="post" onSubmit="return OnSubmitForm();" style="display: inline;">
	Maksimum Dosya Boyutu: <?php echo $maxfilesize; ?> MB
	<div id="upfile">
		<input type="file" name="upfile" size="50" onchange="showoptions(this)" id="fileupload"><br />
	</div>
	<div id="upurl" style="display: none">
		<input type="text" id="from" name="from" value="Yüklemek Ýstediðiniz Dosya Linkini Girin / Yapýþtýrýn." style=color:#888888 onfocus="focusfield(this)" size="50"> <img src=./images/url.gif><br />
	</div>
	
	<table border=0 cellpadding=0 cellspacing=0 align=center width=405>
	<tr>
	<td>
	<input type="radio" name="operation" onclick="toggleuploadmode(true);" value="1" checked>File
	<input type="radio" name="operation" onclick="toggleuploadmode(false);" value="2">Url 
	</td>
	<td align=right>
	<input name="agreecheck" type="checkbox" onClick="agreesubmit(this)"> Yükleme Kosullarýný Kabul Ediyorum. - <a href="?page=tos" target=_blank>Kosullar</a>.
	</td>
	</tr>
	</table>
	<br />
	<input type="submit" value="Dosyayý Yükle!" id="upload" style=width:405px disabled />
	</form>
        <div id="part2" style="display: none;">
	<script language="javascript" src="progressbar.js"></script>
	Yükleme devam ediyor. Lütfen Bekleyin ...
        
	<BR><BR>
	<!-- Adjust your progress bar colors in the function "creatBar" below as follows: -->
	<!-- total_width, total_height, background_color, border_width, border_color, block_color, scroll_speed, block_count, scroll_count, action_to_perform_after_scrolled_n_times -->
	<script type="text/javascript">
	var bar1= createBar(300,15,'EAF4FE',1,'899EBF','21B3F7',85,7,3,"");
	</script>
</div>
</div>
</div>