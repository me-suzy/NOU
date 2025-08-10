<?php
require_once("top.php");
require_once("security.php");
require_once("templatizer.php");
require_once("menu.php");

secRequire("Link");

$tp = new Templatizer();
$tp->Load_Template("link.tpl");
$tp->Load_Template("standard.tpl");


$vMenuno = dc_fetch_menu_no("/link-browse.php");
if ($vMenuno != -1) {
  $m = new Menu($vMenuno);
  $tp->element["Pagemenu"] = $m->Load();
}
else {
  $tp->element["Pagemenu"] = "&nbsp;";
}


$sql = "SELECT link_no, name, url, submitted_by, approved FROM link";
$result = mysql_query($sql);
if ($result) {
  while ($row = mysql_fetch_object($result)) {
    $tp->element["Linklink_no"] = $row->link_no;
    $tp->element["Linkname"] = $row->name;
    $tp->element["Linkurl"] = $row->url;
    $tp->element["Linksubmitted_by"] = $row->submitted_by;
    $tp->element["Linkapproved"] = $row->approved;
    $tp->Parse("linkbrowserow", "Linkbrowserows", 1);
  }
}
else {
  $tp->element["Message"] = mysql_error() . "\n<pre>\n" . $sql . "\n</pre>\n";
}

$tp->Parse("linkbrowsegrid", "Pagebody");
$tp->Parse("page", "main");
echo $tp->element["main"];

?>
