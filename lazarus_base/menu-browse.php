<?php
require_once("top.php");
require_once("security.php");
require_once("menu.php");
require_once("templatizer.php");

secRequire("Menu");

$tp = new Templatizer();
$tp->Load_Template("standard.tpl");
$tp->Load_Template("menu.tpl");

$tp->element["Pagetitle"] = "Menus";
$tp->element["Title"] = "Menus";

$sql = "SELECT menu_no, name FROM menu";
$result = mysql_query($sql);
if ($result) {
  while ($row = mysql_fetch_object($result)) {
    $tp->element["Menuno"] = $row->menu_no;
    $tp->element["Menuname"] = $row->name;
    $tp->Parse("menugridrow", "Menugridrows", 1);
  }
}
else {
  $tp->element["Message"] = mysql_error();
}

$tp->Parse("menugrid", "Pagebody");

$vMenuno = dc_fetch_menu_no("/menu-browse.php");
if ($vMenuno != -1) {
  $m = new Menu($vMenuno);
  $tp->element["Pagemenu"] = $m->Load();
}
else {
  $tp->element["Pagemenu"] = "&nbsp;";
}

$tp->Parse("page", "main");

echo $tp->element["main"];

?>



