<?php
require_once("top.php");
require_once("security.php");
secRequire("Page");
?>
<html>
<head><title>Deleting...</title></head>
<script language="Javascript">
function closing(yesorno){
  if (yesorno=="yes"){
    window.confirm("Image Deleted!!");
  }
  else{
    window.confirm("Sorry, Image was not Deleted.  Please try again.");
  }  
  this.close();
}
</script>
<body>
<b>Deleting...</b>
<?php

//$dirname = $DOCUMENT_ROOT;
$dirname = './';
if (substr($dirname, strlen($dirname) - 1, 1) != "/") {
  $dirname .= "/";
}
$dirname .= "images/";

$file=$dirname . $file;
if (unlink($file)){
  echo '<script language="Javascript">closing("yes");</script>';
}
else{
  echo '<script language="Javascript">closing("no");</script>';
}  
?>
</body>
</html>
