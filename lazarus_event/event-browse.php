<?php
require_once("top.php");
require_once("templatizer.php");
require_once("menu.php");
require_once("security.php");

secRequire("Event");

$tp = new Templatizer();
$tp->Load_Template("event.tpl");
$tp->Load_Template("standard.tpl");

$m = new Menu(2);
$tp->element["Pagemenu"] = $m->Load();

$tp->element["Pagetitle"] = "Browse Events";
$tp->element["Title"] = "Browse Events";

$sql = "SELECT * FROM event ORDER BY event_begin";
$result = mysql_query($sql);

while ($row = mysql_fetch_object($result)) {
  $tp->element["Fairbegin"] = $row->event_begin;
  $tp->element["Fairend"] = $row->event_end;
  $tp->element["Name"] = $row->name;
  $tp->element["Location"] = $row->location;
  $tp->element["Beginhour"] = $row->begin_hour;
  $tp->element["Endhour"] = $row->end_hour;
  $tp->element["Fairno"] = $row->event_no;
  $tp->Parse("eventbrowserow", "Eventbrowserows", 1);
}

$tp->Parse("eventbrowse", "Pagebody");
$tp->Parse("page", "main");

echo $tp->element["main"];

?>

