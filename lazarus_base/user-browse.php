<?php
require_once("top.php");
require_once("security.php");
require_once("menu.php");
require_once("templatizer.php");

secRequire("User");

$tp = new Templatizer();
$tp->Load_Template("standard.tpl");
$tp->Load_Template("user.tpl");

$tp->element["Pagetitle"] = "Users at " . $dc_domain_name;
$tp->element["Title"] = $tp->element["Pagetitle"];

$vMenuno = dc_fetch_menu_no("/user-browse.php");
if ($vMenuno != -1) {
  $m = new Menu($vMenuno);
  $tp->element["Pagemenu"] = $m->Load();
}
else {
  $tp->element["Pagemenu"] = "&nbsp;";
}

$tp->element["Fname"] = $fname;
$tp->element["Fuserid"] = $fuserid;
$tp->element["Femail"] = $femail;

$sql = "SELECT user_no, user_id, email, name FROM user WHERE\n";
$sql .= sprintf("email LIKE '%s%%'\n", $femail);
$sql .= sprintf("AND user_id LIKE '%s%%'\n", $fuserid);
$sql .= sprintf("AND name LIKE '%s%%'", $fname);

$result = mysql_query($sql);
if ($result) {
  while ($row = mysql_fetch_object($result)) {
    $tp->element["Userno"] = $row->user_no;
    $tp->element["Username"] = $row->name;
    $tp->element["Userid"] = $row->user_id;
    $tp->element["Useremail"] = $row->email;
    $tp->Parse("userrow", "Userrows", 1);
  }
}
else {
  $tp->element["Message"] = mysql_error();
}

$tp->Parse("usergrid", "Pagebody");
$tp->Parse("page", "main");
echo $tp->element["main"];


?>
