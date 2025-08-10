<?php
require_once("top.php");
require_once("security.php");
require_once("templatizer.php");
require_once("menu.php");

$tp = new Templatizer();
$tp->Load_Template("standard.tpl");
$tp->Load_Template("menu_node.tpl");

$vMenuno = dc_fetch_menu_no("/menu-browse.php");
if ($vMenuno != -1) {
  $m = new Menu($vMenuno);
  $tp->element["Pagemenu"] = $m->Load();
}
else {
  $tp->element["Pagemenu"] = "&nbsp;";
}

if ($REQUEST_METHOD == "GET") {

  $tp->element["Menuno"] = $menu_no;
  $tp->element["Menunodeno"] = $menu_node_no;

  $ssql = "SELECT function_no, name FROM function";
  $sresult = mysql_query($ssql);
  $starget = 0;

  if ($menu_node_no != "new") {
    $sql = "SELECT link, label, sort_order, function_no FROM menu_node WHERE ";
    $sql .= sprintf("menu_no=%d AND menu_node_no=%d", $menu_no, $menu_node_no);
    $result = mysql_query($sql);
    if ($result) {
      $row = mysql_fetch_object($result);
      $tp->element["Menunodelabel"] = $row->label;
      $tp->element["Menunodelink"] = $row->link;
      $tp->element["Menunodesortorder"] = $row->sort_order;
      $starget = $row->function_no;
    }
    else {
      $tp->element["Message"] = mysql_error();
    }
  }

  $tp->element["Functionnolist"] = "<option value=\"0\">No Security\n" . dc_option_list($sresult, $starget, "name", "function_no");

}
else { // POST Request

  if ($go == "Update" && $menu_node_no == "new") {
    $sql = "INSERT INTO menu_node (menu_no, link, label, sort_order, function_no)\n";
    $sql .= sprintf("VALUES (%d, '%s', '%s', %d, %d)",
		    $menu_no, $link, $label, $sort_order, $function_no);
    mysql_query($sql);
    if (mysql_error() == "") {
      header("Location: menu-detail.php?menu_no=" . $menu_no);
    }
    else {
      $tp->element["Message"] = mysql_error();
    }
  }

  if ($go == "Update" && $menu_node_no != "new") {
    $sql = "UPDATE menu_node SET\n";
    $sql .= sprintf("link='%s', label='%s', sort_order=%d, function_no=%d\n",
		    $link, $label, $sort_order, $function_no);
    $sql .= sprintf("WHERE menu_no=%d AND menu_node_no=%d",
		    $menu_no, $menu_node_no);
    mysql_query($sql);
    if (mysql_error() == "") {
      header("Location: menu-detail.php?menu_no=" . $menu_no);
    }
    else {
      $tp->element["Message"] = mysql_error();
    }
  }

  if ($go == "Delete" && $menu_node_no != "new") {
    $sql = sprintf("DELETE FROM menu_node WHERE menu_no=%d AND menu_node_no=%d",
		   $menu_no, $menu_node_no);
    mysql_query($sql);
    if (mysql_error() == "") {
      header("Location: menu-detail.php?menu_no=" . $menu_no);
    }
    else {
      $tp->element["Message"] = mysql_error();
    }
  }

}

$tp->Parse("menunodedetail", "Pagebody");
$tp->Parse("page", "main");

echo $tp->element["main"];

?>
