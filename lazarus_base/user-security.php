<?php
require_once("top.php");
require_once("security.php");
require_once("templatizer.php");
require_once("menu.php");

secRequire("User");

if ($user_no == "") {
  // Bail out if they didn't supply a user number.
  Header("Location: " . $HTTP_REFERER);
  exit;
}

$tp = new Templatizer();
$tp->Load_Template("standard.tpl");
$tp->Load_Template("user-security.tpl");

$vMenuno = dc_fetch_menu_no("/user-browse.php");
if ($vMenuno != -1) {
  $m = new Menu($vMenuno);
  $tp->element["Pagemenu"] = $m->Load();
}
else {
  $tp->element["Pagemenu"] = "&nbsp;";
}

$u = new User($user_no);

$tp->element["Pagetitle"] = "User Security";
$tp->element["Title"] = "Security Settings for " . $u->name;
$tp->element["Userno"] = $user_no;
$tp->element["Name"] = $u->name;
$tp->element["Userid"] = $u->user_id;
$tp->element["Email"] = $u->email;

// Load up the list of function numbers
$sql = "SELECT function_no, name FROM function";
$result = mysql_query($sql);
if ($result) {
  while ($row = mysql_fetch_object($result)) {
    $flist[$row->name] = $row->function_no;
  }
}

while(list($key, $value) = each($u->functions)) {

  $tp->element["Functionname"] = $key;
  $tp->element["Functionno"] = $flist[$key];

  if ($value == 0) {
    $tp->element["Image"] = "/images/unchecked.gif";
    $tp->element["Do"] = "add";
    $tp->element["Imageheight"] = "27";
  }
  else {
    $tp->element["Image"] = "/images/checked.gif";
    $tp->element["Do"] = "delete";
    $tp->element["Imageheight"] = "26";
  }

  $tp->Parse("securityrow", "Securityrows", 1);

}

$tp->Parse("securitygrid", "Pagebody");
$tp->Parse("page", "main");

echo $tp->element["main"];

?>
