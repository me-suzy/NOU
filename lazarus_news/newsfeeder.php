<?php

// Load the news and put into the Pagenews templatizer element
// Make sure you've included the Templatizer.

require_once("news_class.php");

global $tp;
global $m;

$news_txt = "";
$ntp = new Templatizer();
$ntp->Load_Template("news.tpl");

$nl = new Newslist();

if (basename($SCRIPT_FILENAME) == "department") { 
  if (isset($m->menu_no))
    $nl->menu_no = $m->menu_no;
}

$result = $nl->Fetchlist();
if ($result != "") {
  $tp->element["Message"] = $result;
}

while(list($i, $article_no) = each($nl->article_no)) {

  $ntp->element["Newsarticle_no"] = $article_no;
  $ntp->element["Newstitle"] = $nl->title[$i];
  $ntp->element["Newsdescription"] = $nl->description[$i];
  if ($nl->icon[$i] == "")
    $ntp->element["Newsicon"] = "blueball.jpeg";
  else
    $ntp->element["Newsicon"] = $nl->icon[$i];

  $ntp->Parse("newsitem", "Newsitems", 1);

}

$ntp->Parse("news", "localnews");

if (basename($SCRIPT_FILENAME) == "department")
  $tp->element["Pagebody"] .= $ntp->element["localnews"];
else
  $tp->element["Pagenews"] = $ntp->element["localnews"];

?>


