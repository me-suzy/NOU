<html>
<head>
  <title>File Browser</title>
  <link rel="stylesheet" type="text/css" href="/toybox.css">
  <script language="JavaScript">
function SetFileName(FileName) {
  window.opener.forms[0].<?php echo $element; ?> = FileName;
  //viewer.src = view;
  //window.close();
  window.alert(FileName);
}
  </script>
</head>
<body bgcolor="white">

<table>
<tr valign="top">
<td>
<?php

if (!isset($startdir))
  $startdir = ".";

$handle=opendir($startdir); 
echo "<table>\n";
while (false!==($file = readdir($handle))) { 
  if ($file != "." && $file != "..") { 
    echo "<tr valign=\"top\">\n";
    echo "  <td><a href=\"#_top\" onClick=\"SetFileName('$file');\">$file</a></td>\n"; 
    echo "</tr>\n";
  } 
}
echo "</table>\n";
closedir($handle); 

?>
</td>
<td>
  <img src="" name="viewer">
</td>
</body>
</html>
