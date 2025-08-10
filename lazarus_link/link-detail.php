<?php

require_once("top.php");
require_once("security.php");
require_once("templatizer.php");
require_once("menu.php");
require_once("link_class.php");

secRequire("Link");

$tp = new Templatizer();
$tp->Load_Template("link.tpl");
$tp->Load_Template("standard.tpl");

$vMenuno = dc_fetch_menu_no("/link-browse.php");
if ($vMenuno != -1) {
  $m = new Menu($vMenuno);
  $tp->element["Pagemenu"] = $m->Load();
}
else {
  $tp->element["Pagemenu"] = "&nbsp;";
}


$lin = new Link();
if ($link_no == "new") 
  $lin->link_no = -1;
else
  $lin->link_no = $link_no;

$result = $lin->Fetch();
if ($result != "") {
  $tp->element["Message"] = $result;
}
  
if ($REQUEST_METHOD == "POST") {
  $lin->name = $name;
  $lin->url = $url;
  $lin->submitted_by = $submitted_by;
  if (isset($approved))
    $lin->approved = $approved;
  else
    $lin->approved = 'N';
  $lin->description = $description;
  
  $result = "";
  if ($go == "Update")
    $result = $lin->Save();
  if ($go == "Delete")
    $result = $lin->Delete();
    
  if ($result == "") {
    Header("Location: /link-browse.php");
    exit();
  }
  else {
    $tp->element["Message"] = $result;
  }
}

$tp->element["Linklink_no"] = $lin->link_no;
$tp->element["Linkname"] = $lin->name;
$tp->element["Linkurl"] = $lin->url;
if ($lin->approved == "Y")
  $tp->element["Linkapproved"] = " checked";
$tp->element["Linkdescription"] = $lin->description;
$tp->element["Linksubmitted_by"] = $lin->submitted_by;

$tp->Parse("linkdetail", "Pagebody");
$tp->Parse("page", "main");
echo $tp->element["main"];

?>
