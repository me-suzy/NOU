<?php
require_once("top.php");
require_once("templatizer.php");
require_once("news_class.php");
require_once("security.php");
require_once("menu.php");

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


$news = new News();

if ($REQUEST_METHOD == "GET") {

  if ($article_no == "new") {
    $rightnow = time();
    $monthfromnow = $rightnow + 5184000; // 60 days from now

    $anow = getdate($rightnow);
    $amonth = getdate($monthfromnow);

    $publish_year = $anow["year"];
    $publish_month = $anow["mon"];
    $publish_day = $anow["mday"];

    $unpublish_year = $amonth["year"];
    $unpublish_month = $amonth["mon"];
    $unpublish_day = $amonth["mday"];

  }
  else {

    $news->Fetch($article_no);

    $anow = explode("-", $news->publish_date);
    $amonth = explode("-", $news->unpublish_date);

    $publish_year = $anow[0];
    $publish_month = $anow[1];
    $publish_day = $anow[2];

    $unpublish_year = $amonth[0];
    $unpublish_month = $amonth[1];
    $unpublish_day = $amonth[2];

  }
}
else {

  if ($article_no != "new") {
    $news->brochure_no = $article_no;
    $news->author_no = $user->user_no;
  }
  else {
    $news->brochure_no = -1;
  }

  $news->title = $title;
  $news->keywords = $keywords;
  $news->description = $description;
  $news->rawbody = $body;
  $news->publish_date = $publish_year . "-" . $publish_month . "-" . $publish_day;
  $news->unpublish_date = $unpublish_year . "-" . $unpublish_month . "-" . $unpublish_day;
  $news->menu_no = $menu_no;

  $result = "";
  if ($go == "Update")
    $result = $news->Save();
  if ($go == "Delete")
    $result = $news->Delete();

  if ($result == "") {
    Header("Location: news-browse.php?" . $news->unpublish_date);
    exit();
  }
  else
    $tp->element["message"] = $result;

}

$msql = "SELECT menu_no, name FROM menu";
$mresult = mysql_query($msql);
if ($mresult) {
  $tp->element["menu_no_options"] = dc_option_list($mresult, $news->menu_no, "name",  "menu_no");
}

$tp->element["Newsarticle_no"] = $news->brochure_no;
$tp->element["Newstitle"] = $news->title;
$tp->element["Newsdescription"] = $news->description;
$tp->element["Newskeywords"] = $news->keywords;
$tp->element["Newsauthor"] = $news->author;
$tp->element["Newsauthor_email"] = $news->author_email;
$tp->element["Newsauthor_no"] = $news->author_no;
$tp->element["Newsbody"] = $news->rawbody;

$tp->element["Newspublish_year"] = $publish_year;
$tp->element["Newspublish_month"] = $publish_month;
$tp->element["Newspublish_day"] = $publish_day;

$tp->element["Newsunpublish_year"] = $unpublish_year;
$tp->element["Newsunpublish_month"] = $unpublish_month;
$tp->element["Newsunpublish_day"] = $unpublish_day;

$tp->Parse("newsdetail", "Pagebody");
$tp->Parse("page", "main");

echo $tp->element["main"];

?>
