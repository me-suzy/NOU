<?php
require_once("top.php");
require_once("security.php");
require_once("templatizer.php");
require_once("menu.php");

$tp = new Templatizer();
$tp->Load_Template("standard.tpl");
$tp->Load_Template("maillist.tpl");

$tp->element["Title"] = "Send Newsletter";
$tp->element["Pagetitle"] = "Send Newsletter";

$vMenuno = dc_fetch_menu_no("/brochure-browse.php");
if ($vMenuno != -1) {
  $m = new Menu($vMenuno);
  $tp->element["Pagemenu"] = $m->Load();
}
else {
  $tp->element["Pagemenu"] = "&nbsp;";
}

$tp->element["subject"] = $subject;
$tp->element["message"] = $message;

if (isset($message) && isset($subject)) {

  $msg = $message . "\n-----\n" . $disclaimer;
  $sql = "SELECT email FROM maillist";
  $result = mysql_query($sql);
  while ($row = mysql_fetch_object($result)) {
    mail($row->email, "[The Toybox] " . $subject, $msg, "From: The Toybox <toybox@toyboxtoys.com>\n");
  }
  $tp->element["Message"] = "The newsletter has been sent";
}

$tp->Parse("maillistsend", "Pagebody");
$tp->Parse("page", "main");
echo $tp->element["main"];

?>