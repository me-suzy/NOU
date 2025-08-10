<?php
require_once("top.php");
require_once("security.php");
require_once("templatizer.php");
require_once("menu.php");

secRequire("User");

$tp = new Templatizer();
$tp->Load_Template("standard.tpl");
$tp->Load_Template("security.tpl");

$vMenuno = dc_fetch_menu_no("/security-browse.php");
if ($vMenuno == -1) {
  $tp->element["Pagemenu"] = "&nbsp;";
}
else {
  $m = new Menu($vMenuno);
  $tp->element["Pagemenu"] = $m->Load();
}

$sql = "SELECT function_no, name FROM function WHERE domain='" . $dc_domain_name . "' ORDER BY name";
$result = mysql_query($sql);
if ($result) {
  while($row = mysql_fetch_object($result)) {
    $tp->element["Functionno"] = $row->function_no;
    $tp->element["Functionname"] = $row->name;
    $tp->Parse("securityrow", "Securityrows", 1);
  }
}
else {
  $tp->element["Pagebody"] = mysql_error();
}

$tp->Parse("securitygrid", "Pagebody");
$tp->Parse("page", "main");
echo $tp->element["main"];

?>







