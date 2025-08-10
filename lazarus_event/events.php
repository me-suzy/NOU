<?php
require_once("top.php");
require_once("templatizer.php");
require_once("menu.php");

$tp = new Templatizer();
$tp->Load_Template("event.tpl");
$tp->Load_Template("standard.tpl");
$tp->element["Year"] = date("Y");
$tp->element["Nextyear"] = date("Y") + 1;

$sql = "SELECT MONTHNAME(event_begin) AS 'begin_month', ";
$sql .= "DAYOFMONTH(event_begin) AS 'begin_day', ";
$sql .= "MONTHNAME(event_end) AS 'end_month', ";
$sql .= "DAYOFMONTH(event_end) AS 'end_day', ";
$sql .= "name, location, begin_hour, end_hour, article_no ";
$sql .= "FROM event WHERE ";

if ($show == "year") {
  $sql .= "event_end >= '" . date("Y") . "-1-1";
  $showrestrictions = "";
  $filtertext = "only upcoming events";
}
else {
  $sql .= "event_end >= '" . date("Y-m-d");
  $showrestrictions = "?show=year";
  $filtertext = "all events";
}

$sql .= "' AND event_end < '" . (date("Y") + 1) . "-1-1' ";
$sql .= "ORDER BY event_begin";

$tp->element["Eventrestrictions"] = $showrestrictions;
$tp->element["Filtertext"] = $filtertext;

$result = mysql_query($sql);
if (!$result) {
  $tp->element["Name"] = mysql_error();
  $tp->Parse("showrow", "Showrows");
}
else {

  while($row = mysql_fetch_object($result)) {
    $tp->element["Fairbegin"] = $row->begin_month . " " . $row->begin_day;

    if (($row->end_day == $row->begin_day && $row->end_month == $row->begin_month) || trim($row->end_month) == "") {
      $tp->element["Fairend"] = "";
      $tp->element["Dash"] = "";
    }
    else {
      $tp->element["Fairend"] = $row->end_month . " " . $row->end_day;
      $tp->element["Dash"] = "-";
    }

    if ($row->article_no > 0) {
      $tp->element["Eventname"] = $row->name;
      $tp->element["Articleno"] = $row->article_no;
      $tp->Parse("eventarticle", "Name");
    }
    else
      $tp->element["Name"] = $row->name;
    $tp->element["Location"] = $row->location;
    
    $tp->element["Hourdash"] = "-";
    if (trim($row->begin_hour) == "") {
      $tp->element["Beginhour"] = "";
      $tp->element["Hourdash"] = "";
    }
    else 
      $tp->element["Beginhour"] = $row->begin_hour;

    if (trim($row->end_hour) == "") {
      $tp->element["Endhour"] = "";
      $tp->element["Hourdash"] = "";
    }
    else
      $tp->element["Endhour"] = $row->end_hour;

    $tp->Parse("eventrow", "Eventrows", 1);
 
  }

  if ($tp->element["Eventrows"] == "") {
    $tp->Parse("noevents", "Eventrows");
  }

}

$tp->Parse("eventgrid", "Pagebody");

$m = new Menu(1);
$tp->element["Pagemenu"] = $m->Load();

$tp->element["Pagetitle"] = "Event Schedule for " . date("Y");
$tp->element["Title"] = date("Y") . " Event Schedule";



$tp->Parse("page", "main");
echo $tp->element["main"];

?>
