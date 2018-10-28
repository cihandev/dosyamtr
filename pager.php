<?php

function paginateRecords($dataFile,$page,$numRecs=5){

$output='';

// validate data file

(file_exists($dataFile))?$data=(file($dataFile)):die('Data file not valid.');

// validate number of records per page

(is_int($numRecs)&&$numRecs>0)?$numRecs=$numRecs:die('Invalid number of records '.$numRecs);

// calculate total of records

$numPages=ceil(count($data)/$numRecs);

// validate page pointer

if(!preg_match("/^\d{1,2}$/",$page)||$page<1||$page>$numPages){

$page=1;

}

// retrieve records from flat file

$data=array_slice($data,($page-1)*$numRecs,$numRecs);

// append records to output

foreach($data as $row){

$output.=$row;

}

$output.='<tr><td colspan=5 height=10></td></tr><tr><td colspan=5>Pages: ';

// create previous link
if($page>1){
$output.='<a href="'.$_SERVER['PHP_SELF'].'?page='.($page-1).'">&lt;&lt;Previous</a>&nbsp;';
}

// create intermediate links
for($i=1;$i<=$numPages;$i++){
($i!=$page)?$output.='<a href="'.$_SERVER['PHP_SELF'].'?page='.$i.'">'.$i.'</a>&nbsp;':$output.=$i.'&nbsp;';

// fix for page width, the following limits to page numbers to 30 per line:
if (is_int($i/30)) $output.="<br>";
}

// create next link
if($page<$numPages){
$output.='&nbsp;<a href="'.$_SERVER['PHP_SELF'].'?page='.($page+1).'">Next&gt;&gt;</a> ';
}

// return final output
$output.='</td></tr>';
return $output;

}
<?