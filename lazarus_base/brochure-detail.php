<?php
require_once("top.php");
require_once("security.php");
require_once("menu.php");
require_once("templatizer.php");
require_once("textpage_class.php");

secRequire("Page");

$tp = new Templatizer();

$tp->Load_Template("standard.tpl");
$tp->Load_Template("brochure.tpl");

$tp->element["Pagetitle"] = "Brochure Detail for " . $brochure_no;
$tp->element["Title"] = $tp->element["Pagetitle"];

$vMenuno = dc_fetch_menu_no("/brochure-browse.php");
if ($vMenuno != -1) {
  $m = new Menu($vMenuno);
  $tp->element["Pagemenu"] = $m->Load();
}
else {
  $tp->element["Pagemenu"] = "&nbsp;";
}
$tp->element["Action"] = "brochure-detail.php";

$page = new TextPage();

if ($REQUEST_METHOD == "GET") {

  $tp->element["Brochureno"] = $brochure_no;

  if ($brochure_no != "new") {
    $page->Fetch($brochure_no);
  }

  $tp->element["Brochureurl"] = $page->url;
  $tp->element["Brochurekeywords"] = $page->keywords;
  $tp->element["Brochuredescription"] = $page->description;
  $tp->element["Brochurebody"] = $page->rawbody;
  $tp->element["Brochuretitle"] = $page->title;
  $tp->element["Brochurepagetitle"] = $page->pagetitle;

  if ($page->published == "Y") $tp->element["PUBLISHED"] = "checked";

}
else { // POST Request

  if ($function_no == "") $tfunction_no = 0;
  else $tfunction_no = $function_no;

  if ($published == "") $tpublished = "N";
  else $tpublished = "Y";

  $page->url_prefix = "/brochure.php";
  if ($brochure_no != "new") $page->brochure_no = $brochure_no;
  $page->url = $url;
  $page->title = $title;
  $page->pagetitle = $pagetitle;
  $page->keywords = $keywords;
  $page->description = $description;
  $page->rawbody = $body;
  $page->menu_no = $menu_no;
  $page->function_no = $tfunction_no;
  $page->published = $tpublished;

  if ($go == "Update") $result = $page->Save();
  if ($go == "Delete") $result = $page->Delete();

  if ($result == "")
    Header("Location: brochure-browse.php");
  else {
    $tp->element["Message"] = $result;
  }

  // Save the page as a static HTML file.  This is a very useful
  // technique for reducing server load.
  
  if ($go == "Store") {
    $fin = file("http://sorcerer.story-game.com/brochure.php/" . $url);
    $fout = fopen("./docs/" . $url, "w");
    if ($fout) {
      while (list($lineno, $text) = each($fin)) {
	fputs($fout, $text . "\n");
      }
      fclose($fout);
    }
  }

}

// Build the list of menus
$msql = "SELECT menu_no, name FROM menu";
$mresult = mysql_query($msql);
$tp->element["Menulist"] = dc_option_list($mresult, $page->menu_no, "name", "menu_no");

$fsql = "SELECT function_no, name FROM function";
$fresult = mysql_query($fsql);
$tp->element["Functionlist"] = dc_option_list($fresult, $page->function_no, "name", "function_no");

$tp->Parse("brochuredetail", "Pagebody");
$tp->Parse("page", "main");

echo $tp->element["main"];

?>

