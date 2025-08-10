<?php
require_once("top.php");
require_once("templatizer.php");
require_once("menu.php");
require_once("security.php");

secRequire("News");

$tp = new Templatizer();
$tp->Load_Template("standard.tpl");
$tp->Load_Template("news.tpl");

$vMenuno = dc_fetch_menu_no("/news-browse.php");
if ($vMenuno != -1) {
  $m = new Menu($vMenuno);
  $tp->element["Pagemenu"] = $m->Load();
}
else {
  $tp->element["Pagemenu"] = "&nbsp;";
}

if (!isset($base)) $base=0;
if ($base < 0) $base=0;


$sql = "SELECT article_no, title, publish_date, unpublish_date FROM article\n";
$sql .= "ORDER BY publish_date DESC\n";
$sql .= "LIMIT " . $base . ", 31\n";

$result = mysql_query($sql);
if ($result) {

  if (mysql_num_rows($result) == 31) {
    $nextbase = $base + 30;
  }
  else 
    $nextbase = -1;

  $previousbase = $base - 30;

  if ($previousbase > 0) {
    $tp->element["previousbase"] = $previousbase;
    $tp->Parse("newsprevous", "Newsprevious");
  }
  if ($nextbase > 0) {
    $tp->element["nextbase"] = $nextbase;
    $tp->Parse("newsnext", "Newsnext");
  }

  $bgcolor="gray";

  while ($row = mysql_fetch_object($result)) {

    $tp->element["bgcolor"] = $bgcolor;
    if ($bgcolor == "white") $bgcolor="gray";
    else $bgcolor = "white";

    $tp->element["Newsarticle_no"] = $row->article_no;
    $tp->element["Newstitle"] = $row->title;
    $tp->element["Newspublish_date"] = $row->publish_date;
    $tp->element["Newsunpublish_date"] = $row->unpublish_date;
    $tp->Parse("newsrow", "Newsrows", 1);
  }
}
else {
  $tp->element["Newsrows"] = mysql_error() . "\n<pre>\n" . $sql . "\n</pre>\n";
}

$tp->Parse("newsgrid", "Pagebody");
$tp->Parse("page", "main");

echo $tp->element["main"];

?>
