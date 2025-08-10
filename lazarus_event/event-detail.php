<?php
require_once("top.php");
require_once("templatizer.php");
require_once("menu.php");
require_once("event_class.php");
require_once("news_class.php");

$tp = new Templatizer();
$tp->Load_Template("standard.tpl");
$tp->Load_Template("event.tpl");

$m = new Menu(2);
$tp->element["Pagemenu"] = $m->Load();
$tp->element["Pagetitle"] = "Event Detail for Event " . $fair_no;
$tp->element["Title"] = $tp->element["Pagetitle"];

$eve = new Event();

if ($REQUEST_METHOD == "GET") {

  $eve->event_no = $fair_no;
  $eve->Fetch();

  if ($fair_no == "new") {
    $tp->element["Beginyear"] = date("Y");
    $tp->element["Endyear"] = date("Y");
  }

} 
else { // POST Request
  
  $eve->event_no = $event_no;
  $eve->begin_year = $begin_year;
  $eve->begin_month = $begin_month;
  $eve->begin_day = $begin_day;
  $eve->end_year = $end_year;
  $eve->end_month = $end_month;
  $eve->end_day = $end_day;
  $eve->name = $name;
  $eve->location = $location;
  $eve->article_no = $article_no;
  $eve->begin_hour = $begin_hour;
  $eve->end_hour = $end_hour;

  if ($go == "Update" || $go == "Article") {

    $result = $eve->Save();

    if ($result != "") {
      $tp->element["Message"] = $result;
    }
    else {

      if ($go == "Article") {
	if ($eve->article_no <= 0) {
	  $n = new News();
	  $n->title = $eve->name;
	  $n->description = $eve->location;
	  
	  $rightnow = time();
	  $anow = getdate($rightnow);
	  $n->publish_date = sprintf("%d-%d-%d", $anow["year"], $anow["mon"],
				     $anow["mday"]);
	  
	  
	  $n->unpublish_date = $eve->event_end;
	  $n->Save();
	  $eve->Link_Article($n->brochure_no);
	}
	header("Location: news-detail.php?article_no=" . $eve->article_no);
	exit();
      }

      header("Location: event-browse.php");
      exit();
    }
  }
  
  if ($go == "Delete") {
    $result = $eve->Delete();
    if ($result != "") {
      $tp->element["Message"] = $result;
    }
    else {
      header("Location: event-browse.php");
      exit();
    }
  }

}

$tp->element["Eventno"] = $eve->event_no;
$tp->element["Beginmonth"] = $eve->begin_month;
$tp->element["Beginday"] = $eve->begin_day;
$tp->element["Beginyear"] = $eve->begin_year;

$tp->element["Endmonth"] = $eve->end_month;
$tp->element["Endday"] = $eve->end_day;
$tp->element["Endyear"] = $eve->end_year;

$tp->element["Name"] = $eve->name;
$tp->element["Location"] = $eve->location;
$tp->element["Beginhour"] = $eve->begin_hour;
$tp->element["Endhour"] = $eve->end_hour;
$tp->element["Articleno"] = $eve->article_no;

  
$tp->element["Action"] = "event-detail.php";
$tp->Parse("eventdetail", "Pagebody");
$tp->Parse("page", "main");
echo $tp->element["main"];

?>
