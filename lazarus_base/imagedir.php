<?php
require_once("top.php");
require_once("security.php");

secRequire("Page");

?>
<html>
<head><title>Directory Listing for /images:</title></head>
<body bgcolor='#ffebcd'>
<script language="Javascript">
window.name="imagedir";

function viewimage(imagefile){
  open("imageload.php?image=" + imagefile+"&action=Use",null,"left=300,top=200,status=yes,scrollbars=no,toolbar=no,menubar=no,location=no");
}

function delimage(imagefile){
  open("imageload.php?image=" + imagefile+"&action=Delete",null,"left=300,top=200,status=yes,scrollbars=no,toolbar=no,menubar=no,location=no");
}

function getview(imagefile){
  this.opener.document.item_detailform.image_file.value=imagefile;
  this.opener.focus();
  this.close();
}

function getrid(imagefile){
  this.opener.upform.delfile.value=imagefile;
  this.opener.focus();
  this.close();
}
</script>

Directory of files in /images:<p>
<table cellspacing=1 cellpadding=3 width=100% border=1>

<?php

//$dirname = $DOCUMENT_ROOT;
$dirname = "./";
if (substr($dirname, strlen($dirname) - 1, 1) != "/") {
  $dirname .= "/";
}
$dirname .= "images/";

static $result_array=array(); 
echo "Directory: " . $dirname . "<br>\n";
$handle=opendir($dirname); 
while ($file = readdir($handle)) 
{ 
if($file!='.'&&$file!='..'&&!is_dir($file)) 
  $result_array[]=$dirname.$file; 
}
closedir($handle); 

sort ($result_array);
echo "<font size=-1>";
foreach ($result_array as $element){
  if ($action=="Delete"){
    echo '<tr valign="top"><td><a name="'.basename($element).'" href="#" onClick="getrid(\'' .basename($element). '\');">'.basename($element).'</a></td><td align=right><a href="#" onClick="delimage(\''.basename($element).'\');">View It!</a></td></tr>';
  }
  else{
    echo "<tr valign='top'><td><a name=\"".basename($element)."\" href=\"#\" onClick=\"getview('" .basename($element). "');\">".basename($element)."</a></td><td align=right><a href='#' onClick=\"viewimage('".basename($element)."');\">View It!</a></td></tr>";
  }
}
echo "</font>";
?>

</table>
</body>
</html>
