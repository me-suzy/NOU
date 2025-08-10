<?php
require_once("top.php");
require_once("security.php");
require_once("templatizer.php");
require_once("menu.php");

secRequire("Menu");

$tp = new Templatizer();
$tp->Load_Template("standard.tpl");
$tp->Load_Template("menu.tpl");
$tp->Load_Template("menu_node.tpl");

$tp->element["Pagetitle"] = "Menu Detail for Menu " . $menu_no;
$tp->element["Title"] = $tp->element["Pagetitle"];

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

  if ($menu_no != "new") {
    $sql = "SELECT name, background_color, graphic_1, graphic_2 FROM menu\n";
    $sql .= " WHERE menu_no=" . $menu_no;
    $result = mysql_query($sql);
    if ($result) {
      $row = mysql_fetch_object($result);
      $tp->element["Menuname"] = $row->name;
      $tp->element["Menubackgroundcolor"] = $row->background_color;
      $tp->element["Menugraphic1"] = $row->graphic_1;
      $tp->element["Menugraphic2"] = $row->graphic_2;

      $sql = "SELECT menu_node_no, link, label, name FROM menu_node LEFT OUTER JOIN function ON function.function_no = menu_node.function_no WHERE\n";
      $sql .= "menu_no=" . $menu_no;
      $sql .= "\nORDER BY sort_order";
      $result = mysql_query($sql);
      if ($result) {
	while($row = mysql_fetch_object($result)) {
	  $tp->element["Menunodeno"] = $row->menu_node_no;
	  $tp->element["Menunodelink"] = $row->link;
	  $tp->element["Menunodelabel"] = $row->label;
	  $tp->element["Menunodefunction"] = $row->name;
	  $tp->Parse("menunoderow", "Menunoderows", 1);
	}

	$tp->Parse("menunodegrid", "Menunodegrid");

      }
      else {
	$tp->element["Message"] = "Menu Nodes: " . mysql_error();
      }

    }
    else {
      $tp->element["Message"] = "Menu: " . mysql_error() . "<br>\n" . $sql;
    }
  }
}
else { // POST Requests

  if ($go == "Update" && $menu_no=="new") {
    $sql = "INSERT INTO menu (name, background_color, graphic_1, graphic_2)\n";
    $sql .= sprintf("VALUES ('%s', '%s', '%s', '%s')", $name, $background_color, $graphic1, $graphic2);
    mysql_query($sql);
    if (mysql_error() == "") {
      header("Location: menu-detail.php?menu_no=" . mysql_insert_id());
    }
    else {
      $tp->element["Message"] = mysql_error();
    }
  }

  if ($go == "Update" && $menu_no != "new") {
    $sql = sprintf("UPDATE menu SET name='%s', background_color='%s',\ngraphic_1='%s', graphic_2='%s'\n",
		   $name, $background_color, $graphic1, $graphic2);
    $sql .= sprintf("WHERE menu_no=%d", $menu_no);

    mysql_query($sql);
    if (mysql_error() == "") {
      header("Location: menu-browse.php");
    }
    else {
      $tp->element["Message"] = mysql_error();
    }
  }

  if ($go == "Delete" && $menu_no != "new") {
    $sql = sprintf("DELETE FROM menu WHERE menu_no=%d", $menu_no);
    mysql_query($sql);
    if (mysql_error() == "") {
      $sql = sprintf("DELETE FROM menu_node WHERE menu_no=%d", $menu_no);
      mysql_query($sql);
      if (mysql_error() == "") {
	header("Location: menu-browse.php");
	exit();
      }
      else
	$tp->element["Message"] = mysql_error();
    }
    else {
      $tp->element["Message"] = mysql_error();
    }
  }

}

$tp->Parse("menudetail", "Pagebody");
$tp->Parse("page", "main");

echo $tp->element["main"];

?>



