<?php
require_once("top.php");
require_once("security.php");
require_once("templatizer.php");
require_once("menu.php");

secRequire("admin");

$tp = new Templatizer();
$tp->Load_Template("standard.tpl");
$tp->Load_Template("security.tpl");

$tp->element["Pagetitle"] = "Security Detail";
$tp->element["Title"] = "Security Detail";
$tp->element["Functionno"] = $function_no;

$vMenuno = dc_fetch_menu_no("/security-browse.php");
if ($vMenuno != -1) {
  $m = new Menu($vMenuno);
  $tp->element["Pagemenu"] = $m->Load();
}
else {
  $tp->element["Pagemenu"] = "&nbsp;";
}

if ($REQUEST_METHOD == "GET") {

  $sql = "SELECT name FROM function WHERE";
  $sql .= sprintf(" domain='%s' AND function_no=%d",
		  $dc_domain_name, $function_no);
  $result = mysql_query($sql);
  if ($result) {
    $row = mysql_fetch_object($result);
    $tp->element["Functionname"] = $row->name;
  }
  else {
    $tp->element["Message"] = mysql_error();
  }

}
else { // POST Method

  $sql = "";
  if ($go == "Update" && $function_no == "new") {
    $sql = "INSERT INTO function (domain, name) ";
    $sql .= sprintf("values ('%s', '%s')", $dc_domain_name, $name);
  }

  if ($go == "Update" && $function_no != "new") {
    $sql = "UPDATE function\n";
    $sql .= sprintf("SET name='%s' WHERE function_no=%d",
		    $name, $function_no);
  }

  if ($go == "Delete" && $function_no != "new") {
    $sql = "DELETE FROM function WHERE function_no=" . $function_no;
  }

  mysql_query($sql);
  if (mysql_error() != "") {
    $tp->element["Message"] = mysql_error();
    $tp->element["Functionno"] = $function_no;
    $tp->element["Functionname"] = $name;
  }
  else {
    Header("Location: security-browse.php");
    exit;
  }

}

$tp->Parse("securitydetail", "Pagebody");
$tp->Parse("page", "main");
echo $tp->element["main"];

?>
