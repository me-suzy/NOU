<?php
require_once("top.php");
require_once("security.php");
require_once("templatizer.php");
require_once("menu.php");
require_once("user_class.php");

secRequire("User");

$tp = new Templatizer();
$tp->Load_Template("user.tpl");
$tp->Load_Template("standard.tpl");

$vMenuno = dc_fetch_menu_no("/user-browse.php");
if ($vMenuno != -1) {
  $m = new Menu($vMenuno);
  $tp->element["Pagemenu"] = $m->Load();
}
else {
  $tp->element["Pagemenu"] = "&nbsp;";
}

$tp->element["Pagetitle"] = "User Detail";
if ($user_no == "new") {
  $tp->element["Title"] = "New User";
}
else {
  $tp->element["Title"] = "Detail for User #" . $user_no;
}

if ($REQUEST_METHOD == "GET") {
  $tp->element["Userno"] = $user_no;

  if ($user_no != "new") {
    $u = new User($user_no);
    $tp->element["Userid"] = $u->user_id;
    $tp->element["Name"] = $u->name;
    $tp->element["Email"] = $u->email;
    $tp->element["Password"] = $u->password;
  }

}
else {

  if ($go == "Update" ) {
    if ($user_no == "new") $u = new User();
    else $u = new User($user_no);

    $u->user_id = $user_id;
    $u->email = $email;
    $u->name = $name;
    $u->password = $password;
    $msg = $u->Save();
    
    if ($msg != "") {
      $tp->element["Message"] = $msg;
    }
    else {
      header("Location: /user-browse.php");
    }

  }

  if ($go == "Delete" && $user_no != "new") {
    $u = new User();
    $msg = $u->Delete($user_no);
    if ($msg == "") 
      header("Location: /user-browse.php");
    else {
      $u->Fetch($user_no);
      $tp->element["Message"] = $msg;
      $tp->element["Userid"] = $u->user_id;
      $tp->element["Name"] = $u->name;
      $tp->element["Email"] = $u->email;
      $tp->element["Password"] = $u->password;
    }
    
  }

  
}

$tp->Parse("useradmindetail", "Pagebody");
$tp->Parse("page", "main");
echo $tp->element["main"];

?>
