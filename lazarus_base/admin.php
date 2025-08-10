<?php
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header ("Pragma: no-cache");                          // HTTP/1.0

require_once("top.php");
require_once("templatizer.php");
require_once("menu.php");
require_once("textpage_class.php");

$tp = new Templatizer();
$tp->Load_Template("index.tpl");

$page = new TextPage();
$page->Find("ADMIN");

if ($page->function_no == 0 && $page->published == "Y") {  
  $tp->element["Pagetitle"] = $page->pagetitle;
  $tp->element["Title"] = $page->title;
  $tp->element["Keywords"] = $page->keywords;
  $tp->element["Description"] = $page->description;
  $tp->element["Pagebody"] = $page->body;
}    
else {
  Header("HTTP/1.0 403 Forbidden");
  include("403.html");
  echo "Function No: " . $page->function_no . "<br>Published: " . $page->published;

  exit;
}
$m = new Menu($page->menu_no);
$tp->element["Pagemenu"] = $m->Load();
$tp->element["Graphic1"] = $m->graphic1;
$tp->element["Graphic2"] = $m->graphic2;

$tp->Parse("admin","main");
echo $tp->element["main"];

?>
