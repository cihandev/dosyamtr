<head>
<title><? echo $sitename ?> - Host Your Files For Free</title>
<link rel="stylesheet" href="stylesheet.css" type="text/css">
</head>
<body bgcolor=white>
<center>

<table width=750 cellpadding=0 cellspacing=0 border=0>
<tr>
<td align=center class=top>
<h1 class="title"><? echo $sitename ?></h1>
<a class=menu href="index.php">Upload</a>
<a class=menu href="index.php?page=faq">FAQ</a>
<a class=menu href="index.php?page=tos">TOS</a>
<? if ($enable_filelist == true) echo "<a class=menu href=\"files.php\">Files</a> "; ?>
<? if ($enable_toplist == true) echo "<a class=menu href=\"index.php?page=top\">Top10</a> "; ?>
</td>
</tr>
<tr>
<td align=center class=middle>
<BR>
<BR>