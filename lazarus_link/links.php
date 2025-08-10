<?php
require_once("top.php");
require_once("templatizer.php");
require_once("menu.php");

$tp = new Templatizer();
$tp->Load_Template("standard.tpl");
$tp->Load_Template("link.tpl");

$tp->element["Title"] = "Useful Web Sites";
$tp->element["Pagetitle"] = "Useful Web Sites";

$vMenuno = dc_fetch_menu_no("/links.php");
if ($vMenuno != -1) {
  $m = new Menu($vMenuno);
  $tp->element["Pagemenu"] = $m->Load();
}
else {
  $tp->element["Pagemenu"] = "&nbsp;";
}

$sql = "SELECT name, url, description FROM link";
$sql .= " WHERE approved='Y' ORDER BY name";
$result = mysql_query($sql);
if ($result) {
  while ($row = mysql_fetch_object($result)) {
    $tp->element["Linkname"] = $row->name;
    $tp->element["Linkurl"] = $row->url;
    $tp->element["Linkdescription"] = $row->description;
    $tp->Parse("linkrow", "Linkrows", 1);
  }
}
else {
  $tp->element["Message"] = mysql_error() . "\n<pre>\n" . $sql . "\n</pre>\n";
}

$tp->Parse("linkgrid", "Pagebody");
$tp->Parse("page", "main");
echo $tp->element["main"];

?>
