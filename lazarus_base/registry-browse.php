<?php

require_once("top.php");
require_once("templatizer.php");
require_once("security.php");
require_once("menu.php");

secRequire("User");

$tp = new Templatizer();
$tp->Load_Template("registry.tpl");
$tp->Load_Template("standard.tpl");

$vMenuno = dc_fetch_menu_no("/registry-browse.php");
if ($vMenuno != -1) {
  $m = new Menu($vMenuno);
  $tp->element["Pagemenu"] = $m->Load();
}
else {
  $tp->element["Pagemenu"] = "&nbsp;";
}

$tp->element["Pagetitle"] = "Registry Browse";
$tp->element["Title"] = "Registry Browse";

$filtersql = "SELECT name FROM registry_category";
$filterresult = mysql_query($filtersql);
$tp->element["Categoryoptions"] = dc_option_list($filterresult, $category, "name");

if ($category == "Show All")
  unset($category);

if (isset($category))
  $sql = sprintf("SELECT * FROM registry WHERE category='%s'", $category);
else 
  $sql = "SELECT * FROM registry";

$result = mysql_query($sql);
if ($result) {
  while ($row = mysql_fetch_object($result)) {
    $tp->element["registry_no"] = $row->registry_no;
    $tp->element["category"] = $row->category;
    $tp->element["entity"] = $row->entity;
    $tp->element["value"] = htmlspecialchars(substr($row->value, 0, 50));
    $tp->Parse("registrybrowserow", "Registrybrowserows", 1);
  }
}
else {
  $tp->element["Message"] = mysql_error();
}

$tp->parse("registrybrowse", "Pagebody");
$tp->parse("page", "main");
echo $tp->element["main"];

?>
