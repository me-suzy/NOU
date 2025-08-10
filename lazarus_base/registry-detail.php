<?php

require_once("top.php");
require_once("security.php");
require_once("registry_class.php");
require_once("menu.php");
require_once("templatizer.php");

secRequire("User");

$tp = new Templatizer();
$tp->Load_Template("standard.tpl");
$tp->Load_Template("registry.tpl");

$vMenuno = dc_fetch_menu_no("/registry-browse.php");
if ($vMenuno != -1) {
  $m = new Menu($vMenuno);
  $tp->element["Pagemenu"] = $m->Load();
}
else {
  $tp->element["Pagemenu"] = "&nbsp;";
}

$reg = new registry();

if ($REQUEST_METHOD == "GET") {

  if ($registry_no > 0) {
    $reg->registry_no = $registry_no;
    $reg->Fetch();
  }

}
else {

  $reg->registry_no = $registry_no;
  $reg->category = $category;
  $reg->entity = $entity;
  $reg->value = $value;

  $result = "";
  if ($go == "Update")
    $result = $reg->Save();
  if ($go == "Delete")
    $result = $reg->Delete();

  if ($result == "") {
    Header("Location: registry-browse.php");
    exit();
  }
  else
    $tp->element["Message"] = $result;

}

$tp->element["registry_no"] = $registry_no;
$tp->element["category"] = $reg->category;
$tp->element["entity"] = $reg->entity;
$tp->element["value"] = $reg->value;
$tp->element["Pagetitle"] = "Registry Detail";
$tp->element["Title"] = "Registry Detail for " . $reg->category . ":" . $reg->entity;

$tp->Parse("registrydetail", "Pagebody");
$tp->Parse("page", "main");
echo $tp->element["main"];

?>
