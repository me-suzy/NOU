<?php
require_once("templatizer.php");

class Menu {
  var $menu_no;
  var $nodes;
  var $name;
  var $bgcolor;
  var $graphic1;
  var $graphic2;

  Function Menu($menu_no = -1) {
    $this->menu_no = $menu_no;
  }

  function Fetch() {

    if ($this->menu_no != -1) {
      $sql = "SELECT name, background_color, graphic_1, graphic_2 FROM menu WHERE ";
      $sql .= "menu_no=" . $this->menu_no;

      $result = mysql_query($sql);
      if ($result) {
	$row = mysql_fetch_object($result);

	// Set up the top-level stuff
	$this->name = $row->name;
	$this->bgcolor = $row->background_color;
	$this->graphic1 = $row->graphic_1;
	$this->graphic2 = $row->graphic_2;

	$sql = "SELECT link, label FROM menu_node WHERE ";
	$sql .= "menu_no=" . $this->menu_no;
	$sql .= " ORDER BY sort_order";

	$result2 = mysql_query($sql);
	if ($result2) {

	  while ($row = mysql_fetch_object($result2)) {
	    $this->nodes[$row->label] = $row->link;
	  }      
	}
      }
    }
  }

  function Render() {

    global $tp;

    if (is_object($tp)) {
      $tp->element["Graphic1"] = $this->graphic1;
      $tp->element["Graphic2"] = $this->graphic2;
    }

    $tmp = new Templatizer();
    $tmp->Load_Template("menu.tpl");

    $tmp->element["Menuname"] = $this->name;
    $tmp->element["Menubackgroundcolor"] = $this->bgcolor;

    while(list($label, $link) = each($this->nodes)) {
      $tmp->element["Link"] = $link;
      $tmp->element["Label"] = $label;
      $tmp->Parse("menurow", "Menurows", 1);
    }

    $tmp->Parse("menu", "mymenu");
    return $tmp->element["mymenu"];

  }

  function Load() {
    $this->Fetch();
    return $this->Render();
  }

}
?>