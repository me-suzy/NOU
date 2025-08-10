<?php
require_once("top.php");
require_once("security.php");
require_once("templatizer.php");
require_once("menu.php");

secRequire("Page");

$tp = new Templatizer();
$tp->Load_Template("standard.tpl");
$tp->Load_Template("brochure.tpl");

$tp->element["Pagetitle"] = "Browse Brochures";
$tp->element["Title"] = $tp->element["Pagetitle"];

$vMenuno = dc_fetch_menu_no("/brochure-browse.php");
if ($vMenuno != -1) {
  $m = new Menu($vMenuno);
  $tp->element["Pagemenu"] = $m->Load();
}
else {
  $tp->element["Pagemenu"] = "&nbsp;";
}

$sql = "SELECT url, brochure_no, title FROM brochure ";

$result = mysql_query($sql);
if ($result) {
  while ($row = mysql_fetch_object($result)) {
    $tp->element["Brochureno"] = $row->brochure_no;
    $tp->element["Brochureurl"] = $row->url;
    $tp->element["Brochuretitle"] = $row->title;
    $tp->Parse("brochurerow", "Brochurerows", 1);
  }
}
else {
  $tp->element["Message"] = mysql_error();
}

$tp->Parse("brochuregrid", "Pagebody");
$tp->Parse("page", "main");

echo $tp->element["main"];

?>
