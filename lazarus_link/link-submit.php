<?php

require_once("top.php");
require_once("templatizer.php");
require_once("menu.php");
require_once("link_class.php");

$tp = new Templatizer();
$tp->Load_Template("standard.tpl");
$tp->Load_Template("link.tpl");

$vMenuno = dc_fetch_menu_no("/links.php");
if ($vMenuno != -1) {
  $m = new Menu($vMenuno);
  $tp->element["Pagemenu"] = $m->Load();
}
else {
  $tp->element["Pagemenu"] = "&nbsp;";
}

if ($REQUEST_METHOD == "POST") {

  $lin = new Link();
  $lin->name = trim($name);
  $lin->url = trim($url);
  $lin->description = trim($description);
  $lin->submitted_by = trim($submitted_by);
  
  $cleared = 1;
  $message = "";
  if ($lin->name == "") {
    $cleared = 0;
    $message .= "<li>The web site's name is required.\n";
  }
  if ($lin->url == "") {
    $cleared = 0;
    $message .= "<li>The web site's URL is required.\n";
  }
  if ($lin->description == "") {
    $cleared = 0;
    $message .= "<li>The web site's description must not be blank.\n";
  }
  if ($lin->submitted_by == "") {
    $cleared = 0;
    $message .= "<li>Please provide your e-mail address so we may contact you when the link is activated.\n";
  }

  $tp->element["Linkname"] = stripslashes($lin->name);
  $tp->element["Linkurl"] = $lin->url;
  $tp->element["Linkdescription"] = stripslashes($lin->description);
  $tp->element["Linksubmitted_by"] = stripslashes($lin->submitted_by);
  
  if ($cleared == 1) {
    $lin->Save();
    $tp->Parse("linksubmitted", "Pagebody");
   
    $tp->element["Linklink_no"] = $lin->link_no;
    $tp->Parse("submittedemail", "Submittedemail");
    mail("toybox@toyboxtoys.com, clay@obrienscafe.com", "A New Link has been submitted", $tp->element["Submittedemail"]);
    
  }
  else {
    $message = "<ol>\n" . $message . "</ol>\n";
    $tp->element["Message"] = $message;    
    $tp->Parse("submitlink", "Pagebody");
  }  
}
else {
  $tp->Parse("submitlink", "Pagebody");
}

$tp->Parse("page", "main");
echo $tp->element["main"];

?>