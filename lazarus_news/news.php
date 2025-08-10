<?php

require_once("top.php");
require_once("templatizer.php");
require_once("news_class.php");
require_once("menu.php");

$tp = new Templatizer();
$tp->Load_Template("standard.tpl");
$tp->Load_Template("news.tpl");

$m = new Menu();
$m->menu_no = 1;

require_once("newsfeeder.php");

if (isset($PATH_INFO)) {

  $article_no = substr($PATH_INFO, 1);
  $art = new News($article_no);

  $tp->element["Articleauthoremail"] = $art->author_email;
  $tp->element["Articleauthor"] = $art->author;
  $tp->element["Articlepublishdate"] = $art->publish_date;
  $tp->element["Articledescription"] = $art->description;
  $tp->element["Articlebody"] = $art->body;
  $tp->element["Title"] = $art->title;
  $tp->element["Pagetitle"] = $art->title;
  $m->menu_no = $art->menu_no;
  
}

$tp->element["Pagemenu"] = $m->Load();

$tp->Parse("newsscreen", "Pagebody");
$tp->Parse("page", "main");
echo $tp->element["main"];

?>
